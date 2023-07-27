<?php

use boctulus\SW\core\libs\Arrays;
use boctulus\SW\core\libs\Cart;
use boctulus\SW\core\libs\Url;
use boctulus\SW\core\libs\Users;
use boctulus\SW\core\libs\Strings;
use boctulus\SW\core\libs\Products;

/*
    Notificaciones emebebidas en el frontoffice

    (requieren de FontAwesome)
*/

css_file('css/my_notices.css');

// FontAwesome

css_file('third_party/fontawesome5/all.min.css');
js_file('third_party/fontawesome5/fontawesome_kit.js');

$is_logged  = Users::isLogged();
$cart_items = Cart::getItems();

$no_stock = [];
$stocks   = [];
foreach ($cart_items as $ix => $cart_item) {
    $pid    = $cart_item['id'];
    $sku    = $cart_item['sku'];
    $_stock = Products::getStock($pid);

    if ($_stock === 0){
        /*
            Elimino de la visualizacion de items de carrito
        */
        foreach ($items as $ix => $item){
            if ($item['sku'] === $sku){
                unset($items[$ix]);
            }
        }

        $no_stock[] = [
            'pid'   => $pid,
            'sku'   => $sku,
            'title' => $cart_item['title'],
            'color' => $cart_item['color']
        ];

        /*
            Remuevo del carrito
        */
        
        Cart::remove($pid);

    }

    $stocks[$sku] = $_stock;    
}
?>

<script>
    addEventListener("DOMContentLoaded", (event) => {
        if (typeof $ === 'undefined' && typeof jQuery !== 'undefined') {
            $ = jQuery
        }

        /*
            Para evitar inconcistencias dado que la cantidad en el carrito
            cambia por Ajax, mejor lo oculto o destruyo el contador
        */

        jQuery(jQuery("span.et-cart-quantity.et-quantity")[0]).remove()

        // Rueditas de carga
        $('.elementor-element-65cdc8a').hide()
        $('.elementor-element-54380b0').hide()

        $('#clear-cart-button').click(function(e) {
            e.preventDefault();

            $.post(`/cart/empty`, function(data, status) {
                    $('.remove-item').closest('tr').remove(); // Elimina todos los elementos del carrito
                    console.log('Carrito borrado');
                })
                .fail(function(data) {
                    console.log("error", data);
                });

            return false;
        });

        // Actualizado 7/7/23
        $('.remove-item').click(function(e) {
            e.preventDefault();
            let a = $(this);
            let tr = a.closest('tr');

            let pid = a.data('pid');

            console.log(pid);

            $.get(`/cart/delete/${pid}`, function(data, status) {
                    console.log("Data: " + data + "\nStatus: " + status);
                    tr.remove();
                })
                .fail(function(data) {
                    console.log("error", data);
                });
        });

        /*
            Si desea disminuir la cantidad de un recurso existente identificado por el ID, 
            se podría considerar un enfoque más apropiado utilizando un endpoint como este:

            PUT /api/v1/items/{id}/decrease_qty

            En este caso, el verbo HTTP PUT se utilizaría para modificar el recurso existente identificado por el ID proporcionado en la URL. 
            El cuerpo de la solicitud puede contener los detalles necesarios para disminuir la cantidad.
        */

        $('.minus').click(function(e) {
            e.preventDefault();
            let input = $(this).parent().find('input.qty');
            let qty = parseInt(input.val());

            if (qty <= 1) {
                return;
            }

            qty--;
            input.val(qty);

            var pid = input.data("pid");

            if (typeof pid === 'undefined') {
                return;
            }

            // console.log("Product ID:", pid);
            // console.log("Cantidad actual:", qty);

            // $.post(`/cart/decrement/${pid}/${qty}`, function (data, status) {
            //    console.log(`DEC O.K. para PID=${pid}`);
            // })
            // .fail(function (data) {
            //     console.log("error", data);
            // });
        });

        // Actualizado 12/7/23
        $('.plus').click(function(e) {
            e.preventDefault();
            let input = $(this).parent().find('input.qty');
            let qty = parseInt(input.val());

            qty++;
            input.val(qty);

            var pid = input.data("pid");

            if (typeof pid === 'undefined') {
                return;
            }

            // console.log("Product ID:", pid);
            // console.log("Cantidad actual:", qty);

            // $.post(`/cart/increment/${pid}/${qty}`, function (data, status) {
            //    console.log(`INC O.K. para PID=${pid}`);
            // })
            // .fail(function (data) {
            //     console.log("error", data);
            // });
        });


    });
</script>

<div id="runa_container" style="margin-bottom:5vh">


    <!---  
        Los atributos "data-pid" son utilizados por codigo Javascript -> favor de conservar

        El id "quote-cart-form" es utilizado para bindear el form --conservar--
    -->
    <form class="woocommerce-cart-form" id="quote-cart-form">

        <?php foreach($no_stock as $my_item): ?>
            

            <?php
                $pid_no_stock = $my_item['pid'];
                
                if (Products::getPostType($pid_no_stock) == 'product_variation'){
                    // $variation = Products::getProduct($pid_no_stock);
                    // $title     = $variation->get_formatted_name(); 

                    $title     = "{$my_item['title']} ({$my_item['color']}) -{$my_item['sku']}-";
                } else {
                    $title     = Products::getName($pid_no_stock); 
                }

            ?>

            <div class="message-box message-box-warn">
                <i class="fa fas fa-warning fa-2x"></i>
                <span class="message-text"><strong>Agotado:</strong> <?= $title ?></span>
                <i class="fa fas fa-times fa-2x exit-button "></i>
            </div>
        <?php endforeach; ?>       

        <div class="table-responsive">
            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <thead>
                    <tr>
                        <th class="product-details" colspan="2">Producto</th>
                        <th class="product-price">Precio</th>
                        <th class="product-sku">SKU</th>
                        <th class="product-quantity">Cantidad</th>
                        <th class="product-subtotal" colspan="2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- foreach -->
                    <?php foreach ($items as $key => $item) : ?>

                        <?php
                        // dd($item);                      
                        // exit;
                        ?>

                        <tr class="woocommerce-cart-form__cart-item cart_item st-item-meta">
                            <td class="product-name" data-title="Producto" data-pid="<?= $item['id'] ?>">
                                <div class="product-thumbnail">
                                    <a href="<?= $item['url'] ?>">
                                        <img width="100" height="100" src="<?= $item['img_url'] ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">

                                    </a>
                                </div>
                            </td>
                            <td class="product-details">
                                <div class="cart-item-details">
                                    <a href="<?= $item['url'] ?>" data-pid="<?= $item['id'] ?>"><?= $item['title'] ?></a> <label class="screen-reader-text"><?= $item['sku'] . ' ' . $item['title'] ?></label>

                                    <dl class="variation">
                                        <dt class="variation-Color" style="display:none;">Color</dt>
                                        <dd class="variation-Color">
                                            <p><?= ucfirst($item['color']) ?></p>
                                        </dd>
                                    </dl>

                                    <!-- boton de borrado OK -->
                                    <a href="#" aria-label="Borrar este artículo" data-pid="<?= $item['id'] ?>" data-product_sku="<?= $item['sku'] ?>" class="remove-item text-underline" title="Eliminar este artículo">Eliminar</a>
                                    <span class="mobile-price sf-hidden"></span>
                                </div>
                            </td>

                            <td class="product-price" data-title="Precio">
                                <?= $is_logged ? Strings::formatNumber($item['price']) : '' ?>
                            </td>

                            <td class="product-sku" data-title="SKU" data-pid="<?= $item['id'] ?>">
                                <?= $item['sku'] ?>
                            </td>

                            <td class="product-quantity" data-title="Cantidad">
                                <div class="quantity">
                                    <span class="minus"><i class="minus et-icon et-minus"></i></span> <label class="screen-reader-text"><?= $item['sku'] . ' ' . $item['title'] ?></label>

                                    <input type="number" class="input-text qty text" step="1" min="1" value="<?= $item['qty'] ?>" title="Cantidad" size="4" placeholder="" inputmode="numeric" autocomplete="off" data-pid="<?= $item['id'] ?>"><label class="screen-reader-text"><?= $item['sku'] . ' ' . $item['title'] ?></label>

                                    <span class="plus"><i class="plus et-icon et-plus"></i></span>
                                </div>
                            </td>
                            <td class="product-subtotal" data-title="Subtotal">
                                <?= $is_logged ? Strings::formatNumber($item['qty'] * $item['price']) : '' ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                    <!-- endforeach -->

                    <?php if (count($items) == 0) : ?>
                        <tr class="woocommerce-cart-form__cart-item cart_item st-item-meta">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="product-name" data-title="Producto">
                                No hay productos para cotizar en su cesta
                            </td>
                            <td></td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="actions clearfix">
            <div class="col-md-12 col-sm-12 mob-center">

                <div class="pull-right text-right" style="margin-right: -15px;">
                    <?php
                    if (empty($items)) :
                    ?>
                        <a class="button-quote btn bordered" href="<?= get_permalink(wc_get_page_id('shop')) ?>">Volve a la tienda</a>
                    <?php
                    else :
                    ?>

                        <a class="button-quote btn bordered" href="#" id="to_contact">Complete sus datos</a>

                    <?php
                    endif;
                    ?>
                </div>

                <div class="pull-left text-left" style="margin-left: -15px;">
                    <a class="clear-cart btn bordered" <?= (count($items) == 0 ? 'disabled' : '');  ?> id="clear-cart-button">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24" xml:space="preserve" width=".8em" height=".8em" fill="currentColor">
                            <g>
                                <path d="M8.8916016,6.215332C8.8803711,6.2133789,8.8735352,6.2143555,8.8666992,6.2148438
                                C8.5517578,6.2197266,8.2988281,6.4799805,8.3037109,6.7944336v13.0141602
                                c-0.0024414,0.1523438,0.0551758,0.296875,0.1621094,0.40625s0.25,0.171875,0.4033203,0.1738281h0.0078125
                                c0.3115234,0,0.5683594-0.2519531,0.5722656-0.5605469c0.0004883-0.0087891,0.0004883-0.0175781,0-0.0195312V6.7954102
                                c0.0019531-0.152832-0.0551758-0.2973633-0.1616211-0.4077148C9.1806641,6.2783203,9.0380859,6.2167969,8.8916016,6.215332z">
                                </path>
                                <path d="M20.8701172,2.578125c-0.0117188-0.0009766-0.0195312-0.0009766-0.0214844,0l-0.9433594,0.0004883
                                c-0.0735035,0-0.1163521-0.0004883-0.1796875-0.0004883h-4.0292969V1.5893555c0-0.8901367-0.7246094-1.6142578-1.6142578-1.6142578
                                H9.9179688c-0.8901367,0-1.6142578,0.7241211-1.6142578,1.6142578V2.578125L4.2807617,2.5786133
                                c-0.0660129,0-0.106863-0.0004883-0.1723633-0.0004883H3.1420898c-0.1494141,0-0.2905273,0.0571289-0.3984375,0.1611328
                                c-0.1098633,0.1074219-0.1713867,0.2504883-0.1733398,0.402832c-0.0024414,0.152832,0.0551758,0.2978516,0.1621094,0.4077148
                                s0.25,0.171875,0.4033203,0.1738281h0.4833984v18.6875c0,0.8896484,0.7241211,1.6142578,1.6137695,1.6142578h13.5336914
                                c0.890625,0,1.6152344-0.7246094,1.6152344-1.6142578v-18.6875h0.4736328c0.1513672,0,0.2939453-0.0576172,0.4003906-0.1621094
                                c0.109375-0.1064453,0.171875-0.2495117,0.1738281-0.402832C21.4335938,2.8427734,21.1816406,2.5820312,20.8701172,2.578125z
                                M9.4492188,2.578125V1.5893555c0-0.2583008,0.2104492-0.46875,0.46875-0.46875h4.1640625
                                c0.2578125,0,0.4677734,0.2104492,0.4677734,0.46875V2.578125H9.4492188z M19.2353516,3.7236328v18.6875
                                c0,0.2578125-0.2099609,0.4677734-0.46875,0.4677734H5.2329102c-0.2583008,0-0.4682617-0.2099609-0.4682617-0.4677734v-18.6875
                                h4.0161133c0.0634766,0.0097656,0.1254883,0.0097656,0.1782227,0h6.0683594c0.0644531,0.0097656,0.1259766,0.0097656,0.1787109,0
                                H19.2353516z">
                                </path>
                                <path d="M12.0146484,6.215332c-0.0112305-0.0019531-0.0180664-0.0009766-0.0249023-0.0004883
                                c-0.3149414,0.0048828-0.5673828,0.2651367-0.5625,0.5795898v13.0141602
                                c-0.0019531,0.1523438,0.0551758,0.296875,0.1616211,0.4072266c0.105957,0.109375,0.2490234,0.1699219,0.4033203,0.1728516H12
                                c0.3115234,0,0.5683594-0.2539062,0.5727539-0.5654297V6.7954102c0.0019531-0.1533203-0.0551758-0.2978516-0.1616211-0.4077148
                                C12.3041992,6.2783203,12.1616211,6.2167969,12.0146484,6.215332z">
                                </path>
                                <path d="M14.5498047,6.7944336v13.0141602c-0.0019531,0.1523438,0.0566406,0.296875,0.1630859,0.40625
                                c0.1064453,0.1103516,0.25,0.171875,0.4033203,0.1738281h0.0068359c0.3115234,0,0.5683594-0.2539062,0.5732422-0.5654297V6.7954102
                                c0.0019531-0.1542969-0.0556641-0.2988281-0.1621094-0.4077148s-0.2470703-0.1699219-0.3974609-0.1728516
                                c-0.0078125-0.0019531-0.0175781-0.0019531-0.0234375,0C14.7988281,6.2197266,14.5458984,6.4799805,14.5498047,6.7944336z">
                                </path>
                            </g>
                        </svg>
                        Borrar carrito</a>
                </div>

                <button type="submit" class="btn gray medium bordered hidden wp-element-button sf-hidden" name="update_cart" value="Update cart" disabled="" aria-disabled="true">Update cart</button>
            </div>
        </div>

    </form>


</div>


<script>
    jQuery('#to_contact').on("click", function(event) {
        form_data = get_form_data();

        console.log(form_data)

        /*
             Almaceno dentro de SessionStorage
        */

        toStorage({
            "cart_items": form_data
        })

        // y redirecciono a /contact

        window.location.replace('/contact')
    });

    const get_form_data = function() {
        let cart_items = []

        jQuery('td.product-name').each((index, td) => {
            const td_el = jQuery(td)

            const pid = td_el.data('pid');
            const a = td_el.children('a');
            const text = a.text();
            const qty = parseInt(td_el.parent().children('td.product-quantity').children('div').children('input.qty').val())

            $.post(`/cart/set_qty/${pid}/${qty}`, function(data, status) {
                    //    console.log(`INC O.K. para PID=${pid}`);
                })
                .fail(function(data) {
                    // console.log("error", data);
                });

            cart_items.push({
                pid,
                qty //, text
            })
        });

        return cart_items
    }
</script>