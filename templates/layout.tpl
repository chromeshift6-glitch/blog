<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$pageTitle|default:'Блог'}</title>
</head>
<body>
    <header>
        <nav aria-label="Основная навигация">
            <a href="/">Блог</a>
        </nav>
    </header>

    <main>
        {block name='content'}{/block}
    </main>
</body>
</html>
