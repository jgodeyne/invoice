<?php
include_once("../common/session.php");
include_once("client_class.php");
$id = isset($_GET["id"]) ? htmlspecialchars((string)$_GET["id"]) : '';
$client=Client::findById($id);
$client->delete();
header("Location: ../client/client_list.php");
exit(0);
?>