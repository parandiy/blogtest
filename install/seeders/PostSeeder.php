<?php

declare(strict_types=1);

namespace Install\Seeders;

use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    private const POSTS_COUNT  = 40;
    private const IMAGES_COUNT = 12; // number of placeholder images available

    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        // Fetch all category IDs from DB
        $categories = $this->db->fetchAll('SELECT id FROM categories');
        if (empty($categories)) {
            $this->info('No categories found — run CategorySeeder first.');
            return;
        }
        $categoryIds = array_column($categories, 'id');

        for ($i = 0; $i < self::POSTS_COUNT; $i++) {
            $title = $faker->unique()->sentence(mt_rand(4, 8));
            $title = rtrim($title, '.');

            $slug = slugify($title) . '-' . $faker->unique()->numberBetween(1000, 9999);

            $description = $faker->realText(mt_rand(120, 220));
            $body        = $this->generateBody($faker);
            $image       = 'images/mock.jpg';
            $views       = $faker->numberBetween(0, 15000);
            $publishedAt = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s');

            $postId = (int) $this->db->execute(
                'INSERT INTO posts (title, slug, description, body, image, views, published_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$title, $slug, $description, $body, $image, $views, $publishedAt]
            );

            // Attach 1–2 random categories
            $assigned = (array) $faker->randomElements($categoryIds, mt_rand(1, 2));
            foreach ($assigned as $catId) {
                $this->db->execute(
                    'INSERT IGNORE INTO post_categories (post_id, category_id) VALUES (?, ?)',
                    [$postId, $catId]
                );
            }
        }

        $this->info('Posts seeded (' . self::POSTS_COUNT . ')');
    }

    /**
     * Generate a realistic multi-paragraph article body.
     */
    private function generateBody(\Faker\Generator $faker): string
    {
        $paragraphs = [];
        $count = mt_rand(4, 7);

        for ($i = 0; $i < $count; $i++) {
            $text = $faker->realText(mt_rand(300, 600));
            $paragraphs[] = "<p>{$text}</p>";
        }

        // Insert an h2 heading in the middle
        $midpoint = (int) floor($count / 2);
        $heading  = '<h2>' . rtrim($faker->sentence(mt_rand(3, 6)), '.') . '</h2>';
        array_splice($paragraphs, $midpoint, 0, [$heading]);

        return implode("\n", $paragraphs);
    }
}
