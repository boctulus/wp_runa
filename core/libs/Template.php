<?php 

namespace boctulus\SW\core\libs;

class Template
{
    static $template;

    static function set($template)
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