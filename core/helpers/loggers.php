<?php

use boctulus\SW\core\libs\Files;

/*
    Requiere que este habilitado el modo debug
*/
function logger($data, ?string $path = null, $append = true){
    if (!config()['debug']){
        return;
    }

    return Files::logger($data, $path, $append);
}

/*
    Requiere que este habilitado el modo debug
*/
function dump($object, ?string $path = null, $append = false){
    if (!config()['debug']){
        return;
    }

    return Files::dump($object, $path, $append);
}

/*
    Requiere que este habilitado el modo debug
*/
function log_error($error){
    if (!config()['debug']){
        return;
    }

    return Files::logError($error);
}

/*
    Requiere que este habilitado el modo debug y log_sql
*/
function log_sql(string $sql_str){
    if (!config()['debug'] || !config()['log_sql']){
        return;
    }

    return Files::logSQL($sql_str);
}