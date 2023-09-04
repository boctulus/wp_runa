/*  
  Corrige implementacion trunca de <input type="number"> donde el max=""
  solo se retringe con las flechas pero no al ingresar por teclado

  Version con JQuery
*/

document.addEventListener('DOMContentLoaded', function() {

    setTimeout(function(){
      const inputElementsWithMax = jQuery('input[type="number"][max]');
    
      inputElementsWithMax.each(function() {
        const $input = jQuery(this);
        // Buscar out-of-stock no deberia ser necesario
        const max = $('p').hasClass("out-of-stock") ? 0 : parseInt($input.attr('max'));
    
        $input.on('change keyup keydown input', function() {
          const currentValue = parseInt($input.val());
          if (!isNaN(currentValue) && currentValue > max) {
            $input.val(max); 
          }
        });
      });  

    }, 700)

    // Aplico otra tecnica
    jQuery('body').on('keyup keydown','input[type="number"][max]',function(){
      const $input = jQuery(this);
      // Buscar out-of-stock no deberia ser necesario
      const max = $('p').hasClass("out-of-stock") ? 0 : parseInt($input.attr('max'));
  
      $input.on('change keyup keydown input', function() {
        const currentValue = parseInt($input.val());
        if (!isNaN(currentValue) && currentValue > max) {
          $input.val(max); 
        }
      });
    });
    
});

      
