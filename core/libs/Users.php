<?php

/*
    @author  Pablo Bozzolo boctulus@gmail.com
*/

namespace boctulus\SW\core\libs;

class Users
{
    static function isLogged(){
        return !empty(get_current_user_id());
    }

    /*
        Admitir el username en el request seria para un caso extremo
        por cuestiones de seguridad
    */
    static function getCurrentUserId(bool $read_username_from_req = false){
        $uid = get_current_user_id();

        if ($uid === 0 && $read_username_from_req){
            $username = Request::getInstance()
            ->getBodyParam('uname');

            $uid = static::getUserIdByUsername($username);
        }

        return $uid;
    }

    static function getUserByEmail($email){
        return get_user_by( 'email', $email);
    }

    static function getUserIdByEmail($email){
        $u = get_user_by( 'email', $email);

        if (!empty($u)){
            return $u->ID;
        }
    }

    static function getUserIdByUsername($username){
        $u = get_user_by( 'login', $username);

        if (!empty($u)){
            return $u->ID;
        }
    }

    static function userExistsByEmail($email){
        return !empty( get_user_by( 'email', $email) );
    }
    
    /*
        https://wordpress.stackexchange.com/a/111788/99153
    */
    static function roleExists($role) {
        if( ! empty( $role ) ) {
            return $GLOBALS['wp_roles']->is_role( $role );
        }

        return false;
    }

    static function createRole(string $role_name, $role_title = null, Array $capabilities = []){
        $role_name = strtolower($role_name);

        if ($role_title === null){
            $role_title = ucfirst($role_name);
        }

        if (empty($capabilities)){
            $capabilities =  array(
                'read'         => true, // true allows this capability
                'edit_posts'   => true,
                'delete_posts' => true, 
            );
        }

        $result = add_role(
            $role_name,
            __( $role_title ),
           $capabilities
        );

        return (null !== $result);
    }

    /**
     * hasRole 
     *
     * function to check if a user has a specific role
     * 
     * @param  string  $role    role to check against 
     * @param  int  $user_id    user id
     * @return boolean
     * 
     */
    static function hasRole($role, $user = null){
        if (!is_user_logged_in()){
            return false;
        }

        if (empty($user)){
            $user = wp_get_current_user();
        } else {
            if (is_numeric($user) ){
                $user = get_user_by('id', $user);
            }
        }
            
        if ( empty( $user ) )
            return false;

        return in_array( $role, (array) $user->roles );
    }

    /*
        Parece ignorar cualquier rol distinto del primero
    */
    static function addRole($role, $user = null){
        if (empty($user)){
            $user = wp_get_current_user();
        } else {
            if (is_numeric($user) ){
                $user = get_user_by('id', $user);
            }
        }   
        
        return $user->add_role( $role );
    }

    static function removeRole($role, $user = null){
        if (empty($user)){
            $user = wp_get_current_user();
        } else {
            if (is_numeric($user) ){
                $user = get_user_by('id', $user);
            }
        }   

        return $user->remove_role( $role );
    }

    static function getRoleNames() {
        global $wp_roles;
        
        if ( ! isset( $wp_roles ) )
            $wp_roles = new \WP_Roles();
        
        return $wp_roles->get_names();
    }

    static function getUsersByRole(Array $roles) {
        $query = new \WP_User_Query(
           array(
              'fields' => 'ID',
              'role__in' => $roles, 
              'limit' => -1        
           )
        );

        return $query->get_results();
    }

    static function getUserIDList() {
        $query = new \WP_User_Query(
           array(
              'fields' => 'ID',
              'limit' => -1                 
           )
        );

        return $query->get_results();
    }
    
    static function getCustomerList() {
        $query = new \WP_User_Query(
           array(
              'fields' => 'ID',
              'role' => 'customer',
              'limit' => -1                 
           )
        );

        return $query->get_results();
    }
}