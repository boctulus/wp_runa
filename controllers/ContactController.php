<?php

namespace boctulus\SW\controllers;

use boctulus\SW\core\libs\Files;

class ContactController
{
    function index(){
        $comunas = Files::getCSV(ETC_PATH . '/comunas.csv', ",", true);

        get_header();
        view('contact_form', ['comunas' => $comunas['rows']]);
        get_footer();
    }

}
