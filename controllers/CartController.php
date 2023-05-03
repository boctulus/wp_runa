<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Orders;
use boctulus\SW\core\libs\Products;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Validator;

/*
    Esta API *no* es completamente Restful porque require de una cookie de WordPress 
    que habilita la session del usuario para identificar su carrito

    No puede testearse tan facilmente desde POSTMAN (al menos no sin enviar la cookie)

    La excepcion en el endpoint para save_form()
*/
class CartController
{    
    function count(){
        response([
            'count' => Cart::count(true)
        ]);
    }

    function set_qty($pid){       
        try {

            $req  = request();
            $data = $req->as_array()->getBody();
    
            if (empty($data)){
                error("Falta el body o esta vacio o el formato es incorrecto", 400);
            }
            
            $qty = $data['qty'] ?? null;
    
            if (empty($qty)){
                return error("Param 'qty' no puede omitise o estar vacio");
            }

            $ok = Cart::setQuantity($pid, $qty);

            if (!$ok){
                return error("No pudo seteare correctamente la cantidad");
            }

            response([
                'OK'
            ]);

        } catch (\Exception $e){
            $err = "Error con el request " . $e->getMessage();
            Logger::log($err);
    
            return error($err);
        }   
    }

    function add($pid){
        try {

            $req  = request();
            $data = $req->as_array()->getBody();
    
            if (empty($data)){
                error("Falta el body o esta vacio o el formato es incorrecto", 400);
            }
            
            $qty = $data['qty'] ?? null;
    
            if (empty($qty)){
                return error("Param 'qty' no puede omitise o estar vacio");
            }

            Cart::add($pid, $qty);

            response([
                'OK'
            ]);

        } catch (\Exception $e){
            $err = "Error con el request " . $e->getMessage();
            Logger::log($err);
    
            return error($err);
        }   
    }

    function delete($pid){
        try {
            $ok = Cart::remove($pid);

            if (!$ok){
                error("No se pudo eliminar para pid=$pid  en el carrito");
            }

            response([
                'message' => "Producto con pid=$pid fue exitosamente borrado del carrito"
            ]);

        } catch (\Exception $e){
            $err = "Error con el request " . $e->getMessage();
            Logger::log($err);
    
            return error($err);
        }   
    }

    /*  
        Custom endpint
    */
    function save_form()
    {
        $req  = request();
        $data = $req->as_array()->getBody();

        if (empty($data)){
            error("Falta el body o esta vacio o el formato es incorrecto", 400);
        }
    
        $rules = [
            'cart_items' 	    => ['type'=>'array','required'=>true, 'min_len'=>1],
            'contact' 			=> ['type'=>'array','required'=>true], 
        ];
    
        $v = new Validator;
    
        if (!$v->validate($data, $rules)){
           return error("Validation error", 400, $v->getErrors());
        } 

        // Client

        $cli = $data['contact'];

        $contact_rules = [
            'nom'  => ['type'=> 'str', 'required'=>true],
            'dir'  => ['type'=> 'str', 'required'=>true],
            'com'  => ['type'=> 'str', 'required'=>true],
            'gir'  => ['type'=> 'str', 'required'=>true],
            'ema'  => ['type'=> 'email', 'required'=>true],
            'fon'  => ['type'=> 'str', 'required'=>true],
            'rut'  => ['required'=>true],
        ];

        if (!$v->validate($cli, $contact_rules)){
            return error("Validation error", 400, $v->getErrors());
        } 

        // Cart items

        $items = $data['cart_items'];

        // $item_rules = [
        //     'id'   => ['type'=> 'integer', 'required'=>true],
        //     'qty'  => ['required'=>true],
        // ];

        // dd($items);

        // if (!$v->validate($items, $item_rules)){
        //     return error("Validation error", 400, $v->getErrors());
        // } 

        $products = [];
        foreach ($items as $ix => $item){
            $p = Products::getProduct($item['id']);

            // https://stackoverflow.com/a/54375782/980631
            $regular_price = (float) $p->get_regular_price(); // Regular price
            $sale_price    = (float) $p->get_price(); // Active price (the "Sale price" when on-sale)
            
            $products[] = [
                'pid' => $item['id'],
                'qty' => $item['qty']
            ];

            $item = [
                'cod' => $p->get_sku(),
                'can' => $item['qty'],
                'pre' => $regular_price,
                'des' => $regular_price - $sale_price,      
                'tot' => $sale_price
            ];

            $items[$ix] = $item;
        }

        /*
            Create order => get Order ID
        */

        $billing_address = array(
            'first_name' => '',
            'last_name'  => '',
            'company'    => $cli['nom'],
            'email'      => $cli['ema'],
            'phone'      => $cli['fon'],
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => 'Chile'
        );

        $shipping_address = array(
            'first_name' => '',
            'last_name'  => '',
            'company'    => $cli['nom'],
            'email'      => $cli['ema'],
            'phone'      => $cli['fon'],
            'address_1'  => $cli['dir'],
            'address_2'  => '',
            'city'       => $cli['com'],
            'state'      => '',
            'postcode'   => '',
            'country'    => 'Chile'
        );
        

        $quoted_order = Orders::create($products, $billing_address, $shipping_address);
        $order_id      = trim(str_replace('#', '', $quoted_order->get_order_number()));


        /*
            Request preparation
        */


        $arr = [
            'num' => $order_id,

            'cli' => $cli,
            
            'art' => $items
        ];

        Logger::dd($arr, 'req'); //

        $cfg = config();

        $url      = $cfg['api_base_url'] . $cfg['endpoints']['pedidos'];
	    $password = $cfg['api_token'];
    
        try {

            $data = XML::fromArray($arr, 'ped', false);

            $params = [ 
                'pass' => $password, 
                'data' => $data
            ];
        
            $url = Url::buildUrl($url, $params);
        
            $client = new ApiClient;
        
            $client
            ->disableSSL()
            ->setUrl($url)
            // ->addOptions([
            //     CURLOPT_FRESH_CONNECT => true,
            //     CURLOPT_HTTPHEADER    => [ "Cache-Control: no-cache" ]
            // ])
            ->setHeaders([
                'Content-type' => 'application/xml',
                'Accept'       => '*/*',
            ])
            ->get();
        
            $status = $client->getStatus();
        
            if ($status != 200){
                throw new \Exception("Error: " . $client->error());
            }

            Logger::varExport($client->dd(), 'request.php');
            Logger::dd($client->data(), 'RES DATA');
           
            return response([
                'data'    => $client->data()
            ]);

        } catch (\Exception $e){
            $err = "Error con el request " . $e->getMessage();
            Logger::log($err);
    
            return error($err);
        }   
    }
}