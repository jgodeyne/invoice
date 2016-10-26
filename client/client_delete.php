<?php
include_once("../common/session.php");
include_once("client_class.php");
$id=htmlspecialchars($_GET["id"]);
$client=Client::findById($id);
$client->delete();
header("Location: ../client/client_list.php");
exit(0);
?>