<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        .page-header-title{
            display:flex;
            align-items:center;
            gap:.75rem;
            margin-top:1rem;
            margin-bottom:.5rem;
        }
        .page-header-title .icon-box{
            background-color:#eef;
            border-radius:.5rem;
            width:40px;
            height:40px;
            display:grid;
            place-items:center;
            font-size:1rem;
            color:#4b2fff;
            border:2px solid #4b2fff;
            font-weight:600;
        }
        .page-header-title h1{
            margin:0;
            font-size:1.8rem;
            font-weight:600;
        }
        .page-header-desc{ color:#444; margin-bottom:1rem; }

        .alumno-strip{
            background-color:#5900ff;
            color:#fff;
            border-radius:6px;
            padding:16px 20px;
            margin-bottom:20px;
        }
        .alumno-strip h2{
            margin:0;
            font-weight:600;
            font-size:1.05rem;
        }
        .alumno-strip small{ color:rgba(255,255,255,.9); font-size:.9rem; }
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
        <main class="container-fluid px-4">

            <div class="page-header-title">
                <div>
                    <h1>Mis Calificaciones</h1>
                    <p class="page-header-desc">Consulta tus calificaciones por asignatura.</p>
                </div>
            </div>

            <div class="alumno-strip shadow-sm">
                <h2 class="h4">
                    <?= htmlspecialchars(($perfil['nombre'] ?? 'Alumno').' '.($perfil['apellido_paterno'] ?? '').' '.($perfil['apellido_materno'] ?? '')) ?>
                </h2>
                <small>
                    Matrícula: <strong><?= htmlspecialchars($matricula ?? 'N/D') ?></strong>
                    <?php if (!empty($perfil['nombre_carrera'])): ?>
                        • Programa: <strong><?= htmlspecialchars($perfil['nombre_carrera']) ?></strong>
                    <?php endif; ?>
                    <?php if (!empty($perfil['cuatrimestre'])): ?>
                        • Cuatrimestre: <strong><?= htmlspecialchars($perfil['cuatrimestre']) ?></strong>
                    <?php endif; ?>
                </small>
            </div>

            <?php if (empty($califs)): ?>
                <div class="alert alert-info">
                    Aún no hay calificaciones registradas para tu matrícula.
                </div>
            <?php else: ?>
                <div class="unicham-table-card mb-4">
                    <div class="table-responsive">
                        <table class="unicham-table">
                            <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th>Asignatura</th>
                                <th>Calificaciones</th>
                                <th>Promedio</th>
                                <th>Calificación final</th>
                                <th>% Asistencia</th>
                                <th>Criterio</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $contador = 1;
                            foreach ($califs as $row):
                                $materia  = $row['nombre_materia'] ?? '---';
                                $clave    = $row['clave_materia']  ?? '';
                                $califNum = $row['calificacion']   ?? '0.0';
                                $estatus  = $row['estatus']        ?? 'N/D';

                                // placeholders
                                $asistencia = 'N/D';
                                $criterio   = $estatus;
                                $promedio   = $califNum;
                                $final      = $califNum;
                                ?>
                                <tr>
                                    <td class="text-center" style="font-weight:600;"><?= $contador ?></td>

                                    <td>
                                        <?= htmlspecialchars(strtoupper($materia)) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($clave) ?></small>
                                    </td>

                                    <td class="text-center" style="white-space:nowrap;">
                                        U1: <?= htmlspecialchars($califNum) ?> 0
                                    </td>

                                    <td class="text-center"><?= htmlspecialchars($promedio) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($final) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($asistencia) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($criterio) ?></td>
                                </tr>
                                <?php
                                $contador++;
                            endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>

<?php require_once('views/template/script.php'); ?>

</body>
</html>