<?php
class TeacherModel {
    private $db;

    public function __construct() {
        $this->db = new PDO(
            'mysql:host=localhost;dbname=unicham;charset=utf8mb4',
            'root',
            ''
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getProfesorById(int $idProfesor) {
        $sql = "
            SELECT 
                p.id_profesor,
                p.nombre,
                p.apellido_paterno,
                p.apellido_materno,
                p.telefono,
                p.correo_institucional
            FROM profesores p
            WHERE p.id_profesor = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idProfesor]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}