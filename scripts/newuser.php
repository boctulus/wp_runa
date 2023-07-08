<?php
// ADD NEW ADMIN USER TO WORDPRESS
// ----------------------------------
// Put this file in your Wordpress root directory and run it from your browser.
// Delete it when you're done.

require_once __DIR__ . '/../../../../wp-blog-header.php';
// require_once __DIR__ . '/../../../../wp-includes/registration.php';

// ----------------------------------------------------
// CONFIG VARIABLES
// Make sure that you set these before running the file.
$username = 'boctulus1';
$password = 'gogogo2k!';
$email    = 'boctulus@gmail.com';
// ----------------------------------------------------


// Check that user doesn't already exist
if ( !username_exists($username) && !email_exists($email) )
{
	// Create user and set role to administrator
	$user_id = wp_create_user( $username, $password, $email);
	if ( is_int($user_id) )
	{
		$wp_user_object = new WP_User($user_id);
		$wp_user_object->set_role('administrator');
		
        echo 'Successfully created new admin user. Now delete this file!';

        // Autologin

        $user_data = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember, // Opcional, si se quiere recordar al usuario
        );
        
        $user = wp_signon($user_data, false);
        
        if (is_wp_error($user)) {
            $error_message = $user->get_error_message();
            echo $error_message;
        } else {
            $redirect_to = user_admin_url();
            wp_safe_redirect( $redirect_to );
            exit();
        }
	}
	else {
		echo 'Error with wp_insert_user. No users were created.';
	}
}
else {
	echo 'This user or email already exists. Nothing was done.';
}

