<?php

namespace boctulus\SW\controllers;

class Cotizador
{
	public function __construct() {
		add_action('wp_loaded', array($this, 'add_filters'));
	}

	public function add_filters() {
		// add_filter( 'wafs_match_condition_subtotal', array( $this, 'wafs_match_condition_subtotal' ), 10, 3 );
		/// ..
	}

    function index(){
        switch_to_locale('en_US');

        require_once ABSPATH . '/wp-load.php';

        /*
            Load an alternate header file by using the $name param:
                
                <?php get_header( 'special' ); ?>

            As second parameter in get_header() we can pass an array

                <?php
                // in index.php or where you want to include header
                get_header( '', array( 'name' => 'Ruhul Amin', 'age' => 23 ) ); 
                ?>
        */

        global $wp;

        require_once ABSPATH . '/wp-config.php';
        
        $wp->init();
        $wp->parse_request();
        $wp->query_posts();
        $wp->register_globals();
        $wp->send_headers();

        require_once(ABSPATH . '/wp-blog-header.php');
        header("HTTP/1.1 200 OK");
        header("Status: 200 All rosy");

        get_header();

       //echo 'Saludo generico!';
    }

}
