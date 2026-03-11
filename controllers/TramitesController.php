<?php
require_once('models/TramitesModel.php');

class TramitesController {
    private $model;

    public function __construct() {
        $this->model = new TramitesModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function constanciaTaller() {
        $this->generarSalida('CONSTANCIA_TALLER', 'Solicitud de talleres', 'constancia_taller.pdf');
    }

    public function certificadoEstudios() {
        $this->generarSalida('CERTIFICADO_ESTUDIOS', 'Certificado oficial', 'certificado_estudios.pdf');
    }

    private function generarSalida($tipo, $desc, $file) {
        $mat = $_SESSION['matricula'] ?? 'S/M';
        $this->model->registrarSolicitud($mat, $tipo, $desc);
        $path = __DIR__ . '/../resources/docs/' . $file;

        if (!file_exists($path)) {
            die("El archivo no se encuentra disponible.");
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$file.'"');
        readfile($path);
        exit;
    }
}
