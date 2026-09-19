<article class="article-card">
    <a class="article-card__image-link" href="/article/{$article.id}" tabindex="-1" aria-hidden="true">
        <img class="article-card__image" src="{$article.image}" alt="">
    </a>
    <div class="article-card__body">
        <h3><a href="/article/{$article.id}">{$article.name}</a></h3>
        <p class="article-card__description">{$article.description}</p>
        <p class="article-meta article-card__meta">
            <time datetime="{$article.created_at}">{$article.created_at}</time>
            <span>{$article.view_count} просмотров</span>
        </p>
    </div>
</article>
