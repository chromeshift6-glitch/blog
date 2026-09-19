{extends file='layout.tpl'}

{block name='content'}
    <section class="error-page">
        <span class="error-page__code">404</span>
        <h1>{$message}</h1>
        <p>Возможно, страница была перемещена или адрес введён неверно.</p>
        <a class="button" href="/">Вернуться на главную</a>
    </section>
{/block}
