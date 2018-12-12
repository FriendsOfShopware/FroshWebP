{extends file="parent:frontend/index/index.tpl"}

{block name="frontend_index_header_javascript_data"}
    {$smarty.block.parent}

    {if $sArticle && $lastSeenProductsConfig.currentArticle.articleId}
        {foreach $sArticle.image.thumbnails as $key => $image}
            {$lastSeenProductsConfig.currentArticle.images[$key] = [
            'source' => $image.source,
            'sourceWebP' => $image.webp.source,
            'retinaSource' => $image.retinaSource,
            'retinaSourceWebP' => $image.webp.retinaSource,
            'sourceSet' => $image.sourceSet,
            'sourceSetWebP' => $image.webp.sourceSet
            ]}
        {/foreach}
    {/if}
{/block}
