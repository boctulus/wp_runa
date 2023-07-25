<?php

namespace boctulus\SW\libs;

use boctulus\SW\core\libs\XML;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Products;
use boctulus\SW\core\libs\ApiClient;
use boctulus\SW\core\libs\FileCache;
use ElementorPro\Modules\Woocommerce\Documents\Product;

class RunaSync 
{
    /*
        Devuelve stocks
    */
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
        
        $stock_xml = FileCache::get('xml_stock_cached');

        if (empty($stock_xml)){
            $stock_xml = static::get_xml();
            FileCache::put('xml_stock_cached', $stock_xml, config()['cache_exp']);
        } else {
            //dd("Usando data de cache. Cache file: " . FileCache::getCachePath('cache_exp'));
        }
       
        if (empty($stock_xml)){
            throw new \Exception("No stock?");
        }
        
        $data  = XML::toArray($stock_xml);
        $prods = $data['art'];

        // dd($prods, 'PRODS');

        // Purga en caso de que haya filtrado por codigo (sku)

        if (!empty($codes)){
            foreach ($prods as $ix => $p){
                if (!in_array($p["cod"], $codes)){
                    unset($prods[$ix]);
                }
            }
        }
        
        $processed       = 0;
        $processed_codes = [];

        foreach ($prods as $p){
            $sku   = $p['cod'];
            $stock = $p['can'];

            // Parche porque RUNA envia duplicados para mitigar en algo el esfuerzo
            if (in_array($sku, $processed_codes)){
                continue;
            }

            try {
                $pid = Products::getProductIdBySKU($sku);

                $post_type = Products::getPostType($pid);

                if ($post_type == 'product_variation'){
                    $parent_pid = wp_get_post_parent_id($pid);
                }

                if (!empty($pid)){
                    /*
                        SI existe, actualizo
                    */   

                    debug("Actualizando producto existente con SKU= '$sku' | pid=$pid" . (isset($parent_pid) ? " (variation of pid=$parent_pid)" : '') );
                    wc_update_product_stock($pid, $stock);                     

                } else {
                    // ..
                }

                $processed_codes[] = $sku;
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