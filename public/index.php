<?php

use App\Core\App;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

(Dotenv\Dotenv::createImmutable(BASE_PATH))->load();

(new App())->run();
