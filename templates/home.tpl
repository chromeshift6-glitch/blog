{extends file='layout.tpl'}

{block name='content'}
    <header class="page-hero">
        <span class="eyebrow">Практика веб-разработки</span>
        <h1>Блог о современной разработке</h1>
        <p>Статьи о PHP, базах данных, клиентской разработке и инфраструктуре.</p>
    </header>

    {foreach $categories as $category}
        <section class="category-section">
            <header class="section-heading">
                <div>
                    <span class="section-heading__label">Категория</span>
                    <h2>{$category.name}</h2>
                    <p>{$category.description}</p>
                </div>
                <a class="button button--secondary section-heading__action" href="/category/{$category.id}">
                    Все статьи
                </a>
            </header>

            <div class="article-grid">
                {foreach $category.articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <div class="empty-state">Статей пока нет.</div>
    {/foreach}
{/block}
