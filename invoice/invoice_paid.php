<?php
include_once("../common/session.php");
include_once("invoice_class.php");

$invoice_id = isset($_GET['id']) ? htmlspecialchars((string)$_GET['id']) : '';

$invoice = Invoice::findById($invoice_id);
$invoice->paid();

header("Location: ../invoice/invoice_list.php");
exit(0);

?>