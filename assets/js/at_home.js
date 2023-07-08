
if (!is_admin_page())
{   
    /*
        Remuevo subtotal de desplegable 
    */

    jQuery('.et_b_header-cart').click((e) => {
        e.preventDefault();
        jQuery('.woocommerce-mini-cart__total').hide();

        // fixeo enlace que se rompe
        jQuery('.mini-cart-buttons').click(()=> {location.replace('/carrito')})

        // parche para mi local
        if (window.location.origin != 'https://zoh.deltaservidor.com'){
            jQuery(jQuery('span.et-svg')[0]).click(()=> {location.replace('/cart')})
        }
    })
    

    /*
        Reemplaza boton de ver carrito en el navbar por boton "ir a cotizacion"
    */

    const quoter_url  = '/cotizador/'
    const new_button  = `<li class="account-item has-icon has-dropdown"><a href="${quoter_url}" class="account-link"><span class="header-account-title go_quote_btn">IR A COTIZACIÓN</span></a></li>`

    jQuery('.header-cart-link').parent().replaceWith(new_button)    
}
