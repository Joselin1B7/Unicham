<?php
require_once('models/TramitesModel.php');

class TramitesController {
    private $model;

    public function __construct() {
        $this->model = new TramitesModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function constanciaTaller() {
        $this->procesarDoc('CONSTANCIA_TALLER', 'Solicitud de talleres', 'constancia_taller.pdf');
    }

    public function certificadoEstudios() {
        $this->procesarDoc('CERTIFICADO_ESTUDIOS', 'Certificado licenciatura', 'certificado_estudios.pdf');
    }

    public function reinscripcion() {
        $this->procesarDoc('REINSCRIPCION', 'Formatos reinscripción', 'reinscripcion_requisitos.pdf');
    }

    private function procesarDoc($tipo, $desc, $fileName) {
        $mat = $_SESSION['matricula'] ?? 'A00123456';
        $this->model->registrarSolicitud($mat, $tipo, $desc);
        $this->streamPDF(__DIR__ . '/../resources/docs/' . $fileName, $tipo . '_' . $mat . '.pdf');
    }

    private function streamPDF($path, $downloadName) {
        if (!file_exists($path)) die("Documento no disponible.");
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$downloadName.'"');
        readfile($path);
        exit;
    }
}
