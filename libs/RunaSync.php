<?php

namespace boctulus\SW\libs;

use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Products;
use boctulus\SW\core\libs\ApiClient;

class RunaSync 
{
    static function get_xml(){
        $cfg = config();
    
        $url = $cfg['api_base_url'] . $cfg['endpoints']['stock_xml_gen'];
    
        $client = ApiClient::instance()
        ->logReq($cfg['log_requests'])
        ->logRes($cfg['log_responses']);
    
        $client
        ->disableSSL()
        ->setUrl($url)
        ->cache(1800)
        ->get()
        ->getResponse();
    
        $status = $client->getStatus();
    
        if ($status != 200){
            throw new \Exception($client->error());
        }
    
        // Doy tiempo a que se genere el archivo XML
    
        if (!get_transient('xml_generado')){
            sleep(15);
            set_transient('xml_generado', true, 360);
        } 
    
        $url = $cfg['api_base_url'] . $cfg['endpoints']['stock_xml_get'];
    
        $client = ApiClient::instance()
        ->logReq($cfg['log_requests'])
        ->logRes($cfg['log_responses']);
    
        $client
        ->disableSSL()
        ->setUrl($url)
        ->cache(1800)
        ->get()
        ->getResponse();
    
        $status = $client->getStatus();
    
        if ($status != 200){
            throw new \Exception($client->error());
        }
    
        //dd($client->getCachePath(), 'CACHE');
    
        return $client->data();
    }
    
    /*
        Sincronización

        Ej:

        php .\sync.php
        php .\sync.php YR0-360,YR0-365,YR0-368

        En general,

        php .\sync.php [{codigos}]
    */
    static function init($codes = null)
    {   
        if (!empty($codes)){
            $codes = explode(',', $codes);
        }

        $stock_xml = static::get_xml();
        
        if (empty($stock_xml)){
            throw new \Exception("No stock?");
        }
        
        $data  = XML::toArray($stock_xml);
        $prods = $data['art'];

        // Purga en caso de que haya filtrado por codigo (sku)

        if (!empty($codes)){
            foreach ($prods as $ix => $p){
                if (!in_array($p["cod"], $codes)){
                    unset($prods[$ix]);
                }
            }
        }
        
        $processed = 0;
        foreach ($prods as $p){
            $sku   = $p['cod'];
            $stock = $p['can'];

            try {
                $pid = Products::getProductIdBySKU($sku);

                if (!empty($pid)){
                    /*
                        SI existe, actualizo
                    */   

                    debug("Actualizando producto existente con SKU= '$sku' | pid= $pid");
                    wc_update_product_stock($pid, $stock);                     

                } else {
                    // ..
                }

                $processed++;

            } catch (\Exception $e){
                $msg = $e->getMessage();
                debug($msg);
                Logger::log($msg);
            }           

        } // end foreach

        
        dd("Se procesaron $processed productos.");
    }

}