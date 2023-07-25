<?php

use boctulus\SW\libs\RUT;
use boctulus\SW\core\libs\Url;

js_file('contact_form.js');
// js_file('contact_form.css')

RUT::formateador();

$user_id = get_current_user_id();

if (!empty($user_id)){
    // Obtener el último pedido del usuario
    // $orders = wc_get_orders(array(
    //     'customer' => $user_id,
    //     'limit' => 1,
    //     'orderby' => 'date',
    //     'order' => 'DESC',
    // ));

    // if (!empty($orders)) {
    //     // Si el usuario tiene al menos un pedido, obtener la información del último pedido
    //     $last_order      = reset($orders); // Obtiene el primer elemento del array, que es el último pedido

    //     $billing_first_name = $order->get_billing_first_name();
    //     $billing_last_name  = $order->get_billing_last_name();
    //     $billing_company    = $order->get_billing_company();

    //     $billing_phone      = $last_order->get_billing_phone();
    //     $billing_email      = $last_order->get_billing_email();
    //     $billing_address    = $last_order->get_address('billing'); // Devuelve un array con la dirección de facturación

    //     $user_full_name = !empty($billing_company) ? $billing_company : "$billing_first_name  $billing_last_name";
    // } else {
        $current_user = wp_get_current_user();

        $first_name      = $current_user->user_firstname;
        $last_name       = $current_user->user_lastname;
        $user_full_name  = "$first_name $last_name";

        $billing_phone   = get_user_meta($user_id, 'billing_phone', true);
        $billing_email   = get_user_meta($user_id, 'billing_email', true);
        $billing_address = [
            'address_1' => get_user_meta($user_id, 'billing_address_1', true),
            'address_2' => get_user_meta($user_id, 'billing_address_2', true),
            'city'      => get_user_meta($user_id, 'billing_city', true),
            'state'     => get_user_meta($user_id, 'billing_state', true),
            'postcode'  => get_user_meta($user_id, 'billing_postcode', true),
            'country'   => get_user_meta($user_id, 'billing_country', true),
        ];
    // }   
}
?>

<script>

let backend_userdata = {
    name:    "<?= $user_full_name ?>",
    phone:   "<?= $billing_phone ?>",
    email:   "<?= $billing_email ?>",
    address: "<?= $billing_address['address_1'] ?>",
    zipcode: "<?= $billing_address['postcode'] ?>",
}

addEventListener("DOMContentLoaded", (event) => {
        if (typeof $ === 'undefined' && typeof jQuery !== 'undefined') {
            $ = jQuery
        }

        // Rueditas de carga
        $('.elementor-element-65cdc8a').hide()
        $('.elementor-element-54380b0').hide()
})    
</script>

<div id="runa_container">
    <h1>Datos de contacto</h1>

    <form id="quoter_contact_form">
        
        <table class="form-table" role="presentation">
            <tbody>

                <!-- Name -->
                <tr class="nom-wrap">
                    <th><label for="nom">Nombre <span class="description">(obligatorio)</span></label></th>
                    <td>
                        <input type="text" name="nom" id="nom" class="regular-text" pattern="[a-zA-Z0-9 ñÑáéíóú]+" required > 
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>
                </tr>

                <tr class="rut-wrap">
                    <th><label for="rut">RUT <span class="description">(obligatorio)</span></label></th>
                    <td>
                        <input type="text" name="rut" id="rut" class="regular-text" required>
                        <span class="validation-error <?= \boctulus\SW\libs\RUT::$rut_err_class ?>" style="color: red; display: none;"></span>
                    </td>                
                </tr>

                <tr class="gir-wrap">
                    <th>
                        <label for="gir">Giro<span class="description">(obligatorio)</span></label>
                    </th>
                    <td>
                        <input type="text" name="gir" id="gir" class="regular-text" required>
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>
                </tr>

                <tr class="fon-wrap">
                    <th>
                        <label for="fon">Teléfono <span class="description">(obligatorio)</span></label>
                    </th>
                    <td>
                        <input type="tel" name="fon" id="fon" class="regular-text" required>
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>
                </tr>

                <tr class="ema-wrap">
                    <th><label for="ema">E-mail <span class="description">(obligatorio)</span></label></th>
                    <td>
                        <input type="email" name="ema" id="ema" class="regular-text" placeholder="Su correo @ lo-que-sea" required >
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>                    
                </tr>

                <tr class="dir-wrap">
                    <th><label for="dir">Dirección <span class="description">(obligatorio)</span></label></th>
                    <td>
                        <input type="text" name="dir" id="dir" class="regular-text" required>
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>
                </tr>

                <tr class="com-wrap">
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
                        <span class="validation-error" style="color: red; display: block;"></span>
                    </td>
                </tr>

                <!-- Extras -->

                <tr>
                    <td colspan="6" class="actions clear">      
                                         
                        <div class="continue-shopping pull-left text-left">
                            <a class="button-quote btn bordered" href="<?= get_permalink(wc_get_page_id('shop')) ?>">Volve a la tienda</a>
                        </div>        
                       
                        <a class="button-quote btn bordered" href="javascript:void(0)" id="ajax_call_btn">Obtener cotización</a>


                         <!-- validation container -->
                         <div class="" id="validation container" role="alert" ><!-- woocommerce-message message-wrapper -->
                            <div class="message-container container medium-text-center"><!-- danger-color --> 
                                    &nbsp;
                            </div>
                            <br>
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
        const contact = data.contact

         // Validation checks
        const validations = {
            'nom': 'Nombre',
            'rut': 'RUT',
            'gir': 'Giro',
            'fon': 'Teléfono',
            'ema': 'E-mail',
            'dir': 'Dirección',
            'com': 'Comuna',
        };

        // Validate email format using regular expression
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        let error_message = {};

        let hasErrors = false;
        for (const field in validations) 
        { 
            const inputType = document.querySelector(`[name="${field}"]`).type;
        
            /*
                Validacion de campo tipo email
            */
            if (inputType === 'email'){
                if (!emailRegex.test(contact.ema)) {
                    error_message = document.querySelector(`.${field}-wrap .validation-error`);
                    error_message.textContent = `Por favor, ingrese ${validations[field]}.`;
                    error_message.style.display = "block";
                    hasErrors = true;
                    continue;
                }
            }

            /*
                Validacion de campo personalizdo tipo RUT chileno
            */
            if (field == 'rut'){
                if (!validar_rut(contact.rut)) {
                    error_message = document.querySelector('.<?= \boctulus\SW\libs\RUT::$rut_err_class ?>');
                    error_message.textContent = "Por favor, ingrese un RUT válido.";
                    error_message.style.display = "block";
                    hasErrors = true;
                    continue;
                }
            }

            /*
                Resto de validaciones
            */
            if (typeof contact[field] === 'undefined' || contact[field].trim() === "") {
                hasErrors = true;
                error_message = document.querySelector(`.${field}-wrap .validation-error`);
                if (error_message) {
                    error_message.textContent = `Por favor, ingrese ${validations[field]}.`;
                    error_message.style.display = "block";
                }
            } else {
                error_message = document.querySelector(`.${field}-wrap .validation-error`);
                if (error_message) {
                    error_message.style.display = "none";
                }
            }
        }   

        if (hasErrors) {
            return; // Return early if there are validation errors
        }

        loadingAjaxNotification()

        const url = base_url + '/cart/quote'; /// apuntar al endpoint

        // console.log(data);

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

        // Si no hay nada.... intento recuperar de info en el backend
        if (contact_data == '' || contact_data == null || contact_data == {}){
            contact_data = {}
            contact_data.nom = backend_userdata.name
            contact_data.fon = backend_userdata.phone       
            contact_data.ema = backend_userdata.email
            contact_data.dir = backend_userdata.address
            contact_data.com = backend_userdata.zipcode

            toStorage(contact_data)
        }  
    
        if (contact_data != '' && contact_data != null && contact_data != {}){
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

        let contact_data = getFormData($("#quoter_contact_form"), false);

        // Elimino puntos y guiones del RUT
        contact_data.rut = contact_data.rut.replace(/[.-]/g, '');

        // obj
        let contact = {            
            "contact" : contact_data
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

    const clearContactForm = () => {
        fillForm({
            "nom": "",
            "rut": "",
            "gir": "",
            "fon": "",
            "ema": "",
            "dir": "",
            "com": ""
        })

        toStorage({contact: null})
    }

    // Solo util para testing
    const wipeContactInfo = () => {
        toStorage({contact: null})
    }
</script>