<?php

function sw_init_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Start session on init hook.
// add_action('init', 'sw_init_session' );

require_once __DIR__ . '/shortcodes.php';

function assets(){
	#css_file('/css/bootstrap/bootstrap.min.css');
	#js_file('/js/bootstrap/bootstrap.bundle.min.js');

    css_file('/css/styles.css');
    js_file('/js/utilities.js');
    js_file('/js/sweetalert.js');
}

enqueue('assets');



