<?php
require_once('db/Conexiondb.php');

class AsesoriasModel extends Conexiondb
{
    private $db;

    public function __construct()
    {
        $this->db = $this->conectardb();
        $this->setNames();
    }

    /**
     * Trae todas las asesorías con su materia, profe, hora, día y lugar.
     */
    public function getAsesorias()
    {
        $sql = "
            SELECT 
                a.dia_semana,
                a.hora_inicio,
                a.hora_fin,
                a.lugar,
                a.descripcion,
                m.nombre_materia AS materia,
                p.nombre AS profesor
            FROM asesorias a
            INNER JOIN materias m ON m.id_materia = a.id_materia
            INNER JOIN profesores p ON p.id_profesor = a.id_profesor
            ORDER BY a.hora_inicio, a.dia_semana
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trae datos para el encabezado de la vista de asesorías:
     * - nombre completo del alumno
     * - matrícula
     * - carrera (nombre_carrera)
     */
    public function getAlumnoHeaderData($matricula)
    {
        $sql = "
            SELECT 
                CONCAT(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) AS nombre_completo,
                a.matricula,
                c.nombre_carrera AS carrera,
                a.fecha_ingreso
            FROM alumnos a
            INNER JOIN carreras c ON c.id_carrera = a.id_carrera
            WHERE a.matricula = :matricula
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return [
                'nombre_completo' => $row['nombre_completo'],
                'matricula'       => $row['matricula'],
                'carrera'         => $row['carrera'],
                'fecha_ingreso'   => $row['fecha_ingreso'],
            ];
        }

        // Fallback si la matrícula no existe en BD (solo para no tronar)
        return [
            'nombre_completo' => 'Alumno Desconocido',
            'matricula'       => $matricula,
            'carrera'         => 'Carrera no disponible',
            'fecha_ingreso'   => '',
        ];
    }
}
