<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<? include("../common/header.php"); ?>
<? include("../common/menu.php"); ?>
<? include("invoice_class.php"); ?>
<? include_once("../client/client_class.php"); ?>
<div id="middle">
<div id="main">
<?php 
$filter_status = htmlspecialchars($_POST['filter_status']);
if($filter_status=='') {
	$filter_status='OPEN';
}
?>
<h1>Facturen</h1>
<h2>Filter:</h2>
<form method="post" action="../invoice/invoice_list.php" name="invoice_filter">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Status:</td>
<td>&nbsp;
<select name="filter_status" onChange="invoice_filter.submit()">
<option value="OPEN" <?=($filter_status=='OPEN')?('selected="selected"'):('');?>>Open</option>
<option value="PAID" <?=($filter_status=='PAID')?('selected="selected"'):('');?>>Betaald</option>
</select>
</td>
</tr>
</tbody>
</table>
</form>
<?php
$total_subtotal = 0;
$total_vat = 0;
$total_total = 0;
?>
<p class="error"><?=htmlspecialchars($_GET["error"])?></p>
<table class="list">
<thead>
<tr>
<td>Id</td>
<td>Datum</td>
<td>Nummer</td>
<td>Klant</td>
<td>Subtotaal</td>
<td>BTW</td>
<td>Totaal</td>
<td>Vervaldatum</td>
<td>&nbsp;</td>
</tr>
</thead>
<tbody>
<?php 
$invoices = Invoice::findAllByCriteriaOrdened("status = '" . $filter_status . "'","due_date asc");
foreach ($invoices as $invoice) { 
$client=Client::findById($invoice->getClientId());

$total_subtotal = $total_subtotal + $invoice->getBaseAmount();
$total_vat = $total_vat + $invoice->getVatAmount();
$total_total = $total_total + $invoice->getTotalAmount();
?>
<tr <?=(strtotime($invoice->getOriginalDueDate()) < strtotime(date("d-m-Y"))&&$invoice->getStatus()=="OPEN")?"class='redrow'":""?> >
<td><?=$invoice->getId()?></td>
<td><?=$invoice->getDate()?></td>
<td><?=$invoice->getNumber()?></td>
<td><?=$client->getName()?></td>
<td align="right"><?=number_format($invoice->getBaseAmount(),'2',',','.')?></td>
<td align="right"><?=number_format($invoice->getVatAmount(),'2',',','.')?></td>
<td align="right"><?=number_format($invoice->getTotalAmount(),'2',',','.')?></td>
<td><?=$invoice->getDueDate()?></td>
<td align="center">
<a href="../invoice/invoice_detail.php?id=<?=$invoice->getId()?>" target="blank"><img border="0" alt="Detail" src="../images/invoice.png"></a>
<?php if ($invoice->getStatus()=='OPEN') { ?>
&nbsp;<a href="../invoice/invoice_form.php?id=<?=$invoice->getId()?>"><img border="0" alt="Edit" src="../images/properties.png"/></a>
&nbsp;<a href="../invoice/invoice_paid.php?id=<?=$invoice->getId()?>" onClick="return (confirm('Deze faktuur is betaald?'));"><img border="0" alt="Detail" src="../images/paid.jpg"/></a>
<?php }?>
</td>
</tr>
<?php } ?>
</tbody>
<tfoot>
<tr>
<td colspan="4">Totaal:</td>
<td align="right"><?=number_format($total_subtotal,'2',',','.')?></td>
<td align="right"><?=number_format($total_vat,'2',',','.')?></td>
<td align="right"><?=number_format($total_total,'2',',','.')?></td>
<td>&nbsp;</td>
</tr>
</tfoot>
</table>
</div>
</div>
</body>
</html>