<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('views/template/header.php'); ?>

    <style>
        :root{
            --purple-main: #6200ea;
            --purple-dark: #4a00c3;
        }

        body.sb-nav-fixed{ background: #eef2f7; }

        .page-title{
            font-size: 1.8rem;
            font-weight: 600;
            color: #222;
            margin-top: 1.5rem;
        }

        .page-subtitle{ color: #666; margin-bottom: 1rem; }

        .card-historial{
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
            background: #ffffff;
            margin-top: 10px;
            margin-bottom: 24px;
        }

        .card-historial .banner-top{
            background: linear-gradient(90deg, var(--purple-main), var(--purple-dark));
            color: #fff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
        }

        .banner-top-icon{
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 26px;
        }

        .banner-top-title{
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .banner-top-sub{
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .banner-top-sub span{ font-weight: 500; }

        .historial-body{ padding: 18px 22px 22px 22px; }

        .totales{
            display:flex;
            gap:.5rem;
            flex-wrap:wrap;
            margin-bottom:12px;
        }

        .totales .pill{
            background:#fafafa;
            border:1px solid #e6e6e6;
            border-radius:999px;
            padding:.35rem .8rem;
            font-size:.85rem;
            color:#111827;
        }

        @media (max-width: 768px){
            .card-historial .banner-top{ flex-direction:column; align-items:flex-start; }
            .banner-top-icon{ margin-bottom:10px; }
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
        <main class="container-fluid px-4">

            <?php
            $perfil     = $perfilView ?? [];
            $matricula  = $matriculaView ?? '';
            $id_grupo   = $id_grupoView ?? '';
            $historial  = $historialView ?? [];

            $totCred = 0;
            $aprob = 0;
            $reprob = 0;
            $cursando = 0;
            $sumCalAprob = 0;
            $countCalAprob = 0;

            foreach ($historial as $row) {
                $estatus = $row['estatus'] ?? 'Cursando';

                if ($estatus === 'Aprobado') {
                    $totCred += (int)($row['creditos'] ?? 0);
                    $aprob++;
                    if (is_numeric($row['calificacion'])) {
                        $sumCalAprob += (float)$row['calificacion'];
                        $countCalAprob++;
                    }
                } elseif ($estatus === 'Reprobado') {
                    $reprob++;
                } else {
                    $cursando++;
                }
            }

            $promGeneral = ($countCalAprob > 0)
                    ? round($sumCalAprob / $countCalAprob, 2)
                    : null;
            ?>

            <h1 class="page-title">Historial Académico</h1>
            <p class="page-subtitle">Consulta tu trayectoria por periodo, materias, créditos y estatus.</p>

            <div class="card-historial">
                <div class="banner-top">
                    <div>
                        <div class="banner-top-title">
                            <?= htmlspecialchars(($perfil['nombre'] ?? 'Alumno').' '.($perfil['apellido_paterno'] ?? '').' '.($perfil['apellido_materno'] ?? '')) ?>
                        </div>
                        <div class="banner-top-sub">
                            Matrícula: <span><?= htmlspecialchars($matricula ?: 'N/D') ?></span>
                            &nbsp; • &nbsp;
                            Programa: <span><?= htmlspecialchars($perfil['nombre_carrera'] ?? 'N/D') ?></span>
                            &nbsp; • &nbsp;
                            Cuatrimestre: <span><?= htmlspecialchars($perfil['cuatrimestre'] ?? 'N/D') ?></span>
                            &nbsp; • &nbsp;
                            Grupo: <span><?= htmlspecialchars($id_grupo ?: 'N/D') ?></span>
                        </div>
                    </div>
                </div>

                <div class="historial-body">
                    <div class="totales">
                        <span class="pill">Créditos acumulados: <strong><?= $totCred ?></strong></span>
                        <span class="pill">Aprobadas: <strong><?= $aprob ?></strong></span>
                        <span class="pill">Reprobadas: <strong><?= $reprob ?></strong></span>
                        <span class="pill">En curso: <strong><?= $cursando ?></strong></span>
                        <span class="pill">Promedio general: <strong><?= ($promGeneral !== null ? $promGeneral : 'N/D') ?></strong></span>
                    </div>

                    <?php if (empty($historial)): ?>
                        <div class="alert alert-info mt-2 mb-0">
                            Aún no hay registros en tu historial académico.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive mt-2 unicham-table-card">
                            <table class="unicham-table">
                                <thead>
                                <tr>
                                    <th>PERIODO</th>
                                    <th>CLAVE</th>
                                    <th>MATERIA</th>
                                    <th>CRÉDITOS</th>
                                    <th>CALIFICACIÓN</th>
                                    <th>ESTATUS</th>
                                    <th>FECHA CIERRE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($historial as $r):
                                    $estatus = $r['estatus'] ?? 'Cursando';
                                    $badgeClass =
                                            ($estatus === 'Aprobado') ? 'unicham-badge--pass' :
                                                    (($estatus === 'Reprobado') ? 'unicham-badge--fail' : 'unicham-badge--prog');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars(trim(($r['periodo'] ?? '').' '.($r['anio'] ?? ''))) ?></td>
                                        <td><?= htmlspecialchars($r['clave'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($r['materia'] ?? '') ?></td>

                                        <td class="text-center"><?= (int)($r['creditos'] ?? 0) ?></td>

                                        <td class="text-center">
                                            <?= is_null($r['calificacion']) ? '-' : htmlspecialchars($r['calificacion']) ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="unicham-badge <?= $badgeClass ?>">
                                                <?= htmlspecialchars($estatus) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <?= !empty($r['fecha_cierre']) ? htmlspecialchars($r['fecha_cierre']) : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </main>

        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>

<?php require_once('views/template/script.php'); ?>

</body>
</html>