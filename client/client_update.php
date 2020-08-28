<?php
include_once("../common/session.php");
include_once("client_class.php");

$id = htmlspecialchars($_POST['id']);
$client = Client::findById($id);
$client->setFromPost($_POST);
$client->save();

header("Location: ../client/client_list.php");
exit(0);
?>	