<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$pageTitle|default:'Блог'}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header class="site-header">
        <nav class="container site-nav" aria-label="Основная навигация">
            <a class="brand" href="/" aria-label="Блог — главная страница">
                <span class="brand__mark" aria-hidden="true">&lt;/&gt;</span>
                <span>PHP Blog</span>
            </a>
            <a class="site-nav__link" href="/">Все категории</a>
        </nav>
    </header>

    <main class="container site-main">
        {block name='content'}{/block}
    </main>

    <footer class="site-footer">
        <div class="container site-footer__content">
            <span>PHP Blog</span>
            <span>PHP · MySQL · Smarty</span>
        </div>
    </footer>
</body>
</html>
