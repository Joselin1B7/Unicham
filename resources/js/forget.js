$(document).ready(function() {
    $('#btnReset').click(function(e) {
        e.preventDefault();
        $('.alert').hide();

        const email = $('#inputEmail').val().trim();
        const pass = $('#inputNewPassword').val().trim();
        const confirm = $('#inputConfirmPassword').val().trim();

        if (validateResetFields(email, pass, confirm)) {
            processForget(email, pass);
        }
    });

    function validateResetFields(email, pass, confirm) {
        if (!email) return showErr('#emailAlert', "Correo obligatorio");
        if (!pass) return showErr('#newPasswordAlert', "Contraseña obligatoria");
        if (!confirm) return showErr('#confirmPasswordAlert', "Confirma contraseña");
        if (pass !== confirm) return showErr('#confirmPasswordAlert', "No coinciden");
        return true;
    }

    function processForget(email, pass) {
        $.post('http://localhost/sim/user/resetPassword', { email, new_password: pass }, function(data) {
            if (data.status == 1) {
                return Swal.fire({ title: '¡Éxito!', text: 'Contraseña restablecida.', icon: 'success' });
            }
            const msg = data.status == 0 ? 'Correo no registrado.' : 'Error en el servidor.';
            Swal.fire('Error', msg, 'warning');
        }, 'json').fail(() => Swal.fire('Error', 'Fallo de conexión', 'error'));
    }
});
