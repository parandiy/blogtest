<?php

declare(strict_types=1);

namespace App;

use App\Core\View;

class Controller
{
    private const PER_PAGE     = 6;
    private const SORT_ALLOWED = ['date', 'views'];

    public function home(): void
    {
        $categories = [];

        $view = new View();
        $view->assign('categories', $categories);
        $view->assign('pageTitle', 'Главная');
        $view->render('pages/index.tpl');
    }

    public function category(string $slug): void
    {
        $view = new View();
        $view->render('pages/category.tpl');
    }

    public function post(string $slug): void
    {
        $view = new View();
        $view->render('pages/post.tpl');
    }

    public function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        $view->render('pages/404.tpl');
    }
}
