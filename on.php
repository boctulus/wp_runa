<?php

/*
    Plugin deactivation script
*/

$path = dirname(__FILE__);
$_pth = explode(DIRECTORY_SEPARATOR, $path);
$name = $_pth[count($_pth)-1];

if (file_exists(__DIR__ . "/$name.ph_")){
    rename(__DIR__ . "/$name.ph_", __DIR__ . "/$name.php");

    print_r("Plugin $name activado");
}

