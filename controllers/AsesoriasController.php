<?php
require_once('models/AsesoriasModel.php');

class AsesoriasController {
    private $model;

    public function __construct() {
        $this->model = new AsesoriasModel();
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $matricula = $_SESSION['matricula'] ?? '20250001';
        
        $alumnoInfo = $this->model->getAlumnoHeaderData($matricula);
        $asesorias = $this->model->getAsesorias();
        
        $diasSemana = ['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Miercoles' => 3, 'Jueves' => 4, 'Viernes' => 5];
        $tablaHorario = [];

        foreach ($asesorias as $row) {
            $diaSemana = $row['dia_semana'];
            $horaInicio = $row['hora_inicio'];
            $horaFin = $row['hora_fin'];
            $materia = $row['materia'];
            $profesor = $row['profesor'];
            $lugar = $row['lugar'];

            $horaRango = substr($horaInicio, 0, 5) . " - " . substr($horaFin, 0, 5);
            $diaColumna = $diasSemana[$diaSemana] ?? null;

            if ($diaColumna) {
                $tablaHorario[$horaRango][$diaColumna] = [
                    'materia' => $materia,
                    'profesor' => $profesor,
                    'lugar' => $lugar
                ];
            }
        }

        $horasUnicas = array_keys($tablaHorario);
        usort($horasUnicas, function($a, $b) {
            return strcmp(explode(' - ', $a)[0], explode(' - ', $b)[0]);
        });

        $periodoActual = "Agosto 2025 - Diciembre 2025";
        require('views/users/asesoriasView.php');
    }
}
