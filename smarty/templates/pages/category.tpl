{extends file='layout.tpl'}

{block name="content"}

    <div class="page-header">
        <div class="page-header__breadcrumb">
            <a href="{$APP_URL}/">Главная</a>
            <span>/</span>
            {$category.name|escape}
        </div>
        <h1 class="page-header__title">{$category.name|escape}</h1>
        {if $category.description}
            <p class="page-header__description">{$category.description|escape}</p>
        {/if}
    </div>

    <div class="sort-bar">
        <span class="sort-bar__label">Сортировка:</span>
        <a href="{$APP_URL}/category/{$category.slug}?sort=date"
           class="sort-bar__btn {if $sortBy == 'date'}sort-bar__btn--active{/if}">По дате</a>
        <a href="{$APP_URL}/category/{$category.slug}?sort=views"
           class="sort-bar__btn {if $sortBy == 'views'}sort-bar__btn--active{/if}">По просмотрам</a>
    </div>

    {if $posts}
        <div class="posts-grid">
            {foreach $posts as $post}
                {include file='partials/post_card.tpl' post=$post}
            {/foreach}
        </div>

        {assign var="baseUrl" value="{$APP_URL}/category/{$category.slug}?sort={$sortBy}"}
        {include file='partials/pagination.tpl' pagination=$pagination baseUrl=$baseUrl}
    {else}
        <p style="color:#888;padding:2rem 0;">В этой категории пока нет статей.</p>
    {/if}

{/block}
