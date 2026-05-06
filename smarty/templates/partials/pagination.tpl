{if $pagination.lastPage > 1}
<nav class="pagination">

    <a href="{$baseUrl}&page={$pagination.currentPage - 1}"
       class="pagination__btn {if $pagination.currentPage <= 1}pagination__btn--disabled{/if}">&laquo;</a>

    {if $pagination.start > 1}
        <a href="{$baseUrl}&page=1" class="pagination__btn">1</a>
        {if $pagination.start > 2}<span class="pagination__ellipsis">&hellip;</span>{/if}
    {/if}
    {for $p=$pagination.start to $pagination.end}
        <a href="{$baseUrl}&page={$p}"
           class="pagination__btn {if $p == $pagination.currentPage}pagination__btn--active{/if}">{$p}</a>
    {/for}

    {if $pagination.end < $pagination.lastPage}
        {if $pagination.end < $pagination.lastPage - 1}<span class="pagination__ellipsis">&hellip;</span>{/if}
        <a href="{$baseUrl}&page={$pagination.lastPage}" class="pagination__btn">{$pagination.lastPage}</a>
    {/if}

    <a href="{$baseUrl}&page={$pagination.currentPage + 1}"
       class="pagination__btn {if $pagination.currentPage >= $pagination.lastPage}pagination__btn--disabled{/if}">&raquo;</a>

</nav>
{/if}
