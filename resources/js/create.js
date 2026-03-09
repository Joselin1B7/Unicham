$(document).ready(function() {
    $('#btnRegister').click(function(e) {
        e.preventDefault();
        $('.alert').hide();

        const data = {
            name: $('#inputName').val().trim(),
            firstname: $('#inputFirstname').val().trim(),
            lastname: $('#inputLastname').val().trim(),
            phone: $('#inputPhone').val().trim(),
            password: $('#inputPassword').val().trim()
        };
        const confirm = $('#inputConfirmPassword').val().trim();

        if (validateRegistrationFields(data, confirm)) {
            processRegistration(data);
        }
    });

    function validateRegistrationFields(d, confirm) {
        if (!d.name) return showErr('#nameAlert', "El nombre es obligatorio.");
        if (!d.firstname) return showErr('#firstnameAlert', "El primer nombre es obligatorio.");
        if (!d.lastname) return showErr('#lastnameAlert', "El apellido es obligatorio.");
        if (!d.phone) return showErr('#phoneAlert', "El teléfono es obligatorio.");
        if (!d.password) return showErr('#passwordAlert', "La contraseña es obligatoria.");
        if (d.password !== confirm) return showErr('#confirmPasswordAlert', "Las contraseñas no coinciden.");
        return true;
    }

    function showErr(id, msg) {
        $(id).text(msg).show();
        return false;
    }

    function processRegistration(info) {
        $.post('/UniCham/index.php?controller=user&method=registro', info, function(res) {
            if (res.status == 1) {
                return Swal.fire({ title: '¡Registro Exitoso!', text: 'Tu cuenta ha sido creada.', icon: 'success' });
            }
            const msg = res.status == 0 ? 'El teléfono ya está registrado.' : 'Error inesperado.';
            Swal.fire('Error', msg, 'error');
        }, 'json').fail(() => Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error'));
    }
});
