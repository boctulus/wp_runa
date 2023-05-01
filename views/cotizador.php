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

                            <?php if (empty($items)){
                                echo "No hay productos para cotizar en su cesta";
                            }
                            ?>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="6" class="actions clear">

                            <br>
                            <div class="pull-left text-left">
                                <?php
                                    if (empty($items)):
                                ?>        
                                    <a class="button-quote button primary is-outline" href="<?= get_permalink(wc_get_page_id('shop')) ?>">Volve a la tienda</a>
                                <?php
                                    else:
                                ?>

                                <a class="button-quote button primary is-outline" href="#" id="to_contact">Complete sus datos</a>

                                <?php
                                    endif;
                                ?>
                            </div>

                        </td>
                    </tr>

                </tbody>
            </table>
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

        toStorage({ "cart_items": form_data })

        // y redirecciono a /contact

        window.location.replace('/contact')
    });

    const get_form_data = function()
    {
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

        return cart_items
    }

</script>