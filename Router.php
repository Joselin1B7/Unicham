<?php

class Router
{
    private $controller;
    private $method;
    private $id;

    public function __construct()
    {
        // Sanitizar parámetros de entrada GET
        $this->controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING);
        $this->method     = filter_input(INPUT_GET, 'method',     FILTER_SANITIZE_STRING);
        $this->id         = filter_input(INPUT_GET, 'id',         FILTER_SANITIZE_NUMBER_INT);
    }

    // Si alguien entra sin method, llamamos index() del controller.
    // Y si alguien entra sin controller en absoluto, vamos a perfil por defecto.
    public function index() {
        header('Location: index.php?controller=user&method=perfil');
        exit;
    }

    public function dispatch()
    {
        if (!empty($this->controller)) {

            $controllerName = ucfirst($this->controller) . "Controller";
            $controllerFile = "./controllers/$controllerName.php";

            if (!file_exists($controllerFile)) {
                http_response_code(404);
                die("Error: Class '$controllerName' not found.");
            }

            // cargar controlador
            require_once $controllerFile;
            $ctrl = new $controllerName;

            if (!empty($this->method)) {

                if (method_exists($ctrl, $this->method)) {

                    if (!empty($this->id)) {
                        // Llamar método con parámetro id
                        $ctrl->{$this->method}($this->id);
                    } else {
                        // Llamar método sin parámetro
                        $ctrl->{$this->method}();
                    }

                } else {
                    http_response_code(404);
                    die("Error: El método '{$this->method}' no existe en '$controllerName'.");
                }

            } else {
                // no vino "method", intentamos index() dentro del controller
                if (method_exists($ctrl, 'index')) {
                    $ctrl->index();
                } else {
                    // fallback
                    $this->index();
                }
            }

        } else {
            // no vino controller -> nos vamos a perfil
            $this->index();
        }
    }
}

$router = new Router();
$router->dispatch();