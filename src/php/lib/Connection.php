<?php

declare(strict_types=1);

require('/var/www/html/vendor/autoload.php');


Dotenv\Dotenv::createImmutable('/var/app/customer_manegement')->load();

class Connection
{

    public readonly PDO $pdo;

    private readonly string $dsn;

    private readonly string $db_user;

    private readonly string $db_pass;

    private readonly array $options;

    public function __construct()
    {

        $this->dsn = 'mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'] . ';charset=utf8';
        $this->db_user = $_ENV['DB_USER'];
        $this->db_pass = $_ENV['DB_PASS'];
        $this->options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->pdo = new PDO($this->dsn, $this->db_user, $this->db_pass, $this->options);
        } catch (PDOException $e) {
            echo "接続失敗: " . $e->getMessage() . "\n";
            exit();
        }
    }
}
