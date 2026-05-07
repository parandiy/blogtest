# PHP Blog

Простой блог на чистом PHP 8.1+ с категориями и статьями. Без фреймворков.

**Стек:** PHP 8.1+ · MySQL · Smarty · SCSS

---

## Требования

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- Composer
- Node.js + npm _(опционально, только для компиляции SCSS)_
- Apache с `mod_rewrite`

---

## Установка

### 1. Клонировать репозиторий

```bash
git clone git@github.com:parandiy/blogtest.git
cd blog
```

### 2. Установить PHP-зависимости

```bash
composer install
```

### 3. Настроить окружение

```bash
cp .env.example .env
```

Открыть `.env` и заполнить параметры подключения к БД:

```env
APP_NAME="PHP Blog"
APP_URL=http://localhost/blog/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

> `APP_URL` — полный URL до папки `public/`. Если сайт в корне домена: `http://localhost`.

### 4. Создать базу данных

```sql
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Создать таблицы и заполнить тестовыми данными

```bash
php install/install.php
```

Открыть в браузере: [http://localhost/blog/public](http://localhost/blog/public)

---

## Компиляция SCSS

```bash
npm install
npm run sass:build
```

SCSS-файлы находятся в `public/assets/scss/`.


---

## Страницы

| URL | Описание |
|-----|----------|
| `/` | Главная — категории с 3 последними статьями |
| `/category/{slug}` | Список статей с сортировкой и пагинацией |
| `/post/{slug}` | Статья + 3 похожих статьи |

Сортировка на странице категории: `?sort=date` (по умолчанию) или `?sort=views`.
