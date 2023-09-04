/*
  Corrige implementacion trunca de <input type="number"> donde el max=""
  solo se retringe con las flechas pero no al ingresar por teclado

  @author Pablo Bozzolo < boctulus@gmail.com >

  Version con JS vanilla
  
  Uso:

  - En pagina invidual de productos

    add_action( 'woocommerce_after_single_product', function(){
      ?>
          < script >
              <!-- ruta al archivo .js -->
              <?= file_get_contents(ROOT_PATH . 'assets/js/min-max.js') ?>
          < /script >
      <?php
    }, 10, 0 );


  - En una "vista" cualquiera se puede colocar al final:

    < script >
        <!-- ruta al archivo .js -->
        <?= file_get_contents(ROOT_PATH . 'assets/js/min-max.js') ?>
    < /script >
*/

document.addEventListener('DOMContentLoaded', function() {
  const numberInputsWithMax = document.querySelectorAll('input[type="number"][max]');
  const numberInputsWithMin = document.querySelectorAll('input[type="number"][min]');

  numberInputsWithMin.forEach(function(input) {
    input.addEventListener('keyup', function() {
      const currentValue = parseInt(input.value);
      const min = parseInt(input.getAttribute('min'));

      if (!isNaN(currentValue)) {
        if (currentValue < min){
          input.value = min;

          // Bloqueo el control por 300 ms para evitar errores por parte del usuario
          input.setAttribute('readonly', 'readonly');

          setTimeout(function() {
            input.removeAttribute('readonly');
          }, 300);

        }
      }
    });
  });

  numberInputsWithMax.forEach(function(input) {
    input.addEventListener('keyup', function() {
      const currentValue = parseInt(input.value);
      const max = parseInt(input.getAttribute('max'));

      if (!isNaN(currentValue)) {
        if (currentValue > max){
          input.value = max;

          // Bloqueo el control por 300 ms para evitar errores por parte del usuario
          input.setAttribute('readonly', 'readonly');

          setTimeout(function() {
            input.removeAttribute('readonly');
          }, 300);
        }
      }
    });
  });
});


      
