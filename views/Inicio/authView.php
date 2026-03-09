<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once('views/template/header.php');
    ?>

    <style>
        /* Estilo personalizado para el botón principal (Azul Académico) */
        .btn-academic-primary {
            background-color: #1A237E;
            border-color: #1A237E;
            color: #fff;
        }
        .btn-academic-primary:hover {
            background-color: #3949AB;
            border-color: #3949AB;
        }

        /* 🔹 Logo del camaleón en el login */
        .login-logo-box{
            text-align:center;
            padding-top:18px;
        }
        .login-logo-box img{
            max-height:120px;           /* tamaño del camaleón */
            width:auto;
            display:inline-block;
        }
    </style>
</head>

<body class="sb-nav-fixed">
<?php
require_once('views/template/Encabezado.php');
?>

<div id="layoutSidenav_content">

    <div class="container" style="margin: 120px auto; max-width: 700px;">
        <div class="panel panel-default"
             style="box-shadow: 0 4px 24px rgba(0,0,0,0.13); border-radius: 16px; background: #f8f9fa; border: 1px solid #e3e3e3; padding: 0 0 10px 0;">

            <!-- 🔹 LOGO DEL CAMALEÓN -->
            <div class="login-logo-box">
                <img src="/UniCham/resources/img/unichameleon-logo.jpeg" alt="UNI CHAMELEON">
            </div>

            <div class="panel-heading">
                <h3 class="panel-title text-center"
                    style="font-size: 26px; margin: 10px 0 10px 0; color: #1A237E;">
                    Acceso al Portal de Servicios
                </h3>
            </div>

            <div class="panel-body" style="padding: 32px 36px 18px 36px;">
                <form action="#" method="post">
                    <div class="form-group" style="margin-bottom: 22px;">

                        <label for="inputMatricula" style="font-size: 1.13em; margin-bottom: 7px;">Matricula</label>
                        <small id="MatriculaAlert" class="form-text text-danger" style="display:none;">
                            El campo de Matricula
                        </small>
                        <input  id="inputMatricula" name="matricula" type="tel"
                                class="form-control"
                                style="height: 46px; font-size: 1.12em; padding-left: 13px; margin-bottom: 6px;" required>

                    </div>
                    <div class="form-group" style="margin-bottom: 22px;">

                        <label for="password" style="font-size: 1.13em; margin-bottom: 7px;">Contraseña</label>
                        <small id="passwordAlert" class="form-text text-danger" style="display:none;">
                            El campo Contraseña no puede estar vacío.
                        </small>
                        <input id="password" name="password" type="password"
                               class="form-control"
                               style="height: 46px; font-size: 1.12em; padding-left: 13px; margin-bottom: 6px;" required>
                    </div>

                    <div class="text-center form-group">
                        <button id="btnLogin"
                                type="button"
                                class="btn btn-academic-primary btn-block"
                                style="margin-top: 10px; border-radius: 7px; font-size: 1.13em; height: 46px; background-color: #007bff; color: white; width: 200px;">
                            <i class="bi bi-person-fill"></i> Iniciar Sesión
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <?php
    require_once('views/template/script.php');
    ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../resources/js/users.js"></script>

</body>
</html>
