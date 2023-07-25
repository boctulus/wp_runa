<?php

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Date;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Taxes;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Products;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Orders;
use boctulus\SW\core\libs\Validator;
use boctulus\SW\core\libs\Plugins;
use boctulus\SW\core\libs\ValidationRules;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (php_sapi_name() != "cli"){
	// return; 
}

require_once __DIR__ . '/app.php';

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', realpath(__DIR__ . '/../../..') . DIRECTORY_SEPARATOR);

	require_once ABSPATH . '/wp-config.php';
	require_once ABSPATH .'/wp-load.php';
}

/////////////////////////////////////////////////

$products = [
    [
        'pid' => Products::getRandomProductIDs(),
        'qty' => rand(1,10)
    ],
    [
        'pid' => Products::getRandomProductIDs(),
        'qty' => rand(1,10)
    ]
];

$billing_address = array(
    'first_name' => 'Joe',
    'last_name'  => 'Conlin',
    'company'    => 'Speed Society',
    'email'      => 'joe@testing.com',
    'phone'      => '760-555-1212',
    'address_1'  => '123 Main st.',
    'address_2'  => '104',
    'city'       => 'San Diego',
    'state'      => 'Ca',
    'postcode'   => '92121',
    'country'    => 'US'
);

Orders::create($products, $billing_address);