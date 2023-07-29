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


Logger::log(__FILE__);
exit;


$sku   = '06te502saf';
$stock = 0;

$pid = Products::getProductIDBySKU($sku);

$post_type = Products::getPostType($pid);
$sku       = $sku ?? Products::getSKUFromProductID($pid);

if ($post_type == 'product_variation'){
	$parent_pid = wp_get_post_parent_id($pid);
}

dd(Products::getName($pid), "SKU= '$sku' | pid=$pid" . (isset($parent_pid) ? " (variation of pid=$parent_pid)" : '' ));

Products::setStock($pid, $stock);

dd(
	Products::getStock($pid), 'STOCK'
);

dd(
	Products::getMeta($pid, '_stock_status'), 'DISP?'
);