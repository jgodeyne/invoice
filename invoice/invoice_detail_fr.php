<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
include_once("../invoice/invoice_class.php");
include_once("../company/company_class.php");
include_once("../client/client_class.php");
include_once("../job/job_class.php");

$invoice_id = htmlspecialchars($_GET['invoice_id']);
$invoice = Invoice::findById($invoice_id);

$company = Company::findById("1");

$client = Client::findById($invoice->getClientId());

// Setup Language settings
$directory = '../locale';
$domain = 'invoice';
$locale = "fr_BE.utf8";
 
setlocale(LC_ALL, $locale, "French_Belgium");
bindtextdomain($domain, $directory) . "<br>";
textdomain($domain) . "<br>";
$locale = localeconv();
$dec_point = $locale['mon_decimal_point'];
$thousands_sep = $locale['mon_thousands_sep'];
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
	border-color: ;
	border-collapse: separate;
	background-color: white;
}
table th {
	border-width: 1px;
	padding: 5px;
	border-style: none;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
table td {
	border-width: 1px;
	padding: 5px;
	border-style: none;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
table.regels {
	width=1000px;
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
	-moz-border-radius: ;
}
table.regels td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
	-moz-border-radius: ;
}
table.totaal {
	width=1000px;
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
	-moz-border-radius: ;
}
table.totaal td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: black;
	background-color: white;
	-moz-border-radius: ;
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
Tél.: <?=$company->getPhoneNumber()?><br>
<?php } ?>
GSM: <?=$company->getMobileNumber()?><br>
e-mail: <?=$company->getEmail()?><br>
N° T.V.A.: <?=$company->getVatNumber()?>
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
à l'attn de&nbsp;<?=$client->getContact()?><br>
<?=$client->getAddressLine1()?><br>
<?=$client->getAddressLine2()?>
</td>
</tr>
</table>
<table>
<tr height="15px"><td></td></tr>
<tr><td align="center" style="font-weight: bold;">*** FACTURE ***</td></tr>
<tr><td>Facture n°: <?=$invoice->getNumber()?></td></tr>
<tr><td>T.V.A. destinataire: <?=$client->getVatNumber()?></td></tr>
<tr><td>Référence: <?=$invoice->getClientReference()?></td></tr>
</table>
<table class="regels">
<tr style="font-weight: bold">
<td>Date<br>livraison</td>
<td>Client<br>référence</td>
<td>Description</td>
<td>Nombre</td>
<td>Prix/unité</td>
<td>Réduction</td>
<td>Forfait</td>
<td>Prix total</td>
</tr>
<?php 
$jobs = Job::findAllByCriteria("invoice_id='" . $invoice->getId() . "'");
foreach ($jobs as $job) { ?>
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
<td>Sous-total:</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getBaseAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
<?php if ($client->getVatRate()>0) {?>
<tr>
<td>T.V.A.(<?=number_format($client->getVatRate(), 2 , $dec_point, $thousands_sep) ?> %):</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getVatAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
<?php }?>
<tr>
<td>Total:</td>
<td align="right">&euro;&nbsp;<?=number_format($invoice->getTotalAmount(), 2,  $dec_point,$thousands_sep)?></td>
</tr>
</table>
<?php if ($client->getVatRate()==0) {?>
<p style="font-size: 12px">Opération intracommunautaire exemptée en application de l'article 21, §3, 7°, d.</p>
<?php } else if ($client->getVatRate()==6) {?>
<p style="font-size: 12px">TVA 6% conformément à l’article 18 § 1,7° du Code sur la TVA et la rubrique XXIX du tableau A de l’A.R. n° 20 d.d. 20/07/1970.</p>
<?php }?>
<p style="font-size: 12px">A payer sur le compte - IBAN <?=$company->getIban()?> - BIC <?=$company->getBic()?> dans les <?=$client->getInvoicePaymentDelay()?> jours à compter de la date de la facture.</p>
</body>
</html>