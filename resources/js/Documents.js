$(document).ready(function() {

    $('#btnUpload').click(function(e) {
        e.preventDefault();
        $('.alert').hide(); // Limpieza rápida de alertas

        const fileData = {
            title: $('#inputTitle').val().trim(),
            category: $('#inputCategory').val(),
            file: $('#inputFile')[0].files[0]
        };

        // Solo procesamos si la validación es exitosa
        if (validateDocumentFields(fileData)) {
            uploadDocument(fileData);
        }
    });

    function validateDocumentFields(data) {
        // Retornos tempranos para eliminar la complejidad ciclomática
        if (!data.title) return showAlert('#titleAlert', "El título es obligatorio.");
        if (!data.category || data.category === "0") return showAlert('#catAlert', "Selecciona una categoría.");
        if (!data.file) return showAlert('#fileAlert', "Debes seleccionar un archivo.");
        
        return true;
    }

    function showAlert(selector, mensaje) {
        $(selector).text(mensaje).show();
        return false;
    }

    function uploadDocument(data) {
        // Usamos FormData para el envío de archivos
        const formData = new FormData();
        formData.append('title', data.title);
        formData.append('category', data.category);
        formData.append('file', data.file);

        $.ajax({
            url: '/UniCham/index.php?controller=document&method=upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                handleUploadResponse(res);
            },
            error: function() {
                Swal.fire('Error', 'Error de conexión con el servidor.', 'error');
            }
        });
    }

    function handleUploadResponse(res) {
        // Simplificación de la lógica de respuesta para SonarQube
        if (res.status == 1) {
            return Swal.fire('¡Éxito!', 'Documento subido correctamente.', 'success');
        }
        
        const errorMsg = res.message || 'No se pudo subir el archivo.';
        Swal.fire('Error de carga', errorMsg, 'error');
    }
});
