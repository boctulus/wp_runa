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


dd(
	wc_get_cart_url()
);

exit;


$xml_encoded = '%3Cped%3E%3Cnum%3E101%3C%2Fnum%3E%3Ccli%3E%3Cnom%3EPablo+Bozzolo%3C%2Fnom%3E%3Crut%3E4.534.543-6%3C%2Frut%3E%3Cgir%3ECon+giro%3C%2Fgir%3E%3Cfon%3E644149161%3C%2Ffon%3E%3Cema%3Eboctulus%40gmail.com%3C%2Fema%3E%3Cdir%3EDiego+de+Torres+5%3C%2Fdir%3E%3Ccom%3ECalama%3C%2Fcom%3E%3C%2Fcli%3E%3Cart%3E%3Ccod%3EYR0-446%3C%2Fcod%3E%3Ccan%3E7%3C%2Fcan%3E%3Cpre%3E54851%3C%2Fpre%3E%3Cdes%3E0%3C%2Fdes%3E%3Ctot%3E54851%3C%2Ftot%3E%3C%2Fart%3E%3Cart%3E%3Ccod%3EYR0-465%3C%2Fcod%3E%3Ccan%3E4%3C%2Fcan%3E%3Cpre%3E51900%3C%2Fpre%3E%3Cdes%3E0%3C%2Fdes%3E%3Ctot%3E51900%3C%2Ftot%3E%3C%2Fart%3E%3C%2Fped%3E';

dd(
	urldecode($xml_encoded)
);

exit;



function test_runa(){
	$cfg = config();

	$url      = $cfg['api_base_url'] . $cfg['endpoints']['pedidos'];
	$password = $cfg['api_token'];
	
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
		'pass' => $password, 
		'data' => $data
	];

	$url = Url::buildUrl($url, $params);

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

	Logger::dd($client->data(), 'RES DATA');

	dd(
		$client->data()         
	);  
}      


//test_runa();


