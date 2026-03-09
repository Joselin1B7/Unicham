<?php
require_once('models/AsesoriasModel.php');

class AsesoriasController
{
    private $model;

    public function __construct()
    {
        $this->model = new AsesoriasModel();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Devuelve la matrícula del alumno loggeado.
     */
    private function getMatriculaSesion()
    {
        if (!empty($_SESSION['matricula'])) {
            return $_SESSION['matricula'];
        }

        // Fallback para pruebas
        return '20250001';  // tu matrícula de ejemplo
    }

    public function index()
    {
        // 1. Datos del alumno
        $matricula   = $this->getMatriculaSesion();
        $alumnoInfo  = $this->model->getAlumnoHeaderData($matricula);
        $alumnoNombre    = $alumnoInfo['nombre_completo'];
        $alumnoMatricula = $alumnoInfo['matricula'];
        $alumnoCarrera   = $alumnoInfo['carrera'];

        // Periodo (por ahora estático)
        $periodo = "Agosto 2025 - Diciembre 2025";

        // 2. Traer asesorías desde BD
        $asesorias = $this->model->getAsesorias();

        // 3. Mapa de día texto -> índice 1..5
        $mapDias = [
            'Lunes'      => 1,
            'Martes'     => 2,
            'Miércoles'  => 3,
            'Miercoles'  => 3,
            'Jueves'     => 4,
            'Viernes'    => 5,
        ];

        // 4. Reorganizar asesorías por franja horaria y día
        $tabla = [];
        foreach ($asesorias as $row) {

            // Etiqueta de hora: "13:00 - 14:00"
            $horaLabel = substr($row['hora_inicio'], 0, 5) . " - " . substr($row['hora_fin'], 0, 5);

            // Día como texto (Jueves/Viernes) -> índice numérico
            $diaTexto = $row['dia_semana'];
            if (!isset($mapDias[$diaTexto])) {
                continue; // por si hubiera algo raro
            }
            $dia = $mapDias[$diaTexto];

            if (!isset($tabla[$horaLabel])) {
                $tabla[$horaLabel] = [];
            }

            $tabla[$horaLabel][$dia] = [
                'materia'  => $row['materia'],
                'profesor' => $row['profesor'],
                'lugar'    => $row['lugar'],
            ];
        }

        // 5. Ordenar las horas
        $horas = array_keys($tabla);
        usort($horas, function($a, $b) {
            list($iniA,) = explode(' - ', $a);
            list($iniB,) = explode(' - ', $b);
            return strcmp($iniA, $iniB);
        });

        // 6. Incluir vista
        require('views/users/asesoriasView.php');
    }
}
