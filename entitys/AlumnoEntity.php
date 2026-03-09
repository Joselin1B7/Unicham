<?php

class AlumnoEntity
{

    private $matricula;           // VARCHAR(10) - Clave primaria (PK)
    private $nombre;              // VARCHAR(255)
    private $apellidoPaterno;     // VARCHAR(255)
    private $apellidoMaterno;     // VARCHAR(255)
    private $fechaNacimiento;     // DATE
    private $telefono;            // VARCHAR(15)
    private $fechaIngreso;        // DATE
    private $idCarrera;           // INT - FK a Carreras
    private $idGrupo;             // INT - FK a Grupos
    private $idEstado;            // INT - FK a Estados_Usuario

    // --- Propiedades de la tabla USUARIOS (para login y perfil) ---
    private $idUsuario;           // INT - FK a Usuarios
    private $email;               // VARCHAR(255)
    private $fotoPerfil;          // LONGBLOB (o ruta)
    private $idRol;               // INT - FK a Roles (Generalmente 3 para Alumno)


    /**
     * Constructor de la entidad Alumno.
     * @param string $matricula Clave del estudiante.
     * @param int $idUsuario ID de la cuenta de usuario para login.
     * @param string $nombre Nombre(s) del alumno.
     * @param string $apellidoPaterno Apellido paterno.
     * @param string|null $apellidoMaterno Apellido materno.
     * @param string|null $fechaNacimiento Fecha de nacimiento (formato 'YYYY-MM-DD').
     * @param string|null $telefono Teléfono de contacto.
     * @param string $fechaIngreso Fecha de inicio de estudios (formato 'YYYY-MM-DD').
     * @param int $idCarrera ID de la carrera.
     * @param int|null $idGrupo ID del grupo/clase.
     * @param int $idEstado ID del estado (activo, egresado, etc.).
     * @param string $email Correo electrónico.
     * @param string|null $fotoPerfil Contenido binario (BLOB) o ruta de la foto.
     * @param int $idRol ID del rol (ej: 3 para Alumno).
     */
    public function __construct(
        $matricula,
        $idUsuario,
        $nombre,
        $apellidoPaterno,
        $apellidoMaterno = null,
        $fechaNacimiento = null,
        $telefono = null,
        $fechaIngreso,
        $idCarrera,
        $idGrupo = null,
        $idEstado,
        $email,
        $fotoPerfil = null,
        $idRol
    ) {
        $this->matricula = $matricula;
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->telefono = $telefono;
        $this->fechaIngreso = $fechaIngreso;
        $this->idCarrera = $idCarrera;
        $this->idGrupo = $idGrupo;
        $this->idEstado = $idEstado;
        $this->email = $email;
        $this->fotoPerfil = $fotoPerfil;
        $this->idRol = $idRol;
    }

    // -------------------------------------------------------------------------
    // --- GETTERS ---
    // -------------------------------------------------------------------------

    public function getMatricula() { return $this->matricula; }
    public function getIdUsuario() { return $this->idUsuario; }
    public function getNombre() { return $this->nombre; }
    public function getApellidoPaterno() { return $this->apellidoPaterno; }
    public function getApellidoMaterno() { return $this->apellidoMaterno; }
    public function getFechaNacimiento() { return $this->fechaNacimiento; }
    public function getTelefono() { return $this->telefono; }
    public function getFechaIngreso() { return $this->fechaIngreso; }
    public function getIdCarrera() { return $this->idCarrera; }
    public function getIdGrupo() { return $this->idGrupo; }
    public function getIdEstado() { return $this->idEstado; }
    public function getEmail() { return $this->email; }
    public function getFotoPerfil() { return $this->fotoPerfil; }
    public function getIdRol() { return $this->idRol; }

    // -------------------------------------------------------------------------
    // --- SETTERS ---
    // -------------------------------------------------------------------------

    public function setMatricula($matricula) { $this->matricula = $matricula; }
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidoPaterno($apellidoPaterno) { $this->apellidoPaterno = $apellidoPaterno; }
    public function setApellidoMaterno($apellidoMaterno) { $this->apellidoMaterno = $apellidoMaterno; }
    public function setFechaNacimiento($fechaNacimiento) { $this->fechaNacimiento = $fechaNacimiento; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    public function setFechaIngreso($fechaIngreso) { $this->fechaIngreso = $fechaIngreso; }
    public function setIdCarrera($idCarrera) { $this->idCarrera = $idCarrera; }
    public function setIdGrupo($idGrupo) { $this->idGrupo = $idGrupo; }
    public function setIdEstado($idEstado) { $this->idEstado = $idEstado; }
    public function setEmail($email) { $this->email = $email; }
    public function setFotoPerfil($fotoPerfil) { $this->fotoPerfil = $fotoPerfil; }
    public function setIdRol($idRol) { $this->idRol = $idRol; }

    public function getNombreCompleto()
    {
        return trim($this->nombre . ' ' . $this->apellidoPaterno . ' ' . $this->apellidoMaterno);
    }
}