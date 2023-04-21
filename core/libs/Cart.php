<?php

namespace boctulus\SW\core\libs;

/*
	@author boctulus
*/

class Cart 
{
	static function getCart(){
		// $WC_ABSPATH =  ABSPATH . "\wp-content\plugins\woocommerce";

		// include_once $WC_ABSPATH . 'includes/wc-cart-functions.php';
		// include_once $WC_ABSPATH . 'includes/class-wc-cart.php';
    	// include_once $WC_ABSPATH . 'includes/wc-notice-functions.php';
        // include_once $WC_ABSPATH . 'includes/wc-template-hooks.php';

		// if ( null === WC()->session ) {
		// 	$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
		
		// 	WC()->session = new $session_class();
		// 	WC()->session->init();
		// }
		
		// if ( null === WC()->customer ) {
		// 	WC()->customer = new \WC_Customer( get_current_user_id(), true );
		// }
		
		if ( null === WC()->cart ) {
			WC()->cart = new \WC_Cart();
		}
		
		return WC()->cart;
	}

	/*
		Devuelve la cantidad de productos distintos
	*/
	static function count(bool $distinct){
		if (!$distinct){
			return static::getCart()->get_cart_contents_count();
		}

		return count(static::getItems());
	}

	static function getItems(){
    	$items = static::getCart()->get_cart();

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
		$cart = static::getCart();

		$product_cart_id = $cart->generate_cart_id( $product_id );
   		$cart_item_key   = $cart->find_product_in_cart( $product_cart_id );

		return $cart_item_key;
	}

	static function getQuantity($product_id){
		// Obtener la cantidad de cada producto en el carrito
		$cart_item_quantities = WC()->cart->get_cart_item_quantities();

		// Buscar el product_id en el array
		if (isset($cart_item_quantities[$product_id])) {
			// Devolver la cantidad correspondiente
			return $cart_item_quantities[$product_id];
		} else {
			// Si el producto no está en el carrito, devolver 0
			return 0;
		}
	}

	/*
		Los siguientes metodos *no* funciona via Ajax y tampoco generaran error alguno
	*/

	static function setQuantity($product_id, int $qty)
	{
		/*
			Por alguna extrana razon es necesario llamar a getQuantity() antes
			o sino falla
		*/

		$cart = static::getCart();
		$prev = static::getQuantity($product_id);

		$cart_item_key = null;

		if (empty($cart_item_key)){
			$cart_item_key = static::find($product_id);
		}

		if (empty($cart_item_key)){
			//Logger::log("No se pudo encontrar item con pid=$product_id en carrito");
			return false;
		}

		//Logger::log("Seteando '$qty' unidades de $product_id");
		$cart->set_quantity( $cart_item_key, $qty );

		//Logger::log(static::getQuantity($product_id), "Nueva cantidad para pid=$product_id en carrito");

		if (static::getQuantity($product_id) != $qty){
			//Logger::log("No se pudo cambiar cantidad para item con pid=$product_id en carrito");
			return false;
		}

		return true;
	}

	/*
		Add to cart X units
	*/
	static function add($product_id, $qty){
		$cart     = static::getCart();

		$prev_qty = static::getQuantity($product_id);

		//Logger::log("Agregando '$qty' unidades de $product_id");
		$cart->add_to_cart($product_id, $qty);

		$expected = $prev_qty + $qty;

		if (static::getQuantity($product_id) != $expected){
			return false;
		}

		//Logger::log(static::getQuantity($product_id), "Nueva cantidad para pid=$product_id en carrito");

		return true;
	}

	static function remove($product_id){
		return static::setQuantity($product_id, 0);
	}

}