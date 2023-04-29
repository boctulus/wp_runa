<?php

/*
    Plugin deactivation script
*/

$path = dirname(__FILE__);
$_pth = explode(DIRECTORY_SEPARATOR, $path);
$name = $_pth[count($_pth)-1];

if (file_exists(__DIR__ . "/$name.php")){

    if (!file_exists(__DIR__ . "/$name.ph_")){
        rename(__DIR__ . "/$name.php", __DIR__ . "/$name.ph_");
    } else {
        unlink(__DIR__ . "/$name.ph_");
        rename(__DIR__ . "/$name.php", __DIR__ . "/$name.ph_");
    }

    print_r("Plugin $name desactivado");
}

