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
                <h1 class="mt-4 mb-3"><i class="fas fa-id-card-alt me-2"></i> Trámite de Reposición de Credencial</h1>
                <p class="lead mb-4">Completa el formulario para iniciar la reposición de tu credencial universitaria por daño o extravío.</p>

                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        Iniciar Solicitud
                    </div>
                    <div class="card-body">

                        <div class="alert alert-danger" role="alert">
                            Costo de reposición: $250.00 MXN. El cargo se aplicará a tu estado de cuenta.
                        </div>

                        <form id="formReposicionCredencial" method="POST" action="procesar_reposicion.php">

                            <h4 class="mb-3">Motivo de Reposición</h4>

                            <div class="mb-3">
                                <label for="motivo" class="form-label">Selecciona el Motivo</label>
                                <select class="form-select" id="motivo" name="motivo" required>
                                    <option value="" selected disabled>Selecciona una opción</option>
                                    <option value="extravio">Extravío o Robo</option>
                                    <option value="deterioro">Deterioro por Uso</option>
                                    <option value="datos">Corrección de Datos Personales</option>
                                    <option value="otro">Otro (Especificar)</option>
                                </select>
                            </div>

                            <div class="mb-4" id="otroMotivoDiv" style="display:none;">
                                <label for="especificarMotivo" class="form-label">Especifica el motivo</label>
                                <textarea class="form-control" id="especificarMotivo" name="especificarMotivo" rows="2"></textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="aceptarCosto" required>
                                <label class="form-check-label" for="aceptarCosto">
                                    Declaro bajo protesta de decir verdad el motivo de la reposición y acepto el costo de $250.00 MXN.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-purple"><i class="fas fa-hand-point-right me-2"></i> Iniciar Trámite de Reposición</button>
                        </form>

                        <div id="resultadoTramite" class="mt-4">
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
    document.getElementById('motivo').addEventListener('change', function() {
        const div = document.getElementById('otroMotivoDiv');
        const textarea = document.getElementById('especificarMotivo');
        if (this.value === 'otro') {
            div.style.display = 'block';
            textarea.setAttribute('required', 'required');
        } else {
            div.style.display = 'none';
            textarea.removeAttribute('required');
        }
    });
</script>
</body>
</html>