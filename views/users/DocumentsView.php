<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once('views/template/header.php');
    ?>

    <style>
        .card-documento {
            border: 1px solid #dcdcdc;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            height: 100%;
        }
        .card-documento:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-purple-outline {
            color: #5900ffff;
            border-color: #5900ffff;
        }
        .btn-purple-outline:hover {
            color: #fff;
            background-color: #5900ffff;
            border-color: #5900ffff;
        }
    </style>
</head>

<body class="sb-nav-fixed">

<?php
require_once('views/template/Encabezado.php');
?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <?php
            require_once('views/template/menuLeft.php');
            ?>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 mb-3">Centro de Documentos Estudiantiles</h1>
                <p class="lead mb-4">Selecciona el documento o trámite que deseas consultar o iniciar.</p>

                <div class="row">

                    <!-- CERTIFICADO DE ESTUDIOS -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card card-documento shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-success">
                                    <i class="fas fa-graduation-cap me-2"></i> Certificado de Estudios
                                </h5>
                                <p class="card-text text-muted">
                                    Consulta el estado de tu certificado de estudios de licenciatura (trámite de egreso).
                                </p>
                                <a
                                        href="/UniCham/index.php?controller=tramites&method=certificadoEstudios"
                                        class="btn btn-sm btn-purple-outline mt-auto"
                                        target="_blank"
                                >
                                    Consultar Trámite
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- PAPELERÍA DE REINSCRIPCIÓN -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card card-documento shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-info">
                                    <i class="fas fa-user-edit me-2"></i> Papeles de Reinscripción
                                </h5>
                                <p class="card-text text-muted">
                                    Accede a los formatos y requisitos necesarios para tu proceso de reinscripción semestral.
                                </p>
                                <a
                                        href="/UniCham/index.php?controller=tramites&method=reinscripcion"
                                        class="btn btn-sm btn-purple-outline mt-auto"
                                        target="_blank"
                                >
                                    Ver Documentos
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- PAGO DE SEGURO -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card card-documento shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-warning">
                                    <i class="fas fa-shield-alt me-2"></i> Pago de Seguro
                                </h5>
                                <p class="card-text text-muted">
                                    Genera tu línea de captura para el pago de tu seguro de gastos médicos estudiantil.
                                </p>
                                <a
                                        href="/UniCham/index.php?controller=tramites&method=pagoSeguro"
                                        class="btn btn-sm btn-purple-outline mt-auto"
                                >
                                    Generar Pago
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- REPOSICIÓN DE CREDENCIAL -->
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card card-documento shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-danger">
                                    <i class="fas fa-id-card-alt me-2"></i> Reposición de Credencial
                                </h5>
                                <p class="card-text text-muted">
                                    Inicia el trámite y pago para la reposición de tu credencial universitaria por pérdida o daño.
                                </p>
                                <a
                                        href="/UniCham/index.php?controller=tramites&method=reposicionCredencial"
                                        class="btn btn-sm btn-purple-outline mt-auto"
                                >
                                    Iniciar Trámite
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>


        <?php
        require_once('views/template/footer.php');
        ?>
    </div>
</div>

<?php
require_once('views/template/script.php');
?>

</body>
</html>