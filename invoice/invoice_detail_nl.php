<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
include_once("../invoice/invoice_class.php");
include_once("../company/company_class.php");
include_once("../client/client_class.php");
include_once("../job/job_class.php");
include_once '../common/date_functions.php';

$invoice_id = htmlspecialchars($_GET['invoice_id']);
$invoice = Invoice::findById($invoice_id);

$company = Company::findById("1");

$client = Client::findById($invoice->getClientId());

// Setup Language settings
$directory = '../locale';
$domain = 'invoice';
$locale = "nl_BE.utf8";
 
setlocale(LC_ALL, $locale, "Dutch_Belgium");
$localeconv = localeconv();
$dec_point = $localeconv['mon_decimal_point'];
$thousands_sep = $localeconv['mon_thousands_sep'];
$invoice_date = strftime("%A %d %B %Y", strtotime($invoice->getOriginalDate()));

?>
<style type="text/css">
body {
	font-family: Verdana;
	font-size: 14px;
	color: #000000;
}
table {
	width: 1000px;
	border-width: 1px;
	border-spacing: 0px;
	border-style: none;
	border-collapse: separate;
	background-color: white;
}
table th {
	border-width: 1px;
	padding: 5px;
	border-style: none;
	border-color: gray;
	background-color: white;
}
table td {
	border-width: 1px;
	padding: 5px;
	border-style: none;
	border-color: gray;
	background-color: white;
}
table.regels {
	width: 1000px;
	border-width: 1px;
	border-spacing: 0px;
	border-style: solid;
	border-color: black;
	border-collapse: separate;
	background-color: white;
}
table.regels th {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
}
table.regels td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
}
table.totaal {
	width: 1000px;
	border-width: 1px;
	border-spacing: 0px;
	border-style: solid;
	border-color: black;
	border-collapse: separate;
	background-color: white;
}
table.totaal th {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
}
table.totaal td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
}
</style>
<body>
<table>
<tr><td align="right" valign="top"><?=$invoice_date?></td></tr>
<tr><td align="left" valign="top">
<?=$company->getName()?><br>
<?=$company->getAddressLine1()?><br>
<?=$company->getAddressLine2()?><br>
<br>
<?php if($company->getPhoneNumber()!="") {?>
Tel.: <?=$company->getPhoneNumber()?><br>
<?php } ?>
GSM: <?=$company->getMobileNumber()?><br>
e-mail: <?=$company->getEmail()?><br>
BTW Nr: <?=$company->getVatNumber()?>
</td>
</tr>
<tr height="25px"><td></td></tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
<table>
<tr>
<td width="50%">&nbsp;</td>
<td width="50%">
<?=$client->getName()?><br>
T.a.v.&nbsp;<?=$client->getContact()?><br>
<?=$client->getAddressLine1()?><br>
<?=$client->getAddressLine2()?>
</td>
</tr>
</table>
<table>
<tr height="15px"><td></td></tr>
<tr><td align="center" style="font-weight: bold;">*** FACTUUR ***</td></tr>
<tr><td>Factuur Nr: <?=$invoice->getNumber()?></td></tr>
<tr><td>BTW Nr bestemmeling: <?=$client->getVatNumber()?></td></tr>
<tr><td>Referentie: <?=$invoice->getClientReference()?></td></tr>
</table>
<table class="regels">
<tr style="font-weight: bold">
<td>Datum<br>levering</td>
<td>Klant<br>referentie</td>
<td>Omschrijving</td>
<td>Aantal</td>
<td>Eenheids-<br>prijs</td>
<td>Korting</td>
<td>Vaste<br>prijs</td>
<td>Totaal<br>prijs</td>
</tr>
<?php
$jobs = Job::findAllByCriteria("invoice_id=" . $invoice->getId());
foreach ($jobs as $job) { 
?>
<tr>
<td><?=$job->getDeliveryDate()?></td>
<td><?=$job->getClientReference()?></td>
<td><?=$job->getDescription()?></td>
<td align="right"><?=$job->getNumberOfUnits()?>&nbsp;<?=$job->getUnit()?></td>
<td align="right"><?=number_format($job->getUnitPrice(), 4,  $dec_point,$thousands_sep)?></td>
<td align="right"><?=number_format($job->getDiscountPercentage(), 2,  $dec_point,$thousands_sep)?>%</td>
<td align="right"><?=number_format($job->getFixedPrice(), 2,  $dec_point,$thousands_sep)?></td>
<td align="right"><?=number_format($job->getTotalPrice(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
<?php } ?>
</table>
<table class="totaal">
<tr>
<td width="60%" rowspan="3">&nbsp;</td>
<td>Subtotaal:</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getBaseAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
<?php if ($client->getVatRate()>0) {?>
<tr>
<td>
BTW (<?=number_format($client->getVatRate(), 2 , $dec_point, $thousands_sep) ?> %):
</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getVatAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
<?php } ?>
<tr>
<td>Totaal:</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getTotalAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
</table>
<?php if ($client->getVatRate()==0) {?>
<p style="font-size: 12px">Vrijstelling van Belgische BTW overeenkomstig Art. 21, §3, 7° van het Belgische BTW-Wetboek.</p>
<?php } else if ($client->getVatRate()==6) {?>
<p style="font-size: 12px">6% BTW overeenkomstig artikel 18 par. 1,7° van het BTW-wetboek en rubriek XXIX van tabel A van het KB nr. 20 d.d. 20/07/1970.</p>
<?php }?>
<p style="font-size: 12px">Te betalen op rekening - IBAN <?=$company->getIban()?> - BIC <?=$company->getBic()?> binnen <?=$client->getInvoicePaymentDelay()?> dagen na datum van de factuur.</p>
<p style="font-size: 12px">De verkoopvoorwaarden van LINGO BV zijn van toepassing op deze factuur.</p>
</body>
</html>