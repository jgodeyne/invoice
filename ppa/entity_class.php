<?php
include_once("database_class.php");
include_once("entity_interface.php");


class Entity implements EntityInterface {
	
	protected $id;

	/* 
	 * Methods
	 * 
	 */
	public static function getTableName($classname) {
		$entity_mapping=parse_ini_file("entity_mapping.ini");
		return $entity_mapping[$classname];
	}
	
	public function getProperties() {
		return get_object_vars($this);
	}
	
	public function setFromPost($post) {
		
	}
	
	public static function findById($id) {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class()) . " where id = '" . $id . "'";
		error_log($query);
		$result = $db->query($query);
		if($result)
			$object = $db->fetchObject($result, get_called_class());
		$db->disconnect();
		
		return $object;
	}
	
	public static function findByCriteria($criteria) {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class()) . " where " . $criteria;
		error_log($query);
		$result = $db->query($query);
		if($result)
			$object = $db->fetchObject($result, get_called_class());
		$db->disconnect();
		
		return $object;
	}
	
	public static function findAll() {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class());
		error_log($query);
		$result = $db->query($query);
		if($result) {
			while ($object = $db->fetchObject($result, get_called_class())) {
				$objects[]=$object;
			}
		}
		$db->disconnect();
		return $objects;
	}
	
	public static function findAllByCriteria($criteria) {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class()) . " where " . $criteria;
		error_log($query);
		$result = $db->query($query);
		if($result) {
			while ($object = $db->fetchObject($result, get_called_class())) {
				$objects[]=$object;
			}
		}
		$db->disconnect();
		return $objects;
	}
	
	public static function findAllOrdened($order) {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class())
			. " order by " . $order;
		error_log($query);
		$result = $db->query($query);
		if($result) {
			while ($object = $db->fetchObject($result, get_called_class())) {
				$objects[]=$object;
			}
		}
		$db->disconnect();
		return $objects;
	}
	
	public static function findAllByCriteriaOrdened($criteria, $order) {
		$db = new Database();
		$db->connect();
		$query = "SELECT * FROM " . Entity::getTableName(get_called_class()) . " where " . $criteria
			. " order by " . $order;
		error_log($query);
		$result = $db->query($query);
		if($result) {
			while ($object = $db->fetchObject($result, get_called_class())) {
				$objects[]=$object;
			}
		}
		$db->disconnect();
		return $objects;
	}

	public function delete() {
		if ($this->getId()) {
			$db = new Database();
			$db->connect();
			$query = "delete from " . Entity::getTableName(get_called_class()) . " where id='" . $this->getId() . "'";
			error_log($query);
			$result= $db->query($query);
			$db->disconnect();
		}
	}
	
	public function save() {
		$result = false;
		$db = new Database();
		$db->connect();
		if ($this->getId()) {
			// Update
			$query="update " . Entity::getTableName(get_called_class()) . " set ";
			$classvars = $this->getProperties();
			foreach ($classvars as $varname => $varvalue) {
				if ($varname!="id") {
					$query .= $varname . "='" . $varvalue . "', ";
				}
			}
			$query = substr($query, 0, -2);
			$query .= " where id='" . $this->getId() . "'";
			error_log($query);
			$result = $db->query($query);
		}else{
			// Insert
			$query="insert into " . Entity::getTableName(get_called_class());
			$classvars = $this->getProperties();
			$varnames = "";
			$varvalues = "";
			foreach ($classvars as $varname => $varvalue) {
				if ($varname!="id") {
					$varnames .= $varname . ",";
					$varvalues .= "'" . $varvalue . "',";
				}
			}
			$varnames = substr($varnames, 0, -1);
			$varvalues = substr($varvalues, 0, -1);
			$query .= " (" . $varnames . ") values (" . $varvalues . ")";
			error_log($query);
			$result=$db->query($query);
			if ($result)
				$this->id = $db->getInsertId();
		}
		$db->disconnect();
	}

	public function getId()
	{
	    return $this->id;
	}
}
?>