<?php
include_once("../common/session.php");
include_once("client_class.php");

$client= new Client();
$client->setFromPost($_POST);
$client->save();

header("Location: ../client/client_list.php");
exit(0);
?>	