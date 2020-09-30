{extends file="parent:frontend/listing/product-box/box-big-image.tpl"}


{block name='frontend_listing_box_article_image_picture_element'}
    <picture>
        {if isset($sArticle.image.thumbnails[1].webp)}
            <source srcset="{$sArticle.image.thumbnails[1].webp.sourceSet}" type="image/webp">
        {/if}
        <img loading="lazy" srcset="{$sArticle.image.thumbnails[1].sourceSet}"
             alt="{$desc}"
             title="{$desc|truncate:160}"/>
    </picture>
{/block}
