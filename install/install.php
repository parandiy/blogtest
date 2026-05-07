<?php

declare(strict_types=1);

// Must be run from the project root: php seed.php [--fresh]
if (php_sapi_name() !== 'cli') {
    exit('This script must be run from the command line.' . PHP_EOL);
}

const BASE_PATH = __DIR__.'/..';

require_once BASE_PATH . '/vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$pdo = App\Core\Database::getInstance()->getPdo();

// ── Migrate: create tables if they don't exist ────────────────
$sql = file_get_contents(BASE_PATH . '/install/migrations/create_tables.sql');
$pdo->exec($sql);
echo "\033[32m[Migrate]\033[0m Tables ready.\n";

// ── Optionally wipe data before seeding (php seed.php --fresh) ──
if (in_array('--fresh', $argv ?? [], true)) {
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('TRUNCATE TABLE post_categories');
    $pdo->exec('TRUNCATE TABLE posts');
    $pdo->exec('TRUNCATE TABLE categories');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "\033[33m[Seeder]\033[0m Tables truncated.\n";
}

// ── Run seeders in order ──────────────────────────────────────
$seeders = [
    \Install\Seeders\CategorySeeder::class,
    \Install\Seeders\PostSeeder::class,
];

foreach ($seeders as $seederClass) {
    /** @var \Install\Seeders\Seeder $seeder */
    $seeder = new $seederClass();
    $seeder->run();
}

echo "\033[32m[Seeder]\033[0m Done.\n";
