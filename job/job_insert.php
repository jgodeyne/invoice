<?php
include_once("../common/session.php");
include_once("job_class.php");

if (empty($_POST['expected_delivery_datetime'])) {
    $_SESSION['error'] = "Expected delivery date/time is required.";
    header("Location: ../job/job_form.php");
    exit(0);
}

if (empty($_POST['number_of_units'])) {
    $_SESSION['error'] = "Number of units is required.";
    header("Location: ../job/job_form.php");
    exit(0);
}

if (empty($_POST['unit_price'])) {
    $_SESSION['error'] = "Unit price is required.";
    header("Location: ../job/job_form.php");
    exit(0);
}

$job= new Job();
$job->setFromPost($_POST);
$job->save();

header("Location: ../job/job_list.php");
exit(0);
?>	