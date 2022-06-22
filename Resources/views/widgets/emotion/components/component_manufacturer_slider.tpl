{extends file="parent:widgets/emotion/components/component_manufacturer_slider.tpl"}

{block name="frontend_widgets_manufacturer_slider_item_image"}
    {$imgSrc = $supplier.image}
    {$imgWebpSrc = ''}

    {if $supplier.media.attributes.webp}
        {$imgWebpSrc = $supplier.media.attributes.webp->get('image')}
    {/if}

    {$imgSrcSet = ''}
    {$imgWebPSrcSet = ''}

    {if $supplier.media.thumbnails[0]}
        {$imgSrc = $supplier.media.thumbnails[0].source}
        {if $supplier.media.thumbnails[0].retinaSource}
            {$retinaSource = $supplier.media.thumbnails[0].retinaSource}
            {$imgSrcSet = "$imgSrc, $retinaSource 2x"}
        {/if}

        {if $supplier.media.thumbnails[0].webp}
            {$imgWebpSrc = $supplier.media.thumbnails[0].webp.source}

            {if $supplier.media.thumbnails[0].webp.retinaSource}
                {$retinaSource = $supplier.media.thumbnails[0].webp.retinaSource}
                {$imgWebPSrcSet = "$imgWebpSrc, $retinaSource 2x"}
            {/if}
        {/if}
    {/if}
    <picture>
        {if !empty($imgWebpSrc)}
            <source type="image/webp" src="{$imgWebpSrc}" {if !empty($imgWebPSrcSet)}srcset="{$imgWebPSrcSet}"{/if}>
        {/if}
        <img class="manufacturer--image" src="{$imgSrc}" {if !empty($imgSrcSet)}srcset="{$imgSrcSet}" {/if}alt="{$supplier.name|escape}" />
    </picture>
{/block}
