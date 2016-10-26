<?php
include_once("../common/session.php");

$id=htmlspecialchars($_GET["id"]);

include_once 'job_class.php';
$job = Job::findById($id);
$job->delete();

header("Location: ../job/job_list.php");
exit(0);
?>