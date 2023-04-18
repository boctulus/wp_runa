<?php

return [
    // Dejar vacio ('') para no cambiarlo
    'add_to_cart_button_text' => 'Cotizar', // 'Agregar al carrito'
    
    'cotizador_slug' => '/2023/04/16/cotizador', //


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

    'debug'     => env('DEBUG'),  

    'namespace' => "boctulus\SW",

	'composer_autoload' => false,

    'front_controller' => true
];

