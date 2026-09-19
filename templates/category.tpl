{extends file='layout.tpl'}

{block name='content'}
    <h1>{$category.name}</h1>
    <p>{$category.description}</p>

    <nav aria-label="Сортировка статей">
        Сортировать:
        <a href="/category/{$category.id}?sort=date">по дате</a>
        <a href="/category/{$category.id}?sort=views">по просмотрам</a>
    </nav>

    <div>
        {foreach $articles as $article}
            {include file='partials/article-card.tpl' article=$article}
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {if $pagination.total_pages > 1}
        <nav aria-label="Пагинация">
            {for $pageNumber=1 to $pagination.total_pages}
                {if $pageNumber === $pagination.current_page}
                    <strong aria-current="page">{$pageNumber}</strong>
                {else}
                    <a href="/category/{$category.id}?sort={$sort}&page={$pageNumber}">{$pageNumber}</a>
                {/if}
            {/for}
        </nav>
    {/if}
{/block}
