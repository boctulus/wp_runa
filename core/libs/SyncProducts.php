<?php

namespace boctulus\SW\core\libs;

/*
	@author boctulus
*/

class SyncProducts
{
    static function delete(Array $skus){
        foreach($skus as $sku){
            Products::deleteProductBySKU($sku);
        }
    }

    static function restore(Array $skus){
        foreach($skus as $sku){
            Files::logger("Restaurando producto con SKU '$sku'");
            Products::restoreBySKU($sku);
        }
    }

    static function import(Array $products, Array $simple_product_attrs = null)
    {
        $errors    = [];
        $processed = 0;

        debug("Procesando ". count($products) . " productos");

        foreach($products as $p)
        {
            $processed++;

            dd("Procesando el producto # $processed");

            $sku = $p['sku'] ?? null;

            if ($sku == null){
                debug("Skipping producto without SKU");
                continue;
            }

            debug("...");

            try {
                $pid = Products::getProductIdBySKU($sku);

                if (!empty($pid)){
                    /*
                        SI existe, actualizo
                    */   

                    debug("Actualizando producto existente con SKU '$sku' (pid = $pid)");
                
                    products::updateProductBySku($p);             
                } else {
                    /*
                        Sino existe, lo creo
                    */

                    debug("Creando producto para SKU '$sku'");

                    $pid = Products::createProduct($p);   
                }

                /*
                    Agrego atributos si los hubiera
                */

                $simple_product_attrs = $p['attributes'] ?? null;

                if ($p['type'] == 'simple' && !empty($simple_product_attrs)){
                    Products::setProductAttributesForSimpleProducts($pid, $simple_product_attrs); 
                }

            } catch (\Exception $e){
                $msg      = $e->getMessage();
                $errors[] = $msg;
                debug($msg);
            }    
        }

        
        return [
            'errors'    => $errors,
            'processed' => $processed
        ];

    }


}
