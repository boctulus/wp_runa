<?php

/*
    @author  Pablo Bozzolo boctulus@gmail.com
*/

namespace boctulus\SW\core\libs;

class XML
{
    static function toArray(string $xml){
        $xml = trim($xml);

        libxml_use_internal_errors(true);

        if (substr($xml, 0, 1) != '<'){
            if (!file_exists($xml)){
                throw new \InvalidArgumentException("File '$xml' not found");
            }    

            $objXmlDocument = simplexml_load_file($xml);
        } else {
            $objXmlDocument = simplexml_load_string($xml);
        }

        if ($objXmlDocument === false) {
            $msg = "There were errors parsing the XML file.\n";
            
            $errors = [];
            foreach(libxml_get_errors() as $error) {
                $errors[] = $error->message;
            }

            throw new \Exception($msg . implode('. ', $errors));
        }

        $objJsonDocument = json_encode($objXmlDocument);
        $arrOutput       = json_decode($objJsonDocument, true);
        
        libxml_use_internal_errors(false);

        return $arrOutput;
    }  
    
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



