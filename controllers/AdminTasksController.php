<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\DB;
use boctulus\SW\core\libs\Users;
use boctulus\SW\core\libs\Products;

use function TranslatePress\file_get_html;

class AdminTasksController
{
    function __construct()
    {   
        // Restringe acceso a admin
        Users::restrictAccess();
    }

    function index(){
        dd('Hi Admin!'); 
    }

    function debug_log(){
        return file_exists(__DIR__ . '/../wp-content/debug.log') ? file_get_contents(__DIR__ . '/../wp-content/debug.log') : '--x--';
    }

    function error_log(){
       return file_exists(LOGS_PATH . 'errors.txt') ? file_get_html(LOGS_PATH . 'errors.txt') : '--x--';
    }

    function adminer(){
        require_once __DIR__ . '/../scripts/adminer.php';
    }

    function update_db(){
        require __DIR__ . '/../scripts/installer.php';
        dd('done table creation');

        $this->insert();
        dd('done insert table');
    }

    function insert(){
        global $wpdb;
        
       // ...
    }
    
    
    
}
