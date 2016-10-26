<?php 
include_once("../common/session.php");
include_once("company_class.php");

include_once("../common/html_head.php");
?>
<body>
<script type="text/javascript">function confirmMsg(msg, url) {  if (confirm(msg)) {   document.location = url;  }}</script>
<? include("../common/header.php"); ?>
<? include("../common/menu.php"); ?>
<div id="middle">
<div id="main">
<h1>Uitvoerders</h1>
<table class="list">
<thead>
<tr>
<td>Id</td>
<td>Naam</td>
<td>Contact</td>
<td>Tel</td>
<td>GSM</td>
<td>Email</td>
<td align="center" valign="middle"><a href="../company/company_form.php"><img border="0" alt="Toevoegen" src="../images/new.png" /></a></td>
</tr>
</thead>
<tbody>
<?php
try{
$companies=Company::findAllNotMe();
foreach ($companies as $company) {
?>
<tr>
<td><?=$company->getId()?></td>
<td><?=$company->getName()?></td>
<td><?=$company->getContact()?></td>
<td><?=$company->getPhoneNumber()?></td>
<td><?=$company->getMobileNumber()?></td>
<td><?=$company->getEmail()?></td>
<td align="center"><a href="../company/company_form.php?id=<?=$company->getId()?>"><img border="0" alt="Wijzigen" src="../images/properties.png"></a></td>
<?php
}
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}?>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>