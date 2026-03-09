<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once('views/template/header.php');
    ?>
    <style>
        :root {
            --bs-academic-blue: #1A237E;
            --bs-academic-hover: #3949AB;
            --bs-border-color: #dee2e6;
        }

        .btn-academic-primary {
            background-color: var(--bs-academic-blue);
            border-color: var(--bs-academic-blue);
            color: #fff;
            font-weight: 600;
        }
        .btn-academic-primary:hover {
            background-color: var(--bs-academic-hover);
            border-color: var(--bs-academic-hover);
            color: #fff;
        }

        .form-control:focus {
            border-color: var(--bs-academic-blue);
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
        }

        .shadow-sm-custom {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
        }

        #form-wrapper {
            max-width: 500px;
            margin: 2rem auto;
        }

        #layoutSidenav_content .mt-4 {
            display: none;
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
                <h1 class="mt-4">Solicitud de Registro</h1>

                <div id="form-wrapper">
                    <div class="card border-0 shadow-sm-custom rounded-3">

                        <div class="card-body p-4">
                            <h4 class="text-center text-dark fw-bold mb-4">Complete sus datos académicos</h4>

                            <form method="POST" action="#">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputName" class="form-label small fw-semibold">Nombre(s)</label>
                                            <small id="nameAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputName" class="form-control" name="name" type="text" placeholder="Ingrese su nombre(s)" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputFirstname" class="form-label small fw-semibold">Apellido Paterno</label>
                                            <small id="firstnameAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputFirstname" class="form-control" name="firstname" type="text" placeholder="Ingrese su apellido paterno" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputLastname" class="form-label small fw-semibold">Apellido Materno</label>
                                            <small id="lastnameAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputLastname" class="form-control" name="lastname" type="text" placeholder="Ingrese su apellido materno" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputPhone" class="form-label small fw-semibold">Número de Teléfono</label>
                                            <small id="phoneAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputPhone" class="form-control" name="phone" type="tel" placeholder="Ingrese su número de teléfono" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputPassword" class="form-label small fw-semibold">Crear Contraseña</label>
                                            <small id="passwordAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputPassword" class="form-control" name="password" type="password" placeholder="Crear contraseña" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-0">
                                            <label for="inputConfirmPassword" class="form-label small fw-semibold">Confirmar Contraseña</label>
                                            <small id="confirmPasswordAlert" class="form-text text-danger" style="display:none;"></small>
                                            <input id="inputConfirmPassword" class="form-control" name="confirm_password" type="password" placeholder="Confirmar contraseña" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mb-3">
                                    <button id="btnRegister" class="btn btn-lg btn-academic-primary" type="button">Enviar Solicitud de Registro</button>
                                </div>
                            </form>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="../resources/js/create.js"></script>

</body>
</html>