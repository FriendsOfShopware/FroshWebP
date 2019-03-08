{extends file="parent:frontend/index/logo-container.tpl"}

{block name='frontend_index_logo'}
    <div class="logo--shop block">
        <a class="logo--link" href="{url controller='index'}" title="{"{config name=shopName}"|escapeHtml} - {"{s name='IndexLinkDefault' namespace="frontend/index/index"}{/s}"|escape}">
            <picture>
                {if $theme.desktopLogoWebp}
                    <source srcset="{link file=$theme.desktopLogoWebp}" type="image/webp" media="(min-width: 78.75em)">
                {/if}

                <source srcset="{link file=$theme.desktopLogo}" media="(min-width: 78.75em)">

                {if $theme.tabletLandscapeLogoWebp}
                    <source srcset="{link file=$theme.tabletLandscapeLogoWebp}" type="image/webp" media="(min-width: 64em)">
                {/if}

                <source srcset="{link file=$theme.tabletLandscapeLogo}" media="(min-width: 64em)">

                {if $theme.tabletLogoWebp}
                    <source srcset="{link file=$theme.tabletLogoWebp}" type="image/webp" media="(min-width: 48em)">
                {/if}

                <source srcset="{link file=$theme.tabletLogo}" media="(min-width: 48em)">

                {if $theme.mobileLogoWebp}
                    <source srcset="{link file=$theme.mobileLogoWebp}" type="image/webp">
                {/if}

                <img srcset="{link file=$theme.mobileLogo}" alt="{"{config name=shopName}"|escapeHtml} - {"{s name='IndexLinkDefault' namespace="frontend/index/index"}{/s}"|escape}" />
            </picture>
        </a>
    </div>
{/block}