<?php
//require_once('./db/Conexiondb.php');
require_once __DIR__ . '/../db/Conexiondb.php';

class HomeModel extends Conexiondb
{
    protected $conectar;

    public function save($table, HomeEntity $homeEntity) /*Sin ORM */
    {
        $pkHome = $homeEntity->getPkHome();
        $home   = $homeEntity->getHome();

        $this->conectar = parent::conectardb();
        parent::setNames();
		        
        $sql = "INSERT INTO {$table} (pkHome, home) VALUES (?,?);";
        
        $data = $this->conectar->prepare($sql);
        
        $data->bindParam(1, $pkHome,   PDO::PARAM_STR);
        $data->bindParam(2, $home,     PDO::PARAM_STR);
        
        $status = $data->execute();
        $this->conectar = parent::desconectardb();
		
		return $status;
	} 
    
    public function insert($table, array $data) /*Con ORM */
    {
        $this->conectar = parent::conectardb();
        parent::setNames();
        
        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $statement = $this->conectar->prepare($sql);
        
        // Bind parameters automatically from the array.
        foreach ($data as $key => $value) 
        {
            $statement->bindValue(":{$key}", $value);
        }
        
        $status = $statement->execute();
        $this->conectar = parent::desconectardb();
        
        return $status;
    }

    public function modify($table, $home)
	{
		$this->conectar = parent::conectardb();
		parent::setNames();

		$sql = "UPDATE {$table} SET home=? WHERE pkHome = ?";

		$data = $this->conectar->prepare($sql);
		$data->bindParam(1, $home["home"],  PDO::PARAM_STR);
		$data->bindParam(2, $home["pkHome"], PDO::PARAM_STR);
	
		$data->execute(); 

		$this->conectar = parent::desconectardb();

		return $data->rowCount();
	}

    public function edit($table, array $data, $primaryKey) /*Con ORM */
    {
        $this->conectar = parent::conectardb();
        parent::setNames();
        
        $setClauses = [];
        foreach ($data as $key => $value) 
        {
            if ($key !== $primaryKey) 
            {
                $setClauses[] = "{$key} = :{$key}";
            }
        }
        $set = implode(', ', $setClauses);
        
        $sql = "UPDATE {$table} SET {$set} WHERE {$primaryKey} = :{$primaryKey}";
        
        $statement = $this->conectar->prepare($sql);
        
        foreach ($data as $key => $value) 
        {
            $statement->bindValue(":{$key}", $value);
        }
        
        $status = $statement->execute();
        $this->conectar = parent::desconectardb();
        
        return $status;
    }

}

?>