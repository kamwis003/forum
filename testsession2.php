<?php
session_start();
echo 'Login: ' . ($_SESSION['user_login'] ?? 'Gość');
