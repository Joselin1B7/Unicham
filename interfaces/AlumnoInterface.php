
<?php

interface AlumnoInterface
{
    // Método para autenticar al alumno. Recibe la matrícula.
    public function getAlumnoByMatricula(string $matricula);
    public function auth();
    public function validador();
    public function forget();
    public function resetPassword();
}

?>