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
		$database_conf = parse_ini_file("database_conf.ini");
		$this->db_host = $database_conf["Host"];
		$this->db_user = $database_conf["User"];
		$this->db_pass = $database_conf["Password"];
		$this->db_schema = $database_conf["Schema"];
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