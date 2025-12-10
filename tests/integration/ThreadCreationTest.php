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
        $name = 'Testowy wątek ' . time();
        $stmt = $this->pdo->prepare("INSERT INTO threads (tname) VALUES (?)");
        $stmt->execute([$name]);

        $stmt = $this->pdo->prepare("SELECT tname FROM threads WHERE tname = ?");
        $stmt->execute([$name]);
        $row = $stmt->fetch();
        $this->assertEquals($name, $row['tname']);
    }
}
