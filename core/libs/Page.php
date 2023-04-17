<?php 

namespace boctulus\SW\core\libs;

class Page
{

    /*
        Pages
    */
    
    static function isHone(){
        return is_home();
    }

    static function is404(){
        return is_404();
    }

    /*
        Para productos, devolveria 'product'
    */
    static function getType($post_type){
        return get_post_type();
    }

    /*
        Si es una pagina de archives o categorias,
        devolveria false
    */
    static function isSingular(){
        return is_singular();
    }

    static function isCart(){
        return is_cart();
    }

    static function isCheckout(){
        return is_checkout();
    }

    static function isProductArchive(){
        return is_shop(); 
    }

    static function isProduct(){
        return is_product();
    }

    static function isProductCategory(){
        return is_product_category();
    }
    
    /*
        Extras
    */

    static function getSlug(){
        return get_post_field('post_name', get_post());
    }

    /*
        Devuelve el post con sus atributos dada la pagina actual

        @param $post_type por ejemplo 'page' o 'product'    
    */
    static function getPost($post_type = 'page') : Array {
        return get_page_by_path(static::getSlug(), ARRAY_A, $post_type );
    }

}