<?php
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<BODY>
Formularz logowania
<form method="post" action="weryfikacja.php">
Login:<input type="text" name="user" maxlength="20" size="20"><br>
Hasło:<input type="password" name="pass" maxlength="20" size="20"><br>
<input type="submit" value="Send"/>
</form>
<a href="register.php">Rejestracja</a><br>
<a href="geo.php">Kontynuuj bez logowania</a><br>
</BODY>
</HTML>