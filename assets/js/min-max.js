document.addEventListener('DOMContentLoaded', function() {
  const inputElementsWithMax = jQuery('input[type="number"][max]');

  inputElementsWithMax.each(function() {
    const $input = jQuery(this);
    const max = parseInt($input.attr('max'));

    $input.on('change keyup keydown input', function() {
      const currentValue = parseInt($input.val());
      if (!isNaN(currentValue) && currentValue > max) {
        $input.val(max); // Restringir el valor al máximo
      }
    });
  });  
});

      
