{extends file="parent:frontend/detail/image.tpl"}

{block name='frontend_detail_images_picture_element'}
    <picture>
        {if isset($image.thumbnails[1].webp)}
            <source srcset="{$image.thumbnails[1].webp.sourceSet}" type="image/webp">
        {/if}
        <img srcset="{$image.thumbnails[1].sourceSet}" alt="{$alt}" itemprop="image" />
    </picture>
{/block}

{block name='frontend_detail_image_default_picture_element'}
    <picture>
        {if isset($sArticle.image.thumbnails[1].webp)}
            <source srcset="{$sArticle.image.thumbnails[1].webp.sourceSet}" type="image/webp">
        {/if}
        <img srcset="{$sArticle.image.thumbnails[1].sourceSet}"
             src="{$sArticle.image.thumbnails[1].source}"
             alt="{$alt}"
             itemprop="image" />
    </picture>
{/block}