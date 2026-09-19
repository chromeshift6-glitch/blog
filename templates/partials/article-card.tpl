<article>
    <img src="{$article.image}" alt="{$article.name}">
    <h3><a href="/article/{$article.id}">{$article.name}</a></h3>
    <p>{$article.description}</p>
    <p>
        <time datetime="{$article.created_at}">{$article.created_at}</time>
        · Просмотров: {$article.view_count}
    </p>
</article>
