<?php 

namespace boctulus\SW\core\libs;

class WPNotices
{
    const SEVERITY_INFO    = 'info';
    const SEVERITY_SUCCESS = 'success';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_ERROR   = 'error';
    
    /*
        En caso de "cerrar" la notificacion, el mensaje no se volvera a mostrar
        (en caso de seguir siendo enviado) lo que dure la cookie
    */
    static function send($msg, $severity, bool $dismissible = true){    
        if (!in_array($severity, ['info', 'success', 'warning', 'error'])){
            throw new \InvalidArgumentException("Severity value can only be 'success', 'warning' or 'error'");
        }
    
        if (empty($msg)){
            Logger::log("admin_notice() con mensaje *vacio* de severidad '$severity'");
            return;
        }
    
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
    
        add_action('admin_notices', function() use ($msg, $severity, $dismissible){   
            $extras  = $dismissible ? 'is-dismissible' : '';      
            $classes = trim("notice notice-$severity $extras");
            
            $notice = <<<EOT
                <div class="$classes" id="my-custom-notice">
                    <p>$msg</p>
                </div>
            EOT;
    
            echo $notice;
            ?>
    
            <script>
            (function($) {
                $(document).on('click', '#my-custom-notice .notice-dismiss', function() {
                    var date = new Date();
                    date.setTime(date.getTime() + (86400 * 1000)); // Caducidad de la cookie: 1 dia
                    document.cookie = 'my_custom_notice_dismissed_1=1; expires=' + date.toUTCString() + '; path=/';
                });
            })(jQuery);
            </script>
    
            <?php
        }, 10, 2);
    
        add_action('admin_enqueue_scripts', function() {
            $screen = get_current_screen();
            if ($screen->id === 'dashboard') {
                wp_enqueue_script('jquery');
            }
        });
        
        add_action('admin_init', function(){
            if (isset($_COOKIE['my_custom_notice_dismissed_1']) && $_COOKIE['my_custom_notice_dismissed_1'] == 1) {
                add_action('admin_enqueue_scripts', function() {
                    wp_add_inline_script('jquery', 'jQuery(document).ready(function($) { $("#my-custom-notice").remove(); });');
                });
            }
        });
    }
}