<?php

return [
    /*
        API
    */

    'api_base_url' => 'http://201.148.107.125',
    'api_token'    => 'f32fq3fq32412',
    'endpoints'    => [
        'pedidos'        => '/~runa/js/zoh/pedidos.php',
        'stock_xml_gen'  => '/~runa/js/zoh/stock.php',
        'stock_xml_get'  => '/~runa/js/zoh/stock.xml'
    ],
    

    // No editar desde aqui -->

    "namespace" => "boctulus\SW",

	"composer_autoload" => false,

    'debug'     => env('DEBUG'),  
];

