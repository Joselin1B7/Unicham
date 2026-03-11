<?php
require_once('db/Conexiondb.php');

class AsesoriasModel extends Conexiondb {
    private $db;

    public function __construct() {
        $this->db = $this->conectardb();
        $this->setNames();
    }

    public function getAlumnoHeaderData($matricula) {
        $sql = "SELECT CONCAT(a.nombre, ' ', a.apellido_paterno) AS nombre_completo,
                       a.matricula, c.nombre_carrera AS carrera
                FROM alumnos a
                INNER JOIN careers c ON c.id_carrera = a.id_carrera
                WHERE a.matricula = :matricula LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':matricula' => $matricula]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['nombre_completo' => 'N/D', 'matricula' => $matricula, 'carrera' => 'N/D'];
    }

    /**
     * Devuelve el horario ya agrupado por hora y día para que el controlador no haga bucles.
     * Complejidad: 4
     */
    public function getHorarioEstructurado() {
        $sql = "SELECT a.dia_semana, a.hora_inicio, a.hora_fin, a.lugar, m.nombre_materia AS materia, p.nombre AS profesor
                FROM asesorias a
                INNER JOIN materias m ON m.id_materia = a.id_materia
                INNER JOIN profesores p ON p.id_profesor = a.id_profesor";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $diasMap = ['Lunes'=>1, 'Martes'=>2, 'Miércoles'=>3, 'Jueves'=>4, 'Viernes'=>5];
        $tabla = [];

        foreach ($raw as $r) {
            $hora = substr($r['hora_inicio'], 0, 5) . " - " . substr($r['hora_fin'], 0, 5);
            $diaId = $diasMap[$r['dia_semana']] ?? 0;
            if ($diaId > 0) $tabla[$hora][$diaId] = $r;
        }
        ksort($tabla);
        return $tabla;
    }
}
