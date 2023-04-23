<?php

namespace boctulus\SW\controllers;

class CotizadorController
{
    function index(){
        get_header();
        runa_cotizador(); // shortcode
        get_footer();
    }

}
