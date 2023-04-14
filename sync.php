<?php

use boctulus\SW\libs\Alia;
use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Date;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Validator;
use boctulus\SW\libs\AliaBotQuestions;

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

// ...

function get_xml(){
	$cfg = config();

	$url = $cfg['api_base_url'] . $cfg['endpoints']['stock_xml_gen'];

	$client = ApiClient::instance();

	$client
	->disableSSL()
	->setUrl($url)
	->cache(1800)
	->get()
	->getResponse();

	$status = $client->getStatus();

	if ($status != 200){
		throw new \Exception($client->error());
	}

	// Doy tiempo a que se genere el archivo XML

	if (!get_transient('xml_generado')){
		sleep(15);
		set_transient('xml_generado', true, 1800);
	} 

	$url = $cfg['api_base_url'] . $cfg['endpoints']['stock_xml_get'];

	$client = ApiClient::instance();

	$client
	->disableSSL()
	->setUrl($url)
	->cache(1800)
	->get()
	->getResponse();

	$status = $client->getStatus();

	if ($status != 200){
		throw new \Exception($client->error());
	}

	//dd($client->getCachePath(), 'CACHE');

	return $client->data();
}


$stock_xml = get_xml();

if (empty($stock_xml)){
	throw new \Exception("No stock?");
}

$stock = XML::toArray($stock_xml);

dd($stock);