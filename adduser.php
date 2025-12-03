<?php
header('Content-Type: text/html; charset=utf-8');

$user        = $_POST['user'] ?? '';
$pass        = $_POST['pass'] ?? '';
$repeatpass  = $_POST['repeatpass'] ?? '';

// filtrowanie podstawowe
$user = htmlentities($user, ENT_QUOTES, "UTF-8");
$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");
$repeatpass = htmlentities($repeatpass, ENT_QUOTES, "UTF-8");

// --- PDO CONNECTION ---
$host = "forumchmury.mysql.database.azure.com";
$dbname   = "forum";
$username = "htmlentities";
$password = "Ewald123#";

// Ścieżka do certyfikatu root CA
$ca_cert_path = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO(
        "sqlsrv:server=$server;Database=$db",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE                 => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE      => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_CA            => $ca_cert_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia z bazą: " . $e->getMessage());
}

// --- SPRAWDZENIE CZY UŻYTKOWNIK ISTNIEJE ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$rekord = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rekord) {

    if ($pass === $repeatpass) {

        // haszowanie hasła – ZALECANE!
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $insert = $pdo->prepare(
            "INSERT INTO users (username, password) VALUES (?, ?)"
        );
        $insert->execute([$user, $hashed]);

        header("Location: https://forumchmury.mysql.database.azure.com/login.php");
        exit;

    } else {
        echo "Hasła się nie zgadzają";
    }

} else {
    echo "Użytkownik już istnieje";
}

?>
