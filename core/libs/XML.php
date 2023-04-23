<?php

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

    /*
        Requiere del paquete de Composer spatie/array-to-xml

        composer require spatie/array-to-xml
    */
    static function fromArray(array $arr, string $root_elem = 'root', $header = true){
        if (!\Composer\InstalledVersions::isInstalled('spatie/array-to-xml')){
            throw new \Exception("Composer package spatie/array-to-xml is requiered");
        }

        if (!class_exists(\Spatie\ArrayToXml\ArrayToXml::class)){
            throw new \Exception("Class not found");
        } else {
            $class = "\Spatie\ArrayToXml\ArrayToXml";
            $converter = new $class($arr, $root_elem);
        }

        $result = $converter::convert($arr, $root_elem, $header);

        if (!$header){
            $result = trim(substr($result, 21));
        }

        return $result;
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

     /*
        https://stackoverflow.com/a/7131156/980631
    */
    static function stripTagScript(string $html) {
        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $script = $dom->getElementsByTagName('script');

        $remove = [];
        foreach($script as $item){
            $remove[] = $item;
        }

        foreach ($remove as $item){
            $item->parentNode->removeChild($item); 
        }

        $html = $dom->saveHTML();
        
        return $html;
    }

    /*
        https://davidwalsh.name/remove-html-comments-php
    */
    static function removeComments(string $html = '') {
        return preg_replace('/<!--(.|\s)*?-->/', '', $html);
    }
   
}

