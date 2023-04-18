<?php

namespace boctulus\SW\libs;

use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Reactor;
use boctulus\SW\core\libs\Products;

// class MyReactor extends Reactor 
// {  
//     private function notify($pid, $sku, $action, $product){
//         debug("SKU: $sku | ACTION: $action");

//         if (empty($sku)){
//             return;
//         }

//         try {            
//             $category_ids = Products::getCategoriesFromProductSKU($sku);

//             debug($category_ids, ">>>>> Categorias del producto con SKU '$sku' | Action: '$action'");

//             foreach ($category_ids as $category_id){
//                 $category_name = Products::getCategoryNameById($category_id);

//                 $rec = [
//                     "product_id" => $pid ?? 0,
//                     "sku" => $sku,
//                     "category_id"    => $category_id,
//                     "category_name"  => $category_name,
//                     "operation" => $action
//                 ];

//                 Logger::log($rec);
                
//                 $id = table('product_updates')
//                 ->insert($rec);

//                 Logger::log("'$id' | product_updates.id");
//             }

//             /*
//                 Aca tocaria correr en background
//             */

//             if (config()["difer_exec"] === false){
//                 MySync::run(false); /////
//             }

//             Logger::log(__FILE__ . ':'. __FUNCTION__ . ':' . __LINE__);
//         } catch (\Exception $e){
//             debug("Problema al crear notifiacion de $action sobre SKU '$sku'. Detalle: " . $e->getMessage());
//         }
//     } 

// 	function onCreate($pid, $sku, $action, $product){
//         if (Products::isSimple($pid) && empty(Products::getPrice($pid))){
//             return;
//         }

//         $this->notify($pid, $sku, $action, $product);
//     }

// 	function onUpdate($pid, $sku, $action, $product){
//         $this->notify($pid, $sku, $action, $product);
//     }
	
//     function onDelete($pid, $sku, $action, $product){
//         $this->notify($pid, $sku, $action, $product);
//     }

// 	function onRestore($pid, $sku, $action, $product){
//         // if (empty(Products::getPrice($pid))){
//         //     return;
//         // }
        
//         $this->notify($pid, $sku, $action, $product);
//     }   
// }