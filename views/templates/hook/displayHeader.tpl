{if isset($banners) && $banners}
<div class="dynamic-banners-header">
    {foreach from=$banners item=banner}
    <div class="dynamic-banner-header-item">
        {if $banner.link}<a href="{$banner.link}" title="{$banner.title}">{/if}
            {if $banner.image}
                <img src="{$image_path}{$banner.image}" alt="{$banner.title}" class="img-responsive" 
                     style="max-width: 100%; height: auto;" />
            {else}
                <div style="background: #ffcccc; color: #000; padding: 10px; text-align: center;">
                    ❌ IMAGE MANQUANTE: {$banner.title}
                </div>
            {/if}
        {if $banner.link}</a>{/if}
    </div>
    {/foreach}
</div>
{/if}