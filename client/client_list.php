<?php 
include_once("../common/session.php");
include_once("../common/html_head.php");
?>
<body>
<?php include("../common/header.php"); ?>
<?php include("../common/menu.php"); ?>
<?php include("client_class.php"); ?>
<div id="middle">
<div id="main">
<h1>Klanten</h1>
<table class="list">
<thead>
<tr>
<td>Id</td>
<td>Naam</td>
<td>Kontact<br/>persoon</td>
<td>Opmerking</td>
<td>Telefoon<br>nummer</td>
<td>Mobiel<br>nummer</td>
<td>E-Mail</td>
<td>BTW<br/>nummer</td>
<td>Taal</td>
<td align="center" valign="middle"><a href="../client/client_form.php"><img border="0" alt="Toevoegen" src="../images/new.png" /></a></td>
</tr>
</thead>
<tbody>
<?php
$clients=Client::findAllOrdened("name");
foreach($clients as $client) { ?>
<tr>
<td><?=$client->getId()?></td>
<td><?=$client->getName()?></td>
<td><?=$client->getContact()?></td>
<td><?=$client->getRemark()?></td>
<td><?=$client->getPhoneNumber()?></td>
<td><?=$client->getMobileNumber()?></td>
<td><?=$client->getEmail()?></td>
<td><?=$client->getVatNumber()?></td>
<td><?=$client->getLanguage()?></td>
<td align="center"><a href="../client/client_form.php?id=<?=$client->getId()?>"><img border="0" alt="Wijzigen" src="../images/properties.png"></a></td>
<!-- td align="center"><a href="../client/client_delete.php?id=<?=$client->getId()?>"><img border="0" alt="Verwijderen" src="../images/delete.png"></a></td-->
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</body>
</html>