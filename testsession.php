<?php
session_start();
$_SESSION['loggedin'] = true;
$_SESSION['user_login'] = 'TEST';
echo '<a href="testsession2.php">Przejdź dalej</a>';
