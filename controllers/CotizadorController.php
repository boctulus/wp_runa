<?php

namespace boctulus\SW\controllers;

class CotizadorController
{
    function index(){
        return 'Cotizador';
    }

    function cotizar(){
        $params = request()->getParams();
    
        if (empty($params)){
            return 'Nada que cotizar';
        }

        $prod = $params[0];

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
