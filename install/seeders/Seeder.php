<?php

declare(strict_types=1);

namespace Install\Seeders;

use App\Core\Database;

abstract class Seeder
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public function run(): void;

    protected function info(string $message): void
    {
        echo "\033[32m[Seeder]\033[0m {$message}\n";
    }
}
