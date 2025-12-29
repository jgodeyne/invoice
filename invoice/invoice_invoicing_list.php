<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<?php include("../ppa/database_class.php"); ?>
<div id="middle">
<div id="main">
<?php
$db = new Database();
$db->connect();
$query="SELECT a.id, a.name, a.number_of_jobs, a.subtotal, ((a.subtotal * a.vat_rate) / 100) vat, (a.subtotal + ((a.subtotal * a.vat_rate) / 100)) total"
				. " FROM (SELECT c.id id, c.name name, c.vat_rate, count( j.id ) number_of_jobs, sum(((j.number_of_units * j.unit_price) - (((j.number_of_units * j.unit_price) * j.discount_percentage) / 100)) + j.fixed_price) subtotal"
				. " FROM jobs j, clients c WHERE j.client_id = c.id and j.status='CLOSED' group by c.id) a";
$result = $db->query($query);
$objects = null;
$total_subtotal = 0;
$total_vat = 0;
$total_total = 0;
?>
<h1>Factureren</h1>
<table class="list">
<thead>
<tr>
<td>Klant id</td>
<td>Klant naam</td>
<td>Aantal<br>jobs</td>
<td>Subtotaal</td>
<td>BTW</td>
<td>Totaal</td>
<td>&nbsp;</td>
</tr>
</thead>
<tbody>
<?php while ($object = $db->fetchObjectWithoutClass($result)) { 
$total_subtotal = $total_subtotal + $object->subtotal;
$total_vat = $total_vat + $object->vat;
$total_total = $total_total + $object->total;
?>
<tr>
<td><?=$object->id?></td>
<td><?=$object->name?></td>
<td align="right"><?=$object->number_of_jobs?></td>
<td align="right"><?=number_format($object->subtotal,'2',',','.')?></td>
<td align="right"><?=number_format($object->vat,'2',',','.')?></td>
<td align="right"><?=number_format($object->total,'2',',','.')?></td>
<td align="center" valign="middle"><a href="../invoice/invoice_create.php?client_id=<?=$object->id?>"><img border="0" alt="Faktureren" src="../images/invoice.png" /></a></td>
</tr>
<?php } 
$db->disconnect();
?>
</tbody>
<tfoot>
<tr>
<td colspan="3">Totaal:</td>
<td align="right"><?=number_format($total_subtotal, 2, ',', '.')?></td>
<td align="right"><?=number_format($total_vat, 2, ',', '.')?></td>
<td align="right"><?=number_format($total_total, 2, ',', '.')?></td>
<td>&nbsp;</td>
</tr>
</tfoot>
</table>
<?php $err = isset($_GET["error"]) ? htmlspecialchars((string)$_GET["error"]) : ""; ?>
<p class="error"><?=$err?></p>
</div>
</div>
</body>
</html>