<?php

declare(strict_types=1);

namespace App\Core;

use App\Controller;

class App
{
    public function run(): void
    {
        $controller = new Controller();

        $m = [];
        match (true) {
            $this->is('/')                           => $controller->home(),
            $this->is('/category/(?P<slug>[^/]+)', $m) => $controller->category($m['slug']),
            $this->is('/post/(?P<slug>[^/]+)', $m)     => $controller->post($m['slug']),
            default                                  => $controller->notFound(),
        };
    }

    private function is(string $pattern, array &$matches = []): bool
    {
        $path = $this->resolvePath();

        if ($pattern === '/') {
            return $path === '/';
        }

        return (bool) preg_match('#^' . $pattern . '$#', $path, $matches);
    }

    private function resolvePath(): string
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return '/' . ltrim(substr($uri, strlen($base)), '/');
    }
}
