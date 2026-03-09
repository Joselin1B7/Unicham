$(document).ready(function() {
    $('#btnLogin').click(function(e) {
        e.preventDefault();
        $('#MatriculaAlert, #passwordAlert').hide();

        const matricula = $('#inputMatricula').val().trim();
        const password = $('#password').val().trim();

        // Validación inmediata (Retorno temprano)
        if (!matricula) return $('#MatriculaAlert').text("Matrícula obligatoria.").show();
        if (!password) return $('#passwordAlert').text("Contraseña obligatoria.").show();

        // Petición simplificada
        $.post('http://localhost/UniCham/login/validador', { matricula, password }, function(res) {
            if (res && res.status === 1) {
                return Swal.fire({
                    title: 'Acceso Satisfactorio',
                    icon: 'success',
                    confirmButtonText: 'Continuar'
                }).then(() => {
                    window.location.href = res.redirect_url || 'http://localhost/UniCham/user/perfil';
                });
            }
            
            Swal.fire('Error de Login', res.message || 'Credenciales incorrectas.', res.status === 0 ? 'error' : 'warning');
        }, 'json').fail(() => Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error'));
    });
});
