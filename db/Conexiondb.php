<?php
include_once dirname(__FILE__) . '/keys.php';

abstract class Conexiondb
{
    // Propiedad para almacenar la instancia de PDO.
    // La hacemos protected para que las clases hijas puedan acceder si fuera necesario.
    protected $pdo;

    /**
     * Establece la conexión con la base de datos.
     * @return PDO|null Retorna el objeto PDO si la conexión es exitosa, o null si falla.
     */
    public function conectardb()
    {
        // Parámetros de conexión desde keys.php
        $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
        $username = DB_USER;
        $password = DB_PASS;

        try
        {
            // Crea la nueva instancia de PDO y la guarda en la propiedad de la clase.
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura el manejo de errores
            return $this->pdo;
        }
        catch(PDOException $e)
        {
            // Si hay un error, lo mostramos (solo para desarrollo) y terminamos la ejecución.
            // En producción, es mejor registrar el error en un log.
            error_log("Error de Conexión a DB: " . $e->getMessage());
            echo "Error de conexión a la base de datos. Por favor, revisa la configuración.";
            exit;
        }
    }

    /**
     * Cierra la conexión a la base de datos.
     */
    public function desconectardb()
    {
        // Al establecer $pdo a null, PHP libera la conexión.
        $this->pdo = null;
    }

    /**
     * Configura la codificación de caracteres.
     */
    public function setNames()
    {
        if ($this->pdo) {
            // Utilizamos exec() para comandos que no devuelven conjuntos de resultados
            $this->pdo->exec("SET NAMES 'utf8'");
        }
    }
}
?>

