<?php
include_once 'database_interface.php';

class Database implements DatabaseInterface {
	
	private $con;
	private $db_host;
	private $db_user;
	private $db_pass;
	private $db_schema;
	private $myconn;
		
	function Database() {
		// Prefer environment variables (DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA)
		$env_host = getenv('DB_HOST');
		if ($env_host !== false) {
			$this->db_host = $env_host;
			$this->db_user = getenv('DB_USER') !== false ? getenv('DB_USER') : 'invoice';
			$this->db_pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'invoice';
			$this->db_schema = getenv('DB_SCHEMA') !== false ? getenv('DB_SCHEMA') : 'invoice';
		} else {
			$database_conf = @parse_ini_file("database_conf.ini");
			if ($database_conf !== false) {
				$this->db_host = isset($database_conf["Host"]) ? $database_conf["Host"] : 'mysql';
				$this->db_user = isset($database_conf["User"]) ? $database_conf["User"] : 'invoice';
				$this->db_pass = isset($database_conf["Password"]) ? $database_conf["Password"] : 'invoice';
				$this->db_schema = isset($database_conf["Schema"]) ? $database_conf["Schema"] : 'invoice';
			} else {
				// Defaults
				$this->db_host = 'mysql';
				$this->db_user = 'invoice';
				$this->db_pass = 'invoice';
				$this->db_schema = 'invoice';
			}
		}
	}
	
	public function connect()   {
	    if(!$this->con) {  
            $this->myconn = mysqli_connect($this->db_host,$this->db_user,$this->db_pass);  
            if($this->myconn) {  
                $seldb = mysqli_select_db($this->myconn, $this->db_schema);  
                if($seldb) {  
                    $this->con = true;  
                } else {  
                    throw new Exception(mysqli_error($this->myconn));  
                }  
            } else {  
                throw new Exception(mysqli_error($this->myconn));  
            }
			return $this->myconn;  
        }  
	}
	
	public function disconnect()    {
		if($this->con) {  
	        if(mysqli_close($this->myconn)) {  
	        	$this->con = false;  
	        } else {  
	            throw new Exception(mysqli_error($this->myconn));  
	        }  
	    }  
	}
	
	public function query($query) {
		$result = mysqli_query($this->myconn, $query);
		if(!$result) {
			throw new Exception(mysqli_error($this->myconn));
		}
		return $result;
	}
	
	public function fetchObject($result, $class) {
		$fetchObject = mysqli_fetch_object($result, $class);
		return $fetchObject;
	}
	
	public function fetchObjectWithoutClass($result) {
		$fetchObject = mysqli_fetch_object($result);
		return $fetchObject;
	}
	
	public function getInsertId() {
		$result = mysqli_insert_id($this->myconn);
		if(!$result) {
			throw new Exception(mysqli_error($this->myconn));
		}
		return $result;
	}
}

?>