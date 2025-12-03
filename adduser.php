<?php
header('Content-Type: text/html; charset=utf-8');

$user        = $_POST['user'] ?? '';
$pass        = $_POST['pass'] ?? '';
$repeatpass  = $_POST['repeatpass'] ?? '';

$user = htmlentities($user, ENT_QUOTES, "UTF-8");
$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");
$repeatpass = htmlentities($repeatpass, ENT_QUOTES, "UTF-8");

// --- PDO CONNECTION ---
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

// --- SPRAWDZENIE CZY UŻYTKOWNIK ISTNIEJE ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$rekord = $stmt->fetch();

if ($rekord) {
    echo "Użytkownik już istnieje";
    exit;
}

if ($pass !== $repeatpass) {
    echo "Hasła się nie zgadzają";
    exit;
}

// --- Dodanie użytkownika ---
$hashed = password_hash($pass, PASSWORD_DEFAULT);
$insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$insert->execute([$user, $hashed]);

// --- Ustawienie cookie, żeby użytkownik był od razu zalogowany ---
setcookie('user_login', $user, time() + 3600, "/");

// --- Przekierowanie do geo.php lub viewforum.php ---
header("Location: geo.php");
exit;
