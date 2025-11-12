{if isset($banners) && $banners}
<div class="dynamic-banners-footer">
    {foreach from=$banners item=banner}
    <div class="dynamic-banner-footer-item">
        {if $banner.link}<a href="{$banner.link}" title="{$banner.title}">{/if}
            <img src="{$image_path}{$banner.image}" alt="{$banner.title}" class="img-responsive" />
        {if $banner.link}</a>{/if}
    </div>
    {/foreach}
</div>
{/if}