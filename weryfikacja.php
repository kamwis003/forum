<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
</head>
<body>

<?php
session_start();

// Pobranie danych
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';

$user = htmlentities($user, ENT_QUOTES, "UTF-8");
$pass = htmlentities($pass, ENT_QUOTES, "UTF-8");

// --- PDO CONNECTION (Azure MySQL) ---
$host = "forumchmury.mysql.database.azure.com";
$dbname   = "forum";
$username = "htmlentities";
$password = "Ewald123#";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia: " . $e->getMessage());
}

// --- SPRAWDZENIE UŻYTKOWNIKA ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$rekord = $stmt->fetch();

if (!$rekord) {
    // Uzytkownik nie istnieje
    header("Location: https://forumchmury.mysql.database.azure.com/login.php");
    exit;
}

// --- SPRAWDZENIE HASŁA ---
if (password_verify($pass, $rekord['password'])) {

    $_SESSION['loggedin']  = true;
    $_SESSION['user_login'] = $rekord['username'];
    $_SESSION['user_id']    = $rekord['id'];

    header("Location: https://forumchmury.mysql.database.azure.com/geo.php");
    exit;

} else {
    // Błędne hasło
    header("Location: https://forumchmury.mysql.database.azure.com/login.php");
    exit;
}
?>

</body>
</html>
