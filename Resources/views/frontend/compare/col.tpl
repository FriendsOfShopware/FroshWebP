{extends file="parent:frontend/compare/col.tpl"}

{block name="frontend_compare_article_picture"}
    <li class="list--entry entry--picture">
        {* Product image - uses the picturefill polyfill for the HTML5 "picture" element *}
        <a href="{$sArticle.linkDetails}" title="{$sArticle.articleName|escape}" class="box--image">
            <span class="image--element">
                <span class="image--media">

                    {$desc = $sArticle.articleName|escape}

                    {if isset($sArticle.image.thumbnails)}

                        {if $sArticle.image.description}
                            {$desc = $sArticle.image.description|escape}
                        {/if}
                    {if isset($sArticle.image.thumbnails[0].webp)}
                        <source srcset="{$sArticle.image.thumbnails[0].webp.sourceSet}" alt="{$desc|strip_tags|truncate:160}" type="image/webp"/>
                    {/if}
                        <img srcset="{$sArticle.image.thumbnails[0].sourceSet}"
                             alt="{$desc}"
                             title="{$desc|truncate:160}" />
                    {else}
                        <img src="{link file='frontend/_public/src/img/no-picture.jpg'}"
                             alt="{$desc}"
                             title="{$desc|truncate:160}" />
                    {/if}
                </span>
            </span>
        </a>
    </li>
{/block}