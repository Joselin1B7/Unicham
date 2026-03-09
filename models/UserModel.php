<?php

class UserModel {
    private $db;

    public function __construct() {
        // Conexión estándar usando PDO
        $this->db = new PDO('mysql:host=localhost;dbname=unicham;charset=utf8mb4','root','');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Obtiene el perfil completo del usuario por su nombre de usuario.
     */
    public function getPerfilPorUsuario(string $nombreUsuario) {
        $sql = "SELECT u.id_usuario, u.nombre_usuario, u.email, u.foto_perfil,
                       a.matricula, a.nombre, a.apellido_paterno, a.apellido_materno,
                       a.fecha_ingreso, a.telefono,
                       g.nombre_grupo, g.cuatrimestre,
                       c.nombre_carrera
                FROM usuarios u
                LEFT JOIN alumnos a ON a.id_usuario = u.id_usuario
                LEFT JOIN grupos g  ON g.id_grupo   = a.id_grupo
                LEFT JOIN carreras c ON c.id_carrera = a.id_carrera
                WHERE u.nombre_usuario = ?
                LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute([$nombreUsuario]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la foto de perfil (BLOB) por ID de usuario.
     */
    public function getPhotoBlobByIdUsuario(int $idUsuario) {
        $st = $this->db->prepare("SELECT foto_perfil FROM usuarios WHERE id_usuario=?");
        $st->execute([$idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['foto_perfil'] : null;
    }

    /**
     * Actualiza la foto de perfil manejando errores de tamaño de paquete.
     */
    public function updatePhotoBlobByIdUsuario(int $idUsuario, string $blob): array {
        try {
            $st = $this->db->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
            $ok = $st->execute([$blob, $idUsuario]);
            return ['ok' => (bool)$ok, 'msg' => null];
        } catch (PDOException $e) {
            return $this->manejarErrorUpdateFoto($e);
        }
    }

    private function manejarErrorUpdateFoto(PDOException $e): array {
        $mysqlCode = $e->errorInfo[1] ?? null;
        if ($mysqlCode === 1153 || stripos($e->getMessage(), 'max_allowed_packet') !== false) {
            return ['ok' => false, 'msg' => 'La imagen es demasiado grande. Sube una más ligera (menor a 1MB).'];
        }
        if (stripos($e->getMessage(), 'Data too long') !== false) {
            return ['ok' => false, 'msg' => 'La imagen excede el tamaño permitido por la base de datos.'];
        }
        return ['ok' => false, 'msg' => 'No se pudo guardar la imagen. Intenta con otra.'];
    }

    /**
     * Obtiene el historial de calificaciones.
     */
    public function getCalificacionesPorMatricula(string $matricula) {
        $sql = "SELECT c.id_calificacion, c.matricula, c.calificacion, c.estatus, c.fecha_registro,
                       m.clave_materia, m.nombre_materia, m.creditos,
                       p.periodo AS periodo, p.anio AS anio, p.fecha_inicio, p.fecha_fin
                FROM calificaciones c
                INNER JOIN materias m ON c.id_materia = m.id_materia
                INNER JOIN periodos_escolares p ON c.id_periodo = p.id_periodo
                WHERE c.matricula = ?
                ORDER BY p.fecha_inicio DESC, m.nombre_materia ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Datos básicos del alumno para vinculación de grupo.
     */
    public function getAlumnoBasicoPorMatricula(string $matricula) {
        $sql = "SELECT a.matricula, a.id_grupo, a.id_carrera, a.id_usuario,
                       a.nombre, a.apellido_paterno, a.apellido_materno
                FROM alumnos a
                WHERE a.matricula = ?
                LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene y estructura el horario del grupo.
     */
    public function getHorarioPorGrupo(int $id_grupo) {
        $sql = "SELECT h.id_horario, h.dia_semana, h.hora_inicio, h.hora_fin,
                       m.clave_materia, m.nombre_materia,
                       p.nombre AS nombre_profesor, p.apellido_paterno AS apellido_profesor_paterno,
                       p.apellido_materno AS apellido_profesor_materno,
                       au.nombre_aula
                FROM horarios h
                INNER JOIN carga_academica ca ON h.id_carga = ca.id_carga
                INNER JOIN materias m ON ca.id_materia = m.id_materia
                INNER JOIN profesores p ON ca.id_profesor = p.id_profesor
                INNER JOIN aulas au ON h.id_aula = au.id_aula
                WHERE ca.id_grupo = ?
                ORDER BY FIELD(h.dia_semana, 'Lunes','Martes','Miércoles','Miercoles','Jueves','Viernes','Sábado','Sabado'),
                         h.hora_inicio ASC";

        $st = $this->db->prepare($sql);
        $st->execute([$id_grupo]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $horarioPorDia = [];
        foreach ($rows as $r) {
            $dia = $r['dia_semana'];
            if (!isset($horarioPorDia[$dia])) $horarioPorDia[$dia] = [];
            $horarioPorDia[$dia][] = $this->formatearFilaHorario($r);
        }
        return $horarioPorDia;
    }

    private function formatearFilaHorario($r) {
        $nombreProf = trim(($r['nombre_profesor'] ?? '') . ' ' . 
                           ($r['apellido_profesor_paterno'] ?? '') . ' ' . 
                           ($r['apellido_profesor_materno'] ?? ''));
        return [
            'hora'     => substr($r['hora_inicio'], 0, 5) . " - " . substr($r['hora_fin'], 0, 5),
            'materia'  => $r['nombre_materia'] ?? 'Materia',
            'clave'    => $r['clave_materia'] ?? '',
            'profesor' => $nombreProf !== '' ? $nombreProf : 'Profesor N/D',
            'aula'     => $r['nombre_aula'] ?? 'Aula N/D'
        ];
    }

    public function getPerfilAlumno($matricula) {
        $sql = "SELECT a.matricula, a.nombre, a.apellido_paterno, a.apellido_materno,
                       c.nombre_carrera, g.id_grupo, g.cuatrimestre
                FROM alumnos a
                LEFT JOIN carreras c ON a.id_carrera = c.id_carrera
                LEFT JOIN grupos g   ON a.id_grupo   = g.id_grupo
                WHERE a.matricula = ?";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistorialAlumno($matricula) {
        $sql = "SELECT cal.id_periodo, pe.periodo, pe.anio, pe.fecha_fin AS fecha_cierre,
                       m.clave_materia AS clave, m.nombre_materia AS materia, m.creditos,
                       cal.calificacion, cal.estatus
                FROM calificaciones cal
                INNER JOIN periodos_escolares pe ON cal.id_periodo = pe.id_periodo
                LEFT JOIN materias m ON cal.id_materia = m.id_materia
                WHERE cal.matricula = ?
                ORDER BY cal.id_periodo, m.clave_materia";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTalleresAlumno(string $matricula) {
        $sql = "SELECT it.id_inscripcion, it.matricula, it.id_taller, it.horas_acumuladas,
                       it.calificacion_final, it.estatus, it.fecha_inscripcion,
                       t.nombre_taller, t.responsable, t.lugar, t.horario, t.cupo_maximo, t.activo
                FROM inscripciones_talleres it
                INNER JOIN talleres t ON it.id_taller = t.id_taller
                WHERE it.matricula = ?
                ORDER BY it.fecha_inscripcion DESC, t.nombre_taller ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeriodoActual() {
        // CORRECCIÓN SEGURIDAD: Usar prepare en lugar de query
        $sql = "SELECT id_periodo, periodo, anio, fecha_inicio, fecha_fin
                FROM periodos_escolares
                ORDER BY fecha_inicio DESC
                LIMIT 1";
        $st = $this->db->prepare($sql);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getTalleresDisponiblesParaInscripcion(string $matricula) {
        $sql = "SELECT t.id_taller, t.nombre_taller, t.horas, t.lugar, t.fecha_inicio, t.fecha_fin,
                       p.nombre AS profesor_nombre, p.apellido_paterno AS profesor_apellido_p,
                       p.apellido_materno AS profesor_apellido_m
                FROM talleres t
                LEFT JOIN profesores p ON p.id_profesor = t.id_profesor
                WHERE t.estado = 'Activo'
                  AND t.id_taller NOT IN (SELECT id_taller FROM inscripciones_talleres WHERE matricula = ?)
                ORDER BY t.nombre_taller ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inscribirAlumnoEnTaller(string $matricula, int $id_taller): bool {
        if ($id_taller <= 0) return false;
        
        $sqlCheck = "SELECT COUNT(*) AS c FROM inscripciones_talleres WHERE matricula = ? AND id_taller = ?";
        $stC = $this->db->prepare($sqlCheck);
        $stC->execute([$matricula, $id_taller]);
        $row = $stC->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($row['c']) && (int)$row['c'] > 0) return false;

        $sqlIns = "INSERT INTO inscripciones_talleres (matricula, id_taller, fecha_inscripcion, horas_asignadas, estado_asistencia)
                   VALUES (?, ?, NOW(), 0, 'Inscrito')";
        $stI = $this->db->prepare($sqlIns);
        return $stI->execute([$matricula, $id_taller]);
    }

    public function getPasswordHashByUserId($idUsuario) {
        $sql = "SELECT password FROM usuarios WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }

    public function updatePassword($idUsuario, $nuevoHash) {
        $sql = "UPDATE usuarios SET password = :pwd WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':pwd', $nuevoHash, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserIdByMatricula($matricula) {
        $sql = "SELECT id_usuario FROM alumnos WHERE matricula = :mat LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':mat', $matricula, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_usuario'] : null;
    }
}
