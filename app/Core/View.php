<?php

declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

class View
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();

        $this->smarty->setTemplateDir(BASE_PATH . '/smarty/templates');
        $this->smarty->setCompileDir(BASE_PATH  . '/smarty/templates_c');
        $this->smarty->setCacheDir(BASE_PATH     . '/smarty/cache');

        $caching = (bool) ($_ENV['SMARTY_CACHING'] ?? false);
        $this->smarty->setCaching($caching ? Smarty::CACHING_LIFETIME_CURRENT : Smarty::CACHING_OFF);
        $this->smarty->setCacheLifetime((int) ($_ENV['SMARTY_CACHE_LIFETIME'] ?? 3600));

        $this->smarty->assign('APP_URL', rtrim($_ENV['APP_URL'] ?? '', '/'));
        $this->smarty->assign('APP_NAME', $_ENV['APP_NAME'] ?? 'Blog');
    }

    public function assign(string|array $key, mixed $value = null): void
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->smarty->assign($k, $v);
            }
        } else {
            $this->smarty->assign($key, $value);
        }
    }

    public function render(string $template): void
    {
        $this->smarty->display($template);
    }
}
