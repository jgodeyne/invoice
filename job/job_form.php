<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<? include("../common/header.php"); ?>
<? include("../common/menu.php"); ?>
<div id="middle">
<div id="main">
<?php
include_once 'job_class.php';
include_once '../client/client_class.php';
include_once '../company/company_class.php';
try{
if(isset($_GET["id"])) {
	$id=htmlspecialchars($_GET["id"]);
}else{
	$id="";
}
$job = "";
$request_date = "";
$expected_delivery_datetime = "";
$client_id = "";
$client_reference = "";
$description = "";
$unit = "";
$number_of_units = "";
$unit_price = "";
$discount_percentage = "";
$fixed_price = "";
$delivery_date_exec = "";
$executor_id = "";
$price_exec = "";
$client = "";
$executor = "";
$author_rights = true;
if($id) {
	$job = Job::findById($id);
	$request_date = $job->getRequestDate();
	$expected_delivery_datetime = $job->getExpectedDeliveryDatetime();
	$client_id = $job->getClientId();
	$client_reference = $job->getClientReference();
	$description = $job->getDescription();
	$unit = $job->getUnit();
	$number_of_units = $job->getNumberOfUnits();
	$unit_price = $job->getUnitPrice();
	$discount_percentage = $job->getDiscountPercentage();
	$fixed_price = $job->getFixedPrice();
	$delivery_date_exec = $job->getDeliveryDateExec();
	$price_exec = $job->getPriceExec();
	$executor_id = $job->getExecutorId();
	$client = Client::findById($client_id);
	$executor = Company::findById($executor_id);
	$author_rights = $job->getAuthorRights();

	$title="Wijzig Job";
	$action="../job/job_update.php";
	$disabled="readonly";
}
else {
	$title="Nieuwe Job";
	$action="../job/job_insert.php";
	$request_date = date("d/m/Y");
	$disabled="";
	$executor_id = 1;
}
?>
<h1><?=$title?></h1>
<form method="post" action="<?=$action?>" name="job_form">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Id:</td>
<td>&nbsp;<?=$id?><input type="hidden" name="id" value="<?=$id?>"/></td>
</tr>
<tr>
<td>&nbsp;Datum aanvraag:</td>
<td>&nbsp;<?=$request_date?><input type="hidden" name="request_date" value="<?=$request_date?>"/></td>
</tr>
<tr>
<td>&nbsp;Klant:</td>
<td>&nbsp;
<select name="client_id" autofocus>
<?php
foreach (Client::findAllOrdened("name") as $client) {
?>
<option value="<?=$client->getId()?>" <?=$client->getId()==$client_id?'selected="selected"':''?>><?=$client->getName()?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Datum/tijd te leveren (dd/mm/yyyy hh:mm):</td>
<td>&nbsp;<input type="text" maxlength="19" size="19" name="expected_delivery_datetime" id="expected_delivery_datetime" value="<?=$expected_delivery_datetime?>" pattern="\d{1,2}/\d{1,2}/\d{4} \d{1,2}:\d{1,2}" />
</td>
</tr>
<tr>
<td>&nbsp;Klant referentie:</td>
<td>&nbsp;<input type="text" maxlength="50" size="50" name="client_reference" value="<?=$client_reference?>"/></td>
</tr>
<tr>
<td>&nbsp;Omschrijving:</td>
<td>&nbsp;<input type="text" maxlength="500" size="100" name="description" value="<?=$description?>" /></td>
</tr>
<tr>
<td>&nbsp;Eenheid:</td>
<td>&nbsp;
<select name="unit">
<option value=""<?=$unit==''?'selected="selected"':''?>></option>
<option value="woorden" <?=$unit=='woorden'?'selected="selected"':''?>>Woorden</option>
<option value="regels" <?=$unit=='regels'?'selected="selected"':''?>>Regels</option>
<option value="ondertitels" <?=$unit=='ondertitels'?'selected="selected"':''?>>Ondertitels</option>
<option value="uren" <?=$unit=='uren'?'selected="selected"':''?>>Uren</option>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Aantal enheden:</td>
<td>&nbsp;<input type="text" maxlength="10" size="10" name="number_of_units" value="<?=str_replace(".",",",$number_of_units)?>"/></td>
</tr>
<tr>
<td>&nbsp;Prijs per eenheid:</td>
<td>&nbsp;<input type="text" maxlength="15" size="15" name="unit_price" value="<?=str_replace(".",",",$unit_price)?>"/></td>
</tr>
<tr>
<td>&nbsp;Korting %:</td>
<td>&nbsp;<input type="text" maxlength="15" size="15" name="discount_percentage" value="<?=str_replace(".",",",$discount_percentage)?>"/>%</td>
</tr>
<tr>
<td>&nbsp;Vaste prijs:</td>
<td>&nbsp;<input type="text" maxlength="15" size="15" name="fixed_price" value="<?=str_replace(".",",",$fixed_price)?>"/></td>
</tr>
<tr>
<td>&nbsp;Uitvoerder:</td>
<td>&nbsp;
<select name="executor_id">
<?php
foreach (Company::findAll() as $company) {
?>
<option value="<?=$company->getId()?>" <?=$company->getId()==$executor_id?'selected="selected"':''?>><?=$company->getName()?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Auteurs Rechten:</td>
<td>&nbsp;<input type="checkbox" name="author_rights" <?=($author_rights?'checked':'')?>/></td>
</tr>
<tr>
<td>&nbsp;Datum te leveren door uitvoerder (dd/mm/yyyy hh:mm):</td>
<td>&nbsp;<input type="text" maxlength="19" size="19" name="delivery_date_exec" id="delivery_date_exec" value="<?=$delivery_date_exec?>" pattern="\d{1,2}/\d{1,2}/\d{4} \d{1,2}:\d{1,2}" />
</td>
</tr>
<tr>
<td>&nbsp;Prijs uitvoerder:</td>
<td>&nbsp;<input type="text" maxlength="15" size="15" name="price_exec" value="<?=str_replace(".",",",$price_exec)?>"/></td>
</tr>
</tbody>
</table>
<p>
<input type="submit" value="Bewaren" name="bewaren" class="button">
</p>
</form>
<p></p>
</div>
</div>
<?php
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}?>
</body>
</html>
