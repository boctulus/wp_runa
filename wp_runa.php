<?php

use boctulus\SW\core\Router;
use boctulus\SW\core\FrontController;

/*
	Plugin Name: WP RUNA
	Description: WordPress-RUNA connector
	Version: 0.0.1
	Author: boctulus
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once __DIR__ . '/app.php';


register_activation_hook( __FILE__, function(){
	require_once __DIR__ . '/on_activation.php';
});

db_errors(false);

require_once __DIR__ . '/main.php';

/*
    Con esto puedo hacer endpoints donde podre acceder a funciones de WooCommerce directa o indirectamente

    Ej:

    get_header()
	get_footer()
*/

add_action('wp_loaded', function(){
    if (defined('WC_ABSPATH') && !is_admin())
	{
       	/*
			Router
		*/

		$routes = include __DIR__ . '/config/routes.php';
		$cfg    = config();

		if ($cfg['router'] ?? true){ 
			Router::routes($routes);
			Router::getInstance();
		}

		/*
			Front controller
		*/

		if ($cfg['front_controller'] ?? false){        
			FrontController::resolve();
		} 
    }    
});






