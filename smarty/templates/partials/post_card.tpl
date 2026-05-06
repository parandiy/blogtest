<article class="post-card">
    {if $post.image}
        <a href="{$APP_URL}/post/{$post.slug}">
            <img src="{$post.image}" alt="{$post.title|escape}" class="post-card__image" loading="lazy">
        </a>
    {/if}

    <div class="post-card__title">
        <a href="{$APP_URL}/post/{$post.slug}">{$post.title|escape}</a>
    </div>

    <div class="post-card__date">{$post.published_at|date_format:'%B %d, %Y'}</div>

    {if $post.description}
        <p class="post-card__excerpt">{$post.description|escape|truncate:120:'...'}</p>
    {/if}

    <a href="{$APP_URL}/post/{$post.slug}" class="post-card__link">Continue Reading</a>
</article>
