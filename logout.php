<?php
// logout.php

session_start();    // rozpoczęcie sesji
session_unset();    // usunięcie wszystkich zmiennych sesyjnych
session_destroy();  // zniszczenie sesji
setcookie("user_login", "", time() - 3600);

// Przekierowanie do strony logowania
header("Location: https://forumewaldowe.azurewebsites.net/login.php");
exit; // zatrzymanie dalszego wykonywania skryptu
?>
