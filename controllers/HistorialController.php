<?php
class HistorialController {

    private $db; // conexión (mysqli o PDO, lo que uses)

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function historialAlumno() {

        // asumimos que ya tienes la matrícula del alumno en sesión
        session_start();
        $matricula = $_SESSION['matricula']; // <-- AJUSTA si usas otro nombre

        // =========================================
        // 1. SACAR DATOS DEL ALUMNO
        // =========================================
        // ejemplo de estructura típica:
        // alumnos(id_alumno, matricula, nombre, apellido_paterno, apellido_materno,
        //         id_carrera, cuatrimestre_actual, id_grupo)
        // carreras(id_carrera, nombre_carrera)
        $sqlPerfil = "
            SELECT  a.nombre,
                    a.apellido_paterno,
                    a.apellido_materno,
                    c.nombre_carrera    AS nombre_carrera,
                    a.cuatrimestre_actual AS cuatrimestre,
                    a.id_grupo
            FROM alumnos a
            INNER JOIN carreras c ON c.id_carrera = a.id_carrera
            WHERE a.matricula = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sqlPerfil);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $resPerfil = $stmt->get_result();
        $perfilRow = $resPerfil->fetch_assoc() ?: [];

        // esto alimenta la vista:
        $perfilView = [
            'nombre'           => $perfilRow['nombre']            ?? '',
            'apellido_paterno' => $perfilRow['apellido_paterno']  ?? '',
            'apellido_materno' => $perfilRow['apellido_materno']  ?? '',
            'nombre_carrera'   => $perfilRow['nombre_carrera']    ?? '',
            'cuatrimestre'     => $perfilRow['cuatrimestre']      ?? '',
        ];

        $matriculaView = $matricula;
        $id_grupoView  = $perfilRow['id_grupo'] ?? '';

        // =========================================
        // 2. SACAR EL HISTORIAL / KARDEX
        // =========================================
        // te propongo esta forma de tabla (ajusta a lo tuyo):
        // calificaciones_finales(
        //      id_calif,
        //      matricula,
        //      id_materia,
        //      periodo,           -- "Enero-Abril 2024"
        //      calificacion_final,
        //      estatus            -- 'APROBADA' / 'REPROBADA'
        // )
        //
        // materias(
        //      id_materia,
        //      clave_materia,     -- 'ETC-401'
        //      nombre_materia,    -- 'Redes I'
        //      creditos
        // )
        //
        // IMPORTANTE: si tus tablas tienen otros nombres, solo cambias el FROM y los campos.

        $sqlKardex = "
            SELECT 
                cf.periodo,
                m.clave_materia       AS clave,
                m.nombre_materia      AS materia,
                cf.calificacion_final AS calificacion,
                m.creditos            AS creditos,
                cf.estatus            AS estatus
            FROM calificaciones_finales cf
            INNER JOIN materias m ON m.id_materia = cf.id_materia
            WHERE cf.matricula = ?
            ORDER BY cf.periodo, m.nombre_materia
        ";

        $stmt2 = $this->db->prepare($sqlKardex);
        $stmt2->bind_param("s", $matricula);
        $stmt2->execute();
        $resKardex = $stmt2->get_result();

        $kardexView = [];
        while ($row = $resKardex->fetch_assoc()) {
            $kardexView[] = [
                'periodo'      => $row['periodo'],
                'clave'        => $row['clave'],
                'materia'      => $row['materia'],
                'calificacion' => $row['calificacion'],
                'creditos'     => $row['creditos'],
                'estatus'      => $row['estatus'],
            ];
        }

        // =========================================
        // 3. MANDAR A LA VISTA
        // =========================================
        // estas variables son exactamente las que usa historialView.php
        require 'views/user/historialView.php';
    }
}