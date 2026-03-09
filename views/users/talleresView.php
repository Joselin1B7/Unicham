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

        .resumen-horas{
            display:flex;
            flex-wrap:wrap;
            gap:.5rem;
            margin-bottom:20px;
        }
        .pill{
            background:#fafafa;
            border:1px solid #e6e6e6;
            border-radius:999px;
            padding:.5rem .8rem;
            font-size:.85rem;
            font-weight:500;
        }
        .pill strong{ font-weight:600; }
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
                    <h1>Talleres / Actividades Complementarias</h1>
                    <p class="page-header-desc">
                        Revisa tus talleres inscritos, tus horas acumuladas y cuánto te falta para completar las 150 horas.
                    </p>
                </div>
            </div>

            <div class="alumno-strip shadow-sm">
                <h2>
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

                    <?php if (!empty($perfil['id_grupo'])): ?>
                        • Grupo: <strong><?= htmlspecialchars($perfil['id_grupo']) ?></strong>
                    <?php endif; ?>

                    <?php if (!empty($periodoActual['periodo']) && !empty($periodoActual['anio'])): ?>
                        • Periodo actual: <strong><?= htmlspecialchars($periodoActual['periodo'].' '.$periodoActual['anio']) ?></strong>
                    <?php endif; ?>
                </small>
            </div>

            <div class="resumen-horas">
                <div class="pill">
                    Horas totales acumuladas:
                    <strong><?= (int)($horasTotalesAcumuladas ?? 0) ?></strong>
                    / <?= (int)($TOTAL_REQUERIDO ?? 150) ?>
                </div>

                <div class="pill">
                    Horas restantes globales:
                    <strong><?= (int)($horasRestantesGlobal ?? 0) ?></strong>
                </div>

                <div class="pill">
                    Mínimo recomendado por cuatrimestre:
                    <strong><?= (int)($MIN_POR_CUATRI ?? 35) ?>h</strong>
                </div>
            </div>

            <?php if (empty($talleres)): ?>
                <div class="alert alert-info">
                    Aún no estás inscrito en ningún taller.
                </div>
            <?php else: ?>
                <div class="unicham-table-card">
                    <div class="table-responsive">
                        <table class="unicham-table">
                            <thead>
                            <tr>
                                <th>Taller / Responsable</th>
                                <th>Lugar / Horario</th>
                                <th>Horas acumuladas</th>
                                <th>Estatus</th>
                                <th>Inscrito el</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($talleres as $t): ?>
                                <?php
                                $rawStatus = trim($t['estatus'] ?? 'Pendiente');

                                $badgeClass = 'unicham-badge--prog';
                                if (in_array($rawStatus, ['Aprobado','Completado'], true)) $badgeClass = 'unicham-badge--pass';
                                if (in_array($rawStatus, ['Reprobado','Pendiente','Inactivo'], true)) $badgeClass = 'unicham-badge--fail';
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($t['nombre_taller'] ?? '') ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars($t['responsable'] ?? '') ?></small>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($t['lugar'] ?? '') ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($t['horario'] ?? '') ?></small>
                                    </td>

                                    <td class="text-center">
                                        <?= (int)($t['horas_acumuladas'] ?? 0) ?> h
                                    </td>

                                    <td class="text-center">
                                        <span class="unicham-badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars($rawStatus) ?>
                                        </span>
                                    </td>

                                    <td class="text-center" style="white-space:nowrap;">
                                        <?= htmlspecialchars($t['fecha_inscripcion'] ?? '') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <?php
        $TOTAL = (int)($TOTAL_REQUERIDO ?? 150);
        $acum  = (int)($horasTotalesAcumuladas ?? 0);

        $yaCumplio = ($acum >= $TOTAL);

        // Taller “principal” para mostrar en constancia (elige uno)
        $tallerConstancia = '';
        if (!empty($talleres) && is_array($talleres)) {
            // opción 1: el primer taller
            $tallerConstancia = $talleres[0]['nombre_taller'] ?? '';
            // opción 2 (mejor): el que tenga más horas
            $maxH = -1;
            foreach ($talleres as $tt) {
                $h = (int)($tt['horas_acumuladas'] ?? 0);
                if ($h > $maxH) { $maxH = $h; $tallerConstancia = $tt['nombre_taller'] ?? $tallerConstancia; }
            }
        }
        ?>

        <?php if ($yaCumplio): ?>
            <div class="unicham-table-card p-4 mt-3" style="text-align:center;">
                <h3 style="margin:0 0 .5rem 0;">Tu Progreso</h3>

                <div style="height:22px;border-radius:12px;background:#e9ecef;overflow:hidden;max-width:900px;margin:12px auto;">
                    <div style="height:100%;width:100%;background:#198754;"></div>
                </div>
                <small class="text-muted">100% (<?= $acum ?> hrs)</small>

                <div style="margin-top:18px;display:inline-block;background:#d1e7dd;border:1px solid #badbcc;border-radius:10px;padding:16px 18px;">
                    <div style="font-weight:700;font-size:1.1rem;margin-bottom:10px;">¡Felicidades!</div>

                    <a class="btn btn-success"
                       href="/UniCham/index.php?controller=user&method=constanciaTaller&mat=<?= urlencode($matricula ?? '') ?>&taller=<?= urlencode($tallerConstancia) ?>">
                        🌟 Ver mi Constancia
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>

<?php require_once('views/template/script.php'); ?>
</body>
</html>