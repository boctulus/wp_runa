<?php

namespace boctulus\SW\libs;

class RUT 
{
    static $rut_err_class = 'rut-error-message';

    static function setRUTerrorClass(string $rut_err_class){
        static::$rut_err_class = $rut_err_class;
    }

    /*
        @author: Christian Vergara Reyes

        Ejemplos de RUTs para Chile validos:

        18098211-6
        7427969-4
        6439773-7
        16742481-3
        14414630-1
        7515738-k
        8218199-7
        14559208-9
        12618454-9
        8841615-5

        Requiere de un campo de validacion para RUT como el
    */
    static function formatear(){
        wp_enqueue_script('jquery');

        ?>
        <script>
            if (typeof $ === 'undefined'){
                $ = jQuery
            }

            const formatear_rut = function(rut) {
                rut = rut.replace(/[^0-9Kk]/g, "");
                rut = rut.replace(/^0+/, "");

                if (rut.length > 9) {
                    rut = rut.slice(0, 9);
                }

                var rut_formato = "";
                var cuerpo = rut.slice(0, -1);
                var dv = rut.slice(-1).toUpperCase();
                var i = cuerpo.length - 1;
                var contador = 0;

                while (i >= 0) {
                    if (contador === 3) {
                        rut_formato = "." + rut_formato;
                        contador = 0;
                    }
                    rut_formato = cuerpo[i] + rut_formato;
                    contador++;
                    i--;
                }

                if (rut.length > 0) {
                    rut_formato = rut_formato + "-" + dv;
                }

                return rut_formato;
            }

            const validar_rut = function(rut) {

                if (rut.length < 3) {
                    return false;
                }

                rut = rut.replace(/[^0-9Kk]/g, "");

                var cuerpo = rut.slice(0, -1);
                var dv = rut.slice(-1).toUpperCase();
                var suma = 0;
                var multiplo = 2;

                for (var i = cuerpo.length - 1; i >= 0; i--) {
                    suma += multiplo * cuerpo[i];
                    multiplo = (multiplo < 7) ? multiplo + 1 : 2;
                }

                var modulo = 11;
                var residuo = suma % modulo;
                var dv_calculado = modulo - residuo;

                if (dv_calculado === 10) {
                    dv_calculado = "K";
                } else if (dv_calculado === 11) {
                    dv_calculado = "0";
                }

                return dv.toString().toUpperCase() === dv_calculado.toString();
            }

            jQuery(document).ready(function(jQuery) {               
                //var error_message = jQuery('<span class="rut-error-message" style="color:red;display:none;"></span>');
                //rut.after(error_message);

                let error_message = $('.<?= static::$rut_err_class ?>')

                var rut_el = jQuery('#rut');

                rut_el.on('input', function() {
                    var rut = rut_el.val();
                    var rut_formato = formatear_rut(rut);

                    console.log(rut_formato)

                    if (rut !== rut_formato) {
                        rut_el.val(rut_formato);
                    }

                    if (!validar_rut(rut)) {
                        error_message.text('Por favor, ingrese un RUT válido.');
                        error_message.css('display', 'block');
                    } else {
                        error_message.hide();
                    }
                });

                // jQuery('form.checkout').on('checkout_place_order', function() {
                //     var rut = rut_el.val();

                //     if (!validar_rut(rut)) {
                //         return false;
                //     } else {
                //         return true;
                //     }
                // });
            });
        </script>
        <?php
    }
}