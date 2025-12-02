<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

// Pobranie danych
$tid     = $_POST['tid'] ?? '';
$message = $_POST['message'] ?? '';

$tid     = htmlentities($tid, ENT_QUOTES, "UTF-8");
$message = htmlentities($message, ENT_QUOTES, "UTF-8");

// --- UPLOAD PLIKU ---
$target_dir = "./files/";
$target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
$ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// określenie typu pliku
switch ($ext) {
    case "jpg": case "jpeg": case "png": case "gif":
        $type = "img"; break;
    case "mp3":
        $type = "audio"; break;
    case "mp4":
        $type = "video"; break;
    default:
        $type = "other"; break;
}

// upload
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " uploaded.";
} else {
    echo "Error uploading file.";
}

// Nazwa pliku (do bazy)
$file_name = basename($_FILES["fileToUpload"]["name"]);

// --- PDO CONNECTION ---
$host = "forumewaldowe.database.windows.net";
$dbname   = "forumewaldowe";
$username = "htmlentities";
$password = "Ewald123#";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia z bazą: " . $e->getMessage());
}

// --- SPRAWDZENIE ZALOGOWANEGO UŻYTKOWNIKA ---
if (!isset($_SESSION['user_login'])) {
    die("Brak zalogowanego użytkownika.");
}

$user = $_SESSION['user_login'];

// pobranie ID użytkownika
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$user]);
$row = $stmt->fetch();

if (!$row) {
    die("Błąd: użytkownik nie istnieje.");
}

$user_id = $row['id'];

// --- DODANIE WIADOMOŚCI ---
$stmt = $pdo->prepare("
    INSERT INTO messages (tid, id, message, file, ext)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $tid,
    $user_id,
    $message,
    $file_name,
    $type
]);

// przekierowanie
header("Location: viewtopic.php?tid=" . $tid);
exit;
?>
