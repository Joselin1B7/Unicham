<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('views/template/header.php'); ?>

    <style>
        :root{
            --purple-main: #6200ea;
            --purple-dark: #4a00c3;
        }

        body.sb-nav-fixed{ background:#eef2f7; }

        .page-title{
            font-size: 1.8rem;
            font-weight: 600;
            color: #222;
            margin-top: 1.5rem;
        }

        .page-subtitle{
            color: #666;
            margin-bottom: 1.5rem;
        }

        .card-horario-asesorias{
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
            background: #ffffff;
            margin-top: 10px;
        }

        .card-horario-asesorias .banner-top{
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

        .tabla-asesorias-wrapper{
            padding: 18px 22px 20px 22px;
            background: #fff;
        }

        .slot-asesoria{
            text-align: left;
            background-color: #eef2ff;
            border-left: 3px solid var(--purple-main);
            padding: 6px 8px;
            font-size: 0.9rem;
            line-height: 1.35;
        }

        .slot-asesoria-titulo{
            font-weight: 700;
            margin-bottom: 2px;
            color: #111827;
        }

        .slot-asesoria small{
            display: block;
            color: #4b5563;
        }

        .slot-vacio{
            color: #d1d5db;
            font-size: 0.85rem;
        }

        .alerta-importante{
            margin-top: 16px;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px){
            .banner-top{ flex-direction: column; align-items: flex-start; }
            .banner-top-icon{ margin-bottom: 10px; }
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

                <h1 class="page-title">Horario de Asesorías Docentes</h1>
                <p class="page-subtitle">
                    Consulta los horarios y ubicaciones disponibles para las sesiones de asesoría de tus profesores.
                </p>

                <div class="card-horario-asesorias">

                    <div class="banner-top">
                        <div>
                            <div class="banner-top-title">
                                <?= htmlspecialchars($alumnoNombre ?? 'Alumno') ?>
                            </div>
                            <div class="banner-top-sub">
                                Matrícula: <span><?= htmlspecialchars($alumnoMatricula ?? 'N/D') ?></span>
                                &nbsp; • &nbsp;
                                Programa: <span><?= htmlspecialchars($alumnoCarrera ?? 'N/D') ?></span>
                                &nbsp; • &nbsp;
                                Período: <span><?= htmlspecialchars($periodo ?? 'N/D') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="tabla-asesorias-wrapper">
                        <div class="table-responsive unicham-table-card">
                            <table class="unicham-table unicham-table--center">
                                <thead>
                                <tr>
                                    <th class="unicham-col-hour">HORAS</th>
                                    <th>LUNES</th>
                                    <th>MARTES</th>
                                    <th>MIÉRCOLES</th>
                                    <th>JUEVES</th>
                                    <th>VIERNES</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $horasLocal = $horas ?? [];
                                $tablaLocal = $tabla ?? [];

                                if (!empty($horasLocal)) {
                                    foreach ($horasLocal as $horaLabel) {
                                        echo "<tr>\n";
                                        echo '<td class="unicham-col-hour">' . htmlspecialchars($horaLabel) . '</td>' . "\n";

                                        for ($dia = 1; $dia <= 5; $dia++) {
                                            if (isset($tablaLocal[$horaLabel][$dia])) {
                                                $slot = $tablaLocal[$horaLabel][$dia];

                                                echo '<td>';
                                                echo '  <div class="slot-asesoria">';
                                                echo '      <div class="slot-asesoria-titulo">' . htmlspecialchars($slot['materia'] ?? '') . '</div>';
                                                echo '      <small>Profesor: ' . htmlspecialchars($slot['profesor'] ?? '') . '</small>';
                                                echo '      <small>Ubicación: ' . htmlspecialchars($slot['lugar'] ?? '') . '</small>';
                                                echo '  </div>';
                                                echo '</td>';
                                            } else {
                                                echo '<td class="slot-vacio">--</td>';
                                            }
                                        }

                                        echo "</tr>\n";
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center text-muted p-4">';
                                    echo 'No hay horarios de asesoría registrados.';
                                    echo '</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning alerta-importante" role="alert">
                            Importante: La disponibilidad del profesor puede variar. Se recomienda confirmar la asesoría
                            directamente con el docente antes de asistir.
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