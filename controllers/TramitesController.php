<?php
require_once('models/TramitesModel.php');

class TramitesController {
    private $model;

    public function __construct() {
        $this->model = new TramitesModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function constanciaTaller() {
        $this->descargarDoc('CONSTANCIA_TALLER', 'Solicitud talleres', 'constancia_taller.pdf');
    }

    public function certificadoEstudios() {
        $this->descargarDoc('CERTIFICADO_ESTUDIOS', 'Certificado oficial', 'certificado_estudios.pdf');
    }

    private function descargarDoc($tipo, $desc, $file) {
        $mat = $_SESSION['matricula'] ?? 'Guest';
        $this->model->registrarSolicitud($mat, $tipo, $desc);
        
        $path = __DIR__ . '/../resources/docs/' . $file;
        if (!file_exists($path)) die("Archivo no disponible.");

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$file.'"');
        readfile($path);
        exit;
    }
}
