{extends file="parent:frontend/detail/image.tpl"}

{block name='frontend_detail_image_default_image_element'}
    {$alt = $sArticle.articleName|escape}

    {if $sArticle.image.description}
        {$alt = $sArticle.image.description|escape}
    {/if}

    <span class="image--element"
          {if $sArticle.image}
              data-img-large="{$sArticle.image.thumbnails[2].source}"
              data-img-small="{$sArticle.image.thumbnails[0].source}"
              data-img-original="{$sArticle.image.source}"
              {if $sArticle.image.thumbnails[2].attributes.webp}
                  data-img-webp-original="{$sArticle.image.attributes.webp->get('image')}"
              {/if}
          {/if}
           data-alt="{$alt}">

        {block name='frontend_detail_image_default_image_media'}
            <span class="image--media">
                {if isset($sArticle.image.thumbnails)}
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
                {else}
                    {block name='frontend_detail_image_fallback'}
                    <img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$alt}" itemprop="image" />
                {/block}
                {/if}
            </span>
        {/block}
    </span>
{/block}

{block name='frontend_detail_images_picture_element'}
    <picture>
        {if isset($image.thumbnails[1].webp)}
            <source srcset="{$image.thumbnails[1].webp.sourceSet}" type="image/webp">
        {/if}
        <img srcset="{$image.thumbnails[1].sourceSet}" alt="{$alt}" itemprop="image" />
    </picture>
{/block}