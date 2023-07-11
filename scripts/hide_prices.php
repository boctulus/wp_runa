<?php

use boctulus\SW\core\libs\Users;

/*
    Oculta el precio si el usuario no esta logueado
*/

function customized_price_html( $price, $product ) { 
    if (!Users::isLogged()){
        $price = '';
    }
    
    return $price; 
} 
    
add_filter( 'woocommerce_get_price_html', 'customized_price_html', 100, 2 ); 


/*
    Hide prices del cart
*/

function hide_cart_item_prices( $price, $cart_item, $cart_item_key ) {
    return '';
}
add_filter( 'woocommerce_cart_item_price', 'hide_cart_item_prices', 10, 3 );

function hide_cart_totals( $value ) {
    return '';
}
add_filter( 'woocommerce_cart_totals_order_total_html', 'hide_cart_totals' );

function hide_cart_item_totals( $total, $cart_item, $cart_item_key ) {
    return '';
}
add_filter( 'woocommerce_cart_item_subtotal', 'hide_cart_item_totals', 10, 3 );


/*
    Extras, por si las dudas
*/

add_action('wp_loaded', function(){
    if (defined('WC_ABSPATH') && !is_admin())
    {
        if (!Users::isLogged()){
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        }
    }    
});

