<?php
include_once("../common/html_head.php");
?>
<body>
<?php include("../common/header.php"); ?>
<div id="middle" align="center">
<div id="login">
<h1>Aanmelden</h1>
<form action="../login/process.php" method="post">
<table class="form">
<tr><td colspan="2"><font color=red><?=$_REQUEST['login_error'] ?></font></td></tr>
<tr><td>Gebruiker:</td><td><input type="text" name="user" maxlength="30" size="15" autofocus="autofocus" required="required" /></td></tr>
<tr><td>Wachtwoord:</td><td><input type="password" name="pass" maxlength="30" size="15" /></td></tr>
<tr><td colspan="2" align="left">
<input type="hidden" name="sublogin" value="1" class="button"/>
<input type="submit" value="Aanmelden"  class="button"/></td></tr>
</table>
</form>
</div>
</div>
</body>
</html>
