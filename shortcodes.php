<?php

use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Strings;
use boctulus\SW\core\libs\Template;

/** Shortcodes */

// shortcode [runa-cotizador]
function runa_cotizador()
{
    $items = Cart::getItems();
    
    //dd(Cart::count(), 'ITEMs');
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
                        <?php foreach($items as $key => $item): ?>

                        <tr class="woocommerce-cart-form__cart-item cart_item">

                            <td class="product-remove">
                                <a href="#" class="remove" aria-label="Borrar este artículo" data-product_id="<?= $item['id'] ?>" data-product_sku="<?= $item['sku'] ?>">×</a>
                            </td>

                            <td class="product-thumbnail">
                                <?= $item['img'] ?>
                            </td>

                            <td class="product-name" data-title="Producto" data-product_id="<?= $item['id'] ?>">
                                <?= $item['link'] ?>
                                <div class="show-for-small mobile-product-price">
                                    <span class="mobile-product-price__qty"> <?= $item['qty'] ?> x </span>
                                </div>
                            </td>

                    
                            <td class="product-quantity" data-title="Cantidad">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus button is-form"> <label class="screen-reader-text"><?= $item['sku'] . ' '. $item['title'] ?></label>
                                    <input type="number" class="input-text qty text" step="1" min="0" max="5" value="<?= $item['qty'] ?>" title="Cantidad" size="4" placeholder="" inputmode="numeric">
                                    <input type="button" value="+" class="plus button is-form">
                                </div>
                            </td>

                            </td>
                        </tr>

                        <?php endforeach; ?>

                        <!-- Extras -->

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
