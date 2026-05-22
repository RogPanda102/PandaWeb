/**
* PHP Email Form Validation - v3.7
* URL: https://bootstrapmade.com/php-email-form/
* Author: BootstrapMade.com
*/
(function () {
  "use strict";
  //Selecciona todos los formularios con la clase php-email-form usando querySelectorAll
  let forms = document.querySelectorAll('.php-email-form');
  // Añade un listener al evento submit de cada formulario y lo intercepta para manejarlo con fetch
  forms.forEach( function(e) {
    e.addEventListener('submit', function(event) {
      event.preventDefault();// Evita que la pagina se recargue al enviar el formulario

      let thisForm = this;

      let action = thisForm.getAttribute('action');// Valida el atributo action del formulario
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');
      // Manda un mensaje de error si no se ha configurado la accion
      if( ! action ) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }
      // Muestra el mensaje de cargando y oculta los mensajes de error y exito
      // thisform es el formulario actual
      const submitButton = thisForm.querySelector('button[type="submit"]');
      submitButton.disabled = true; // Deshabilita el botón de envío para evitar múltiples envíos
      submitButton.innerHTML = 'Enviando...'; // Cambia el texto del botón para indicar que se está enviando
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');
      // Recoge los datos del formulario
      let formData = new FormData( thisForm );


      // Si se ha configurado reCaptcha, se ejecuta la verificacion
      if ( recaptcha ) {
        if(typeof grecaptcha !== "undefined" ) {
          grecaptcha.ready(function() {
            try {
              grecaptcha.execute(recaptcha, {action: 'php_email_form_submit'})
              .then(token => {
                formData.set('recaptcha-response', token);
                php_email_form_submit(thisForm, action, formData);
              })
            } catch(error) {
              displayError(thisForm, error);
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  // Envia los datos al backend via fetch (peticion POST AJAX)
  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(response => { // Maneja la respuesta del servidor
      return response.text().then(text => {
        if (response.ok) {
          return text; // Si es exitosa, devolver el texto
        } else {
          // Si es error, lanzar con el texto del cuerpo (o mensaje por defecto)
          throw new Error(text || `${response.status} ${response.statusText} ${response.url}`);
        }
      });
    })
    .then(data => {
      thisForm.querySelector('.loading').classList.remove('d-block');
      const submitButton = thisForm.querySelector('button[type="submit"]');
      submitButton.disabled = false; // Rehabilita el botón de envío
      submitButton.innerHTML = 'Enviar'; // Restaura el texto original del botón
      if (data.trim() == 'OK') {
        thisForm.querySelector('.sent-message').classList.add('d-block');
        thisForm.reset(); 
      } else {
        throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action); 
      }
    })
    .catch((error) => {
      const submitButton = thisForm.querySelector('button[type="submit"]');
      submitButton.disabled = false; // Rehabilita el botón de envío
      submitButton.innerHTML = 'Enviar';
      displayError(thisForm, error.message);
    });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block'); // Oculta el mensaje de cargando
    thisForm.querySelector('.error-message').innerHTML = error; // Ahora 'error' es una cadena (el mensaje)
    thisForm.querySelector('.error-message').classList.add('d-block');
  }

  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-btn');
    const form = document.getElementById('quote-form');
    
    if (toggleBtn && form) {  // Verifica que existan los elementos
      toggleBtn.addEventListener('click', function() {
        if (form.style.display === 'none' || form.style.display === '') {
          form.style.display = 'block';
        } else {
          form.style.display = 'none';
        }
      });
    }
  });

})();
