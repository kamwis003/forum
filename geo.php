<?php declare(strict_types=1);

// --- DANE DO PDO (Azure MySQL) ---
$host = "forumewaldowe.mysql.database.azure.com";   // <-- Twój host z Azure
$dbname   = "forumewaldowe";                            // <-- baza
$username = "htmlentities";                             // <-- login
$password = "Ewald123#";                                // <-- hasło

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Błąd połączenia: " . $e->getMessage());
}

// --- POBRANIE IP UŻYTKOWNIKA ---
$ipaddress = $_SERVER["REMOTE_ADDR"];

// Funkcja pobierająca geo-info
function ip_details(string $ip) {
    $json = @file_get_contents("http://ipinfo.io/{$ip}/geo");
    return $json ? json_decode($json) : null;
}

$details = ip_details($ipaddress);
$ipgoscia = $details->ip ?? $ipaddress;

// --- ZAPIS IP DO BAZY ---
$stmt = $pdo->prepare("INSERT INTO goscieportalu (ipaddress) VALUES (?)");
$stmt->execute([$ipgoscia]);

// --- POBRANIE LISTY WSZYSTKICH GOŚCI ---
$tabela = $pdo->query("SELECT * FROM goscieportalu ORDER BY datetime DESC");

?>
<table border="1" cellspacing="0" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Adres IP</th>
        <th>Czas</th>
        <th>Lokalizacja</th>
    </tr>

<?php foreach ($tabela as $row): ?>

    <?php
        // indywidualny link do lokalizacji
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
<a href="viewforum.php">Do portalu</a>
