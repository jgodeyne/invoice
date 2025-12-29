<?php 
include_once("../common/session.php");
include_once("job_class.php");
include_once("../client/client_class.php");
include_once("../company/company_class.php");

include_once("../common/html_head.php");
?>
<body>
<script type="text/javascript">function confirmMsg(msg, url) {  if (confirm(msg)) {   document.location = url;  }}</script>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<div id="middle">
<div id="main">
<?php 
$error="";
if(isset($_POST['filter_client_id'])) {
	$filter_client_id = htmlspecialchars($_POST['filter_client_id']);
}else{
	$filter_client_id='none';
}
if(isset($_POST['filter_status'])){
	$filter_status = htmlspecialchars($_POST['filter_status']);
}else{
	$filter_status='OPEN';
}
if(isset($_POST['filter_executor_id'])) {
	$filter_executor_id = htmlspecialchars($_POST['filter_executor_id']);
}else{
	$filter_executor_id = "none";
}
if(isset($_POST['filter_year'])) {
	$filter_year = htmlspecialchars($_POST['filter_year']);
} else {
	$filter_year = date('Y');	
}
?>
<h1>Jobs</h1>
<h2>Filter:</h2>
<form method="post" action="../job/job_list.php" name="job_filter">
<table class="form">
<tbody>
<tr>
<td>&nbsp;Status:</td>
<td>&nbsp;
<select name="filter_status" onChange="job_filter.submit()">
<option value="OPEN" <?=($filter_status=='OPEN')?('selected="selected"'):('');?>>Open</option>
<option value="CLOSED" <?=($filter_status=='CLOSED')?('selected="selected"'):('');?>>Geleverd</option>
<option value="INVOICED" <?=($filter_status=='INVOICED')?('selected="selected"'):('');?>>Gefaktureerd</option>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Klant:</td>
<td>&nbsp;
<select name="filter_client_id" onChange="job_filter.submit()">
<option value="none"></option>
<?php
$clients = Client::findAllOrdened("name");
foreach ($clients as $client) {
?>
<option value="<?=$client->getId()?>" <?=($client->getId()==$filter_client_id)?('selected="selected"'):('');?>><?=$client->getName()?></option>
<?php
} ?>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Uitvoerder:</td>
<td>&nbsp;
<select name="filter_executor_id" onChange="job_filter.submit()">
<option value="none"></option>
<?php
$company = Company::findAllOrdened("name");
foreach ($company as $company) {
?>
<option value="<?=$company->getId()?>" <?=($company->getId()==$filter_executor_id)?('selected="selected"'):('');?>><?=$company->getName()?></option>
<?php
} ?>
</select>
</td>
</tr>
<tr>
<td>&nbsp;Jaartal</td>
<td>&nbsp;
<select name="filter_year" onChange="job_filter.submit()">
<?php
$currentyear = date('Y');
for ($i = 0; $i <= 19; $i++) {
?>
<option value="<?=$currentyear-$i?>" <?=($filter_year==$currentyear-$i)?('selected="selected"'):('');?>><?=$currentyear-$i?></option>
<?php } ?>
</select>
</td>
</tr>
</tbody>
</table>
</form>
<?php

$criteria="status = '" . $filter_status . "'";
$criteria=$criteria . " and year(request_date)='" . $filter_year . "'";
if ($filter_client_id!='none') {
	$criteria=$criteria . " and client_id='" . $filter_client_id . "'";
}
if ($filter_executor_id!='none') {
	$criteria=$criteria . " and executor_id='" . $filter_executor_id . "'";
}
?>
<h2>Lijst:</h2>
<p class="error"><?=isset($_GET["error"])?htmlspecialchars($_GET["error"]):""?></p>
<table class="list">
<thead>
<tr>
<td>Id</td>
<td>Datum<br/>aanvraag</td>
<td>Datum/Tijd<br/>te leveren</td>
<td>Klant</td>
<td>Klant<br/>referentie</td>
<td>Beschrijving</td>
<td>Eenheid</td>
<td>Aantal<br />eenheden</td>
<td>Uitvoerder</td>
<td>Uitvoerder<br/>Datum te Leveren</td>
<td>Uitvoerder<br/>Prijs</td>
<td>Datum<br />geleverd</td>
<td colspan="2">&nbsp;</td>
</tr>
</thead>
<tbody>
<?php
$jobs=Job::findAllByCriteriaOrdened($criteria,"expected_delivery_datetime");
foreach ($jobs as $job) {
	$client=Client::findById($job->getClientId());
	$executor=Company::findById($job->getExecutorId());
?>
<tr>
<td><?=$job->getId()?></td>
<td><?=$job->getRequestDate()?></td>
<td><?=$job->getExpectedDeliveryDatetime()?></td>
<td><?=$client->getName()?></td>
<td><?=$job->getClientReference()?></td>
<td><?=$job->getDescription()?></td>
<td><?=$job->getUnit()?></td>
<td align="right"><?=number_format($job->getNumberOfUnits(),'2',',','.')?></td>
<td><?=$executor->getName()?></td>
<td><?=$job->getDeliveryDateExec()?>
<td><?=number_format($job->getPriceExec(),'2',',','.')?>
<td><?=$job->getDeliveryDate()?></td>
<?php if($job->getStatus()=='OPEN') {?>
<td align="center">
<a href="javascript:confirmMsg('Job sluiten voor fakturatie', '../job/job_close.php?id=<?=$job->getId()?>')"><img border="0" alt="Leveren" src="../images/deliver.png"></a>
</td>
<?php }?>
<?php if($job->getStatus()=='OPEN' || $job->getStatus()=='CLOSED') {?>
<td align="center">
<a href="../job/job_form.php?id=<?=$job->getId()?>"><img border="0" alt="Wijzigen" src="../images/properties.png"></a>
</td>
<?php }?>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</body>
</html>