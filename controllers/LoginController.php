<?php
require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../models/AlumnoModel.php';

class LoginController {
    public function auth() { View::render("Inicio/authView", ["user" => 0]); }

    public function validador() {
        header('Content-Type: application/json');
        $mat = $_POST['matricula'] ?? null;
        $pwd = $_POST['password'] ?? null;

        if (!$mat || !$pwd) return $this->jsonResponse(-1, 'Faltan datos.');

        $alumnoModel = new AlumnoModel();
        $data = $alumnoModel->getAlumnoByMatricula($mat);

        if (!$data || !password_verify($pwd, $data['password_hash'])) {
            return $this->jsonResponse(0, 'Matrícula o contraseña incorrectas.');
        }

        $this->establecerSesion($data);
        return $this->jsonResponse(1, 'Acceso concedido.', ['redirect_url' => '/UniCham/index.php?controller=user&method=perfil']);
    }

    public function resetPassword() {
        header('Content-Type: application/json');
        $email = $_POST['email'] ?? null;
        $newPwd = $_POST['new_password'] ?? null;

        if (!$email || !$newPwd) return $this->jsonResponse(-1, 'Faltan datos.');

        $model = new AlumnoModel();
        $alumno = $model->getAlumnoByEmail($email);

        if (!$alumno) return $this->jsonResponse(0, 'El correo no está registrado.');

        $hashed = password_hash($newPwd, PASSWORD_DEFAULT);
        $status = $model->updatePassword($alumno['id_usuario'], $hashed);

        return $status ? $this->jsonResponse(1, 'Éxito.') : $this->jsonResponse(0, 'Error técnico.');
    }

    private function establecerSesion($data) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $data['id_usuario'];
        $_SESSION['matricula'] = $data['matricula'];
    }

    private function jsonResponse($status, $msg, $extra = []) {
        echo json_encode(array_merge(['status' => $status, 'message' => $msg], $extra));
        exit();
    }
}
