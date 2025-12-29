<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<?php include("invoice_class.php"); ?>
<?php include("../client/client_class.php"); ?>
<div id="middle">
<div id="main">
<?php
$invoice_id=htmlspecialchars($_GET["id"]);
$invoice = Invoice::findById($invoice_id);

$client = Client::findById($invoice->getClientId());
?>
<h1>Invoeren klantreferentie voor deze faktuur</h1>
<form method="post" action="invoice_update.php" name="invoice_form">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Id:</td>
<td>&nbsp;<?=$invoice_id?><input type="hidden" name="id" value="<?=$invoice_id?>"/></td>
</tr>
<tr>
<td>&nbsp;Faktuur Nr:</td>
<td>&nbsp;<?=$invoice->getNumber()?></td>
</tr>
<tr>
<td>&nbsp;Klant:</td>
<td>&nbsp;<?=$client->getName()?></td>
</tr>
<tr>
<td>&nbsp;Referentie:</td>
<td>&nbsp;<input type="text" maxlength="30" size="30" name="client_reference" value="<?=$invoice->getClientReference()?>"/><br/></td>
</tr>
</tbody>
</table>
<p>
<input type="submit" value="Bewaren" name="bewaren" class="button">
<INPUT TYPE="button" VALUE="Terug" onClick="window.location='../invoice/invoice_list.php';" class="button">
</p>
</form>
<p />
<?php $err = isset($_GET["error"]) ? htmlspecialchars((string)$_GET["error"]) : ""; ?>
<p class="error"><?=$err?></p>
</div>
</div>
</body>
</html>