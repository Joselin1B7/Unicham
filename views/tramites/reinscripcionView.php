<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        .file-icon { font-size: 1.5rem; color: #17a2b8; }
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
                <h1 class="mt-4 mb-3"><i class="fas fa-user-edit me-2"></i> Documentos y Formatos de Reinscripción</h1>
                <p class="lead mb-4">Descarga los documentos necesarios para completar tu trámite de reinscripción para el siguiente cuatrimestre.</p>

                <div class="card shadow-lg">
                    <div class="card-header bg-info text-white">
                        Archivos para Reinscripción: Cuatrimestre Enero - Abril 2026
                    </div>
                    <div class="card-body">

                        <div class="alert alert-info" role="alert">
                            <h5 class="alert-heading">¡Aviso Importante!</h5>
                            Asegúrate de tener un estatus de No Adeudo para poder generar tu formato de reinscripción.
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-pdf file-icon me-3"></i>
                                    Formato Único de Solicitud de Reinscripción
                                    <span class="d-block text-muted small">Versión 2026. Revísalo, llénalo e imprímelo.</span>
                                </div>
                                <a href="descargar_formato_solicitud.pdf" class="btn btn-outline-secondary" target="_blank">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-book-open file-icon me-3"></i>
                                    Guía de Requisitos y Fechas Clave
                                    <span class="d-block text-muted small">Calendario de pagos y entrega de documentos.</span>
                                </div>
                                <a href="descargar_guia_reinscripcion.pdf" class="btn btn-outline-secondary" target="_blank">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-gavel file-icon me-3"></i>
                                    Reglamento Estudiantil (Actualizado)
                                    <span class="d-block text-muted small">Para conocimiento y firma de aceptación.</span>
                                </div>
                                <a href="descargar_reglamento.pdf" class="btn btn-outline-secondary" target="_blank">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </a>
                            </li>
                        </ul>
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