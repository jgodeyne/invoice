<?php 
include_once("../invoice/invoice_class.php");
include_once("../client/client_class.php");

$invoice_id = isset($_GET['id']) ? htmlspecialchars((string)$_GET['id']) : '';
$invoice = Invoice::findById($invoice_id);

$client = Client::findById($invoice->getClientId());

if ($client->getLanguage() == "NL") {
	header("Location: ../invoice/invoice_detail_nl.php?invoice_id=" . $invoice_id);
	exit(0);
} elseif ($client->getLanguage() == "FR") {
	header("Location: ../invoice/invoice_detail_fr.php?invoice_id=" . $invoice_id);
	exit(0);
}
?>
