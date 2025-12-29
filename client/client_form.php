<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
include_once("client_class.php");
?>
<body>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<div id="middle">
<div id="main">
<?php
$disabled='';
$id=htmlspecialchars($_GET["id"]);
if($id) {
	$client=Client::findById($id);
	$name = $client->getName();
	$contact = $client->getContact();
	$address_line_1 = $client->getAddressLine1();
	$address_line_2 = $client->getAddressLine2();
	$phone_number = $client->getPhoneNumber();
	$mobile_number = $client->getMobileNumber();
	$email = $client->getEmail();
	$vat_number = $client->getVatNumber();
	$vat_rate = $client->getVatRate();
	$invoice_payment_delay = $client->getInvoicePaymentDelay();
	$language = $client->getLanguage();
	$remark = $client->getRemark();

	$title="Wijzig Klant";
	$action="../client/client_update.php";
}
else {
	$title="Nieuwe Klant";
	$action="../client/client_insert.php";
}
?>
<h1><?=$title?></h1>
<form method="post" action="<?=$action?>" name="client_form">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Id:</td>
<td>&nbsp;<?=$id?><input type="hidden" name="id" value="<?=$id?>"/></td>
</tr>
<tr>
<td>&nbsp;Naam:</td>
<td>&nbsp;<input type="text"  maxlength="50" size="50" name="name" value="<?=$name?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Kontakt:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="contact" value="<?=$contact?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Adres:</td>
<td>
&nbsp;<input type="text" maxlength="50" size="50" name="address_line_1" value="<?=$address_line_1?>" <?=$disabled?>/><br/>
&nbsp;<input type="text" maxlength="50" size="50" name="address_line_2" value="<?=$address_line_2?>" <?=$disabled?>/>
</td>
</tr>
<tr>
<td>&nbsp;Telefoon Nr.:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="phone_number" value="<?=$phone_number?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Mobiel Nr.:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="mobile_number" value="<?=$mobile_number?>" <?=$disabled?>/></td>
</tr>
<tr>
<tr>
<td>&nbsp;E-Mail:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="email" value="<?=$email?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;BTW Nr.:</td>
<td>&nbsp;<input type="text" maxlength="20" size="20" name="vat_number" value="<?=$vat_number?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;BTW Tarief:</td>
<td>&nbsp;<input type="text" maxlength="5" size="5" name="vat_rate" value="<?=$vat_rate?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Faktuur betalings uitstel:</td>
<td>&nbsp;<input type="text" maxlength="10" size="10" name="invoice_payment_delay" value="<?=$invoice_payment_delay?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Taal:</td>
<td>&nbsp;<input type="text" maxlength="10" size="10" name="language" value="<?=$language?>" <?=$disabled?>/></td>
</tr>
<tr>
<td>&nbsp;Opmerking:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="remark" value="<?=$remark?>" <?=$disabled?>/></td>
</tr>
</tbody>
</table>
<p>
<input type="submit" value="Bewaren" name="bewaren"  class="button">
<INPUT TYPE="button" VALUE="Terug" onClick="window.location='../client/client_list.php';" class="button">
</p>
</form>
<p />
<?php $err = isset($_GET["error"]) ? htmlspecialchars((string)$_GET["error"]) : ""; ?>
<p class="error"><?=$err?></p>
</div>
</div>
</body>
</html>