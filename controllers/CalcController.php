<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Request;

class CalcController
{
    function index(){
        return 'Calculadora';
    }

    function add($a, $b)
    {
        $res = (int) $a + (int) $b;
        return  "$a + $b = " . $res;
    }

    // function mul()
    // {
    //     $req = Request::getInstance();
    //     $res = (int) $req[0] * (int) $req[1];
    //     return "$req[0] * $req[1] = " . $res . PHP_EOL;
    // }

    function div()
    {
        $ch = request();
        $res = $ch->getParam(0) / $ch->getParam(1);

        //dd($res);
        //
        // hacer un return en vez de un "echo" me habilita a manipular
        // la "respuesta", conviertiendola a JSON por ejemplo 
        //

        return [
            'result' => $res
        ];
    }

    function inc($val)
    {
        $res = (float) $val + 1;
        response($res);
    }

    function inc2($val)
    {
        $res = (float) $val + 1;
        return $res;
    }

    function inc3($val)
    {
        $res = (float) $val + 1;
        response($res);
    }

}
