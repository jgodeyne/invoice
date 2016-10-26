<?php
interface DatabaseInterface {
	
	function Database();	
	public function connect();
	public function disconnect();
	public function query($query);
	public function fetchObject($result, $class);
	public function getInsertId();
}
?>