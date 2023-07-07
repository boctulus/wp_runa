
if (!is_admin_page())
{
    console.log('HERE');

    /*
        Remuevo subtotal de desplegable 
    */

    jQuery('.et_b_header-cart').click((e) => {
        e.preventDefault();
        jQuery('.woocommerce-mini-cart__total').remove();
    })
    

    /*
        Reemplaza boton de ver carrito en el navbar por boton "ir a cotizacion"
    */

    const quoter_url  = '/cotizador/'
    const new_button  = `<li class="account-item has-icon has-dropdown"><a href="${quoter_url}" class="account-link"><span class="header-account-title go_quote_btn">IR A COTIZACIÓN</span></a></li>`

    jQuery('.header-cart-link').parent().replaceWith(new_button)    
}
