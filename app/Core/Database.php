<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host     = $_ENV['DB_HOST']     ?? '127.0.0.1';
        $port     = $_ENV['DB_PORT']     ?? '3306';
        $database = $_ENV['DB_DATABASE'] ?? 'blog';
        $username = $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Do not expose credentials in production
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a SELECT query and return all rows.
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Execute a SELECT query and return all rows.
     */
    public function fetchAllColumn(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Execute a SELECT query and return a single row.
     */
    public function fetchOne(string $sql, array $params = []): array|false
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch();
    }

    /**
     * Execute an INSERT / UPDATE / DELETE statement.
     * Returns last insert ID for INSERT, affected row count otherwise.
     */
    public function execute(string $sql, array $params = []): string|int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Return last insert ID if available, otherwise row count
        $lastId = $this->pdo->lastInsertId();

        return $lastId !== '0' ? $lastId : $stmt->rowCount();
    }

    // Prevent cloning and unserialization of the singleton
    private function __clone() {}
    public function __wakeup(): never
    {
        throw new \RuntimeException('Cannot unserialize singleton.');
    }
}
