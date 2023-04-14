<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Request;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\Validator;

class AjaxForm
{    
    function save_form(){
        $req  = Request::getInstance();
        $data = $req->as_array()->getBody();

        if (empty($data)){
            error("Falta el body o esta vacio o el formato es incorrecto", 400);
        }
    
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
    
        if (!$v->validate($data, $rules)){
           return error("Validation error", 400, $v->getErrors());
        } 

        // return response([
        //     'data' => $data
        // ]);
        //////////////////////////////
    
        $cfg = config();

        Files::dump($data); //
    
        try {

            $client = ApiClient::instance()
            ->setHeaders([
                'Authorization: ' . $cfg['api_token'],
                'Content-Type: application/json'
            ]);

            $client
            ->disableSSL()
            //->cache()
            //->redirect()
            ->setBody($data)
            ->setUrl($cfg['api_url'])
            ->post()
            ->getResponse();

            $status = $client->getStatus();

            if ($status != 201 && $status != 200){
                throw new \Exception($client->error());
            }
           
            return response([
                'data' => $client->data()
            ]);

        } catch (\Exception $e){
            $err = "Error con el request " . $e->getMessage();
            Files::logger($err);
    
            return error($err);
        }   
    }
}