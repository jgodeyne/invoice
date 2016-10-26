<?php
include_once("../common/session.php");
include_once("company_class.php");

$job= new Company();
$job->setFromPost($_POST);
$job->save();

header("Location: ../company/company_list.php");
exit(0);
?>	