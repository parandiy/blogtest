<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Database;

class BlogService
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Return all categories that have at least one published post,
     * each with the 3 most recent posts eager-loaded under the key "posts".
     */
    public function getActiveWithLatestPosts(): array
    {
        // First get all categories that have posts
        $categories = $this->db->fetchAll(
            'SELECT c.id, c.name, c.slug, c.description,
                    COUNT(DISTINCT pc.post_id) AS post_count
             FROM categories c
             INNER JOIN post_categories pc ON pc.category_id = c.id
             INNER JOIN posts p ON p.id = pc.post_id
             GROUP BY c.id
             ORDER BY c.name ASC'
        );

        if (empty($categories)) {
            return [];
        }

        // Eager-load the 3 latest posts per category using a window function
        $categoryIds    = array_column($categories, 'id');
        $placeholders   = implode(',', array_fill(0, count($categoryIds), '?'));

        $latestPosts = $this->db->fetchAll(
            "SELECT * FROM (
                SELECT p.id, p.title, p.slug, p.description, p.image,
                       p.views, p.published_at, pc.category_id,
                       ROW_NUMBER() OVER (
                           PARTITION BY pc.category_id
                           ORDER BY p.published_at DESC
                       ) AS rn
                FROM posts p
                INNER JOIN post_categories pc ON pc.post_id = p.id
                WHERE pc.category_id IN ({$placeholders})
            ) ranked
            WHERE rn <= 3",
            $categoryIds
        );

        // Group posts by category_id for fast lookup
        $postsByCategory = [];
        foreach ($latestPosts as $post) {
            $postsByCategory[$post['category_id']][] = $post;
        }

        // Attach posts to their category
        foreach ($categories as &$category) {
            $category['posts'] = $postsByCategory[$category['id']] ?? [];
        }

        return $categories;
    }

    /**
     * Find a single category by its slug.
     */
    public function findCategoryBySlug(string $slug): array|false
    {
        return $this->db->fetchOne(
            'SELECT * FROM categories WHERE slug = ?',
            [$slug]
        );
    }

    /**
     * Count posts in a category (used for pagination).
     */
    public function countPosts(int $categoryId): int
    {
        $row = $this->db->fetchOne(
            'SELECT COUNT(*) AS total
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = ?',
            [$categoryId]
        );

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Return paginated posts for a category with a given sort order.
     */
    public function getPosts(int $categoryId, string $sortBy, int $limit, int $offset): array
    {
        $orderClause = match ($sortBy) {
            'views' => 'p.views DESC',
            default => 'p.published_at DESC',
        };

        return $this->db->fetchAll(
            "SELECT p.id, p.title, p.slug, p.description, p.image,
                    p.views, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = ?
             ORDER BY {$orderClause}
             LIMIT ? OFFSET ?",
            [$categoryId, $limit, $offset]
        );
    }

    /**
     * Find a post by slug and increment its view counter atomically.
     */
    public function findPostBySlug(string $slug): array|false
    {
        $post = $this->db->fetchOne(
            'SELECT * FROM posts WHERE slug = ?',
            [$slug]
        );

        if ($post) {
            $this->db->execute(
                'UPDATE posts SET views = views + 1 WHERE id = ?',
                [$post['id']]
            );
            $post['views']++;
        }

        return $post;
    }

    public function getRelatedPosts(int $postId, $limit = 3) {
        $categoryIds =  $this->db->fetchAllColumn(
            'SELECT c.id
             FROM categories c
             INNER JOIN post_categories pc ON pc.category_id = c.id
             WHERE pc.post_id = ?
             ORDER BY c.name ASC',
            [$postId]
        );

        if (empty($categoryIds)) {
            return $this->getLatestExcluding($postId, $limit);
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $related = $this->db->fetchAll(
            "SELECT p.id, p.title, p.slug, p.description, p.image,
                    p.views, p.published_at,
                    COUNT(pc.category_id) AS shared_categories
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id IN ({$placeholders})
               AND p.id != ?
             GROUP BY p.id
             ORDER BY shared_categories DESC, p.published_at DESC
             LIMIT ?",
            [...$categoryIds, $postId, $limit]
        );

        // If we have fewer results than needed, pad with latest posts
        if (count($related) < $limit) {
            $existingIds = array_column($related, 'id');
            $existingIds[] = $postId;
            $needed = $limit - count($related);

            $fallback = $this->getLatestExcluding($postId, $needed, $existingIds);
            $related  = array_merge($related, $fallback);
        }

        return $related;
    }

    /**
     * Fetch the latest posts excluding specific IDs.
     */
    private function getLatestExcluding(int $excludeId, int $limit, array $excludeIds = []): array
    {
        $excludeIds[] = $excludeId;
        $excludeIds   = array_unique($excludeIds);
        $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));

        return $this->db->fetchAll(
            "SELECT id, title, slug, description, image, views, published_at
             FROM posts
             WHERE id NOT IN ({$placeholders})
             ORDER BY published_at DESC
             LIMIT ?",
            [...$excludeIds, $limit]
        );
    }
}
