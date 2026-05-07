<?php

declare(strict_types=1);

namespace App;

use App\Core\View;
use App\Service\BlogService;

class Controller
{
    private const PER_PAGE = 6;
    private const SORT_ALLOWED = ['date', 'views'];

    private BlogService $blogService;

    public function __construct()
    {
        $this->blogService = new BlogService();
    }

    public function home(): void
    {
        $categories = $this->blogService->getActiveWithLatestPosts();

        $view = new View();
        $view->assign('categories', $categories);
        $view->assign('pageTitle', 'Главная');
        $view->render('pages/index.tpl');
    }

    public function category(string $slug): void
    {
        $sortBy = in_array($_GET['sort'] ?? '', self::SORT_ALLOWED, true)
            ? $_GET['sort']
            : 'date';

        $category = $this->blogService->findCategoryBySlug($slug);

        if (!$category) {
            $this->notFound();
            return;
        }

        $total = $this->blogService->countPosts((int)$category['id']);

        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $lastPage = max(1, (int)ceil($total / self::PER_PAGE));
        $currentPage = min($currentPage, $lastPage);
        $offset = ($currentPage - 1) * self::PER_PAGE;
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);

        $posts = $this->blogService->getPosts(
            categoryId: (int)$category['id'],
            sortBy: $sortBy,
            limit: self::PER_PAGE,
            offset: $offset,
        );

        $view = new View();
        $view->assign([
            'category' => $category,
            'posts' => $posts,
            'sortBy' => $sortBy,
            'pagination' => [
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'start' => $start,
                'end' => $end,
                'total' => $total,
                'perPage' => self::PER_PAGE,
                'offset' => $offset

            ],
            'pageTitle' => $category['name'],
        ]);
        $view->render('pages/category.tpl');
    }

    public function post(string $slug): void
    {
        $post = $this->blogService->findPostBySlug($slug);

        if (!$post) {
            $this->notFound();
            return;
        }

        $related = $this->blogService->getRelatedPosts($post['id']);

        $view = new View();
        $view->assign([
            'post' => $post,
            'related' => $related,
        ]);

        $view->render('pages/post.tpl');
    }

    public function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        $view->render('pages/404.tpl');
    }
}
