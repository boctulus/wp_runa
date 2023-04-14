<?php

use boctulus\SW\core\libs\Url;

/** Shortcodes */

// shortcode
function my_sc()
{
    ?>
                
    <script>

        const base_url = '<?= Url::getBaseUrl() ?>'
        const url      = base_url + '/api/v1/form/save';  /// apuntar al endpoint reg. en rutas

        function setNotification(msg){
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

        function do_it(e){
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

        jQuery('#hola_form').on("submit", function(event){ do_it(event); });
    </script>

    <?php
}


add_shortcode('my_sc', 'my_sc');