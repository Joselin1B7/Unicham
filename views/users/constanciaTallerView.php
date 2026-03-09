<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        body.sb-nav-fixed{ background:#f4f6fb; }
        .wrap{
            max-width: 900px;
            margin: 30px auto;
            padding: 0 14px;
        }
        .back{
            display:inline-block;
            margin-bottom:12px;
            text-decoration:none;
            font-weight:600;
            color:#6f42c1;
        }
        .card-constancia{
            background:#fff;
            border-radius:14px;
            box-shadow:0 12px 30px rgba(15,23,42,.12);
            padding: 42px 38px;
            border: 4px solid #6f00ff;
            outline: 2px solid #6f00ff;
            outline-offset: -14px;
        }
        .brand{
            text-align:center;
            font-size:44px;
            letter-spacing:2px;
            color:#6f00ff;
            font-weight:900;
            margin-bottom:6px;
        }
        .dept{
            text-align:center;
            font-weight:700;
            color:#6b7280;
            margin-bottom:28px;
        }
        .txt{ text-align:center; color:#111; font-size:18px; }
        .name{
            text-align:center;
            font-size:34px;
            font-weight:900;
            margin:18px 0 6px 0;
        }
        .line{
            width: 360px;
            height: 3px;
            background:#6f00ff;
            margin: 0 auto 22px auto;
        }
        .row-bottom{
            display:flex;
            justify-content:space-between;
            gap:16px;
            margin-top:34px;
            align-items:flex-end;
        }
        .firma{
            flex:1;
            text-align:center;
        }
        .firma .hr{
            height:1px;background:#111;margin:0 auto 8px auto;max-width:320px;
        }
        .print-area{
            text-align:center;
            margin-top:20px;
        }

        @media print{
            .no-print{ display:none !important; }
            body{ background:#fff !important; }
            .card-constancia{ box-shadow:none !important; }
        }
    </style>
</head>
<body class="sb-nav-fixed">

<div class="wrap">
    <a class="back no-print" href="/UniCham/index.php?controller=user&method=talleres">← Volver a mis Talleres</a>

    <div class="card-constancia">
        <div class="brand">UNICHAM</div>
        <div class="dept">DEPARTAMENTO DE ACTIVIDADES COMPLEMENTARIAS</div>

        <div class="txt">Se otorga la presente constancia a:</div>

        <div class="name">
            <?= htmlspecialchars(($perfil['nombre'] ?? '').' '.($perfil['apellido_paterno'] ?? '').' '.($perfil['apellido_materno'] ?? '')) ?>
        </div>
        <div class="line"></div>

        <div class="txt">
            Por haber cumplido satisfactoriamente con un total de
            <b><?= (int)($horasTotales ?? 150) ?> horas</b>
        </div>

        <div class="txt" style="margin-top:10px;">
            En la academia de Talleres Estudiantiles</b>
        </div>

        <div class="row-bottom">
            <div class="firma">
                <div class="hr"></div>
                <small>Firma de la Institución</small>
            </div>

            <div style="min-width:220px;text-align:right;">
                <b>Fecha:</b> <?= htmlspecialchars($fechaHoy ?? date('d/m/Y')) ?>
            </div>
        </div>

        <div class="print-area no-print">
            <button class="btn btn-success" onclick="window.print()">
                🖨️ Imprimir / Guardar como PDF
            </button>
        </div>
    </div>
</div>

</body>
</html>