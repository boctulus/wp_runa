<?php

use boctulus\SW\core\libs\Page;
use boctulus\SW\core\libs\Users;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Template;

/*
    By boctulus
*/

//Template::set('kadence');


add_action('wp_loaded', function(){
    if (defined('WC_ABSPATH') && !is_admin())
	{
        //Cart::addRandomly(2);
    }    
});

function sw_init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Start session on init hook.
// add_action('init', 'sw_init_session' );

require_once __DIR__ . '/shortcodes.php';

function assets(){
	//css_file('/css/bootstrap/bootstrap.min.css');
	//js_file('/js/bootstrap/bootstrap.bundle.min.js');

    css_file('/css/styles.css');
    
    js_file('/js/sweetalert.js');
    js_file('/js/notices.js');

    if (!Users::isLogged()){
        js_file('/js/not_logged.js');
    }
}

enqueue('assets');

/*
    Me aseguro que la extension SimpleXML este instalada
*/
if (!in_array('SimpleXML', get_loaded_extensions())){
    Logger::log("Advertencia: Extension de PHP 'SimpleXML' no instalada!");
    admin_notice("Favor de instalar la extension de PHP 'SimpleXML'", 'error');
}


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
    Cambio el boton de "Agrear al carrito" por otro como "Cotizar" si el usuario
    no esta lougueado
*/
function woocommerce_add_to_cart_button_text() {  
    $text = 'Add to cart';

    if (!Users::isLogged()){
        $text = config()['add_to_cart_button_text'] ?? 'Add to cart';
    }

    return __($text, 'woocommerce' ); 
}

// Change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_add_to_cart_button_text' ); 

// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text',        'woocommerce_add_to_cart_button_text' );  


/*
    Cambiar el texto del boton "proceed to checkout" y la url del checkout

    Otra forma de cambiar textos:
    https://stackoverflow.com/a/34290090/980631
*/

function custom_button_proceed_to_checkout() {
    $text = "Proceed to checkout";
    
    if (!Users::isLogged()){
        $text = "Cotizar pedido";
    }

    echo '<a href="'.esc_url(wc_get_checkout_url()).'" class="checkout-button button alt wc-forward">' .
    __($text, "woocommerce") . '</a>';
}

remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_proceed_to_checkout', 'custom_button_proceed_to_checkout', 20 );

function new_checkout_url( $url ) {
    if (!Users::isLogged()){
        $url = "/bla/bla/mi_nuevo_checkout";  // quizas no quiera cambiar la url
    }
    
    return $url;
}
add_filter( 'woocommerce_get_checkout_url', 'new_checkout_url', 30 );


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
       // dd(Users::isLogged(), 'Logged?');

        if (!Users::isLogged()){
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
        
            //add_filter( 'woocommerce_is_purchasable', '__return_false' );
            //add_filter( 'woocommerce_get_price_html', '__return_empty_string' );
        }
    }    
});




////////////////////////////////////////////


Page::replaceContent(function(&$content){
    // $content = preg_replace('/Mi cuenta/', "CuentaaaaaaaX", $content);
});



