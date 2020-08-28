<?
/**
 * index.php
 *
 * This is an example of the main page of a website. Here
 * users will be able to login. However, like on most sites
 * the login form doesn't just have to be on the main page,
 * but re-appear on subsequent pages, depending on whether
 * the user has logged in or not.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 26, 2004
 */
session_start();
$_SESSION['module'] = htmlspecialchars($_REQUEST['module']);
$_SESSION['mode'] = htmlspecialchars($_REQUEST['mode']);
if ($_SESSION['mode']=="view") {
	$_SESSION['authorized']=true;
    $_SESSION['userlevel'] = 0;
} else {
	$_SESSION['authorized']=false;
}
if($_SESSION['authorized']){
	header("Location: job/job_list.php");
} else {
	header("Location: login/login.php?");
}
?>

