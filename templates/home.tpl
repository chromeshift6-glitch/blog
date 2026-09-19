{extends file='layout.tpl'}

{block name='content'}
    <h1>Блог</h1>

    {foreach $categories as $category}
        <section>
            <h2>{$category.name}</h2>
            <p>{$category.description}</p>

            <div>
                {foreach $category.articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>

            <a href="/category/{$category.id}">Все статьи</a>
        </section>
    {foreachelse}
        <p>Статей пока нет.</p>
    {/foreach}
{/block}
