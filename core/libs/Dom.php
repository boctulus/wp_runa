<?php

/*
    @author  Pablo Bozzolo boctulus@gmail.com
*/

namespace boctulus\SW\core\libs;

class Dom
{
    static function getDomDocument(string $html){
        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_use_internal_errors(false);

        return $doc;
    }  
    
    static function getXPath(string $html){
        return new \DOMXPath(
            static::getDomDocument($html)
        );
    }   

}

