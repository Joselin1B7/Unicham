<?php
require_once('models/TramitesModel.php');

class TramitesController
{
    private $model;

    public function __construct()
    {
        $this->model = new TramitesModel();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getMatriculaAlumno()
    {
        if (isset($_SESSION['matricula'])) {
            return $_SESSION['matricula'];
        }

        // temporal para pruebas si aún no jalas la matrícula real al iniciar sesión
        return 'A00123456';
    }

    // -------- CONSTANCIA DE TALLER --------
    public function constanciaTaller()
    {
        $matricula = $this->getMatriculaAlumno();

        $this->model->registrarSolicitud(
            $matricula,
            'CONSTANCIA_TALLER',
            'Solicitud de constancia de talleres y actividades extracurriculares'
        );

        $pdfPath = __DIR__ . '/../resources/docs/constancia_taller.pdf';
        $this->streamPDF($pdfPath, 'Constancia_Taller_' . $matricula . '.pdf');
    }

    // -------- CERTIFICADO DE ESTUDIOS --------
    public function certificadoEstudios()
    {
        $matricula = $this->getMatriculaAlumno();

        $this->model->registrarSolicitud(
            $matricula,
            'CERTIFICADO_ESTUDIOS',
            'Solicitud de certificado de estudios/licenciatura'
        );

        $pdfPath = __DIR__ . '/../resources/docs/certificado_estudios.pdf';
        $this->streamPDF($pdfPath, 'Certificado_Estudios_' . $matricula . '.pdf');
    }

    // -------- REINSCRIPCIÓN --------
    public function reinscripcion()
    {
        $matricula = $this->getMatriculaAlumno();

        $this->model->registrarSolicitud(
            $matricula,
            'REINSCRIPCION',
            'Descarga de formatos y requisitos de reinscripción'
        );

        $pdfPath = __DIR__ . '/../resources/docs/reinscripcion_requisitos.pdf';
        $this->streamPDF($pdfPath, 'Reinscripcion_' . $matricula . '.pdf');
    }

    // -------- PAGO DE SEGURO --------
    public function pagoSeguro()
    {
        $matricula = $this->getMatriculaAlumno();

        $this->model->registrarSolicitud(
            $matricula,
            'PAGO_SEGURO',
            'Generación de línea de captura para seguro médico'
        );

        header("Location: https://sfpya.edomexico.gob.mx/recaudacion/faces/municipios/organismosAuxiliares/OrganismosAuxiliares.xhtml#!");
        exit;
    }

    // -------- REPOSICIÓN DE CREDENCIAL --------
    public function reposicionCredencial()
    {
        $matricula = $this->getMatriculaAlumno();

        $this->model->registrarSolicitud(
            $matricula,
            'REPOSICION_CREDENCIAL',
            'Trámite y pago de reposición de credencial'
        );

        header("Location: https://sfpya.edomexico.gob.mx/recaudacion/faces/municipios/organismosAuxiliares/OrganismosAuxiliares.xhtml#!");
        exit;
    }

    // -------- función privada para mandar el PDF --------
    private function streamPDF($absolutePath, $downloadName)
    {
        if (!file_exists($absolutePath)) {
            header("HTTP/1.1 404 Not Found");
            echo "El documento solicitado no está disponible por el momento.";
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($absolutePath));
        readfile($absolutePath);
        exit;
    }
}
