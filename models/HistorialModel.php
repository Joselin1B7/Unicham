<?php
// models/HistorialModel.php
class HistorialModel {
    private $pdo;

    // Pasa aquí tu instancia PDO (en tu app suele vivir en el controlador base)
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getHistorialByMatricula(string $matricula): array {
        $sql = "SELECT 
                    ha.id_historial,
                    ha.matricula,
                    ha.id_materia,
                    ha.id_periodo,
                    ha.calificacion,
                    ha.estatus,
                    ha.creditos,
                    ha.fecha_cierre,
                    m.clave_materia   AS clave,
                    m.nombre_materia  AS materia,
                    pe.nombre_periodo AS periodo,
                    pe.fecha_inicio,
                    pe.fecha_fin
                FROM historial_academico ha
                INNER JOIN materias m           ON m.id_materia   = ha.id_materia
                INNER JOIN periodos_escolares pe ON pe.id_periodo = ha.id_periodo
                WHERE ha.matricula = :mat
                ORDER BY pe.fecha_inicio DESC, m.nombre_materia ASC";
        $st = $this->pdo->prepare($sql);
        $st->execute([':mat' => $matricula]);
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
