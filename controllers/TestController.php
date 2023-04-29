<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Taxes;
use boctulus\SW\core\libs\Request;

class TestController
{
    function index(){
        dd(Taxes::VATapplied(), 'IVA?');
    }

    function add($a, $b){
        dd($a + $b, "A+B");
    }
}
