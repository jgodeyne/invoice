<?php
include_once("../common/session.php");
include_once("job_class.php");

$job= new Job();
$job->setFromPost($_POST);
$job->save();

header("Location: ../job/job_list.php");
exit(0);
?>	