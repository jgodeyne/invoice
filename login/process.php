<?php
include_once '../ppa/database_class.php';
session_start();
$db = new Database();
$conn = $db->connect();
$query = 'select * from users where username = "' . $_POST['user'] . '" and password = "' .	md5($_POST['pass']) . '"';
$result = $db->query($query);
	
if (mysqli_num_rows($result) != 0) 
{
    $_SESSION['authorized'] = true;
	$user = $db->fetchObjectWithoutClass($result);
    $_SESSION['userlevel'] = $user->userlevel;
	
	header("Location: ../job/job_list.php");
	exit;
	} 
else 
{
	$_SESSION['authorized'] = false;
    $_SESSION['userlevel'] = 0;
    header("Location: ../login/login.php?login_error=Gebruikersnaam of wachtwoord ongekend.");
    exit;	
}
?>
