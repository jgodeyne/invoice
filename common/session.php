<?php 
session_start();
//error_log("session->start (SID=" . SID . ")", 0);
if ($_SESSION['authorized'] != true) 
{
    header("Location: ../index.php");	
    exit;
}
$sec_level=$_SESSION['userlevel'];
?>
