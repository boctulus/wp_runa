<?php

namespace boctulus\SW\controllers;

class ContactController
{
    function index(){
        get_header();
        view('contact_form');
        get_footer();
    }

}
