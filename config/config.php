<?php

return [
    /*
        Bodega a utilizar para el stock
    */
    'warehouse'   => '01',

    /*
        CronJob

        Intervalo en segundos

        Ej:

        3600 * 6  // para cada 6 horas

        (para el caso de querer utilizar CronJobs de WordPress)
    */

    'cronjob_interval' => 3600 * 12,

    /*
        API
    */

    'api_base_url' => 'http://201.148.107.125',
    'api_token'    => 'f32fq3fq32412',
    'endpoints'    => [
        'pedidos'        => '/~runa/js/zoh/pedidosb2b.php',
        'stock_xml_gen'  => '/~runa/js/zoh/stock.php',
        'stock_xml_get'  => '/~runa/js/zoh/stock.xml',
        'sync_productos' => '....???'
    ],

    'log_requests'  => 'req.txt',
    'log_responses' => 'res.txt',

    'cache_exp'     => env('CHACHE_EXP'),

    /*
        Permite deshabilitar /cart o /carrito
        y lo mismo para el checkout
    */

    'disable_cart'     => true,
    'disable_checkout' => true,
    
    
    // Dejar vacio ('') para no cambiarlo
    'add_to_cart_button_text' => 'Cotizar', // 'Agregar al carrito'


    // No editar desde aqui -->

    'debug'             => env('DEBUG'),  

    'front_controller'  => true,

    'router'            => true,

    'namespace'         => "boctulus\SW",

	'use_composer'      => true,
];

