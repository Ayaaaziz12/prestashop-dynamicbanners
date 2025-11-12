{if isset($dynamic_banners) && $dynamic_banners}
<div class="dynamic-banners-cart">
    {foreach from=$dynamic_banners item=banner}
    <div class="dynamic-banner-cart-item">
        {if $banner.link}<a href="{$banner.link}" title="{$banner.title}">{/if}
            <img src="{_MODULE_DIR_}dynamicbanners/img/{$banner.image}" alt="{$banner.title}" class="img-responsive" />
        {if $banner.link}</a>{/if}
    </div>
    {/foreach}
</div>
{/if}