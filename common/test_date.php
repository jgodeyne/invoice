<?php
include_once 'date_functions.php';

echo "<h1>Date Invertion</h1>";
echo "<p>Inverting 2012/06/15 becomes " . invertDate("2012/06/15") . "</p>";
echo "<p>Inverting 15/06/2012 becomes " . invertDate("15/06/2012") . "</p>";

echo "<h1>DateTime Invertion</h1>";
echo "<p>Inverting 2012/06/15 12:00 becomes " . invertDateTime("2012/06/15 12:00") . "</p>";
echo "<p>Inverting 15/06/2012 12:00 becomes " . invertDateTime("15/06/2012 12:00") . "</p>";

?>