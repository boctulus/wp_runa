<?php

use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Date;
use boctulus\SW\core\libs\Files;
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


function test_runa(){
	$base_url = "http://201.148.107.125/~runa/js/zoh/pedidos.php";
	$password = "f32fq3fq32412";
	
	$arr = array (
		'num' => '123434421',
		'cli' =>
		array (
		  'rut' => '1-9',
		  'nom' => 'david lara oyarzun',
		  'dir' => 'los dominicos 7177',
		  'gir' => 'sin giro',
		  'fon' => '89993450773',
		  'ema' => 'dlara@runasssssssss.cl',
		  'com' => 'huechuraba',
		),
		'art' =>
		array (
		  0 =>
		  array (
			'cod' => '2345432134532',
			'pre' => '1000',
			'can' => '1',
			'des' => '0',
			'tot' => '1000',
		  ),
		  1 =>
		  array (
			'cod' => '2345432134532',
			'pre' => '1000',
			'can' => '1',
			'des' => '0',
			'tot' => '1000',
		  )
		),
	);

	$data = XML::fromArray($arr, 'ped', false);

	$params = [
		'pass' => 'f32fq3fq32412', 
		'data' => $data
	];

	$url = Url::buildUrl('http://201.148.107.125/~runa/js/zoh/pedidos.php', $params);

	$client = new ApiClient;

	$client
	->disableSSL()
	//->cache()
	//->redirect()
	->setUrl($url)
	->get();

	$status = $client->getStatus();

	if ($status != 200){
		throw new \Exception("Error: " . $client->error());
	}

	dd(
		$client->data()         
	);  
}      


test_runa();


