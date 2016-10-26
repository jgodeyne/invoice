<div class="menu">
    <ul>
<?php if(isset($_SESSION['authorized']) && $_SESSION['authorized']==true) { ?>
        <li><a href="../job/job_form.php" style="margin-left: 3px"><span>Nieuwe Job</span></a></li>
        <li><a href="../job/job_list.php" style="margin-left: 3px"><span>Jobs</span></a></li>
        <li><a href="../invoice/invoice_invoicing_list.php" style="margin-left: 3px"><span>Factureren</span></a></li>
        <li><a href="../invoice/invoice_list.php" style="margin-left: 3px"><span>Facturen</span></a></li>
        <li><a href="../client/client_list.php" style="margin-left: 3px"><span>Klanten</span></a></li>
        <li><a href="../company/company_list.php" style="margin-left: 3px"><span>Uitvoerders</span></a></li>
        <li><a href="../company/company_form.php?id=1" style="margin-left: 3px"><span>Instellingen</span></a></li>
		<li><a href="../login/logoff.php" style="margin-left: 3px"><span>Afmelden</span></a></li>
<?php } else { ?>
        <li><a href="../login/login.php" style="margin-left: 3px"><span>Afmelden</span></a></li>
<?php } ?>
    </ul>
</div>
