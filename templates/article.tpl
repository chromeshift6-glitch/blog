{extends file='layout.tpl'}

{block name='content'}
    <article>
        <img src="{$article.image}" alt="{$article.name}">
        <h1>{$article.name}</h1>
        <p>{$article.description}</p>

        <p>
            Опубликовано: <time datetime="{$article.created_at}">{$article.created_at}</time>
            · Просмотров: {$article.view_count}
        </p>

        <nav aria-label="Категории статьи">
            {foreach $article.categories as $category}
                <a href="/category/{$category.id}">{$category.name}</a>
            {/foreach}
        </nav>

        <p>{$article.text}</p>
    </article>

    {if $similarArticles}
        <section>
            <h2>Похожие статьи</h2>
            <div>
                {foreach $similarArticles as $similarArticle}
                    {include file='partials/article-card.tpl' article=$similarArticle}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
