<?php
declare(strict_types=1);
session_start();

$tid = $_GET['tid'] ?? null;
if (!$tid) {
    die("Brak ID wątku.");
}

// --- PDO CONNECTION (Azure MySQL) ---
$host = "forumewaldowe.database.windows.net";
$dbname   = "forumewaldowe";
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
?>

<a href="viewforum.php">Powrót</a><br>
Temat:
<?php
// --- POBRANIE NAZWY WĄTKU ---
$stmt = $pdo->prepare("SELECT tname FROM threads WHERE tid = ?");
$stmt->execute([$tid]);
$thread = $stmt->fetch();

if (!$thread) {
    die("Wątek nie istnieje.");
}

echo htmlspecialchars($thread['tname'], ENT_QUOTES);
?>
<br><br>

<!-- Formularz dodawania posta -->
Dodaj posta<br>
<form action="addpost.php" method="post" enctype="multipart/form-data">
    Wiadomość: <input type="text" name="message"><br>
    Plik: <input type="file" name="fileToUpload"><br>

    <input type="hidden" name="tid" value="<?= htmlspecialchars($tid) ?>">

    <input type="submit" value="Wyślij"
        <?php if (!isset($_SESSION['loggedin'])) echo "disabled"; ?>>
</form>

<br><br>

<?php
// --- POBRANIE WIADOMOŚCI W WĄTKU ---
$stmt = $pdo->prepare("SELECT * FROM messages WHERE tid = ?");
$stmt->execute([$tid]);

echo "<table cellpadding='5' border='1'>";
echo "<tr>
        <td>messageid</td>
        <td>username</td>
        <td>wiadomość</td>
        <td>Plik</td>
        <td>czas</td>
      </tr>";

while ($row = $stmt->fetch()) {

    // pobranie nazwy użytkownika
    $userStmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $userStmt->execute([$row['id']]);
    $user = $userStmt->fetch();

    $username = $user['username'] ?? '???';

    // filtrowanie przekleństw
    $message = preg_replace("/\bcholera\b/i", "co przeklinasz?", $row['message']);

    $filename = $row['file'];
    $datetime = $row['datetime'];
    $type     = $row['ext'];

    echo "<tr>";
    echo "<td>{$row['messageid']}</td>";
    echo "<td>$username</td>";
    echo "<td>$message</td>";

    echo "<td>";

    // wyświetlanie plików
    if ($type == "img") {
        echo "<img src='./files/$filename' width='200' height='200'>";
    }
    elseif ($type == "audio") {
        echo "<audio controls><source src='./files/$filename' type='audio/mp3'></audio>";
    }
    elseif ($type == "video") {
        echo "<video width='320' height='240' controls>
                <source src='./files/$filename' type='video/mp4'>
              </video>";
    }

    echo "</td>";

    echo "<td>$datetime</td>";

    echo "</tr>";
}

echo "</table>";
?>
