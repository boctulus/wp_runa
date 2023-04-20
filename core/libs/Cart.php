<?php

namespace boctulus\SW\core\libs;

/*
	@author boctulus
*/

class Cart 
{
	static function count(){
		global $woocommerce; 

		return $woocommerce->cart->cart_contents_count;
	}

	static function getItems($simple_format = true){
    	global $woocommerce;

    	$items = $woocommerce->cart->get_cart();

		$arr = [];
		foreach($items as $item) { 
			$prod = wc_get_product( $item['product_id'] );

			$p = [];

			$p['id']            = $item['data']->get_id();
			$p['img']           = $prod->get_image(); // accepts 2 arguments ( size, attr )
			$p['img_url']       = Strings::match($p['img'], '/< *img[^>]*src *= *["\']?([^"\']*)/i');
			$p['title']         = $prod->get_title();
			$p['url']           = get_post_permalink($item['id']);
			$p['link']          = '<a href="'. get_post_permalink($p['id']). '">'. $p['title'] .'</a>';
			$p['qty']           = $item['quantity'];

			$p['line_subtotal']      = $item['line_subtotal']; 
			$p['line_subtotal_tax']  = $item['line_subtotal_tax'];

			// gets the cart item total
			$p['line_total']         = $item['line_total'];
			$p['line_tax']           = $item['line_tax'];

			// unit price of the product
			$p['item_price']         = $p['line_subtotal'] / $p['qty'];
			$p['item_tax']           = $p['line_subtotal_tax'] / $p['qty'];

			/*
				Product object data

				Nota: los precios en el carrito pueden no corresponderse a los precios actuales
			*/
			
			$p['price']         = get_post_meta($item['product_id'] , '_price', true);
			$p['regular_price'] = get_post_meta($item['product_id'] , '_regular_price', true);
			$p['sale_price']    = get_post_meta($item['product_id'] , '_sale_price', true);
			$p['sku']			= get_post_meta($item['product_id'] , '_sku', true);
			
			$arr[] = $p;
		}

		return $arr;    
	}

	static function find($product_id){
		$product_cart_id = WC()->cart->generate_cart_id( $product_id );
   		$cart_item_key   = WC()->cart->find_product_in_cart( $product_cart_id );

		return $cart_item_key;
	}

	static function setQuantity($product_id, int $qty)
	{
		if (empty($cart_item_key)){
			$cart_item_key = static::find($product_id);
		}

		//Logger::log("Seteando '$qty' unidades de $product_id");
		WC()->cart->set_quantity( $cart_item_key, $qty );
	}

	static function addToCart($product_id, $qty){
		//Logger::log("Agregando '$qty' unidades de $product_id");
		WC()->cart->add_to_cart($product_id, $qty);
	}

}