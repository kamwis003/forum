<?php declare(strict_types=1);  /* Ta linia musi byc pierwsza */ 
session_start();
$login = $_SESSION['user_login'];
?>
<a href = "logout.php">Wyloguj</a><br>
Zalogowany jako:
<?php
if (!isset($_SESSION['loggedin']))
{
    echo "Gość";
}
else
{
echo $login;
}
?>
<br><br>
Utwórz temat<br>
<form action="newtopic.php" method="post" enctype="multipart/form-data">
Temat:<input type="text" name="tname" maxlength="20" size="20"><br>
Wiadomość:<input type="text" name="message"><br>
Plik:<input type="file" name="fileToUpload" id="fileToUpload"><br>
<input type="submit" value="Utwórz" name="submit" <?php if (!isset($_SESSION['loggedin']))
{echo "disabled";}?>>
</form>
<?php
// --- PDO CONNECTION (Azure MySQL) ---
$host = "forumchmury.mysql.database.azure.com";
$dbname   = "forum";
$username = "htmlentities";
$password = "Ewald123#";

// Ścieżka do certyfikatu root CA
$ca_cert_path = __DIR__ . '/certs/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
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
    die("Błąd połączenia: " . $e->getMessage());
}

// --- ODCZYT THREADS ---
$stmt = $pdo->query("SELECT tid, tname FROM threads ORDER BY tid DESC");

while ($row = $stmt->fetch()) {
    echo "<a href='viewtopic.php?tid={$row['tid']}'>{$row['tname']}</a><br>";
}
?>
