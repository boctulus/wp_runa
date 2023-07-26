<?php

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Date;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Posts;
use boctulus\SW\core\libs\Taxes;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Plugins;
use boctulus\SW\core\libs\Products;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Validator;
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

$pid   = 3827; 
$stock = 21111;

Products::setStock($pid, $qty);

// $key = '_stock';
// Products::setMeta($pid, $key, $stock);

$product = wc_get_product($pid);

dd(
	$product->get_stock_quantity(), 'STOCK NATIVO'
);

exit;

$key = '_stock';

Products::setMeta($pid, $key, $stock);

dd(
	Products::getMeta($pid, $key)
, 'STOCK COMO CUSTOM METADATO');

$product = wc_get_product($pid);

/*
	--| Stock
	D:\www\woo6\wp-content\plugins\wp_runa\core\libs\VarDump.php:49:
	NULL
*/
dd(
	$product->get_stock_quantity(), 'STOCK NATIVO'
);

exit;
/////////////////////

$pid   = 3827; // simple
$stock = 999;

wc_update_product_stock($pid, $stock);  
$product = wc_get_product($pid);

/*
	--| Stock
	D:\www\woo6\wp-content\plugins\wp_runa\core\libs\VarDump.php:49:
	NULL
*/
dd(
	$product->get_stock_quantity(), 'Stock'
);



$pid   = 2736; // variant
$stock = 999;

wc_update_product_stock($pid, $stock);  
$product = wc_get_product($pid);

/*
	--| Stock
	D:\www\woo6\wp-content\plugins\wp_runa\core\libs\VarDump.php:49:
	NULL
*/
dd(
	$product->get_stock_quantity(), 'Stock'
);