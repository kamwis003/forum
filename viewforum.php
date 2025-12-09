<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Forum</title>
</head>
<body>

<?php
// Pobranie loginu z cookie
$login = $_COOKIE['user_login'] ?? 'Gość';
?>

<a href="logout.php">Wyloguj</a><br>
Zalogowany jako: <?= htmlspecialchars($login) ?><br><br>

Utwórz temat<br>
<form action="newtopic.php" method="post" enctype="multipart/form-data">
    Temat: <input type="text" name="tname" maxlength="20" size="20"><br>
    Wiadomość: <input type="text" name="message"><br>
    Plik: <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Utwórz" name="submit" <?php if ($login === 'Gość') echo "disabled"; ?>>
</form>

<?php
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
            PDO::MYSQL_ATTR_SSL_CA            => $ca_cert_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia: " . $e->getMessage());
}

// --- Odczyt threads ---
foreach ($rows as $row) {
    echo "<a href='viewtopic.php?tid=" . htmlspecialchars($row['tid']) . "'>" 
         . htmlspecialchars($row['tname']) . "</a><br>";
}
?>

</body>
</html>
