<?php
include_once 'database_interface.php';

class Database implements DatabaseInterface {
	
	private $con;
	private $db_host;
	private $db_user;
	private $db_pass;
	private $db_schema;
	private $myconn;
		
	// Maintain legacy constructor signature for DatabaseInterface compatibility
	public function Database() { return $this->__construct(); }

	public function __construct() {
		// Always read INI from the same directory as this class
		$iniPath = __DIR__ . "/database_conf.ini";
		$database_conf = @parse_ini_file($iniPath, true);
		if ($database_conf !== false && isset($database_conf["Database Configuration"])) {
			$config = $database_conf["Database Configuration"];
			$this->db_host = isset($config["Host"]) ? $config["Host"] : 'mysql';
			$this->db_user = isset($config["User"]) ? $config["User"] : 'invoice';
			$this->db_pass = isset($config["Password"]) ? $config["Password"] : 'invoice';
			$this->db_schema = isset($config["Schema"]) ? $config["Schema"] : 'invoice';
		} else {
			// Defaults
			$this->db_host = 'mysql';
			$this->db_user = 'invoice';
			$this->db_pass = 'invoice';
			$this->db_schema = 'invoice';
		}
	}
	
	public function connect()   {
		if(!$this->con) {  
			$this->myconn = mysqli_connect($this->db_host,$this->db_user,$this->db_pass);  
			if($this->myconn) {  
				$seldb = mysqli_select_db($this->myconn, $this->db_schema);  
				if($seldb) {  
					mysqli_set_charset($this->myconn, 'utf8mb4');
					$this->con = true;  
				} else {  
					throw new Exception(mysqli_error($this->myconn));  
				}  
			} else {  
				throw new Exception(mysqli_connect_error());  
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