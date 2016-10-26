<?php
include_once("../common/session.php");
include_once("invoice_class.php");

$invoice_id = htmlspecialchars($_POST['id']);
$client_reference = htmlspecialchars($_POST['client_reference']);

$invoice = Invoice::findById($invoice_id);
$invoice->setClientReference($client_reference);
$invoice->save();

header("Location: ../invoice/invoice_list.php");
exit(0);
?>	