<?php
include_once("../common/session.php");
include_once("job_class.php");

$id = htmlspecialchars($_POST['id']);
$job = Job::findById($id);
$job->setFromPost($_POST);
$job->save();

header("Location: ../job/job_list.php");
exit(0);
?>	