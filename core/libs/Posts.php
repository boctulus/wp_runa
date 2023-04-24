<?php declare(strict_types=1);

namespace boctulus\SW\core\libs;

/*
	@author boctulus
*/

class Posts 
{
    /*
        Ej de uso:

            $slug = 'aplique-espejo-impermeable-50-cm';
            Posts::getBySlug($slug, 'product')

        Retorna un array de propiedades

        [
            'ID' => 2295,
            'post_author' => '1',
            'post_date' => '2022-06-27 20:23:42',
            'post_date_gmt' => '2022-06-27 20:23:42',
            'post_content' => '',
            'post_title' => 'Aplique espejo impermeable 50 cm',
            'post_excerpt' => '',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_password' => '',
            'post_name' => 'aplique-espejo-impermeable-50-cm',
            'to_ping' => '',
            'pinged' => '',
            'post_modified' => '2022-07-11 17:00:20',
            'post_modified_gmt' => '2022-07-11 17:00:20',
            'post_content_filtered' => '',
            'post_parent' => 0,
            'guid' => 'http://woo1.lan/producto/aplique-espejo-impermeable-50-cm/',
            'menu_order' => 0,
            'post_type' => 'product',
            'post_mime_type' => '',
            'comment_count' => '0',
            'filter' => 'raw',
            'ancestors' =>
            array (
            ),
            'page_template' => '',
            'post_category' =>
            array (
            ),
            'tags_input' =>
            array (
            ),
        ]
    */

    static function getBySlug(string $slug, string $post_type = 'post', $post_status = 'publish')
    {
        $args = array(
        'name'        => $slug,
        'post_type'   => $post_type,
        'post_status' => $post_status,
        'numberposts' => 1
        );

        $arr = get_posts($args);

        return (empty($arr)) ? NULL : $arr[0]->to_array();
    }

}