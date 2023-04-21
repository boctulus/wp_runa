

/*
    Reemplaza boton de ver carrito en el navbar por boton "ir a cotizacion"
*/

const quoter_url  = '/2023/04/16/cotizador/'
const new_button  = `<li class="account-item has-icon has-dropdown"><a href="${quoter_url}" class="account-link"><span class="header-account-title go_quote_btn">IR A COTIZACIÓN</span></a></li>`

if (!is_admin_page()){
    jQuery('.header-cart-link').parent().replaceWith(new_button)
}

jQuery('tr > td > a.remove').click(function(e){  
	let a   = jQuery(this)
	let td  = a.parent()
	let tr  = td.parent()  

	let pid = a.data('product_id')

	console.log(pid)

	jQuery.get(`/my_cart/delete/${pid}`, function(data, status){
	    console.log("Data: " + data + "\nStatus: " + status);
	    tr.hide()
	})
	.fail(function(data) {
    	console.log("error", data);
  	});
	
});