<?php

require_once('db/Conexiondb.php');

class TramitesModel extends Conexiondb
{
    private $db;

    public function __construct()
    {
        // conectardb() viene de Conexiondb
        $this->db = $this->conectardb();
        $this->setNames();
    }

    public function registrarSolicitud($matricula, $tipoTramite, $descripcion = '')
    {
        $sql = "INSERT INTO solicitudes_tramites 
                    (matricula, tipo_tramite, descripcion, estado_solicitud, fecha_solicitud)
                VALUES
                    (:matricula, :tipo_tramite, :descripcion, 'SOLICITADO', NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':tipo_tramite', $tipoTramite);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
    }
}
