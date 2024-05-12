document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('turnoForm');
    const mensaje = document.getElementById('mensaje');
  
    form.addEventListener('submit', function(event) {
      event.preventDefault();
  
      const formData = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        mensaje.innerHTML = data.message;
        form.reset();
      })
      .catch(error => {
        mensaje.innerHTML = 'Hubo un error al procesar la solicitud.';
      });
    });
  });
  