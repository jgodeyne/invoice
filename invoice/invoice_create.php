<?php
include_once("../common/session.php");
include_once("../client/client_class.php");
include_once("../company/company_class.php");
include_once("invoice_class.php");
include_once("../job/job_class.php");

$client_id = htmlspecialchars($_GET['client_id']);

$client = Client::findById($client_id);

$company = Company::findById("1");

$invoice_date = date("d/m/Y");
$due_date = date("d/m/Y", strtotime("+" . $client->getInvoicePaymentDelay() . " day"));

// Calculate invoice number & update faktuur sequence number
$invoice_sequence = $company->getInvoiceSequence() + 1;
$invoice_number = $company->getInvoiceYear() . "-" .  $invoice_sequence;

$company->setInvoiceSequence($invoice_sequence);
$company->save();

// Create invoice and retrieve invoice id
$invoice = new Invoice();
$invoice->setDate($invoice_date);
$invoice->setNumber($invoice_number);
$invoice->setDueDate($due_date);
$invoice->setClientId($client_id);
$invoice->open();

// Calculate invoice base amount by summing up the jobs total prices
$invoice_base_amount = 0;

$jobs = Job::findAllByCriteria("client_id = " . $client_id . " and status='CLOSED'");
foreach ($jobs as $job) { 
	// Calculate job total price and update job
	$job_total_price = 0;
	$job_total_price += ($job->getNumberOfUnits() * $job->getUnitPrice());
	$job_total_price += $job->getFixedPrice();
	
	// Calculate job discount
	if ($job->getDiscountPercentage() != 0) {
		$job_total_price = $job_total_price - (($job_total_price * $job->getDiscountPercentage()) / 100);
	}
	$invoice_base_amount = $invoice_base_amount + $job_total_price;
	$job->setTotalPrice($job_total_price);
	$job->setInvoiceId($invoice->getId());
	$job->invoiced();
}

// Calculate VAT amount
$invoice_vat_amount = 0;
if ($client->getVatRate() != 0) {
	$invoice_vat_amount = ($invoice_base_amount * $client->getVatRate()) / 100;
}

// Calculate total amount
$invoice_total_amount = $invoice_base_amount + $invoice_vat_amount;

// Update the invoice
$invoice->setBaseAmount($invoice_base_amount);
$invoice->setVatAmount($invoice_vat_amount);
$invoice->setTotalAmount($invoice_total_amount);
$invoice->save();
?>
<script type="text/javascript"> 
<!--
	var style = "status=no, menubar=no, toolbar=no, addressbar=no";
	window.open("../invoice/invoice_invoicing_list.php","_self");
	window.open("../invoice/invoice_detail.php?invoice_id=<?=$invoice->getId()?>","", style);
//-->
</script>
