<?php

use boctulus\SW\core\libs\Cart;


// shortcode [runa-cotizador]
function runa_cotizador()
{
    $items = Cart::getItems();

    view('cotizador', ['items' => $items]);
}


add_shortcode('runa-cotizador', 'runa_cotizador');
