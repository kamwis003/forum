<?php
declare(strict_types=1);

// --- WŁĄCZENIE WYŚWIETLANIA BŁĘDÓW I OUTPUT BUFFERING ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

// --- POBRANIE LOGINU Z COOKIE ---
$login = isset($_COOKIE['user_login']) ? $_COOKIE['user_login'] : 'Gość';

// --- POŁĄCZENIE Z BAZĄ (Azure MySQL) ---
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
    die("Błąd połączenia: " . $e->getMessage());
}

// --- POBRANIE LISTY TEMATÓW ---
try {
    $stmt = $pdo->query("SELECT tid, tname FROM threads ORDER BY tid DESC");
    $threads = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Błąd pobrania tematów: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Forum</title>
</head>
<body>

<a href="logout.php">Wyloguj</a><br>
Zalogowany jako: <?php echo htmlspecialchars($login); ?><br><br>

<h3>Utwórz temat</h3>
<form action="newtopic.php" method="post" enctype="multipart/form-data">
    Temat: <input type="text" name="tname" maxlength="20" size="20"><br>
    Wiadomość: <input type="text" name="message"><br>
    Plik: <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Utwórz" name="submit"
        <?php if ($login === 'Gość') { echo "disabled"; } ?>>
</form>

<h3>Lista tematów:</h3>

<?php
if (empty($threads)) {
    echo "<p>Brak tematów w bazie.</p>";
} else {
    foreach ($threads as $row) {
        $tid = htmlspecialchars((string)$row['tid']); // rzutowanie na string
        $tname = htmlspecialchars($row['tname']);
        if ($tname === '') $tname = '[Bez nazwy]';

        echo '<div><a href="viewtopic.php?tid=' . $tid . '">' . $tname . '</a></div>';
    }
}
?>

</body>
</html>
<?php
// --- WYŁĄCZENIE OUTPUT BUFFERING ---
ob_end_flush();
?>
