<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('views/template/header.php'); ?>
    <style>
        .btn-purple {
            color: #fff; background-color: #5900ffff; border-color: #5900ffff; transition: background-color 0.3s;
        }
        .btn-purple:hover {
            background-color: #4c00cc; border-color: #4c00cc;
        }
        .info-box { border-left: 5px solid #ffc107; padding: 15px; background-color: #fffbf5; }
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
                <h1 class="mt-4 mb-3"><i class="fas fa-shield-alt me-2"></i> Pago de Seguro de Gastos Médicos</h1>
                <p class="lead mb-4">Confirma la generación de tu línea de captura para el pago del seguro estudiantil obligatorio.</p>

                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-dark">
                        Detalles del Pago
                    </div>
                    <div class="card-body">

                        <div class="info-box mb-4">
                            <h5>Periodo: Agosto 2025 - Julio 2026</h5>
                            <p class="mb-0">Costo Anual: $850.00 MXN</p>
                            <p class="mb-0 text-danger">Fecha Límite de Pago: 30 de Agosto de 2025</p>
                        </div>

                        <form id="formGenerarPago" method="POST" action="generar_pago_seguro.php">
                            <h4 class="mb-3">Confirmación</h4>

                            <div class="mb-4">
                                <p>Al presionar el botón, se generará una línea de captura y se registrará un adeudo en tu estado de cuenta por el monto total. Podrás pagar en cualquier banco asociado o en caja de la universidad.</p>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="" id="confirmarGeneracion" required>
                                <label class="form-check-label" for="confirmarGeneracion">
                                    Confirmo que deseo generar la línea de pago para el seguro estudiantil.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-purple"><i class="fas fa-barcode me-2"></i> Generar Línea de Captura</button>
                        </form>

                        <div id="resultadoPago" class="mt-4 p-3 border rounded" style="display:none;">
                            <h5 class="text-success"><i class="fas fa-check-circle me-2"></i> Línea de Captura Generada</h5>
                            <p>Tu línea de captura es: 990000123456789</p>
                            <p>Descarga tu formato de pago <a href="#">aquí (PDF)</a>.</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php require_once('views/template/footer.php'); ?>
    </div>
</div>
<?php require_once('views/template/script.php'); ?>
<script>
    document.getElementById('formGenerarPago').addEventListener('submit', function(event) {
        event.preventDefault();
        document.getElementById('formGenerarPago').style.display = 'none';
        document.getElementById('resultadoPago').style.display = 'block';
    });
</script>
</body>
</html>