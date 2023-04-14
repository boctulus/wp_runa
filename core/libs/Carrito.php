<?php

namespace boctulus\SW\core\libs;

/*
	@author boctulus
*/

class Carrito 
{
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

		//Files::logger("Seteando '$qty' unidades de $product_id");
		WC()->cart->set_quantity( $cart_item_key, $qty );
	}

	static function addToCart($product_id, $qty){
		//Files::logger("Agregando '$qty' unidades de $product_id");
		WC()->cart->add_to_cart($product_id, $qty);
	}

}