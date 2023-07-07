<?php

use boctulus\SW\libs\RUT;
use boctulus\SW\core\libs\Url;

js_file('contact_form.js');
// js_file('contact_form.css')

RUT::formateador();

?>

<div class="container">
    <h1>Datos de contacto</h1>

    <form id="quoter_contact_form">
        
        <table class="form-table" role="presentation">
            <tbody>

                <!-- Name -->
                <tr class="nom-wrap">
                    <th><label for="nom">Nombre <span class="description">(obligatorio)</span></label></th>
                    <td><input type="text" name="nom" id="nom" class="regular-text" pattern="[a-zA-Z0-9 ñÑáéíóú]+" required > 
                    <!-- <span class="description">Nombre completo o razón social</span> -->
                </td>
                </tr>

                <tr class="rut-wrap">
                    <th><label for="rut">RUT <span class="description">(obligatorio)</span></label></th>
                    <td>
                        <input type="text" name="rut" id="rut" class="regular-text" required>
                        <span class="validation-error rut-error-message" style="color:red;display:none;"></span>
                    </td>                
                </tr>

                <tr class="gir-wrap">
                    <!-- <th><label for="gir">gir</label></th>
                    <td><input type="text" name="gir" id="gir" class="regular-text"></td> -->

                    <th>
                        <label for="gir">Giro<span class="description">(obligatorio)</span></label>
                    </th>
                    <td>
                        <select name="gir" id="gir" required>
                            <option selected="selected"></option>
                            <option id="sin giro">Sin giro</option>
                            <option id="con giro">Con giro</option>
                        </select>
                    </td>
                </tr>

                <tr class="fon-wrap">
                    <th><label for="fon">Teléfono <span class="description">(obligatorio)</span></label></th>
                    <td><input type="tel" name="fon" id="fon" class="regular-text" required></td>
                </tr>

                <tr class="email-wrap">
                    <th><label for="ema">E-mail <span class="description">(obligatorio)</span></label></th>
                    <td><input type="email" name="ema" id="ema" class="regular-text" placeholder="Su correo @ lo-que-sea" required ></td>
                </tr>

                <tr class="dir-wrap">
                    <th><label for="dir">Dirección <span class="description">(obligatorio)</span></label></th>
                    <td><input type="text" name="dir" id="dir" class="regular-text" required></td>
                </tr>

                <tr class="user-display-com-wrap">
                    <th>
                        <label for="com">Comuna <span class="description">(obligatorio)</span></label>
                    </th>
                    <td>
                        <select name="com" id="com" required>
                            <option selected="selected"></option>
                            
                            <?php foreach($comunas as $com): ?>
                                <option id="<?= $com['codigo'] ?>"><?= $com['comuna'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <!-- Extras -->

                <tr>
                    <td colspan="6" class="actions clear">

                        <!-- validation container -->
                        <div class="" id="validation container" role="alert"><!-- woocommerce-message message-wrapper -->
                            <div class="message-container container medium-text-center"><!-- danger-color --> 
                                    &nbsp;
                            </div>
                            <br>
                        </div>

                        <div class="pull-left text-left">
                            <a class="button-quote button primary is-outline" href="#" id="ajax_call_btn">Obtener cotización</a>
                        </div>

                        <div class="continue-shopping pull-left text-left">
                            <a class="button-quote button primary is-outline" href="<?= get_permalink(wc_get_page_id('shop')) ?>">Volve a la tienda</a>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>

    </form>
    


    <!-- id es usado por JS, favor de conservar -->
    <div id="loading-text"></div>
    
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
    function do_ajax_call(data) 
    {
        loadingAjaxNotification()

        const url = base_url + '/cart/quote'; /// apuntar al endpoint

        //console.log(data);

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

                console.log('RES ERROR', res);
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

    /*
        Al cargar la pagina, si el form estaba salvado en storage,
        se recupera y se rellena automaticamente el form
    */
    window.addEventListener("DOMContentLoaded", (event) => {
        let contact_data = fromStorage()?.contact
    
        if (contact_data != '' || contact_data != null){
            // re-populate form
            fillForm(contact_data)
        }  
    });

    jQuery('#ajax_call_btn').on("click", function(event) {  

        // obj
        let prev_data  = fromStorage()

        let cart_items = prev_data?.cart_items

        if (cart_items == null || Object.keys(cart_items).length === 0){
            jQuery('.message-container').text('No hay nada que cotizar')
            return
        }
        
        /*
            for each campo => validar => agregar / remover clases css
        */
        
        if (prev_data['ema'] == ''){
            jQuery('.message-container').text('E-mail es requerido')
            return
        }

        // obj
        let contact = {            
            "contact" : getFormData($("#quoter_contact_form"), false)
        }

        ///////////////////////////////////// 
        // Ahora almaceno de nuevo en Storage

        let data = toStorage(contact)

        /*
            Si todo sale bien, limpio errores
        */

        jQuery('.message-container').text('')

        //console.log(data)

        do_ajax_call(data);
    });
</script>