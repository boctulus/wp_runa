<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Taxes;
use boctulus\SW\core\libs\Request;

class TestController
{
    function index(){
        $this->test_send();
    }

    function test_send(){
        $base_url = "http://201.148.107.125/~runa/js/zoh/pedidos.php";
        $password = "f32fq3fq32412";
        
        $cli = array (
            'rut' => '1-9',
            'nom' => 'david lara oyarzun',
            'dir' => 'los dominicos 7177',
            'gir' => 'sin giro',
            'fon' => '89993450773',
            'ema' => 'dlara@runasssssssss.cl',
            'com' => 'huechuraba'
        );

        $quote_num = '123434421';

        /*
            los precios informados deben incluir el iva ***
        */
        $items = [
            [
                'cod' => '2345432134532',
                'pre' => '1000',
                'can' => '1',
                'des' => '0',
                'tot' => '1000',
            ],
      
            [
                'cod' => '2345432134532',
                'pre' => '1000',
                'can' => '1',
                'des' => '0',
                'tot' => '1000',
            ]
        ];

        $arr = [
            'num' => $quote_num,

            'cli' => $cli,
            
            'art' => $items
        ];

        $data = XML::fromArray($arr, 'ped', false);

        dd($data);
        exit;

        // $params = [
        //     'pass' => 'f32fq3fq32412', 
        //     'data' => $data
        // ];

        // $url = Url::buildUrl('http://201.148.107.125/~runa/js/zoh/pedidos.php', $params);

        // $client = new ApiClient;

        // $client
        // ->disableSSL()
        // //->cache()
        // //->redirect()
        // ->setUrl($url)
        // ->get();

        // $status = $client->getStatus();

        // if ($status != 200){
        //     throw new \Exception("Error: " . $client->error());
        // }

        // dd(
        //     $client->data()         
        // );  
    }      
}
