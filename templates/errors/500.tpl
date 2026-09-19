{extends file='layout.tpl'}

{block name='content'}
    <section class="error-page">
        <span class="error-page__code">500</span>
        <h1>{$message}</h1>
        <p>Попробуйте обновить страницу немного позже.</p>
        <a class="button" href="/">Вернуться на главную</a>
    </section>
{/block}
