<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Logger;
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
            'email' 			=> ['type'=>'email','required'=>true], 
            'cart_items' 	    => ['type'=>'array','required'=>true, 'min'=>1],
        ];
    
        $v = new Validator;
    
        if (!$v->validate($data, $rules)){
           return error("Validation error", 400, $v->getErrors());
        } 

        return response(['data' => $data]);
        // //////////////////////////////
    
        // $cfg = config();

        // Logger::dump($data); //
    
        // try {

        //     $client = ApiClient::instance()
        //     ->setHeaders([
        //         'Authorization: ' . $cfg['api_token'],
        //         'Content-Type: application/json'
        //     ]);

        //     $client
        //     ->disableSSL()
        //     //->cache()
        //     //->redirect()
        //     ->setBody($data)
        //     ->setUrl($cfg['api_url'])
        //     ->post()
        //     ->getResponse();

        //     $status = $client->getStatus();

        //     if ($status != 201 && $status != 200){
        //         throw new \Exception($client->error());
        //     }
           
        //     return response([
        //         'data' => $client->data()
        //     ]);

        // } catch (\Exception $e){
        //     $err = "Error con el request " . $e->getMessage();
        //     Logger::log($err);
    
        //     return error($err);
        // }   
    }
}