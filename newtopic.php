<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

$user = $_COOKIE['user_login'] ?? null;
if (!$user) {
    die("Brak zalogowanego użytkownika.");
}
// Pobranie danych
$tname   = $_POST['tname'] ?? '';
$message = $_POST['message'] ?? '';

$tname   = htmlentities($tname, ENT_QUOTES, "UTF-8");
$message = htmlentities($message, ENT_QUOTES, "UTF-8");

// --- UPLOAD ---
$target_dir = "./files/";
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
$ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Typ pliku
switch ($ext) {
    case "jpg":
    case "jpeg":
    case "png":
    case "gif":
        $type = "img"; break;
    case "mp3":
        $type = "audio"; break;
    case "mp4":
        $type = "video"; break;
    default:
        $type = "other";
}

// Upload pliku
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
} else {
    echo "Error uploading file.";
}

// Nazwa pliku do bazy
$file_name = basename($_FILES["fileToUpload"]["name"]);

// --- PDO CONNECTION (Azure MySQL) ---
$host = "forumchmury.mysql.database.azure.com"; // tak jak w viewforum.php
$dbname = "forum";                               // tak jak w viewforum.php
$username = "htmlentities";
$password = "Ewald123#";

// Ścieżka do certyfikatu root CA
//$ca_cert_path = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE                 => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE      => PDO::FETCH_ASSOC,
            //PDO::MYSQL_ATTR_SSL_CA            => $ca_cert_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia: " . $e->getMessage());
}

// Pobranie ID użytkownika
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user]);
$userRow = $stmt->fetch();

if (!$userRow) {
    die("Błąd: użytkownik nie istnieje.");
}

$uid = $userRow['id'];

// --- 1. UTWORZENIE NOWEGO WĄTKU ---
$stmt = $pdo->prepare("INSERT INTO threads (tname, id) VALUES (?, ?)");
$stmt->execute([$tname, $uid]);

// Pobranie ID nowo utworzonego wątku
$tid = $pdo->lastInsertId();

// --- 2. UTWORZENIE PIERWSZEJ WIADOMOŚCI ---
$stmt = $pdo->prepare("
    INSERT INTO messages (tid, id, message, file, ext)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $tid,
    $uid,
    $message,
    $file_name,
    $type
]);

// Przekierowanie
header("Location: viewforum.php");
exit;
?>
