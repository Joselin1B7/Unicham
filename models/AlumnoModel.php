<?php

require_once __DIR__ . '/../db/Conexiondb.php';
require_once __DIR__ . '/../entitys/AlumnoEntity.php';

// Extiende de la clase base de conexión para acceder a la DB

class AlumnoModel extends Conexiondb
{
    /**
     * Obtiene datos del alumno (y del usuario ligado) por matrícula.
     * Esto es lo que usa LoginController->validador().
     *
     * Devuelve un array con:
     *  - matricula
     *  - nombre, apellido_paterno, apellido_materno
     *  - telefono
     *  - fecha_ingreso
     *  - id_carrera
     *  - id_grupo
     *  - id_usuario
     *  - nombre_usuario
     *  - email
     *  - password_hash  (alias de usuarios.password)
     *  - id_rol
     *  - id_estado
     *
     * ó false si no existe.
     */
    public function getAlumnoByMatricula(string $matricula)
    {
        // Conectamos usando la clase base
        $db = parent::conectardb();
        if (!$db) {
            return false;
        }
        parent::setNames(); // charset/collation

        // IMPORTANTE:
        // usamos columnas que SÍ existen según tu phpMyAdmin:
        // alumnos: id_alumno, matricula, nombre, apellido_paterno, apellido_materno,
        //          fecha_ingreso, telefono, id_carrera, id_grupo, id_usuario
        //
        // usuarios: id_usuario, nombre_usuario, password, email,
        //           id_rol, id_estado, fecha_creacion, foto_perfil
        //
        // OJO: password lo renombramos como password_hash para que
        // LoginController->validador() lo pueda usar tal cual.
        $sql = "
            SELECT 
                A.id_alumno,
                A.matricula,
                A.nombre,
                A.apellido_paterno,
                A.apellido_materno,
                A.fecha_ingreso,
                A.telefono,
                A.id_carrera,
                A.id_grupo,
                A.id_usuario,
                
                U.nombre_usuario,
                U.email,
                U.password       AS password_hash,
                U.id_rol,
                U.id_estado,
                U.foto_perfil

            FROM alumnos A
            INNER JOIN usuarios U 
                ON A.id_usuario = U.id_usuario
            WHERE A.matricula = :matricula
            LIMIT 1
        ";

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
            $stmt->execute();

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            parent::desconectardb();

            if ($data) {
                return $data;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            // si hay error de SQL, lo registramos y devolvemos false
            error_log("Error getAlumnoByMatricula(): " . $e->getMessage());
            parent::desconectardb();
            return false;
        }
    }

    // Estos métodos existen porque tu clase implementa la interfaz,
    // pero el login real lo maneja LoginController.
    public function auth() {}
    public function validador() {}
    public function forget() {}
    public function resetPassword() {}
}
