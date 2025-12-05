<?php
// Pobranie loginu z cookie
$login = $_COOKIE['user_login'] ?? 'Gość';

// --- PDO CONNECTION (tak samo jak w weryfikacja.php) ---
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

// --- Pobranie IP użytkownika ---
$ipaddress = $_SERVER["REMOTE_ADDR"];
$stmt = $pdo->prepare("INSERT INTO goscieportalu (ipaddress) VALUES (?)");
$stmt->execute([$ipaddress]);

// --- Pobranie wszystkich gości ---
$tabela = $pdo->query("SELECT * FROM goscieportalu ORDER BY datetime DESC");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Geo</title>
</head>
<body>

<h2>Sesja w geo.php: <?= htmlspecialchars($login) ?></h2>

<table border="1" cellspacing="0" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Adres IP</th>
    <th>Czas</th>
    <th>Lokalizacja</th>
</tr>

<?php foreach ($tabela as $row): 
    $linki = "https://www.google.pl/maps/place/" . $row['ipaddress'];
?>
<tr>
    <td><?= htmlspecialchars($row['id']) ?></td>
    <td><?= htmlspecialchars($row['ipaddress']) ?></td>
    <td><?= htmlspecialchars($row['datetime']) ?></td>
    <td><a href="<?= $linki ?>" target="_blank">Link</a></td>
</tr>
<?php endforeach; ?>
</table>

<br>
<p>Za chwilę zostaniesz przeniesiony do portalu...</p>

<script>
    // Automatyczne przekierowanie po 3 sekundach
    setTimeout(() => {
        window.location.href = "viewforum.php";
    }, 3000);
</script>

</body>
</html>
