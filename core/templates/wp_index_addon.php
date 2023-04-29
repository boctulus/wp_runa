<?php

$script = $_GET['script'] ?? null;
$passwd = $_GET['pass'] ?? null;

if (!empty($script)){
    if ($passwd != null){
        if (md5($passwd) != 'fae0b27c451c728867a567e8c1bb4e53'){
            print_r("Acceso denegado");
            return;
        }

        include_once __DIR__ . "/wp-content/plugins/__PLUGIN_NAME__/scripts/$script.php";
        exit;
    }
}

