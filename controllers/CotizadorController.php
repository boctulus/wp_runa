<?php

namespace boctulus\SW\controllers;

class CotizadorController
{
    function index(){
        get_header();
        ?>
        <div>
            <h1>Hello World</h1>
            
            <br><br><br><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br><br>
        </div>

        <?php
        get_footer();
    }

    function cotizar($prod)
    {
        switch($prod){
            case 'iphone':
                return 1000;
            case 'android':
                return 700;

            default:
                return 'Producto desconocido';
        }
    }

}
