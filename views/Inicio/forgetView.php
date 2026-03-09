<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once('views/template/header.php');
    ?>

    <style>
        /* Estilo personalizado para el botón principal (Azul Académico: Indigo 900) */
        .btn-academic-primary {
            background-color: #1A237E;
            border-color: #1A237E;
            color: #fff;
        }
        .btn-academic-primary:hover {
            background-color: #3949AB; /* Indigo 600 */
            border-color: #3949AB;
        }
    </style>
</head>
<body class="sb-nav-fixed">

<?php
require_once('views/template/Encabezado.php');
?>

<div id="layoutSidenav">


    <div id="layoutSidenav_content">
        <main>
            <div class="container" style="margin: 120px auto; max-width: 700px;">
                <div class="panel panel-default" style="box-shadow: 0 4px 24px rgba(0,0,0,0.13); border-radius: 16px; background: #f8f9fa; border: 1px solid #e3e3e3; padding: 0 0 10px 0;">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center" style="font-size: 26px; margin: 18px 0 10px 0; color: #1A237E;">Restablecer Contraseña</h3>
                    </div>
                    <div class="panel-body" style="padding: 32px 36px 18px 36px;">
                        <form action="#" method="post">
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label for="inputEmail" style="font-size: 1.13em; margin-bottom: 7px;">Correo Electrónico Institucional</label>
                                <small id="emailAlert" class="form-text text-danger" style="display:none;"></small>
                                <input type="email" class="form-control" id="inputEmail" name="email" style="height: 46px; font-size: 1.12em; padding-left: 13px; margin-bottom: 6px;" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label for="inputNewPassword" style="font-size: 1.13em; margin-bottom: 7px;">Nueva Contraseña</label>
                                <small id="newPasswordAlert" class="form-text text-danger" style="display:none;"></small>
                                <input type="password" class="form-control" id="inputNewPassword" name="new_password" style="height: 46px; font-size: 1.12em; padding-left: 13px; margin-bottom: 6px;" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 22px;">
                                <label for="inputConfirmPassword" style="font-size: 1.13em; margin-bottom: 7px;">Confirmar Nueva Contraseña</label>
                                <small id="confirmPasswordAlert" class="form-text text-danger" style="display:none;"></small>
                                <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" style="height: 46px; font-size: 1.12em; padding-left: 13px; margin-bottom: 6px;" required>
                            </div>
                            <div class="text-center ">
                                <button id="btnReset" type="button" class="btn btn-academic-primary btn-block" style="margin-top: 10px; border-radius: 7px; font-size: 1.13em; height: 46px;"><i class="bi bi-key"></i> Restablecer Contraseña</button>
                            </div>
                        </form>
                        <p class="text-center" style="margin-top: 22px; font-size: 1.08em;">
                            <a href="http://localhost/UniCham/login/auth">Volver al inicio</a>.
                        </p>
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

<script src="../resources/js/forget.js"></script>

</body>
</html>