<?php
include_once("../common/session.php");
include_once("company_class.php");

$id = htmlspecialchars($_POST['id']);
$company = Company::findById($id);
$company->setFromPost($_POST);
$company->save();

if($company->getMe()) {
	header("Location: ../job/job_list.php");
}else{
	header("Location: ../company/company_list.php");
}
exit(0);
?>	