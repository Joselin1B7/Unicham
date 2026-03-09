// Fichero: users.js (Corregido)

$(document).ready(function() {

    // Manejador del evento de clic en el botón de Login
    $('#btnLogin').click(function() {
        console.log('Click en btnLogin');
        // 1. Ocultar alertas de matrícula y contraseña
        $('#MatriculaAlert').hide();
        $('#passwordAlert').hide();

        // 2. Obtener valores de los campos
        var matricula = $('#inputMatricula').val().trim();
        var password = $('#password').val().trim();

        // 3. Validar campos del lado del cliente
        var isValid = validateFields(matricula, password);

        // 4. Si la validación local es exitosa, iniciar el proceso de login (AJAX)
        if (isValid) {
            processLogin(matricula, password);
        }
    });


    /**
     * Valida que los campos no estén vacíos y que la matrícula tenga el formato correcto.
     * @param {string} matricula
     * @param {string} password
     * @returns {boolean}
     */
   // Sustituye solo la función validateFields en users.js
function validateFields(matricula, password) {
    // Retornos tempranos: SonarQube ama esto porque no hay anidación
    if (!matricula) {
        $('#MatriculaAlert').text("El campo Matrícula no puede estar vacío.").show();
        return false;
    }
    
    if (!password) {
        $('#passwordAlert').text("El campo Contraseña no puede estar vacío.").show();
        return false;
    }

    return true; // Si llega aquí, todo está bien
}

    /**
     * Realiza la llamada AJAX al controlador para validar las credenciales.
     * @param {string} matricula
     * @param {string} password
     */
    function processLogin(matricula, password){
        var url = 'http://localhost/UniCham/login/validador'; // Endpoint del LoginController.php

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                'matricula': matricula,
                'password': password
            },

            success: function(response) {
                console.log('Respuesta del servidor:', response);

                // Asegurar que la respuesta tiene el formato esperado
                if (typeof response !== 'object' || response === null || typeof response.status === 'undefined') {
                    Swal.fire({
                        title: 'Error de Respuesta',
                        text: 'El servidor no devolvió un formato válido (JSON).',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    });
                    return;
                }

                var status = response.status;
                var message = response.message || 'Error en la validación.';
                var redirectUrl = response.redirect_url || 'http://localhost/UniCham/user/perfil'; // URL de fallback


                if (status === 1) { // Credenciales correctas
                    Swal.fire({
                        title: 'Acceso Satisfactorio',
                        text: 'Redirigiendo al sistema...',
                        icon: 'success',
                        confirmButtonText: 'Continuar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirige usando la URL enviada por el controlador (ej: documents)
                            window.location.href = redirectUrl;
                        }
                    });

                } else if (status === 0 || status === -1) { // Credenciales incorrectas o error de datos
                    Swal.fire({
                        title: 'Error de Login',
                        text: message, // Muestra el mensaje del servidor ('Matrícula o contraseña incorrectas.')
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    });
                } else {
                    Swal.fire({
                        title: 'Error Inesperado',
                        text: message,
                        icon: 'warning',
                        confirmButtonText: 'Cerrar'
                    });
                }
            },

            error: function(xhr, status, error) {
                // Manejo de errores de red o del servidor (e.g., error 500)
                console.error('Error AJAX:', status, error, xhr.responseText);
                Swal.fire({
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión o la ruta del controlador.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
    }

});
