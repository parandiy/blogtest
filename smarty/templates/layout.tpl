<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$pageTitle|default:'Blog'} — {$APP_NAME}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{$APP_URL}/assets/css/main.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="{$APP_URL}/" class="navbar__brand">{$APP_NAME}</a>
        <div class="navbar__nav">
            <a href="{$APP_URL}/">Главная</a>
        </div>
    </div>
</nav>

<main>
    <div class="container">
        {block name="content"}{/block}
    </div>
</main>

<footer class="footer">
    Copyright &copy;{$smarty.now|date_format:'%Y'}. All Rights Reserved.
</footer>

</body>
</html>
