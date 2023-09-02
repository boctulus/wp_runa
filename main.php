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

require_once __DIR__ . '/wp_crons.php';


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

require_once __DIR__ . '/shortcodes.php'; 

add_action( 'woocommerce_after_single_product', function(){
    ?>
        <script>
            <?= file_get_contents(ROOT_PATH . 'assets/js/min-max.js') ?>
        </script>
    <?php
}, 10, 0 );

// ok
function assets(){
	// css_file('/third_party/bootstrap/bootstrap.min.css');
    // js_file('/third_party/bootstrap/bootstrap.bundle.min.js');

    if (!Page::isHome()){
        css_file('/css/styles.css');
    }

    if (Page::isCart()){
        css_file('/css/cotizador.css'); 
    }
    
    // if (Page::isPage('product') || Page::isPage('cotizador')){
        // css_file('/css/xstore_cart/xstore_cart.min.css');
        // css_file('/css/xstore_cart/elementor.min.css');
        // css_file('/css/xstore_cart/slick.min.css');
        // css_file('/css/xstore_cart/breadcrumbs.min.css');
        // css_file('/css/xstore_cart/back-top.min.css');
        // css_file('/css/xstore_cart/mobile-panel.min.css');
        // css_file('/css/xstore_cart/global.min.css');
        // css_file('/css/xstore_cart/archive.min.css');
        // css_file('/css/xstore_cart/swatches.min.css');
        // css_file('/css/xstore_cart/single-product-builder.min.css');
        // css_file('/css/xstore_cart/single-product-elements.min.css');
        // css_file('/css/xstore_cart/star-rating.min.css');
        // css_file('/css/xstore_cart/comments.min.css');
        // css_file('/css/xstore_cart/meta.min.css');
        // css_file('/css/xstore_cart/contact-forms.min.css');
        // css_file('/css/xstore_cart/menu.min.css');
        // css_file('/css/xstore_cart/search.min.css');
        // css_file('/css/xstore_cart/product-view-default.min.css');
        // css_file('/css/xstore_cart/blog-global.min.css');
        // css_file('/css/xstore_cart/portfolio.min.css');
        // css_file('/css/xstore_cart/kirki-styles.css');
        // css_file('/css/xstore_cart/style.css');
        // css_file('/css/xstore_cart/categories-carousel.min.css');
        // css_file('/css/xstore_cart/navigation.min.css');
        // css_file('/css/xstore_cart/ajax-search.min.css');
        // css_file('/css/xstore_cart/full-width-search.min.css');
        // css_file('/css/xstore_cart/categories-carousel.min.css');
        // css_file('/css/xstore_cart/off-canvas.min.css');
        // css_file('/css/xstore_cart/cart-widget.min.css');
        // css_file('/css/xstore_cart/mobile-menu.min.css');
        // css_file('/css/xstore_cart/toggles-by-arrow.min.css');
        // css_file('/css/xstore_cart/tabs.min.css');
        // css_file('/css/xstore_cart/navigation.min.css');
        // css_file('/css/xstore_cart/etheme-icon-list.min.css');
        // css_file('/css/xstore_cart/photoswipe.min.css');
    // }
    
    js_file('/third_party/sweetalert2/sweetalert.js'); 

    js_file('/js/utilities.js');  
    js_file('/js/notices.js');  
    js_file('/js/storage.js');  
    js_file('/js/at_home.js');  
}

enqueue('assets');
enqueue_admin('assets');


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

    echo '<a href="'.esc_url(wc_get_checkout_url()).'" class="checkout-button button alt wc-forward se-rompe">' .
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





// Page::replaceContent(function(&$content){
//     // $content = preg_replace('/Mi cuenta/', "CuentaaaaaaaX", $content);
// });



