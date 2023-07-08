<?php

/*
    Plugin deactivation script

    /wp-content/plugins/{nombre_plugin}/scripts/off.php

    o

    php .\scripts\off.php
*/

define('ROOT_PATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);

$_pth        = explode(DIRECTORY_SEPARATOR, ROOT_PATH);
$plugin_name = $name = $_pth[count($_pth)-2];

$msg = "Plugin $plugin_name desactivado";

if (file_exists(ROOT_PATH . "$name.php")){

    if (file_exists(ROOT_PATH . "$name.ph_")){
        unlink(ROOT_PATH . "$name.ph_");
    } 
    
    rename(ROOT_PATH . "$name.php", ROOT_PATH . "$name.ph_");
    echo $msg;
    return;   
} 

$name = 'index';
if (file_exists(ROOT_PATH . "$name.php")){

    if (file_exists(ROOT_PATH . "$name.ph_")){
        unlink(ROOT_PATH . "$name.ph_");
    } 
    
    rename(ROOT_PATH . "$name.php", ROOT_PATH . "$name.ph_");
    echo $msg;
    return;   
} 

print_r("Plugin $plugin_name ya estaba desactivado");

