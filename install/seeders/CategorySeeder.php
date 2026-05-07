<?php

declare(strict_types=1);

namespace Install\Seeders;

class CategorySeeder extends Seeder
{
    private const CATEGORIES = [
        [
            'name'        => 'Технологии',
            'description' => 'Новости и тренды мира IT: искусственный интеллект, разработка, облачные сервисы и многое другое.',
        ],
        [
            'name'        => 'Наука',
            'description' => 'Открытия, исследования и всё, что расширяет границы человеческих знаний.',
        ],
        [
            'name'        => 'Дизайн',
            'description' => 'UI/UX, графика, типографика и всё об эстетике цифровых продуктов.',
        ],
        [
            'name'        => 'Бизнес',
            'description' => 'Стартапы, менеджмент, маркетинг и предпринимательство.',
        ],
        [
            'name'        => 'Здоровье',
            'description' => 'Советы по физическому и ментальному здоровью, медицинские новости.',
        ],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $data) {
            $slug = slugify($data['name']);

            // Upsert — skip if slug already exists
            $existing = $this->db->fetchOne(
                'SELECT id FROM categories WHERE slug = ?',
                [$slug]
            );

            if ($existing) {
                continue;
            }

            $this->db->execute(
                'INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)',
                [$data['name'], $slug, $data['description']]
            );
        }

        $this->info('Categories seeded (' . count(self::CATEGORIES) . ')');
    }
}
