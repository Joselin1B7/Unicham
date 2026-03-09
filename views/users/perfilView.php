<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        .profile-header {
            background-color: #5900ffff;
            color: white;
            padding: 20px;
            border-radius: 6px 6px 0 0;
            text-align: center;
        }

        .profile-picture-container {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .change-photo-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #fff;
            transition: background-color 0.3s;
        }

        .change-photo-btn:hover {
            background-color: #0056b3;
        }

        #photo-upload-input {
            display: none;
        }

        .btn-purple {
            color: #fff;
            background-color: #5900ffff;
            border-color: #5900ffff;
            transition: background-color 0.3s;
        }

        .btn-purple:hover {
            background-color: #4c00cc;
            border-color: #4c00cc;
        }

        /* Mensaje dinámico de cambio de contraseña */
        #pwdMsg.d-none {
            display: none !important;
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

                <h1 class="mt-4 mb-4">Perfil Estudiantil</h1>
                <p class="lead mb-4">Verifica tu información personal, foto de perfil y credenciales de acceso.</p>

                <!-- Mensajes flash (foto de perfil, etc.) -->
                <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                <?php if (!empty($_SESSION['flash_ok'])): ?>
                    <div class="alert alert-success" style="margin-bottom:1rem;">
                        <?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger" style="margin-bottom:1rem;">
                        <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>

                <div class="card mb-4 shadow-lg">
                    <div class="profile-header">

                        <!-- FOTO DE PERFIL + FORM -->
                        <div class="profile-picture-container">

                            <img
                                    id="profile-img-display"
                                    class="profile-picture"
                                    src="/UniCham/index.php?controller=user&method=fotoPerfil&t=<?=time()?>"
                                    alt="Foto de Perfil"
                            >

                            <form
                                    id="formFoto"
                                    action="/UniCham/index.php?controller=user&method=actualizarFoto"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    style="position:absolute; bottom:0; right:0;"
                            >
                                <label for="photo-upload-input"
                                       class="change-photo-btn"
                                       title="Cambiar Foto">
                                    <i class="fas fa-camera"></i>
                                </label>

                                <input
                                        type="file"
                                        id="photo-upload-input"
                                        name="foto"
                                        accept="image/*"
                                >
                            </form>
                        </div>

                        <!-- NOMBRE Y MATRÍCULA -->
                        <h3 class="text-white mb-0" id="userNameHeader">
                            <?=htmlspecialchars(($user['nombre']??'').' '.($user['apellido_paterno']??'').' '.($user['apellido_materno']??''))?>
                        </h3>
                        <p class="text-white-50 mb-0" id="userMatriculaHeader">
                            Matrícula: <?=htmlspecialchars($user['matricula'] ?? $user['nombre_usuario'])?>
                        </p>
                    </div>

                    <div class="card-body">

                        <!-- PESTAÑAS -->
                        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"
                                        id="data-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#data-info"
                                        type="button"
                                        role="tab"
                                        aria-controls="data-info"
                                        aria-selected="true">
                                    <i class="fas fa-info-circle me-1"></i> Información General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="password-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#password-change"
                                        type="button"
                                        role="tab"
                                        aria-controls="password-change"
                                        aria-selected="false">
                                    <i class="fas fa-lock me-1"></i> Cambiar Contraseña
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">

                            <!-- TAB 1: INFO GENERAL -->
                            <div class="tab-pane fade show active"
                                 id="data-info"
                                 role="tabpanel"
                                 aria-labelledby="data-tab">

                                <h4 class="mb-3">Datos Académicos y Contacto</h4>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Programa Educativo:</strong>
                                            <span id="programaText">
                                                <?= htmlspecialchars($user['nombre_carrera'] ?? 'N/D') ?>
                                            </span>
                                        </p>

                                        <p><strong>Cuatrimestre:</strong>
                                            <span id="cuatrimestreText">
                                                <?= !empty($user['cuatrimestre'])
                                                        ? htmlspecialchars($user['cuatrimestre'])
                                                        : 'N/D' ?>
                                            </span>
                                        </p>

                                        <p><strong>E-mail:</strong>
                                            <span id="emailText">
                                                <?= htmlspecialchars($user['email'] ?? 'sin correo') ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Teléfono:</strong>
                                            <span id="phoneText">
                                                <?= htmlspecialchars($user['telefono'] ?? 'N/D') ?>
                                            </span>
                                        </p>

                                        <p><strong>Fecha de Ingreso:</strong>
                                            <span id="ingresoText">
                                                <?= htmlspecialchars($user['fecha_ingreso'] ?? 'N/D') ?>
                                            </span>
                                        </p>

                                        <p><strong>Estatus:</strong>
                                            <span id="statusText" class="badge bg-success">
                                                Activo
                                            </span>
                                        </p>
                                    </div>
                                </div>


                            </div>

                            <!-- TAB 2: CAMBIAR CONTRASEÑA -->
                            <div class="tab-pane fade"
                                 id="password-change"
                                 role="tabpanel"
                                 aria-labelledby="password-tab">

                                <!--
                                    Este form ya no hace submit normal.
                                    Lo capturamos con JS y usamos fetch() -> user/updatePassword
                                -->
                                <form id="formPassword" class="mt-3" autocomplete="off">
                                    <div class="mb-3">
                                        <label for="pwdActual" class="form-label">Contraseña Actual</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                id="pwdActual"
                                                name="pwdActual"
                                                required
                                        >
                                    </div>

                                    <div class="mb-3">
                                        <label for="pwdNueva" class="form-label">Nueva Contraseña</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                id="pwdNueva"
                                                name="pwdNueva"
                                                required
                                                minlength="8"
                                        >
                                        <small class="form-text text-muted">
                                            Mínimo 8 caracteres. Combina mayúsculas, minúsculas y números.
                                        </small>
                                    </div>

                                    <div class="mb-4">
                                        <label for="pwdConfirm" class="form-label">Confirmar Nueva Contraseña</label>
                                        <input
                                                type="password"
                                                class="form-control"
                                                id="pwdConfirm"
                                                name="pwdConfirm"
                                                required
                                        >
                                    </div>

                                    <!-- Mensaje dinámico -->
                                    <div id="pwdMsg" class="alert d-none" role="alert"></div>

                                    <button type="submit" class="btn btn-purple">
                                        <i class="fas fa-save me-2"></i>
                                        Guardar Nueva Contraseña
                                    </button>
                                </form>

                                <p class="text-muted mt-3" style="font-size:0.9rem;">
                                    Por seguridad, después de cambiar tu contraseña te recomendamos cerrar sesión y volver a iniciar.
                                </p>

                            </div>
                        </div>

                    </div> <!-- /card-body -->
                </div> <!-- /card -->
            </div> <!-- /container-fluid -->
        </main>

        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>

<?php require_once('views/template/script.php'); ?>

<script>
    // ======================
    // SUBIR FOTO DE PERFIL
    // ======================
    document.getElementById('photo-upload-input').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        // Preview inmediato
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-img-display').src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Enviar al servidor para guardar en BD
        document.getElementById('formFoto').submit();
    });

    // ======================
    // CAMBIO DE CONTRASEÑA
    // ======================
    (function(){
        const form = document.getElementById('formPassword');
        const msgBox = document.getElementById('pwdMsg');

        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const pwdActual  = document.getElementById('pwdActual').value.trim();
            const pwdNueva   = document.getElementById('pwdNueva').value.trim();
            const pwdConfirm = document.getElementById('pwdConfirm').value.trim();

            // validaciones rápidas en front
            if (pwdNueva.length < 8) {
                showMsg('La nueva contraseña debe tener al menos 8 caracteres.', 'danger');
                return;
            }
            if (pwdNueva !== pwdConfirm) {
                showMsg('Las nuevas contraseñas no coinciden.', 'danger');
                return;
            }

            const formData = new FormData();
            formData.append('pwdActual', pwdActual);
            formData.append('pwdNueva', pwdNueva);
            formData.append('pwdConfirm', pwdConfirm);

            try {
                const resp = await fetch('index.php?controller=user&method=updatePassword', {
                    method: 'POST',
                    body: formData
                });

                const data = await resp.json();

                if (data.ok) {
                    showMsg('Contraseña actualizada correctamente ✔', 'success');
                    form.reset();
                } else {
                    showMsg(data.msg || 'No se pudo actualizar la contraseña.', 'danger');
                }

            } catch (err) {
                console.error(err);
                showMsg('Error de red o del servidor.', 'danger');
            }
        });

        function showMsg(text, type) {
            msgBox.classList.remove('d-none', 'alert-success', 'alert-danger');
            msgBox.classList.add('alert-' + type);
            msgBox.textContent = text;
        }
    })();
</script>

</body>
</html>
