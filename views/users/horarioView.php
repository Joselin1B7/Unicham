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

        .page-subtitle{
            color: #666;
            margin-bottom: 1.5rem;
        }

        .card-horario{
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
            background: #ffffff;
            margin-top: 10px;
        }

        .card-horario .banner-top{
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

        .tabla-horario-wrapper{
            padding: 18px 22px 20px 22px;
            background: #fff;
        }

        .slot-clase-titulo{
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 2px;
            color: #111827;
        }

        .slot-clase small{
            display: block;
            color: #4b5563;
            font-size: 0.8rem;
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
        <main class="container-fluid px-4" style="color:#000;">

            <?php
            $perfilView      = $perfil ?? [];
            $matriculaView   = $matricula ?? '';
            $id_grupoView    = $id_grupo ?? '';
            $horario_por_dia = $horario_por_dia ?? [];

            $diasMapa = [
                    "Lunes"     => "LUNES",
                    "Martes"    => "MARTES",
                    "Miércoles" => "MIÉRCOLES",
                    "Jueves"    => "JUEVES",
                    "Viernes"   => "VIERNES",
            ];

            $rangosHorarios = [];
            foreach ($horario_por_dia as $diaClave => $clasesDia) {
                foreach ($clasesDia as $clase) {
                    if (!empty($clase['hora'])) {
                        $h = trim($clase['hora']);
                        if (!in_array($h, $rangosHorarios, true)) {
                            $rangosHorarios[] = $h;
                        }
                    }
                }
            }

            if (empty($rangosHorarios)) {
                $rangosHorarios = ["08:00 - 10:00","10:00 - 12:00","12:00 - 14:00"];
            }

            usort($rangosHorarios, function($a, $b){
                return strcmp(substr($a, 0, 5), substr($b, 0, 5));
            });

            function renderClaseEnCelda($horario_por_dia, $diaClave, $rangoHora): string {
                if (!isset($horario_por_dia[$diaClave])) return "";

                foreach ($horario_por_dia[$diaClave] as $clase) {
                    if (isset($clase['hora']) && trim($clase['hora']) === trim($rangoHora)) {
                        $materia = $clase['materia'] ?? '';
                        $clave   = $clase['clave'] ?? '';
                        $profe   = $clase['profesor'] ?? '';
                        $aula    = $clase['aula'] ?? '';

                        return "
                            <div class='slot-clase'>
                                <div class='slot-clase-titulo'>".htmlspecialchars($materia)."</div>
                                <small>".htmlspecialchars($clave)."</small>
                                <small>".htmlspecialchars($profe)."</small>
                                <small>".htmlspecialchars($aula)."</small>
                            </div>
                        ";
                    }
                }
                return "";
            }
            ?>

            <h1 class="page-title">Horario de Clases</h1>
            <p class="page-subtitle">Consulta tus clases por hora y día.</p>

            <div class="card-horario">
                <div class="banner-top">
                    <div>
                        <div class="banner-top-title">
                            <?= htmlspecialchars(
                                    ($perfilView['nombre'] ?? 'Nombre').' '.
                                    ($perfilView['apellido_paterno'] ?? '').' '.
                                    ($perfilView['apellido_materno'] ?? '')
                            ) ?>
                        </div>
                        <div class="banner-top-sub">
                            Matrícula: <span><?= htmlspecialchars($matriculaView) ?></span>
                            &nbsp; • &nbsp;
                            Programa: <span><?= htmlspecialchars($perfilView['nombre_carrera'] ?? 'N/D') ?></span>
                            &nbsp; • &nbsp;
                            Cuatrimestre: <span><?= htmlspecialchars($perfilView['cuatrimestre'] ?? 'N/D') ?></span>
                            &nbsp; • &nbsp;
                            Grupo: <span><?= htmlspecialchars($id_grupoView ?: 'N/D') ?></span>
                        </div>
                    </div>
                </div>

                <div class="tabla-horario-wrapper">
                    <div class="table-responsive unicham-table-card">
                        <table class="unicham-table unicham-table--top">
                            <thead>
                            <tr>
                                <th class="unicham-col-hour">HORAS</th>
                                <?php foreach ($diasMapa as $diaClave => $diaHeaderTexto): ?>
                                    <th><?= $diaHeaderTexto ?></th>
                                <?php endforeach; ?>
                                <th class="unicham-col-total">TOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rangosHorarios as $rangoHora): ?>
                                <?php
                                $conteo = 0;
                                foreach ($diasMapa as $diaClave => $_txt) {
                                    $cellContent = renderClaseEnCelda($horario_por_dia, $diaClave, $rangoHora);
                                    if (trim($cellContent) !== "") $conteo++;
                                }
                                ?>
                                <tr>
                                    <td class="unicham-col-hour"><?= htmlspecialchars($rangoHora) ?></td>

                                    <?php foreach ($diasMapa as $diaClave => $_txt): ?>
                                        <td><?= renderClaseEnCelda($horario_por_dia, $diaClave, $rangoHora); ?></td>
                                    <?php endforeach; ?>

                                    <td class="unicham-col-total"><?= $conteo ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (empty($horario_por_dia)): ?>
                        <div class="alert alert-info mt-3 mb-0" role="alert" style="font-size:0.9rem;">
                            Aún no hay horario registrado para tu grupo.
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