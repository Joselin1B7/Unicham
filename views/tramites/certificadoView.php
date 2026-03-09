<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('views/template/header.php'); ?>
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
                <h1 class="mt-4 mb-3"><i class="fas fa-graduation-cap me-2"></i> Estado del Certificado de Estudios</h1>
                <p class="lead mb-4">Consulta en tiempo real el progreso de tu trámite de certificado de estudios.</p>

                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        Información del Trámite
                    </div>
                    <div class="card-body">

                        <h4 class="mb-3">Datos del Egresado</h4>
                        <div class="row mb-5">
                            <div class="col-md-6"><p><strong>Matrícula:</strong> A00123456</p></div>
                            <div class="col-md-6"><p><strong>Programa:</strong> Ingeniería de Software</p></div>
                            <div class="col-md-6"><p><strong>Fecha de Egreso:</strong> Diciembre 2024</p></div>
                            <div class="col-md-6"><p><strong>Ciclo de Trámite:</strong> 2024-2</p></div>
                        </div>

                        <h4 class="mb-4 text-center">Progreso Actual del Certificado</h4>

                        <div class="progress mb-4" style="height: 30px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75% Completado</div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i> Paso 1: Solicitud <span class="badge bg-success float-end">Completado (15/Ene/2025)</span>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-check-circle text-success me-2"></i> Paso 2: Pago de Solicitud<span class="badge bg-success float-end">Completado (20/Ene/2025)</span>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-hourglass-half text-warning me-2"></i> Paso 3: Proceso de Validacion <span class="badge bg-warning float-end">En Proceso</span>
                                <p class="small text-muted mt-2 mb-0">Tiempo estimado restante: 2 dias.</p>
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-circle text-secondary me-2"></i> Paso 4: Disponible para su entrega <span class="badge bg-secondary float-end">Pendiente</span>
                            </li>
                        </ul>

                        <div class="mt-4 text-center">
                            <button class="btn btn-outline-success" disabled><i class="fas fa-download me-2"></i> Descargar Certificado (No disponible)</button>
                        </div>

                    </div>
                </div>

            </div>
        </main>
        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>
<?php require_once('views/template/script.php'); ?>
</body>
</html>