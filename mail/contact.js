function mostrarNombreArchivo() {
    var archivoInput = document.getElementById('archivo');
    var archivoSeleccionadoSpan = document.getElementById('archivoSeleccionado');
    if (archivoInput.files.length > 0) {
        archivoSeleccionadoSpan.textContent = 'Archivo seleccionado: ' + archivoInput.files[0].name;
    } else {
        archivoSeleccionadoSpan.textContent = '';
    }
}

$(document).ready(function() {
    $('#contactForm').submit(function(event) {
        event.preventDefault(); // Prevenir el envío del formulario por defecto


        var formData = new FormData();
        // Obtener los valores de los campos individualmente
        var name = $('#name').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var archivo = $('#archivo')[0].files[0];

        // Crear un objeto con los datos
        formData.append('name', name);
        formData.append('email', email);
        formData.append('subject', subject);
        formData.append('message', message);
        

        if (archivo) {
            formData.append('archivo', archivo);
        }


        // Realizar la solicitud Ajax
        $.ajax({
            type: 'POST',
            url: 'mail/contact.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Manejar la respuesta del servidor
                // Puedes mostrar un mensaje de éxito o realizar otras acciones según la respuesta
                console.log(response);
                $('#contactForm').trigger('reset');
                // Aquí podrías utilizar SweetAlert u otro método para mostrar un mensaje de éxito
                Swal.fire(
                    'Mensaje enviado!',
                    'Gracias por contactarnos.',
                    'success'
                );
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error("fallo aqui: "+xhr+error);
                // Aquí podrías mostrar un mensaje de error usando SweetAlert u otro método
                Swal.fire(
                    'Error!',
                    'Hubo un problema al enviar el mensaje.',
                    'error'
                );
            }
        });
    });
});


  