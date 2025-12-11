<?php
use PHPUnit\Framework\TestCase;

class ThreadCreationTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $host = "forumchmury.mysql.database.azure.com";
        $dbname = "forum";
        $username = "htmlentities";
        $password = "Ewald123#";
        $ca_cert_path = __DIR__ . '/../../certs/DigiCertGlobalRootCA.crt.pem';

        $this->pdo = new PDO(
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
    }

    public function testAddThread()
{
    // ---- 1. Utworzenie użytkownika (tak jak realna aplikacja) ----
    $username = 'testuser_' . time();
    $password = 'pass';

    $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    // ---- 2. Pobranie ID użytkownika z bazy (tak jak w newtopic.php) ----
    $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userRow = $stmt->fetch();

    $this->assertNotFalse($userRow, "Nie udało się pobrać użytkownika z bazy");

    $uid = (int)$userRow['id'];

    // ---- 3. Utworzenie wątku ----
    $name = 'Testowy wątek ' . time();

    $stmt = $this->pdo->prepare("INSERT INTO threads (tname, id) VALUES (?, ?)");
    $stmt->execute([$name, $uid]);

    // ---- 4. Sprawdzenie czy wątek powstał ----
    $stmt = $this->pdo->prepare("SELECT tname, id FROM threads WHERE tname = ?");
    $stmt->execute([$name]);
    $row = $stmt->fetch();

    $this->assertNotFalse($row, "Nie znaleziono wątku w bazie");

    $this->assertEquals($name, $row['tname']);
    $this->assertEquals($uid, (int)$row['id']);
}

}
