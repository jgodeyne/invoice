<?php
$invoice_date = date("d-m-Y");
$invoice_payment_delay = 30;
//$due_date = date("d/m/Y", strtotime(date("d/m/Y", strtotime($invoice_date)) . " + " . $invoice_payment_delay . " day"));
$due_date = date("d-m-Y", strtotime("+" . $invoice_payment_delay . " day"));
?>
<h1>Due date calculation</h1>
<p>Invoice Date: <?=$invoice_date?></p>
<p>Delay: <?=$invoice_payment_delay?></p>
<p>Due Date: <?=$due_date?></p>
<p><?=(strtotime($due_date) < strtotime($invoice_date))?"True":"False"?></p>