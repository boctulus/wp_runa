<?php

use boctulus\SW\libs\Alia;
use boctulus\SW\core\libs\Date;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Validator;
use boctulus\SW\libs\AliaBotQuestions;
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


function test_validator(){
	$data = [
		"fname" => "Tomas",
		"lname" => "Cruz",
		"email" => "cruz_t@gmail.com",
		"input_channel_id" => 8,
		"source_id" => 2,
		"interest_type_id" => 4,
		"project_id" => 540,
		"extra_fields" => [
			"rango_de_presupuesto" => "2M-3M"
		]            
	];

	$rules = [
		'fname' 			=> ['type'=>'alpha','required'=>true,'min'=>3],
		'lname' 			=> ['type'=>'alpha','required'=>true,'min'=>2,'max'=>100],
		'email' 			=> ['type'=>'email','required'=>true], 
		'input_channel_id'  => ['type'=>'int',  'required'=>true,'min'=>0],
		'source_id'         => ['type'=>'int',  'required'=>true,'min'=>0],
		'interest_type_id'  => ['type'=>'int',  'required'=>true,'min'=>0],
		'project_id'        => ['type'=>'int',  'required'=>true,'min'=>0],
		'extra_fields' 	    => ['type'=>'array','min'=>1],
	];

	$v = new Validator;

	if ($v->validate($data, $rules /*, $fillables */)){
		dd('Valido');
	} else {
	   dd($v->getErrors(), 'Errores de validacion');
	} 
}

function test_validator2(){
	$data = [
		"fname" => "Tomas",
		"lname" => "Cruz",
		"email" => "cruz_t@gmail.com",
		"input_channel_id" => 8,
		"source_id" => 2,
		"interest_type_id" => 4,
		"project_id" => 540,
		"extra_fields" => [
			"rango_de_presupuesto" => "2M-3M"
		]            
	];

	$rules = (new ValidationRules())
		->field('fname')->type('alpha')->required()->min(4)
		->field('lname')->type('alpha')->required()->min(2)->max(100)
		->field('email')->type('email')->required()
		->field('input_channel_id')->type('int')->required()
		// ...
	->getRules();

	$v = new Validator;

	if ($v->validate($data, $rules /*, $fillables */)){
		dd('Valido');
	} else {
	   dd($v->getErrors(), 'Errores de validacion');
	} 
}


test_validator2();