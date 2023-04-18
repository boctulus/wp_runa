<?php

namespace boctulus\SW\controllers;

class CotizadorController
{
    function index(){
        return 'Cotizador';
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
