<?php

use boctulus\SW\core\libs\Users;


function sw_init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Start session on init hook.
// add_action('init', 'sw_init_session' );

require_once __DIR__ . '/shortcodes.php';

function assets(){
	#css_file('/css/bootstrap/bootstrap.min.css');
	#js_file('/js/bootstrap/bootstrap.bundle.min.js');

    //css_file('/css/styles.css');
    //js_file('/js/utilities.js');
    //js_file('/js/sweetalert.js');
}

// enqueue('assets');

/*
    Me aseguro que la extension SimpleXML este instalada
*/
if (!in_array('SimpleXML', get_loaded_extensions())){
    Files::logger("Advertencia: Extension de PHP 'SimpleXML' no instalada!");
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


