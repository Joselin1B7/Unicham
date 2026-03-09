<?php
require_once 'models/TeacherModel.php';

class TeacherController {
    private $teacherModel;

    public function __construct() {
        $this->teacherModel = new TeacherModel();
    }

    // /UniCham/index.php?controller=teacher&method=perfil&id=3
    public function perfil($id_profesor = null) {
        if ($id_profesor === null) {
            die("No se proporcionó id_profesor.");
        }

        $profesor = $this->teacherModel->getProfesorById((int)$id_profesor);
        if (!$profesor) {
            die("Profesor no encontrado.");
        }

        require_once 'views/maestroPerfilView.php';
    }
}
