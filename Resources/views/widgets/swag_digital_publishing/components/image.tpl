{extends file='parent:widgets/swag_digital_publishing/components/image.tpl'}

{block name='widgets_digital_publishing_components_image_element'}
    {$style = "padding: {$element.paddingTop / 16}rem {$element.paddingRight / 16}rem {$element.paddingBottom / 16}rem {$element.paddingLeft / 16}rem;"}
    {$style = "{$style} text-align: {$element.orientation};"}

    {$wrapperStyle = "max-width: {$element.maxWidth / 16}rem;"}
    {$imageStyle = "max-height: {$element.maxHeight / 16}rem;"}

    {$src = $element.media.source}

    {if $element.media.thumbnails}
        {foreach $element.media.thumbnails as $thumbnail}
            {if $thumbnail.maxWidth >= $element.maxWidth}
                {$src = $thumbnail.sourceSet}
                {break}
            {/if}
        {/foreach}
    {/if}
    <div class="dig-pub--image" style="{$style}">
        <div class="dig-pub--img-wrapper" style="{$wrapperStyle}">
            <picture>
                {if $thumbnail.webp}
                    <source srcset="{$thumbnail.webp.sourceSet}" type="image/webp">
                {/if}
                <img srcset="{$src}"
                     class="dig-pub--img{if $element.class} {$element.class}{/if}"
                     style="{$imageStyle}"
                        {if $element.alt} alt="{$element.alt}"{/if} />
            </picture>
        </div>
    </div>
{/block}
