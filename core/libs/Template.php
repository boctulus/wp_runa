<?php 

namespace boctulus\SW\core\libs;

class Template
{
    static $template;

    /*
        Cambia temporalmente el "theme" de WordPress 

        Ejemplo de uso:

        Template::set('kadence');

        @param string $template
    */  
    static function set(string $template)
    {
        require_once (ABSPATH . WPINC . '/pluggable.php');

        static::$template = $template;

        add_filter( 'template', function( $template ) {
            if (!empty(static::$template)){
                $template = static::$template;
            }
    
            return $template;
        });

        add_filter( 'stylesheet', function( $template ) {
            if (!empty(static::$template)){
                $template = static::$template;
            }
    
            return $template;
        });
    }
}