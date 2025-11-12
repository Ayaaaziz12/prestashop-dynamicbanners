{if isset($banners) && $banners}
<div class="dynamic-banners">
    {foreach from=$banners item=banner}
        <div class="banner-item" style="margin:10px 0;">
            <a href="{$banner.link}" target="_blank">
                <img src="{$banner_path}{$banner.image}" alt="{$banner.title}" class="img-fluid" />
            </a>
            <p>{$banner.title}</p>
        </div>
    {/foreach}
</div>
{else}
<p>Aucune bannière trouvée.</p>
{/if}
