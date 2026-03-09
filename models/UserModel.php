<?php
class UserModel {
    private $db;
    public function __construct() {
        // Ajusta si tus credenciales son distintas
        $this->db = new PDO('mysql:host=localhost;dbname=unicham;charset=utf8mb4','root','');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Perfíl por nombre de usuario
    public function getPerfilPorUsuario(string $nombreUsuario) {
        $sql = "
          SELECT u.id_usuario, u.nombre_usuario, u.email, u.foto_perfil,
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

    // Foto como BLOB (lectura)
    public function getPhotoBlobByIdUsuario(int $idUsuario) {
        $st = $this->db->prepare("SELECT foto_perfil FROM usuarios WHERE id_usuario=?");
        $st->execute([$idUsuario]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['foto_perfil'] : null;
    }


    // Guarda foto como BLOB en usuarios.foto_perfil
    public function updatePhotoBlobByIdUsuario(int $idUsuario, string $blob): array
    {
        try {
            $st = $this->db->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
            $ok = $st->execute([$blob, $idUsuario]);
            return ['ok' => (bool)$ok, 'msg' => null];
        } catch (PDOException $e) {
            // 1153: packet bigger than 'max_allowed_packet'
            $mysqlCode = null;
            if (isset($e->errorInfo[1])) {
                $mysqlCode = (int)$e->errorInfo[1];
            }

            if ($mysqlCode === 1153 || stripos($e->getMessage(), 'max_allowed_packet') !== false) {
                return ['ok' => false, 'msg' => 'La imagen es demasiado grande. Sube una más ligera (por ejemplo < 1MB).'];
            }

            // Por si tu columna es BLOB y el archivo excede el tamaño del campo
            if (stripos($e->getMessage(), 'Data too long') !== false) {
                return ['ok' => false, 'msg' => 'La imagen excede el tamaño permitido por la base de datos.'];
            }

            return ['ok' => false, 'msg' => 'No se pudo guardar la imagen. Intenta con otra.'];
        }
    }


    public function getCalificacionesPorMatricula(string $matricula)
    {
        /*
            Tablas reales en tu DB:
            - calificaciones
                id_calificacion, matricula, id_materia, id_periodo,
                calificacion, estatus, fecha_registro

            - materias
                id_materia, clave_materia, nombre_materia, creditos, ...

            - periodos_escolares
                id_periodo, periodo, anio, fecha_inicio, fecha_fin
                (OJO: NO existe 'nombre_periodo')
        */

        $sql = "
        SELECT
            c.id_calificacion,
            c.matricula,
            c.calificacion,
            c.estatus,
            c.fecha_registro,

            m.clave_materia,
            m.nombre_materia,
            m.creditos,

            p.periodo        AS periodo,     -- ej. 'Enero-Abril'
            p.anio           AS anio,        -- ej. 2025
            p.fecha_inicio,
            p.fecha_fin
        FROM calificaciones c
        INNER JOIN materias m
            ON c.id_materia = m.id_materia
        INNER JOIN periodos_escolares p
            ON c.id_periodo = p.id_periodo
        WHERE c.matricula = ?
        ORDER BY p.fecha_inicio DESC, m.nombre_materia ASC
    ";

        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);

        return $st->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAlumnoBasicoPorMatricula(string $matricula) {
        // Esto es para saber a qué grupo pertenece el alumno
        // y poder usar ese grupo para buscar su horario.
        $sql = "
            SELECT 
                a.matricula,
                a.id_grupo,
                a.id_carrera,
                a.id_usuario,
                a.nombre,
                a.apellido_paterno,
                a.apellido_materno
            FROM alumnos a
            WHERE a.matricula = ?
            LIMIT 1
        ";

        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // HORARIO POR GRUPO
    // ============================================================
    public function getHorarioPorGrupo(int $id_grupo) {
        /*
            Qué estamos haciendo aquí:

            horarios h
                - tiene: id_carga, dia_semana, hora_inicio, hora_fin, id_aula

            carga_academica ca
                - tiene: id_carga, id_materia, id_profesor, id_grupo, id_periodo

            materias m
                - tiene: id_materia, nombre_materia, clave_materia

            profesores p
                - tiene: id_profesor, nombre, apellido_paterno, apellido_materno

            aulas au
                - tiene: id_aula, nombre_aula

            Entonces:
            h --> ca (por id_carga)
            ca --> m (materia), p (profe), id_grupo
            h --> au (aula)

            Y filtramos por el grupo del alumno: ca.id_grupo = ?
        */

        $sql = "
            SELECT
                h.id_horario,
                h.dia_semana,
                h.hora_inicio,
                h.hora_fin,

                m.clave_materia,
                m.nombre_materia,

                p.nombre AS nombre_profesor,
                p.apellido_paterno AS apellido_profesor_paterno,
                p.apellido_materno AS apellido_profesor_materno,

                au.nombre_aula
            FROM horarios h
            INNER JOIN carga_academica ca
                ON h.id_carga = ca.id_carga
            INNER JOIN materias m
                ON ca.id_materia = m.id_materia
            INNER JOIN profesores p
                ON ca.id_profesor = p.id_profesor
            INNER JOIN aulas au
                ON h.id_aula = au.id_aula
            WHERE ca.id_grupo = ?
            ORDER BY 
                FIELD(
                    h.dia_semana,
                    'Lunes','Martes','Miércoles','Miercoles','Jueves','Viernes','Sábado','Sabado'
                ),
                h.hora_inicio ASC
        ";

        $st = $this->db->prepare($sql);
        $st->execute([$id_grupo]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        // Agrupamos por día: 'Lunes' => [clase1, clase2, ...]
        $horarioPorDia = [];

        foreach ($rows as $r) {
            $dia = $r['dia_semana'];

            if (!isset($horarioPorDia[$dia])) {
                $horarioPorDia[$dia] = [];
            }

            // Nombre completo del profe
            $nombreProfesor = trim(
                ($r['nombre_profesor'] ?? '') . ' ' .
                ($r['apellido_profesor_paterno'] ?? '') . ' ' .
                ($r['apellido_profesor_materno'] ?? '')
            );

            $horarioPorDia[$dia][] = [
                'hora'      => substr($r['hora_inicio'],0,5) . " - " . substr($r['hora_fin'],0,5),
                'materia'   => $r['nombre_materia'] ?? 'Materia',
                'clave'     => $r['clave_materia'] ?? '',
                'profesor'  => $nombreProfesor !== '' ? $nombreProfesor : 'Profesor N/D',
                'aula'      => $r['nombre_aula'] ?? 'Aula N/D'
            ];
        }

        return $horarioPorDia;
    }

    public function getPerfilAlumno($matricula) {
        $sql = "SELECT 
                    a.matricula,
                    a.nombre,
                    a.apellido_paterno,
                    a.apellido_materno,
                    c.nombre_carrera,
                    g.id_grupo,
                    g.cuatrimestre
                FROM alumnos a
                LEFT JOIN carreras c    ON a.id_carrera = c.id_carrera
                LEFT JOIN grupos g      ON a.id_grupo   = g.id_grupo
                WHERE a.matricula = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$matricula]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Historial académico crudo (todas las materias por periodo)


    public function getHistorialAlumno($matricula) {
        /*
            Objetivo: regresar TODAS las materias del alumno, junto con
            periodo, año, créditos, calificación, estatus y fecha de cierre.

            Tablas:
            - calificaciones  (matricula, id_materia, id_periodo, calificacion, estatus)
            - periodos_escolares (id_periodo, periodo, anio, fecha_inicio, fecha_fin)
            - materias (id_materia, clave_materia, nombre_materia, creditos)
        */

        $sql = "SELECT 
                cal.id_periodo,

                -- Periodo
                pe.periodo       AS periodo,       -- 'Enero-Abril'
                pe.anio          AS anio,          -- 2025
                pe.fecha_fin     AS fecha_cierre,  -- la usará la vista

                -- Materia (alias que espera la vista)
                m.clave_materia  AS clave,
                m.nombre_materia AS materia,
                m.creditos       AS creditos,

                -- Calificación y estatus
                cal.calificacion,
                cal.estatus
            FROM calificaciones cal
            INNER JOIN periodos_escolares pe 
                    ON cal.id_periodo = pe.id_periodo
            LEFT JOIN materias m 
                    ON cal.id_materia = m.id_materia
            WHERE cal.matricula = ?
            ORDER BY cal.id_periodo, m.clave_materia";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$matricula]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTalleresAlumno(string $matricula)
    {
        /*
            Trae todos los talleres en los que está inscrito el alumno,
            junto con la info del taller.

            Tablas:
            - inscripciones_talleres (it)
                id_inscripcion
                matricula
                id_taller
                horas_acumuladas
                calificacion_final
                estatus
                fecha_inscripcion

            - talleres (t)
                id_taller
                nombre_taller
                responsable
                lugar
                horario
                cupo_maximo
                activo
        */

        $sql = "
        SELECT
            it.id_inscripcion,
            it.matricula,
            it.id_taller,
            it.horas_acumuladas,
            it.calificacion_final,
            it.estatus,
            it.fecha_inscripcion,

            t.nombre_taller,
            t.responsable,
            t.lugar,
            t.horario,
            t.cupo_maximo,
            t.activo
        FROM inscripciones_talleres it
        INNER JOIN talleres t
            ON it.id_taller = t.id_taller
        WHERE it.matricula = ?
        ORDER BY it.fecha_inscripcion DESC, t.nombre_taller ASC
    ";

        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getPeriodoActual()
    {
        /*
            Sacamos el periodo más reciente basándonos en fecha_inicio.
            Tu tabla periodos_escolares debe tener:
            - id_periodo
            - periodo         (ej. 'Enero-Abril')
            - anio            (ej. 2025)
            - fecha_inicio
            - fecha_fin
        */
        $sql = "
        SELECT
            id_periodo,
            periodo,
            anio,
            fecha_inicio,
            fecha_fin
        FROM periodos_escolares
        ORDER BY fecha_inicio DESC
        LIMIT 1
    ";

        $st = $this->db->query($sql);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }


    public function getTalleresDisponiblesParaInscripcion(string $matricula)
    {
        $sql = "
        SELECT t.id_taller,
               t.nombre_taller,
               t.horas,
               t.lugar,
               t.fecha_inicio,
               t.fecha_fin,
               p.nombre AS profesor_nombre,
               p.apellido_paterno AS profesor_apellido_p,
               p.apellido_materno AS profesor_apellido_m
        FROM talleres t
        LEFT JOIN profesores p ON p.id_profesor = t.id_profesor
        WHERE t.estado = 'Activo'
          AND t.id_taller NOT IN (
                SELECT id_taller
                FROM inscripciones_talleres
                WHERE matricula = ?
          )
        ORDER BY t.nombre_taller ASC
    ";

        $st = $this->db->prepare($sql);
        $st->execute([$matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }


// 2) insertar inscripción
    public function inscribirAlumnoEnTaller(string $matricula, int $id_taller): bool
    {
        if ($id_taller <= 0) return false;

        // seguridad básica: evitar duplicado exacto
        $sqlCheck = "SELECT COUNT(*) AS c
                 FROM inscripciones_talleres
                 WHERE matricula = ? AND id_taller = ?";
        $stC = $this->db->prepare($sqlCheck);
        $stC->execute([$matricula, $id_taller]);
        $row = $stC->fetch(PDO::FETCH_ASSOC);
        if (!empty($row['c']) && (int)$row['c'] > 0) {
            return false; // ya inscrito
        }

        // insertar
        $sqlIns = "INSERT INTO inscripciones_talleres
               (matricula, id_taller, fecha_inscripcion, horas_asignadas, estado_asistencia)
               VALUES (?, ?, NOW(), 0, 'Inscrito')";
        $stI = $this->db->prepare($sqlIns);
        return $stI->execute([$matricula, $id_taller]);
    }


    public function getPasswordHashByUserId($idUsuario)
    {
        $sql = "SELECT password FROM usuarios WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($sql); // <-- usar $this->db
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }

    public function updatePassword($idUsuario, $nuevoHash)
    {
        $sql = "UPDATE usuarios SET password = :pwd WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql); // <-- usar $this->db
        $stmt->bindParam(':pwd', $nuevoHash, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Útil si alguna vez sólo guardas matrícula en sesión
    public function getUserIdByMatricula($matricula)
    {
        $sql = "SELECT id_usuario FROM alumnos WHERE matricula = :mat LIMIT 1";
        $stmt = $this->db->prepare($sql); // <-- usar $this->db
        $stmt->bindParam(':mat', $matricula, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_usuario'] : null;
    }









}