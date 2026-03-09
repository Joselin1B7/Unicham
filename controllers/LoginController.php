<?php

require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../models/HomeModel.php';
require_once __DIR__ . '/../models/AlumnoModel.php';
require_once __DIR__ . '/../entitys/AlumnoEntity.php';
require_once __DIR__ . '/../interfaces/AlumnoInterface.php';

class LoginController implements AlumnoInterface
{
    /**
     * Muestra la vista principal de autenticación.
     */
    public function auth() {
        View::render("Inicio/authView", ["user" => 0]);
    }

    /**
     * Procesa el inicio de sesión comparando la matrícula y el hash de la contraseña.
     */
    public function validador() {
        header('Content-Type: application/json');
        
        if (!isset($_POST['matricula']) || !isset($_POST['password'])) {
            echo json_encode(['status' => -1, 'message' => 'Faltan datos de autenticación.']);
            exit();
        }

        $matricula = $_POST['matricula'];
        $password  = $_POST['password'];

        $alumnoModel = new AlumnoModel();
        $alumnoData  = $alumnoModel->getAlumnoByMatricula($matricula);

        if (!$alumnoData) {
            echo json_encode(['status' => 0, 'message' => 'Matrícula o contraseña incorrectas.']);
            exit();
        }

        if (!password_verify($password, $alumnoData['password_hash'])) {
            echo json_encode(['status' => 0, 'message' => 'Matrícula o contraseña incorrectas.']);
            exit();
        }

        $this->establecerSesion($alumnoData);

        echo json_encode([
            'status' => 1,
            'message' => 'Credenciales correctas. Acceso concedido.',
            'redirect_url' => '/UniCham/index.php?controller=user&method=perfil'
        ]);
        exit();
    }

    /**
     * Gestiona las variables de sesión para el usuario autenticado.
     */
    private function establecerSesion($data) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id']   = $data['id_usuario'];
        $_SESSION['matricula'] = $data['matricula'];
        $_SESSION['rol_id']    = $data['id_rol'];
        $_SESSION['username']  = $data['nombre_usuario'];
    }

    /**
     * Muestra la vista para solicitar el restablecimiento de contraseña.
     */
    public function forget()
    {
        View::render("Inicio/forgetView", ["user" => 0]);
    }

    /**
     * Ejecuta el restablecimiento de la contraseña en la base de datos.
     * Implementa password_hash para mitigar vulnerabilidades de seguridad.
     */
    public function resetPassword() {
        header('Content-Type: application/json');
        $action = ['status' => 0];

        if(isset($_POST['email']) && isset($_POST['new_password'])) {
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];

            $alumnoModel = new AlumnoModel();
            
            // Buscar al alumno por correo institucional
            $alumnoData = $alumnoModel->getAlumnoByEmail($email);

            if (!$alumnoData) {
                $action = ['status' => 0, 'message' => 'El correo institucional no está registrado.'];
            } else {
                // Encriptación para mejorar el Security Rating detectado en SonarQube
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Actualización en el modelo
                $updated = $alumnoModel->updatePassword($alumnoData['id_usuario'], $hashedPassword);

                if ($updated) {
                    $action = ['status' => 1, 'message' => 'Contraseña restablecida correctamente.'];
                } else {
                    $action = ['status' => 0, 'message' => 'Error técnico al actualizar la contraseña.'];
                }
            }
        } else {
            $action = ['status' => -1, 'message' => 'Faltan datos para el restablecimiento.'];
        }

        echo json_encode($action);
        exit();
    }

    /**
     * Implementación de la interfaz AlumnoInterface.
     */
    public function getAlumnoByMatricula(string $matricula) {
        $alumnoModel = new AlumnoModel();
        return $alumnoModel->getAlumnoByMatricula($matricula);
    }

    /**
     * Cierra la sesión de forma segura y limpia el caché del navegador.
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Location: /UniCham/login/auth");
        exit;
    }
}
