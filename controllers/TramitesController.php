<?php
require_once('models/TramitesModel.php');

class TramitesController {
    private $model;

    public function __construct() {
        $this->model = new TramitesModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function constanciaTaller() {
        $matricula = $_SESSION['matricula'] ?? 'A00123456';
        $tipoTramite = 'CONSTANCIA_TALLER';
        $descripcion = 'Solicitud de constancia de terminación de talleres';
        
        $this->model->registrarSolicitud($matricula, $tipoTramite, $descripcion);

        $filePath = __DIR__ . '/../resources/docs/constancia_taller.pdf';
        if (file_exists($filePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="constancia_taller.pdf"');
            readfile($filePath);
        } else {
            echo "El archivo no existe.";
        }
        exit;
    }

    public function certificadoEstudios() {
        $matricula = $_SESSION['matricula'] ?? 'A00123456';
        $tipoTramite = 'CERTIFICADO_ESTUDIOS';
        $descripcion = 'Solicitud de certificado parcial de estudios';
        
        $this->model->registrarSolicitud($matricula, $tipoTramite, $descripcion);

        $filePath = __DIR__ . '/../resources/docs/certificado_estudios.pdf';
        if (file_exists($filePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="certificado_estudios.pdf"');
            readfile($filePath);
        } else {
            echo "El archivo no existe.";
        }
        exit;
    }
}
