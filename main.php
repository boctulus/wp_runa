<?php

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Page;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\Taxes;
use boctulus\SW\core\libs\Users;
use boctulus\SW\core\libs\Logger;
use boctulus\SW\core\libs\Plugins;
use boctulus\SW\core\libs\Template;

/*
    By boctulus
*/

// Template::set('kadence');

$quoter_slug = '/cotizador';
$quoter_stp2 = '/contact';   

/*
    Es requisito de RUNA que el IVA este aplicado
*/
if (!Taxes::VATapplied()){
    //admin_notice("Por favor habilite impuestos incluidos: WooCommerce > Impuesto > Opciones de impuestos", "error");
}

/*
    WooCommerce es una dependencia en este proyecto.
*/
if (!Plugins::isActive('woocommerce')){
    admin_notice("WooCommerce es requerido. Por favor instale y/o habilite el plugin", "error");
}

/*
    Deshabilitar carrito y checkout de forma condicional
*/
add_action('wp_loaded', function(){
    global $quoter_slug, $quoter_stp2;

    if (defined('WC_ABSPATH') && !is_admin())
	{
        if (config()['disable_cart']){
            Cart::cartRedirect($quoter_slug);
        }

        if (config()['disable_checkout']){
            Cart::checkoutRedirect($quoter_stp2);
        }
    }    
});

function sw_init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Start session on init hook.
// add_action('init', 'sw_init_session' );

require_once __DIR__ . '/shortcodes.php'; // ok

function assets(){
	// css_file('/third_party/bootstrap/bootstrap.min.css');
    // js_file('/third_party/bootstrap/bootstrap.bundle.min.js');

	css_file('/css/styles.css');

    if (Page::isCart()){
        css_file('/css/cotizador.css'); // ok
    }
    
    js_file('/third_party/sweetalert2/sweetalert.js'); // ok

    js_file('/js/utilities.js');  // ok
    js_file('/js/notices.js');  // ok
    js_file('/js/storage.js');  // ok
    js_file('/js/at_home.js');  // ok
}

enqueue('assets');

/*
    Me aseguro que la extension SimpleXML este instalada
*/
if (!in_array('SimpleXML', get_loaded_extensions())){
    Logger::log("Advertencia: Extension de PHP 'SimpleXML' no instalada!");
    admin_notice("Favor de instalar la extension de PHP 'SimpleXML'", 'error');
}


/////////////////////////[ ACTIONS ]///////////////////////////

// require_once __DIR__ . '/scripts/hide_prices.php';

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

///////////////////////////////////////////////////////////////


Page::replaceContent(function(&$content){
    // $content = preg_replace('/Mi cuenta/', "CuentaaaaaaaX", $content);
});



