{extends file="parent:widgets/emotion/components/component_banner_slider.tpl"}

{block name="frontend_widgets_banner_slider_banner_picture"}
    {if $banner.thumbnails}
        {$baseSource = $banner.thumbnails[0].source}
        {$srcSet = ''}
        {$srcSetWebP = ''}
        {$itemSize = ''}

        {foreach $element.viewports as $viewport}
            {$cols = ($viewport.endCol - $viewport.startCol) + 1}
            {$elementSize = $cols * $cellWidth}
            {$size = "{$elementSize}vw"}

            {if $breakpoints[$viewport.alias]}

                {if $viewport.alias === 'xl' && !$emotionFullscreen}
                    {$size = "calc({$elementSize / 100} * {$baseWidth}px)"}
                {/if}

                {$size = "(min-width: {$breakpoints[$viewport.alias]}) {$size}"}
            {/if}

            {$itemSize = "{$size}{if $itemSize}, {$itemSize}{/if}"}
        {/foreach}

        {foreach $banner.thumbnails as $image}
            {$srcSet = "{if $srcSet}{$srcSet}, {/if}{$image.source} {$image.maxWidth}w"}
            {$srcSetWebP = "{if $srcSetWebP}{$srcSetWebP}, {/if}{$image.webp.source} {$image.webp.maxWidth}w"}

            {if $image.retinaSource}
                {$srcSet = "{if $srcSet}{$srcSet}, {/if}{$image.retinaSource} {$image.maxWidth * 2}w"}
                {$srcSetWebP = "{if $srcSetWebP}{$srcSetWebP}, {/if}{$image.webp.retinaSource} {$image.webp.maxWidth * 2}w"}
            {/if}
        {/foreach}
    {else}
        {$baseSource = $banner.source}
    {/if}

    <picture>
        {if $srcSetWebP}
            <source srcset="{$srcSetWebP}" sizes="{$itemSize}" type="image/webp">
        {/if}
        <img src="{$baseSource}"
             class="banner-slider--image"
             {if $srcSet}sizes="{$itemSize}" srcset="{$srcSet}"{/if}
                {if $banner.altText}alt="{$banner.altText|escape}" {/if}/>
    </picture>
{/block}
