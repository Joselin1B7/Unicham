$(document).ready(function() {

    $('#btnRegister').click(function(e) {
        e.preventDefault();

        $('#nameAlert').hide();
        $('#firstnameAlert').hide();
        $('#lastnameAlert').hide();
        $('#phoneAlert').hide();
        $('#passwordAlert').hide();
        $('#confirmPasswordAlert').hide();

        var name = $('#inputName').val().trim();
        var firstname = $('#inputFirstname').val().trim();
        var lastname = $('#inputLastname').val().trim();
        var phone = $('#inputPhone').val().trim();
        var password = $('#inputPassword').val().trim();
        var confirmPassword = $('#inputConfirmPassword').val().trim();

        var validate = validateFields(name, firstname, lastname, phone, password, confirmPassword);
        validateRegistration(validate, name, firstname, lastname, phone, password);
    });

    function validateFields(name, firstname, lastname, phone, password, confirmPassword){
        var status = true;

        if(name === "") {
            $('#nameAlert').text("El campo Nombre no puede estar vacío.").show();
            status = false;
        }
        if(firstname === "") {
            $('#firstnameAlert').text("El campo Primer Nombre no puede estar vacío.").show();
            status = false;
        }
        if(lastname === "") {
            $('#lastnameAlert').text("El campo Apellido no puede estar vacío.").show();
            status = false;
        }

        if(phone === "")
        {
            $('#phoneAlert').text("El campo Teléfono no puede estar vacío.").show();
            status = false;
        } else if(phone.replace(/\D/g, '') !== phone)
        {
            $('#phoneAlert').text("Por favor, ingresa solo números en el teléfono.").show();
            status = false;
        }

        if(password === "")
        {
            $('#passwordAlert').text("El campo Contraseña no puede estar vacío.").show();
            status = false;
        }

        if(confirmPassword === "")
        {
            $('#confirmPasswordAlert').text("Confirma la contraseña.").show();
            status = false;
        } else if (password !== confirmPassword) {
            $('#confirmPasswordAlert').text("Las contraseñas no coinciden.").show();
            status = false;
        }

        return status;
    }

    function validateRegistration(validate, name, firstname, lastname, phone, password){
        if(validate === true){
            const infoRegistration = [name, firstname, lastname, phone, password];
            processRegistration(infoRegistration);
        }
    }

    function processRegistration(infoRegistration){

        var url = '/UniCham/index.php?controller=user&method=registro';

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'name': infoRegistration[0],
                'firstname': infoRegistration[1],
                'lastname': infoRegistration[2],
                'phone': infoRegistration[3],
                'password': infoRegistration[4]
            },
            success: function(response) {
                var status = response.status;

                if (status == 1) {
                    Swal.fire({
                        title: '¡Registro Exitoso!',
                        text: 'Tu cuenta ha sido creada correctamente.',
                        icon: 'success',
                        confirmButtonText: 'Iniciar Sesión',
                        allowOutsideClick: false
                    });
                    
                } else if (status == 0) {
                    Swal.fire({
                        title: 'Error de Registro',
                        text: 'El teléfono ya está registrado. Por favor, usa otro.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ha ocurrido un error inesperado en el servidor.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }  

            },

            error: function(xhr, status, error) {
                Swal.fire(
                    {
                        title: 'Error de Conexión',
                        text: 'No se pudo conectar con el servidor. Verifica tu conexión.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    }
                );
            }
        });
    }

});