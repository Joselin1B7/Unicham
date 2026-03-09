$(document).ready(function() {

    $('#btnReset').click(function(e) {
        e.preventDefault();

        $('#emailAlert').hide();
        $('#newPasswordAlert').hide();
        $('#confirmPasswordAlert').hide();

        var email = $('#inputEmail').val().trim();
        var newPassword = $('#inputNewPassword').val().trim();
        var confirmPassword = $('#inputConfirmPassword').val().trim();

        var validate = validateFields(email, newPassword, confirmPassword);
        validateReset(validate, email, newPassword);
    });

    function validateFields(email, newPassword, confirmPassword){
        var status = true;

        if(email === "") {
            $('#emailAlert').text("El campo Correo no puede estar vacío.").show();
            status = false;
        }
        
        if(newPassword === "")
        {
            $('#newPasswordAlert').text("La Contraseña no puede estar vacía.").show();
            status = false;
        }

        if(confirmPassword === "")
        {
            $('#confirmPasswordAlert').text("Confirma la contraseña.").show();
            status = false;
        } else if (newPassword !== confirmPassword) {
            $('#confirmPasswordAlert').text("Las contraseñas no coinciden.").show();
            status = false;
        }

        return status;
    }

    function validateReset(validate, email, newPassword){
        if(validate === true){
            const infoReset = [email, newPassword];
            processForget(infoReset);
        }
    }

    function processForget(infoReset){

        var url = 'http://localhost/sim/user/resetPassword'; 
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                'email': infoReset[0],
                'new_password': infoReset[1]
            },

            success: function(data) {
                var status = data.status;

                if (status == 1) {
                    
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Tu contraseña ha sido restablecida correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Iniciar Sesión',
                        allowOutsideClick: false
                    });

                } else if (status == 0) {
                    Swal.fire({
                        title: 'Error de Restablecimiento',
                        text: 'El correo electrónico no se encuentra registrado.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });

                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error inesperado en el servidor.',
                        icon: 'warning',
                        confirmButtonText: 'Cerrar'
                    });
                }
            },

            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error de Conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
    }

});