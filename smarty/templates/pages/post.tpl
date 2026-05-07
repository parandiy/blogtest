{extends file='layout.tpl'}

{block name="content"}

    <div class="post-full">

        <div class="page-header__breadcrumb" style="margin-bottom:1.5rem;">
            <a href="{$APP_URL}/">Главная</a>
            {if $categories}
                <span>/</span>
                <a href="{$APP_URL}/category/{$categories[0].slug}">{$categories[0].name|escape}</a>
            {/if}
            <span>/</span>
            {$post.title|escape|truncate:40:'...'}
        </div>

        {if $post.image}
            <img src="{$APP_URL}/{$post.image}" alt="{$post.title|escape}" class="post-full__cover">
        {/if}

        <div class="post-full__meta">
            <span>{$post.published_at|date_format:'%B %d, %Y'}</span>
            <span>{$post.views|number_format:0:'.':' '} просмотров</span>
        </div>

        <h1 class="post-full__title">{$post.title|escape}</h1>

        {if $categories}
            <div class="post-full__tags">
                {foreach $categories as $cat}
                    <a href="{$APP_URL}/category/{$cat.slug}" class="post-full__tag">{$cat.name|escape}</a>
                {/foreach}
            </div>
        {/if}

        {if $post.description}
            <p class="post-full__excerpt">{$post.description|escape}</p>
        {/if}

        <div class="post-full__body">{$post.body}</div>

    </div>

    {if $related}
        <div class="related">
            <div class="related__title">Похожие статьи</div>
            <div class="posts-grid">
                {foreach $related as $relPost}
                    {include file='partials/post_card.tpl' post=$relPost}
                {/foreach}
            </div>
        </div>
    {/if}

{/block}
