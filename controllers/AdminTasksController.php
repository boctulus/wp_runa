<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\DB;
use boctulus\SW\core\libs\Users;
use boctulus\SW\core\libs\Plugins;

use boctulus\SW\core\libs\Products;
use function TranslatePress\file_get_contents;

class AdminTasksController
{
    function __construct()
    {   
        // Restringe acceso a admin
        Users::restrictAccess();
    }

    function index(){
        dd('Hi Admin!!!'); 
    }

    function plugin_dir(){
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'); 
    }

    /*
        Devuelve algo como

        D:\www\woo6\wp-content\plugins\wp_runa\sync.php
    */
    function cron_dir(){
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . '../sync.php');
    }

    function cron_cmd(){
        return 'php ' . $this->cron_dir() . '  > /dev/null 2>&1';
    }

    function log(){
        return file_exists(LOGS_PATH . 'log.txt') ? file_get_contents(LOGS_PATH . 'log.txt') : '--x--';
    }

    function error_log(){
       return file_exists(LOGS_PATH . 'errors.txt') ? file_get_contents(LOGS_PATH . 'errors.txt') : '--x--';
    }

    function debug_log(){
        return file_exists(__DIR__ . '/../wp-content/debug.log') ? file_get_contents(__DIR__ . '/../wp-content/debug.log') : '--x--';
    }

    function req(){
        return file_exists(LOGS_PATH . 'req.txt') ? file_get_contents(LOGS_PATH . 'req.txt') : '--x--';
    }

    function res(){
        return file_exists(LOGS_PATH . 'res.txt') ? file_get_contents(LOGS_PATH . 'res.txt') : '--x--';
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
