<?php

namespace boctulus\SW\controllers\formalidades;

class SaludoController
{
    function saludar(){
        dd('Te saludo');
    }

    function insultar(){
        return 'Te insulto';
    }

    function index(){
        return 'Saludo generico';
    }

    function save_form(){
        return 'salvando';
    }
}