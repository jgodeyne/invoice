<?php
include_once("../common/session.php");

$id=htmlspecialchars($_GET["id"]);

include_once 'company_class.php';
$company = Company::findById($id);
$company->delete();

header("Location: ../company/company_list.php");
exit(0);
?>