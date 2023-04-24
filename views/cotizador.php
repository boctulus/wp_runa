<?php

use boctulus\SW\core\libs\Url;

?>

<style>
    .buttons_added {
        display: table;  /* Instead of display:block */
        margin-left: auto;
        margin-right: auto;
    }
</style>

<script>
    addEventListener("DOMContentLoaded", (event) => {
        if (typeof $ === 'undefined' && typeof jQuery !== 'undefined'){
            $ = jQuery
        }

        jQuery('tr > td > a.remove').click(function(e){  
            let a   = jQuery(this)
            let td  = a.parent()
            let tr  = td.parent()  

            let pid = a.data('product_id')

            console.log(pid)

            jQuery.get(`/cart/delete/${pid}`, function(data, status){
                console.log("Data: " + data + "\nStatus: " + status);
                tr.remove()
            })
            .fail(function(data) {
                console.log("error", data);
            });
        });

        jQuery('input.minus').click(function(e){  
            let pid = jQuery(this).data('product_id')
            //console.log(pid)
        });

        jQuery('input.plus').click(function(e){  
            let pid = jQuery(this).data('product_id')
            //console.log(pid)
        });
    });    
</script>


<div class="container">

    <!---  
        Los atributos "data-product_id" son utilizados por codigo Javascript -> favor de conservar

        El id "quote-cart-form" es utilizado para bindear el form --conservar--
    -->
    <form class="woocommerce-cart-form" id="quote-cart-form">
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

                        <!---  
                            Las clases "plus" y "minus" son utilizadas por codigo Javascript -> favor de conservar
                        -->
                        <td class="product-quantity" data-title="Cantidad">
                            <div class="quantity buttons_added">
                                <input type="button" value="-" class="minus button is-form" data-product_id="<?= $item['id'] ?>"> <label class="screen-reader-text"><?= $item['sku'] . ' '. $item['title'] ?></label>

                                <input type="number" class="input-text qty text" step="1" min="1" value="<?= $item['qty'] ?>" title="Cantidad" size="4" placeholder="" inputmode="numeric">
                                
                                <input type="button" value="+" class="plus button is-form" data-product_id="<?= $item['id'] ?>">
                            </div>
                        </td>

                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <!-- Extras -->

                    <tr>
                        <td colspan="6" class="actions clear">

                            <input type="email" id="notification_email" class="regular-text" required placeholder="Su correo @ lo-que-sea"/>

                            <!-- validation container -->
                            <div class="woocommerce-message message-wrapper" role="alert">
                                <div class="message-container container danger-color medium-text-center"> 
                                        
                                </div>
                                <br>
                            </div>

                            <div class="continue-shopping pull-left text-left">
                                <a class="button-quote button primary is-outline" href="/contact">Complete sus datos</a>
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

    /*
        Debe incluir el correo.....y hacer un Ajax call ...... al cotizador

        ... el cual debe enviar un correo.... y notificar luego si hubo exito o no en la operacion
    */
    const get_form_data = function()
    {
        const email = jQuery('#notification_email').val()

        if (email == ''){
            jQuery('.message-container').text('E-mail es requerido')
            throw "email esta vacio"
        }

        jQuery('.message-container').text('')

        let cart_items = []

        jQuery('td.product-name').each((index, td) => { 
            const td_el = jQuery(td)

            const id    = td_el.data('product_id');
            const a     = td_el.children('a');
            const text  = a.text();
            const qty   = parseInt(td_el.parent().children('td.product-quantity').children('div').children('input.qty').val())

            cart_items.push({
                id, qty //, text
            })
        });

        const obj      = {
            cart_items,
            email
        }

        const data    = JSON.stringify(obj)

        return data
    }

    function do_ajax_call(e) {
        e.preventDefault();

        let data = get_form_data() //getFormData(e.currentTarget, false)

        console.log(data)

        loadingAjaxNotification()

        const url = base_url + '/cart/quote'; /// apuntar al endpoint

        jQuery.ajax({
            url: url, 
            type: "POST",
            dataType: 'json',
            cache: false,
            contentType: 'application/json',
            data: (typeof data === 'string') ? data : JSON.stringify(data),
            success: function(res) {
                clearAjaxNotification();

                console.log('RES', res);
                
                //setNotification("Gracias por tu mensaje. Ha sido enviado.");

                swal({
                    title: "Enviado!",
                    text: "Recibirá la cotización en su correo",
                    icon: "success",
                });
            },
            error: function(res) {
                clearAjaxNotification();

                // if (typeof res['message'] != 'undefined'){
                //     setNotification(res['message']);
                // }

                console.log('RES', res);
                //setNotification("Hubo un error. Inténtelo más tarde.");

                swal({
                    title: "Error",
                    text: "Hubo un error. Intente más tarde.",
                    icon: "warning", // "warning", "error", "success" and "info"
                });
            }
        });
    }

    // jQuery('#quote-cart-form').on("submit", function(event) {
    //     do_ajax_call(event);
    // });

    jQuery('#ajax_call_btn').on("click", function(event) {
        do_ajax_call(event);
    });
</script>