<?php
// Włączenie błędów (tylko do testów)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pobranie danych z formularza
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';

$user = htmlentities($user, ENT_QUOTES, "UTF-8");
$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");

// --- PDO CONNECTION (Azure MySQL) ---
$host = "forumchmury.mysql.database.azure.com";
$dbname = "forum";
$username = "htmlentities";
$password = "Ewald123#";
$ca_cert_path = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_CA => $ca_cert_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia z bazą: " . $e->getMessage());
}

// --- Sprawdzenie użytkownika ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$rekord = $stmt->fetch();

if (!$rekord || !password_verify($pass, $rekord['password'])) {
    // Błędny login lub hasło
    header("Location: login.php");
    exit;
}

// --- Poprawne logowanie ---
// Ustawienie cookie na 1 godzinę, dostępne w całym serwisie
setcookie('user_login', $rekord['username'], time() + 3600, "/");

header("Location: geo.php");
exit;
