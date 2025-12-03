<?php
// logout.php

session_start();    // rozpoczęcie sesji
session_unset();    // usunięcie wszystkich zmiennych sesyjnych
session_destroy();  // zniszczenie sesji

// Przekierowanie do strony logowania
header("Location: https://forumewaldowe.azurewebsites.net/login.php");
exit; // zatrzymanie dalszego wykonywania skryptu
?>
