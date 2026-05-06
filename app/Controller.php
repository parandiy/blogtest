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
        $categories = [
            [
                'name' => 'Test 1',
                'slug' => 'test-1',
                'posts' => [
                [
                    'slug' => 'test-1-post-1',
                    'title' => 'Test 1 Post 1',
                    'image' => 'https://placehold.co/150',
                    'published_at' => '2023-01-01 00:00:00',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                ],
                    [
                        'slug' => 'test-1-post-1',
                        'title' => 'Test 1 Post 1',
                        'image' => 'https://placehold.co/150',
                        'published_at' => '2023-01-01 00:00:00',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                    ],
                    [
                        'slug' => 'test-1-post-1',
                        'title' => 'Test 1 Post 1',
                        'image' => 'https://placehold.co/150',
                        'published_at' => '2023-01-01 00:00:00',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                    ]
                ],
            ],
            [
                'name' => 'Test 2',
                'slug' => 'test-2',
                'posts' => [
                    [
                        'slug' => 'test-1-post-1',
                        'title' => 'Test 1 Post 1',
                        'image' => 'https://placehold.co/150',
                        'published_at' => '2023-01-01 00:00:00',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                    ],
                    [
                        'slug' => 'test-1-post-1',
                        'title' => 'Test 1 Post 1',
                        'image' => 'https://placehold.co/150',
                        'published_at' => '2023-01-01 00:00:00',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                    ],
                    [
                        'slug' => 'test-1-post-1',
                        'title' => 'Test 1 Post 1',
                        'image' => 'https://placehold.co/150',
                        'published_at' => '2023-01-01 00:00:00',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                    ]
                ],
            ]
        ];

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

        $category = [
            'name' => 'Test 1',
            'slug' => 'test-1',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
        ];

        $posts = [
            [
                'slug' => 'test-1-post-1',
                'title' => 'Test 1 Post 1',
                'image' => 'https://placehold.co/150',
                'published_at' => '2023-01-01 00:00:00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
            ],
            [
                'slug' => 'test-1-post-1',
                'title' => 'Test 1 Post 1',
                'image' => 'https://placehold.co/150',
                'published_at' => '2023-01-01 00:00:00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
            ],
            [
                'slug' => 'test-1-post-1',
                'title' => 'Test 1 Post 1',
                'image' => 'https://placehold.co/150',
                'published_at' => '2023-01-01 00:00:00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
            ]
        ];

        $total = 18;
        $perPage = 4;

        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $lastPage    = max(1, (int) ceil($total / $perPage));
        $currentPage = min($currentPage, $lastPage);
        $offset      = ($currentPage - 1) * $perPage;
        $start = max(1, $currentPage - 2);
        $end   = min($lastPage, $currentPage + 2);

        echo $end;

        $view = new View();
        $view->assign([
            'category'   => $category,
            'posts'      => $posts,
            'sortBy'     => $sortBy,
            'pagination' => compact('currentPage', 'perPage', 'total', 'lastPage', 'offset', 'start', 'end'),
            'pageTitle'  => $category['name'],
        ]);
        $view->render('pages/category.tpl');
    }

    public function post(string $slug): void
    {
        $post = [
            'slug' => 'test-1-post-1',
            'title' => 'Test 1 Post 1',
            'image' => 'https://placehold.co/150',
            'published_at' => '2023-01-01 00:00:00',
            'views' => 100,
            'body' => 'fdd',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
        ];

        $view = new View();
        $view->assign('post', $post);
        $view->render('pages/post.tpl');
    }

    public function notFound(): void
    {
        http_response_code(404);
        $view = new View();
        $view->render('pages/404.tpl');
    }
}
