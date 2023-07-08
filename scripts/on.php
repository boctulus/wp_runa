<?php

/*
    Plugin activation script

    /wp-content/plugins/{nombre_plugin}/scripts/on.php

    o

    php .\scripts\on.php
*/

define('ROOT_PATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);

$_pth        = explode(DIRECTORY_SEPARATOR, ROOT_PATH);
$plugin_name = $name = $_pth[count($_pth)-2];


$msg_activated   = "Plugin $plugin_name activado";
$msg_already_act = "Plugin $plugin_name ya estaba activo";

if (file_exists(ROOT_PATH . "$name.ph_")){
    rename(ROOT_PATH. "$name.ph_", ROOT_PATH."$name.php");
    print_r($msg_activated);
    return;
} 

$name = 'index';
if (file_exists(ROOT_PATH . "$name.ph_")){
    rename(ROOT_PATH. "$name.ph_", ROOT_PATH."$name.php");
    print_r($msg_activated);
    return;
} 

print_r($msg_already_act);
