<?php
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
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

    public function testConnection()
    {
        $stmt = $this->pdo->query("SELECT 1");
        $this->assertEquals(1, $stmt->fetchColumn());
    }

    public function testThreadsExist()
    {
        $stmt = $this->pdo->query("SELECT tid, tname FROM threads");
        $rows = $stmt->fetchAll();
        $this->assertIsArray($rows);
    }
}
