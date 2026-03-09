<?php
require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $model;

    public function __construct() {
        $this->model = new UserModel();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // ===========================
    // REGISTRO DEMO (lo de teléfono)
    // ===========================
    public function registro() {
        $action = ['status' => -1, 'message' => 'Error de servidor'];

        if (isset($_POST['phone']) && isset($_POST['password'])) {
            $name = $_POST['name']        ?? '';
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname']   ?? '';
            $phone = $_POST['phone'];
            $password = $_POST['password'];

            if ($phone == "555") {
                $action = ['status' => 0, 'message' => 'Teléfono ya existe.'];
            } else {
                $action = ['status' => 1, 'message' => 'Registro exitoso.'];
            }

            header('Content-Type: application/json');
            echo json_encode($action);
            exit();
        } else {
            $action = ['status' => -1, 'message' => 'Faltan datos para el registro.'];
            header('Content-Type: application/json');
            echo json_encode($action);
            exit();
        }
    }

    // ===========================
    // VISTAS BÁSICAS
    // ===========================
    public function create() {
        View::render("users/createView", ["user" => 0]);
    }

    public function forget() {
        View::render("users/forgetView", ["user" => 0]);
    }

    public function documents() {
        View::render("users/DocumentsView", ["user" => 0]);
    }

    // ===========================
    // TALLERES
    // ===========================
    public function talleres()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['matricula']) || empty($_SESSION['username'])) {
            header('Location: /UniCham/login/auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        // Datos del alumno
        $perfil = $this->model->getPerfilAlumno($matricula);

        // Periodo actual
        $periodoActual = $this->model->getPeriodoActual();

        // Talleres inscritos
        $talleres = $this->model->getTalleresAlumno($matricula);

        // Métricas de horas
        $TOTAL_REQUERIDO = 150;
        $MAX_POR_CUATRI  = 50;
        $MIN_POR_CUATRI  = 35;

        $horasTotalesAcumuladas = 0;
        $horasEsteCuatri = 0;

        $iniPeriodo = $periodoActual['fecha_inicio'] ?? null;
        $finPeriodo = $periodoActual['fecha_fin'] ?? null;

        foreach ($talleres as $row) {
            $h = (int)($row['horas_acumuladas'] ?? 0);
            $horasTotalesAcumuladas += $h;

            if ($iniPeriodo && $finPeriodo && !empty($row['fecha_inscripcion'])) {
                $soloFecha = substr($row['fecha_inscripcion'], 0, 10);
                if ($soloFecha >= $iniPeriodo && $soloFecha <= $finPeriodo) {
                    $horasEsteCuatri += $h;
                }
            }
        }

        $horasRestantesGlobal = max(0, $TOTAL_REQUERIDO - $horasTotalesAcumuladas);

        View::render("users/talleresView", [
            "perfil"                  => $perfil,
            "matricula"               => $matricula,

            "periodoActual"           => $periodoActual,
            "talleres"                => $talleres,

            "TOTAL_REQUERIDO"         => $TOTAL_REQUERIDO,
            "MAX_POR_CUATRI"          => $MAX_POR_CUATRI,
            "MIN_POR_CUATRI"          => $MIN_POR_CUATRI,

            "horasTotalesAcumuladas"  => $horasTotalesAcumuladas,
            "horasRestantesGlobal"    => $horasRestantesGlobal,
            "horasEsteCuatri"         => $horasEsteCuatri,
        ]);
    }




    //CONSTANCIA DE TALLERES
    public function constanciaTaller()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['matricula']) || empty($_SESSION['username'])) {
            header('Location: /UniCham/login/auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        // Reusar lógica de talleres para calcular horas (igual que en talleres())
        $perfil = $this->model->getPerfilAlumno($matricula);
        $periodoActual = $this->model->getPeriodoActual();
        $talleres = $this->model->getTalleresAlumno($matricula);

        $TOTAL_REQUERIDO = 150;

        $horasTotalesAcumuladas = 0;
        foreach ($talleres as $row) {
            $horasTotalesAcumuladas += (int)($row['horas_acumuladas'] ?? 0);
        }

        // Seguridad: si no ha cumplido, lo regreso
        if ($horasTotalesAcumuladas < $TOTAL_REQUERIDO) {
            $_SESSION['flash_error'] = 'Aún no cumples las 150 horas para generar tu constancia.';
            header('Location: /UniCham/index.php?controller=user&method=talleres');
            exit;
        }

        // Taller a mostrar (opcional: viene por GET, si no, calculo el de más horas)
        $tallerNombre = $_GET['taller'] ?? '';
        $tallerNombre = trim($tallerNombre);

        if ($tallerNombre === '') {
            $maxH = -1;
            foreach ($talleres as $tt) {
                $h = (int)($tt['horas_acumuladas'] ?? 0);
                if ($h > $maxH) { $maxH = $h; $tallerNombre = $tt['nombre_taller'] ?? ''; }
            }
        }

        // Fecha para la constancia
        $fechaHoy = date('d/m/Y');

        View::render("users/constanciaTallerView", [
            "perfil" => $perfil,
            "matricula" => $matricula,
            "tallerNombre" => $tallerNombre,
            "horasTotales" => $horasTotalesAcumuladas,
            "fechaHoy" => $fechaHoy
        ]);
    }




    // ===========================
    // CALIFICACIONES
    // ===========================
    public function calificaciones(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['matricula']) || empty($_SESSION['username'])) {
            header('Location: /UniCham/login/auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        $perfil = $this->model->getPerfilPorUsuario($_SESSION['username']);
        $califs = $this->model->getCalificacionesPorMatricula($matricula);

        View::render("users/calificacionesView", [
            "perfil"      => $perfil,
            "matricula"   => $matricula,
            "califs"      => $califs
        ]);
    }

    // ===========================
    // HORARIO
    // ===========================
    public function horario()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['matricula']) || empty($_SESSION['username'])) {
            header('Location: /UniCham/login/auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        $perfil = $this->model->getPerfilPorUsuario($_SESSION['username']);
        $alumnoBasico = $this->model->getAlumnoBasicoPorMatricula($matricula);

        $horario_por_dia = [];
        $id_grupo = null;

        if ($alumnoBasico && !empty($alumnoBasico['id_grupo'])) {
            $id_grupo = (int)$alumnoBasico['id_grupo'];
            $horario_por_dia = $this->model->getHorarioPorGrupo($id_grupo);
        }

        // Debug opcional
        if (isset($_GET['debug']) && $_GET['debug'] == '1') {
            header('Content-Type: text/plain; charset=utf-8');
            echo "DEBUG HORARIO\n\n";
            echo "matricula:\n";
            print_r($matricula);
            echo "\n\nalumnoBasico:\n";
            print_r($alumnoBasico);
            echo "\n\nhorario_por_dia:\n";
            print_r($horario_por_dia);
            echo "\n\nPERFIL:\n";
            print_r($perfil);
            exit;
        }

        View::render("users/horarioView", [
            "perfil"          => $perfil,
            "matricula"       => $matricula,
            "id_grupo"        => $id_grupo,
            "horario_por_dia" => $horario_por_dia
        ]);
    }

    // ===========================
    // ASESORÍAS (por ahora es estático)
    // ===========================
    public function asesorias()
    {
        View::render("users/asesoriasView", ["user" => 0]);
    }

    // ===========================
    // PERFIL (pantalla principal del usuario)
    // ===========================
    public function perfil()
    {
        if (empty($_SESSION['username'])) {
            header('Location: index.php?controller=login&method=auth');
            exit;
        }

        $user = $this->model->getPerfilPorUsuario($_SESSION['username']);

        if (!$user) {
            http_response_code(404);
            die('Perfil no encontrado');
        }

        View::render("users/perfilView", ["user" => $user]);
    }

    // ===========================
    // FOTO DE PERFIL (GET binario)
    // ===========================
    public function fotoPerfil()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['username'])) {
            http_response_code(401);
            header('Content-Type: image/svg+xml');
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
                <rect width="100%" height="100%" fill="#eee"/>
                <text x="50%" y="50%" font-size="14" text-anchor="middle" fill="#999" dy=".3em">
                    No login
                </text>
              </svg>';
            exit;
        }

        $perfil = $this->model->getPerfilPorUsuario($_SESSION['username']);
        if (!$perfil) {
            http_response_code(404);
            header('Content-Type: image/svg+xml');
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
                <rect width="100%" height="100%" fill="#eee"/>
                <text x="50%" y="50%" font-size="14" text-anchor="middle" fill="#999" dy=".3em">
                    Sin perfil
                </text>
              </svg>';
            exit;
        }

        $blob = $this->model->getPhotoBlobByIdUsuario((int)$perfil['id_usuario']);

        if (!$blob) {
            header('Content-Type: image/svg+xml');
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
                <rect width="100%" height="100%" fill="#5900ff"/>
                <text x="50%" y="50%" font-size="16" text-anchor="middle" fill="#ffffff" dy=".3em">
                    Sin foto
                </text>
              </svg>';
            exit;
        }

        $info = @getimagesizefromstring($blob);
        $mime = ($info && !empty($info['mime'])) ? $info['mime'] : 'image/jpeg';

        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: '.$mime);
        header('Content-Length: '.strlen($blob));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $blob;
        exit;
    }

    // ===========================
    // FOTO DE PERFIL (POST subir nueva)
    // ===========================
    public function actualizarFoto()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['username'])) {
            http_response_code(401);
            exit('No autenticado');
        }

        if (!isset($_FILES['foto'])) {
            $_SESSION['flash_error'] = 'No llegó ningún archivo.';
            header('Location: /UniCham/index.php?controller=user&method=perfil');
            exit;
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Error al subir: código '.$_FILES['foto']['error'];
            header('Location: /UniCham/index.php?controller=user&method=perfil');
            exit;
        }

        if ($_FILES['foto']['size'] > 2*1024*1024) { // 2MB
            $_SESSION['flash_error'] = 'La imagen supera 2MB.';
            header('Location: /UniCham/index.php?controller=user&method=perfil');
            exit;
        }

        $blob = file_get_contents($_FILES['foto']['tmp_name']);
        if ($blob === false) {
            $_SESSION['flash_error'] = 'No se pudo leer el archivo.';
            header('Location: /UniCham/index.php?controller=user&method=perfil');
            exit;
        }

        $perfil = $this->model->getPerfilPorUsuario($_SESSION['username']);
        if (!$perfil) {
            $_SESSION['flash_error'] = 'No se encontró el perfil.';
            header('Location: /UniCham/index.php?controller=user&method=perfil');
            exit;
        }

        $res = $this->model->updatePhotoBlobByIdUsuario((int)$perfil['id_usuario'], $blob);

        if ($res['ok']) {
            $_SESSION['flash_ok'] = 'Foto actualizada correctamente.';
        } else {
            $_SESSION['flash_error'] = $res['msg'] ?? 'Error al guardar la foto.';
        }

        header('Location: /UniCham/index.php?controller=user&method=perfil');
        exit;
    }

    // ===========================
    // HISTORIAL ACADÉMICO
    // ===========================
    public function historial()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['matricula']) || empty($_SESSION['username'])) {
            header('Location: /UniCham/login/auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        $perfil = $this->model->getPerfilAlumno($matricula);
        $historialPlano = $this->model->getHistorialAlumno($matricula);

        View::render("users/historialView", [
            "perfilView"      => $perfil,
            "matriculaView"   => $matricula,
            "id_grupoView"    => $perfil['id_grupo'] ?? '',
            "historialView"   => $historialPlano
        ]);
    }

    // ===========================
    // INSCRIPCIÓN A TALLER
    // ===========================
    public function talleresInscribir()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['matricula'])) {
            header('Location: index.php?controller=login&method=auth');
            exit;
        }

        $matricula = $_SESSION['matricula'];

        $talleresDisponibles = $this->model->getTalleresDisponiblesParaInscripcion($matricula);

        View::render("users/talleresInscribirView", [
            "matricula"           => $matricula,
            "talleresDisponibles" => $talleresDisponibles
        ]);
    }

    public function registrarInscripcionTaller()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['matricula'])) {
            header('Location: index.php?controller=login&method=auth');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=user&method=talleresInscribir');
            exit;
        }

        $matricula = $_SESSION['matricula'];
        $id_taller = isset($_POST['id_taller']) ? (int)$_POST['id_taller'] : 0;

        $ok = $this->model->inscribirAlumnoEnTaller($matricula, $id_taller);

        $_SESSION['flash_taller'] = $ok
            ? "Te inscribiste correctamente al taller."
            : "No se pudo inscribir (tal vez ya estabas inscrito o el cupo está lleno).";

        header('Location: index.php?controller=user&method=talleres');
        exit;
    }

    // ===========================
    // CAMBIO DE CONTRASEÑA (AJAX)
    // ===========================
    public function updatePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // 1) Identificar usuario logueado
        if (isset($_SESSION['user_id'])) {
            $idUsuario = (int)$_SESSION['user_id'];
        } elseif (!empty($_SESSION['matricula'])) {
            $idUsuario = $this->resolverIdUsuarioPorMatricula($_SESSION['matricula']);
            if (!$idUsuario) {
                echo json_encode(['ok' => false, 'msg' => 'No se pudo resolver el usuario desde la matrícula.']);
                return;
            }
        } else {
            echo json_encode(['ok' => false, 'msg' => 'Sesión expirada. Vuelve a iniciar sesión.']);
            return;
        }

        // 2) Leer POST
        $pwdActual  = $_POST['pwdActual']  ?? '';
        $pwdNueva   = $_POST['pwdNueva']   ?? '';
        $pwdConfirm = $_POST['pwdConfirm'] ?? '';

        // 3) Validaciones
        if ($pwdNueva !== $pwdConfirm) {
            echo json_encode(['ok' => false, 'msg' => 'La confirmación no coincide.']);
            return;
        }
        if (strlen($pwdNueva) < 8) {
            echo json_encode(['ok' => false, 'msg' => 'La nueva contraseña debe tener al menos 8 caracteres.']);
            return;
        }

        // 4) Hash actual en BD
        $hashActual = $this->model->getPasswordHashByUserId($idUsuario);
        if (!$hashActual) {
            echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado.']);
            return;
        }

        // 5) Verificar contraseña actual
        if (!password_verify($pwdActual, $hashActual)) {
            echo json_encode(['ok' => false, 'msg' => 'Tu contraseña actual no es correcta.']);
            return;
        }

        // 6) Generar y guardar nuevo hash
        $nuevoHash = password_hash($pwdNueva, PASSWORD_BCRYPT);
        $ok = $this->model->updatePassword($idUsuario, $nuevoHash);

        echo json_encode([
            'ok'  => (bool)$ok,
            'msg' => $ok
                ? 'Contraseña actualizada correctamente.'
                : 'No se pudo actualizar la contraseña en la base de datos.'
        ]);
    }

    // helper interno
    private function resolverIdUsuarioPorMatricula($matricula)
    {
        return $this->model->getUserIdByMatricula($matricula);
    }
}
?>
