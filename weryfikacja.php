<?php
// Ustaw trwały katalog sesji
session_save_path(__DIR__ . '/sessions');
session_start();
$_SESSION['loggedin']  = true;
$_SESSION['user_login'] = $rekord['username'];
$_SESSION['user_id']    = $rekord['id'];

echo 'Zalogowano jako: ' . $_SESSION['user_login'];
echo '<br><a href="geo.php">Przejdź do geo.php</a>';


// Pobranie danych z formularza
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';

$user = htmlentities($user, ENT_QUOTES, "UTF-8");
$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");

// --- KONFIGURACJA BAZY DANYCH ---
$host = "forumchmury.mysql.database.azure.com";
$dbname = "forum";
$username = "htmlentities";
$password = "Ewald123#";

// Ścieżka do certyfikatu root CA
$ca_cert_path = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

// --- POŁĄCZENIE PDO ---
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

// --- SPRAWDZENIE UŻYTKOWNIKA ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$rekord = $stmt->fetch();

if (!$rekord) {
    // Użytkownik nie istnieje
    $_SESSION['login_error'] = "Nieprawidłowy login lub hasło.";
    header("Location: login.php");
    exit;
}

// --- SPRAWDZENIE HASŁA ---
if (password_verify($pass, $rekord['password'])) {
    // Poprawne logowanie
    $_SESSION['loggedin']  = true;
    $_SESSION['user_login'] = $rekord['username'];
    $_SESSION['user_id']    = $rekord['id'];

    header("Location: geo.php");
    exit;
} else {
    // Błędne hasło
    $_SESSION['login_error'] = "Nieprawidłowy login lub hasło.";
    header("Location: login.php");
    exit;
}
?>
