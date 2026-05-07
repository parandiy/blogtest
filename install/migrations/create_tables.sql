-- ------------------------------------------------------------
-- categories
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(150)     NOT NULL,
    `slug`        VARCHAR(160)     NOT NULL UNIQUE,
    `description` TEXT             NULL,
    `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- posts
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
    `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `title`        VARCHAR(255)     NOT NULL,
    `slug`         VARCHAR(265)     NOT NULL UNIQUE,
    `description`  VARCHAR(500)     NULL     COMMENT 'Short excerpt / meta description',
    `body`         LONGTEXT         NOT NULL,
    `image`        VARCHAR(255)     NULL     COMMENT 'Path relative to public/',
    `views`        INT UNSIGNED     NOT NULL DEFAULT 0,
    `published_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_posts_slug`         (`slug`),
    KEY `idx_posts_published_at` (`published_at`),
    KEY `idx_posts_views`        (`views`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- post_categories  (many-to-many pivot)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `post_categories` (
    `post_id`     INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`post_id`, `category_id`),
    CONSTRAINT `fk_pc_post`
        FOREIGN KEY (`post_id`)     REFERENCES `posts`      (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pc_category`
        FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
