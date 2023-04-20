<?php

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Strings;
use boctulus\SW\core\libs\Template;

/** Shortcodes */

// shortcode [runa-cotizador]
function runa_cotizador()
{
    //dd(Cart::getItems());
?>
    <style>
        .buttons_added {
            display: table;  /* Instead of display:block */
            margin-left: auto;
            margin-right: auto;
        }
    </style>

    <div class="col large-7 pb-0 ">


        <form class="woocommerce-cart-form" action="http://woo1.lan/carrito/" method="post">
            <div class="cart-wrapper sm-touch-scroll">


                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="product-name" colspan="3">Producto</th>
                            <th class="product-quantity">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="woocommerce-cart-form__cart-item cart_item">

                            <td class="product-remove">
                                <a href="#" class="remove" aria-label="Borrar este artículo" data-product_id="9229" data-product_sku="YR0-446">×</a>
                            </td>

                            <td class="product-thumbnail">
                                <a href="http://woo1.lan/producto/001r00613-xerox-transfer-belt-cleaner/"><img width="247" height="296" src="http://woo1.lan/wp-content/uploads/2022/12/291220221672328146-247x296.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" decoding="async" loading="lazy"></a>
                            </td>

                            <td class="product-name" data-title="Producto">
                                <a href="http://woo1.lan/producto/001r00613-xerox-transfer-belt-cleaner/">001R00613 Xerox Transfer belt Cleaner</a>
                                <div class="show-for-small mobile-product-price">
                                    <span class="mobile-product-price__qty">2 x </span>
                                </div>
                            </td>

                    
                            <td class="product-quantity" data-title="Cantidad">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus button is-form"> <label class="screen-reader-text" for="quantity_6440efb7c72a9">001R00613 Xerox Transfer belt Cleaner cantidad</label>
                                    <input type="number" id="quantity_6440efb7c72a9" class="input-text qty text" step="1" min="0" max="5" name="cart[471684d6c43cfc529b30d600113dae63][qty]" value="2" title="Cantidad" size="4" placeholder="" inputmode="numeric">
                                    <input type="button" value="+" class="plus button is-form">
                                </div>
                            </td>

                            </td>
                        </tr>


                        <tr class="woocommerce-cart-form__cart-item cart_item">

                            <td class="product-remove">
                                <a href="#" class="remove" aria-label="Borrar este artículo" data-product_id="9141" data-product_sku="YR0-260">×</a>
                            </td>

                            <td class="product-thumbnail">
                                <a href="http://woo1.lan/producto/006r01461-black-toner-p-wc7120/"><img width="247" height="296" src="http://woo1.lan/wp-content/uploads/2022/12/291220221672327976-247x296.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" decoding="async" loading="lazy"></a>
                            </td>

                            <td class="product-name" data-title="Producto">
                                <a href="http://woo1.lan/producto/006r01461-black-toner-p-wc7120/">006R01461 Black Toner p/ WC7120</a>
                                <div class="show-for-small mobile-product-price">
                                    <span class="mobile-product-price__qty">1 x </span>
                                </div>
                            </td>

                          

                            <td class="product-quantity" data-title="Cantidad">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus button is-form"> <label class="screen-reader-text" for="quantity_6440efb7c7c04">006R01461 Black Toner p/ WC7120 cantidad</label>
                                    <input type="number" id="quantity_6440efb7c7c04" class="input-text qty text" step="1" min="0" max="4" name="cart[3677481dc67fc92d2347a706e9a64285][qty]" value="1" title="Cantidad" size="4" placeholder="" inputmode="numeric">
                                    <input type="button" value="+" class="plus button is-form">
                                </div>
                            </td>

                            
                        </tr>
                        <tr class="woocommerce-cart-form__cart-item cart_item">

                            <td class="product-remove">
                                <a href="#" class="remove" aria-label="Borrar este artículo" data-product_id="9168" data-product_sku="YR0-308">×</a>
                            </td>

                            <td class="product-thumbnail">
                                <a href="http://woo1.lan/producto/006r01518-yellow-toner-cartridge-p-wc7500-7835/"><img width="247" height="296" src="http://woo1.lan/wp-content/uploads/2022/12/291220221672328030-247x296.jpeg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" decoding="async" loading="lazy"></a>
                            </td>

                            <td class="product-name" data-title="Producto">
                                <a href="http://woo1.lan/producto/006r01518-yellow-toner-cartridge-p-wc7500-7835/">006R01518 Yellow Toner Cartridge p/ WC7500-7835</a>
                                <div class="show-for-small mobile-product-price">
                                    <span class="mobile-product-price__qty">6 x </span>
                                </div>
                            </td>


                            <td class="product-quantity" data-title="Cantidad">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus button is-form"> <label class="screen-reader-text" for="quantity_6440efb7c8664">006R01518 Yellow Toner Cartridge p/ WC7500-7835 cantidad</label>
                                    <input type="number" id="quantity_6440efb7c8664" class="input-text qty text" step="1" min="0" max="12" name="cart[af086cdab7954f11a518e3af68dc2fce][qty]" value="6" title="Cantidad" size="4" placeholder="" inputmode="numeric">
                                    <input type="button" value="+" class="plus button is-form">
                                </div>
                            </td>

                          
                        </tr>


                        <tr>
                            <td colspan="6" class="actions clear">
 
                                <input type="text" id="notification_email" class="regular-text" placeholder="Su correo @ lo-que-sea"/>

                                <div class="continue-shopping pull-left text-left">
                                    <a class="button-continue-shopping button primary is-outline" href="http://woo1.lan/tienda/">
                                        Obtener cotización </a>
                                </div>

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <script>
        const base_url = '<?= Url::getBaseUrl() ?>'
        const url = base_url + '/api/v1/form/save'; /// apuntar al endpoint reg. en rutas

        function setNotification(msg) {
            $('#response-output').show()
            $('#response-output').html(msg);
        }

        /*
            Agregado para el "loading,.." con Ajax
        */

        function loadingAjaxNotification() {
            <?php $path = asset('images/loading.gif') ?>
            document.getElementById("loading-text").innerHTML = "<img src=\"<?= $path ?>\" style=\"transform: scale(0.5);\" />";
        }

        function clearAjaxNotification() {
            document.getElementById("loading-text").innerHTML = "";
        }

        function do_it(e) {
            e.preventDefault();

            let jsonData = getFormData(e.currentTarget, false)

            // ...

            loadingAjaxNotification()

            jQuery.ajax({
                url: url, // post
                type: "post",
                dataType: 'json',
                cache: false,
                contentType: 'application/json',
                data: JSON.stringify(jsonData),
                success: function(res) {
                    clearAjaxNotification();

                    // if (typeof res['error'] != 'undefined'){
                    //     if (typeof res['error']['message'] != 'undefined'){
                    //         setNotification(res['error']['message']);
                    //     }
                    // }

                    console.log('RES', res);
                    setNotification("Gracias por tu mensaje. Ha sido enviado.");

                },
                error: function(res) {
                    clearAjaxNotification();

                    // if (typeof res['message'] != 'undefined'){
                    //     setNotification(res['message']);
                    // }

                    console.log('RES', res);
                    setNotification("Hubo un error. Inténtelo más tarde.");
                }
            });
        }

        jQuery('#hola_form').on("submit", function(event) {
            do_it(event);
        });
    </script>

<?php
}


add_shortcode('runa-cotizador', 'runa_cotizador');
