<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<?php include("company_class.php"); ?>
<div id="middle">
<div id="main">
<?php
try{
$disabled='';
$id=htmlspecialchars($_GET["id"]);
$object=null;
$name="";
$contact="";
$address_line_1 ="";
$address_line_2 ="";
$phone_number ="";
$mobile_number ="";
$email ="";
$iban = "";
$bic="";
$legal_persons_register="";
$vat_number = "";
$invoice_year = "";
$invoice_sequence = "";
$me = false;
if($id) {
	$object=Company::findById($id);
	$name = $object->getName();
	$contact = $object->getContact();
	$address_line_1 = $object->getAddressLine1();
	$address_line_2 = $object->getAddressLine2();
	$phone_number = $object->getPhoneNumber();
	$mobile_number = $object->getMobileNumber();
	$email = $object->getEmail();
	$iban = $object->getIban();
	$bic = $object->getBic();
	$legal_persons_register = $object->getLegalPersonsRegister();
	$vat_number = $object->getVatNumber();
	$invoice_year = $object->getInvoiceYear();
	$invoice_sequence = $object->getInvoiceSequence();
	$me = $object->getMe();

	$title="Wijzig Bedrijf";
	$action="../company/company_update.php";
} else {
	$title="Nieuwe Bedrijf";
	$action="../company/company_insert.php";
}
if($me) {
	$inputType = "text";
} else {
	$inputType = "hidden";
}
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}?>
<h1>Bedrijf</h1>
<form method="post" action=<?=$action?> name="company_form">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Id:</td>
<td>&nbsp;<?=$id?><input type="hidden" name="id" value="<?=$id?>"/></td>
</tr>
<tr>
<td>&nbsp;Naam:</td>
<td>&nbsp;<input type="text"  maxlength="50" size="50" name="name" value="<?=$name?>"/></td>
</tr>
<tr>
<td>&nbsp;Kontakt:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="contact" value="<?=$contact?>"/></td>
</tr>
<tr>
<td>&nbsp;Adres:</td>
<td>
&nbsp;<input type="text" maxlength="50" size="50" name="address_line_1" value="<?=$address_line_1?>"/><br/>
&nbsp;<input type="text" maxlength="50" size="50" name="address_line_2" value="<?=$address_line_2?>"/>
</td>
</tr>
<tr>
<td>&nbsp;Telefoon Nr.:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="phone_number" value="<?=$phone_number?>"/></td>
</tr>
<tr>
<td>&nbsp;Mobiel Nr.:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="mobile_number" value="<?=$mobile_number?>"/></td>
</tr>
<tr>
<tr>
<td>&nbsp;E-Mail:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="email" value="<?=$email?>"/></td>
</tr>
<tr>
<td>&nbsp;IBAN:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="iban" value="<?=$iban?>"/></td>
</tr>
<tr>
<td>&nbsp;BIC:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="bic" value="<?=$bic?>"/></td>
</tr>
<tr>
<td>&nbsp;RPR:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="legal_persons_register" value="<?=$legal_persons_register?>"/></td>
</tr>
<tr>
<td>&nbsp;BTW Nr.:</td>
<td>&nbsp;<input type=<?=$inputType?> maxlength="15" size="15" name="vat_number" value="<?=$vat_number?>"/></td>
</tr>
<tr>
<td>&nbsp;Factuur jaar:</td>
<td>&nbsp;<input type=<?=$inputType?> maxlength="4" size="4" name="invoice_year" value="<?=$invoice_year?>"/></td>
</tr>
<tr>
<td>&nbsp;Factuur Nr. sequentie:</td>
<td>&nbsp;<input type=<?=$inputType?> maxlength="3" size="3" name="invoice_sequence" value="<?=$invoice_sequence?>"/></td>
</tr>
</tbody>
</table>
<p>
<input type="submit" value="Bewaren" name="bewaren" class="button">
</p>
</form>
<p />
<?php $err = isset($_GET["error"]) ? htmlspecialchars((string)$_GET["error"]) : ""; ?>
<p class="error"><?=$err?></p>
</div>
</div>
</body>
</html>