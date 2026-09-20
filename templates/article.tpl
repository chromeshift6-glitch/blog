{extends file='layout.tpl'}

{block name='content'}
    <article class="article-page">
        <a class="back-link" href="/">← На главную</a>

        <header class="article-page__header">
            <nav class="tag-list" aria-label="Категории статьи">
                {foreach $article.categories as $category}
                    <a class="tag" href="/category/{$category.id}">{$category.name}</a>
                {/foreach}
            </nav>

            <h1>{$article.name}</h1>
            <p class="article-page__lead">{$article.description}</p>

            <p class="article-meta">
                <span>Опубликовано <time datetime="{$article.created_at}">{$article.created_at|format_date}</time></span>
                <span>{$article.view_count|views_label}</span>
            </p>
        </header>

        <img class="article-page__image" src="{$article.image}" alt="{$article.name}">

        <div class="article-page__content">
            <p>{$article.text}</p>
        </div>
    </article>

    {if $similarArticles}
        <section class="related-section">
            <header class="section-heading">
                <div>
                    <span class="section-heading__label">Читайте дальше</span>
                    <h2>Похожие статьи</h2>
                </div>
            </header>
            <div class="article-grid">
                {foreach $similarArticles as $similarArticle}
                    {include file='partials/article-card.tpl' article=$similarArticle}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
