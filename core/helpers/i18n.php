<?php


/*  
    De momento, solo llama a funcion nativa de WP 
    que cambia el idioma
*/
function setLang(?string $lang){
    // Translate::setLang($lang);
    switch_to_locale($lang);
}

/*  
    De momento, no hace nada
*/
function trans($str){
    //return Translate::trans($text);
    return $str;
}