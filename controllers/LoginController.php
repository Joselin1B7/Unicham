<?php

require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../models/HomeModel.php';
require_once __DIR__ . '/../models/AlumnoModel.php';
require_once __DIR__ . '/../entitys/AlumnoEntity.php';
require_once __DIR__ . '/../interfaces/AlumnoInterface.php';

class LoginController implements AlumnoInterface
{
    public function auth() {
        View::render("Inicio/authView", ["user" => 0]);
    }


    public function validador() {
        header('Content-Type: application/json');
        
        // 1. Validar que existan los datos
        if (!isset($_POST['matricula']) || !isset($_POST['password'])) {
            echo json_encode(['status' => -1, 'message' => 'Faltan datos de autenticación.']);
            exit();
        }

        $matricula = $_POST['matricula'];
        $password  = $_POST['password'];

        $alumnoModel = new AlumnoModel();
        $alumnoData  = $alumnoModel->getAlumnoByMatricula($matricula);

        // 2. Validar si el alumno existe
        if (!$alumnoData) {
            echo json_encode(['status' => 0, 'message' => 'Matrícula o contraseña incorrectas.']);
            exit();
        }

        // 3. Validar contraseña
        if (!password_verify($password, $alumnoData['password_hash'])) {
            echo json_encode(['status' => 0, 'message' => 'Matrícula o contraseña incorrectas.']);
            exit();
        }

        // 4. Si todo está bien, iniciar sesión
        $this->establecerSesion($alumnoData);

        echo json_encode([
            'status' => 1,
            'message' => 'Credenciales correctas. Acceso concedido.',
            'redirect_url' => '/UniCham/index.php?controller=user&method=perfil'
        ]);
        exit();
    }

    // Nueva función privada para bajar la complejidad
    private function establecerSesion($data) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id']   = $data['id_usuario'];
        $_SESSION['matricula'] = $data['matricula'];
        $_SESSION['rol_id']    = $data['id_rol'];
        $_SESSION['username']  = $data['nombre_usuario'];
    }


    public function forget()
    {
        echo "Forget Password";
        View::render("Inicio/forgetView", ["user" => 0]);
    }

    public function resetPassword() {
        $action = ['status' => 0];

        if(isset($_POST['email']) && isset($_POST['new_password']))
        {
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];

            if ($email == "error@mail.com") {
                $action = ['status' => 0, 'message' => 'Email no encontrado.'];
            } else {
                // TODO: Implementar lógica real de actualización de contraseña (hash + DB update)
                $action = ['status' => 1, 'message' => 'Contraseña restablecida.'];
            }

            header('Content-Type: application/json');
            echo json_encode($action);
            exit();

        } else {

            $action = ['status' => -1, 'message' => 'Faltan datos para el restablecimiento.'];
            header('Content-Type: application/json');
            echo json_encode($action);
            exit();
        }
    }

    public function getAlumnoByMatricula(string $matricula)
    {
        // El controlador implementa la interfaz pero delega la lógica de búsqueda al modelo.
        // TODO: Implement getAlumnoByMatricula() method.
    }


    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vaciar datos
        $_SESSION = [];

        // Borrar cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destruir sesión
        session_destroy();

        // Evitar cache para que "Atrás" no muestre páginas protegidas
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Redirigir a login
        header("Location: /UniCham/login/auth");
        exit;
    }



}
