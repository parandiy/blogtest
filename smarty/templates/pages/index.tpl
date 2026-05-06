{extends file='layout.tpl'}

{block name="content"}

    {if $categories}
        {foreach $categories as $category}
            <section class="category-section">

                <div class="category-header">
                    <h2 class="category-header__title">{$category.name|escape}</h2>
                    <a href="{$APP_URL}/category/{$category.slug}" class="category-header__link">View All</a>
                </div>

                {if $category.posts}
                    <div class="posts-grid">
                        {foreach $category.posts as $post}
                            {include file='partials/post_card.tpl' post=$post}
                        {/foreach}
                    </div>
                {/if}

            </section>
        {/foreach}
    {else}
        <p style="padding:3rem 0;color:#888;">Статей пока нет. Запустите сидер.</p>
    {/if}

{/block}
