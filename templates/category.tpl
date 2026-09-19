{extends file='layout.tpl'}

{block name='content'}
    <header class="page-heading">
        <a class="back-link" href="/">← Все категории</a>
        <span class="eyebrow">Категория</span>
        <h1>{$category.name}</h1>
        <p>{$category.description}</p>
    </header>

    <nav class="toolbar" aria-label="Сортировка статей">
        <span class="toolbar__label">Сортировать:</span>
        <a class="filter-link{if $sort === 'date'} filter-link--active{/if}"
           href="/category/{$category.id}?sort=date">По дате</a>
        <a class="filter-link{if $sort === 'views'} filter-link--active{/if}"
           href="/category/{$category.id}?sort=views">По просмотрам</a>
    </nav>

    <div class="article-grid">
        {foreach $articles as $article}
            {include file='partials/article-card.tpl' article=$article}
        {foreachelse}
            <div class="empty-state">В этой категории пока нет статей.</div>
        {/foreach}
    </div>

    {if $pagination.total_pages > 1}
        <nav class="pagination" aria-label="Пагинация">
            {for $pageNumber=1 to $pagination.total_pages}
                {if $pageNumber === $pagination.current_page}
                    <strong class="pagination__item pagination__item--active" aria-current="page">
                        {$pageNumber}
                    </strong>
                {else}
                    <a class="pagination__item"
                       href="/category/{$category.id}?sort={$sort}&page={$pageNumber}">{$pageNumber}</a>
                {/if}
            {/for}
        </nav>
    {/if}
{/block}
