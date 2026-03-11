<?php
require_once('models/AsesoriasModel.php');

class AsesoriasController {
    private $model;

    public function __construct() {
        $this->model = new AsesoriasModel();
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $mat = $_SESSION['matricula'] ?? '';
        
        $alumnoInfo = $this->model->getAlumnoHeaderData($mat);
        $asesorias = $this->model->getAsesorias();
        $tabla = $this->mapearHorario($asesorias);

        $periodo = "Enero - Junio 2026";
        require('views/users/asesoriasView.php');
    }

    private function mapearHorario($datos) {
        $dias = ['Lunes'=>1, 'Martes'=>2, 'Miércoles'=>3, 'Jueves'=>4, 'Viernes'=>5];
        $res = [];
        foreach ($datos as $r) {
            $h = substr($r['hora_inicio'], 0, 5) . " - " . substr($r['hora_fin'], 0, 5);
            $d = $dias[$r['dia_semana']] ?? 0;
            if ($d > 0) $res[$h][$d] = $r;
        }
        ksort($res);
        return $res;
    }
}
