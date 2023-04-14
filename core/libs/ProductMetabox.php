<?php

namespace boctulus\SW\core\libs;

/*
    @author Pablo Bozzolo <boctulus@gmail.com>

    Al constructor o a setMetaAtts() pasar un array con los nombres de los atributos.

    Ej:

    $meta_atts = [
        'Att name 1',
        'Att name 2',
    ];
*/

class ProductMetabox 
{
    protected $meta_atts;

    function __construct(Array $meta_atts = [])
    {   
        if (!empty($meta_atts)){
            $this->meta_atts = $meta_atts;
        }

        add_action('add_meta_boxes', [$this, 'productos_meta_box']);
        add_action('save_post', [$this, 'save_productos_meta_box_data']);
    }

    function setMetaAtts(Array $meta_atts){
        $this->meta_atts = $meta_atts;
        return $this;
    }

    function productos_meta_box($screen = 'product') {
        global $meta_atts;
    
        foreach ($meta_atts as $meta){
            $meta_id    = str_replace([' ', '-'], '_', $meta);
            $meta_title = $meta;
    
            $meta_callback = function ( $post ) use ($meta_id, $meta_title) {
                // Add a nonce field so we can check for it later.
                wp_nonce_field( 'productos_nonce', 'productos_nonce' );
                
                $value = get_post_meta($post->ID, '_'.$meta_id, true);
            
                echo '<textarea style="width:100%" id="'.$meta_id.'" name="'.$meta_title.'">' . esc_attr( $value ) . '</textarea>';
            };
    
            add_meta_box(
                $meta_id,
                $meta_title,
                $meta_callback,
                $screen
            );    
        }
    }    
    
    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id
     */
    function save_productos_meta_box_data( $post_id ) {
        // O uso global o creo una clase
        global $meta_atts;
    
        // Check if our nonce is set.
        if ( ! isset( $_POST['productos_nonce'] ) ) {
            return;
        }
    
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['productos_nonce'], 'productos_nonce' ) ) {
            return;
        }
    
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
    
        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
    
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
    
        }
        else {
    
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
    
        /* OK, it's safe for us to save the data now. */
    
        foreach ($meta_atts as $meta){
            $meta_id = str_replace([' ', '-'], '_', $meta);
    
            if (isset( $_POST[$meta_id])) {
                $data = sanitize_text_field( $_POST[$meta_id] );
                dd($data, $meta_id);
        
                update_post_meta( $post_id, "_{$meta_id}", $data ); 
            }
        }    
    }
}

