{extends file="parent:frontend/listing/product-box/product-image.tpl"}


{block name='frontend_listing_box_article_image_picture_element'}
    <picture>
        {if isset($sArticle.image.thumbnails[0].webp)}
            <source srcset="{$sArticle.image.thumbnails[0].webp.sourceSet}" type="image/webp">
        {/if}
        <img srcset="{$sArticle.image.thumbnails[0].sourceSet}"
             alt="{$desc}"
             title="{$desc|truncate:160}" />
    </picture>
{/block}
