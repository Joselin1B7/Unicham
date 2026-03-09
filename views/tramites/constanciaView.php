<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        .btn-purple {
            color: #fff; background-color: #5900ffff; border-color: #5900ffff; transition: background-color 0.3s;
        }
        .btn-purple:hover {
            background-color: #4c00cc; border-color: #4c00cc;
        }
    </style>
</head>
<body class="sb-nav-fixed">
<?php require_once('views/template/Encabezado.php'); ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <?php require_once('views/template/menuLeft.php'); ?>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 mb-3"><i class="fas fa-file-alt me-2"></i> Solicitud de Constancia de Taller</h1>
                <p class="lead mb-4">Selecciona el tipo de constancia que necesitas y verifica las actividades registradas.</p>

                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        Opciones de Constancia
                    </div>
                    <div class="card-body">
                        <form id="formSolicitudConstancia" method="POST" action="solicitud_constancia.php">

                            <div class="alert alert-light border p-3 mb-4">
                                <h6>Datos del Solicitante:</h6>
                                <p class="mb-0">Matrícula: A00123456</p>
                                <p class="mb-0">Nombre: Juan Pérez García</p>
                            </div>

                            <div class="mb-3">
                                <label for="tipoConstancia" class="form-label">Tipo de Documento a Solicitar</label>
                                <select class="form-select" id="tipoConstancia" name="tipoConstancia" required>
                                    <option value="" selected disabled>Selecciona una opción</option>
                                    <option value="horas">Constancia de Horas Totales de Talleres</option>
                                    <option value="especifico">Constancia de Taller Específico (Ej: Inglés Nivel X)</option>
                                </select>
                            </div>

                            <div class="mb-3" id="tallerEspecificoDiv" style="display:none;">
                                <label for="tallerEspecifico" class="form-label">Selecciona el Taller Específico</label>
                                <select class="form-select" id="tallerEspecifico" name="tallerEspecifico">
                                    <option value="ingles">Inglés Avanzado</option>
                                    <option value="robotica">Club de Robótica</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="motivo" class="form-label">Motivo de la Solicitud (Opcional)</label>
                                <textarea class="form-control" id="motivo" name="motivo" rows="2"></textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="pagoCheck" required>
                                <label class="form-check-label" for="pagoCheck">
                                    Acepto el costo de $150.00 MXN por la emisión del documento. (El cargo se reflejará en tu estado de cuenta).
                                </label>
                            </div>

                            <button type="submit" class="btn btn-purple"><i class="fas fa-paper-plane me-2"></i> Solicitar Constancia</button>
                        </form>

                        <div id="resultado" class="mt-4">
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>
<?php require_once('views/template/script.php'); ?>
<script>
    document.getElementById('tipoConstancia').addEventListener('change', function() {
        const div = document.getElementById('tallerEspecificoDiv');
        if (this.value === 'especifico') {
            div.style.display = 'block';
            document.getElementById('tallerEspecifico').setAttribute('required', 'required');
        } else {
            div.style.display = 'none';
            document.getElementById('tallerEspecifico').removeAttribute('required');
        }
    });
</script>
</body>
</html>