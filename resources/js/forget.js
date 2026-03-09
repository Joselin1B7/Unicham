$(document).ready(function() {
    $('#btnReset').click(function() {
        // Obtener valores de los campos
        const email = $('#inputEmail').val().trim();
        const password = $('#inputNewPassword').val();
        const confirmPassword = $('#inputConfirmPassword').val();

        // Limpiar alertas previas
        $('.form-text.text-danger').hide().text('');

        // 1. Validaciones básicas en el cliente
        if (email === "" || password === "" || confirmPassword === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Campos incompletos',
                text: 'Por favor, llena todos los campos solicitados.'
            });
            return;
        }

        if (password !== confirmPassword) {
            $('#confirmPasswordAlert').text('Las contraseñas no coinciden.').show();
            return;
        }

        // 2. Petición AJAX al controlador
        $.ajax({
            url: 'http://localhost/UniCham/login/updatePassword', // Ajusta a tu ruta real
            method: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Tu contraseña ha sido restablecida correctamente.',
                        confirmButtonText: 'Ir al Login'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'http://localhost/UniCham/login/auth';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'No se pudo restablecer la contraseña.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Ocurrió un error al comunicarse con el servidor.'
                });
            }
        });
    });
});
