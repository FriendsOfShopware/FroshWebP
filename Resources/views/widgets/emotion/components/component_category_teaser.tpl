{extends file="parent:widgets/emotion/components/component_category_teaser.tpl"}

{* Category teaser lnk *}
{block name="widget_emotion_component_category_teaser_link"}
    {strip}
        {if !empty($images[0].webp.source)}
    <style type="text/css">
        .has--webp #teaser--{$Data.objectId} {
	        background-image: url('{$images[0].webp.source}');
        }

        {if !empty($images[0].webp.retinaSource)}
        @media screen and (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
	        .has--webp #teaser--{$Data.objectId} {
	            background-image: url('{$images[0].webp.retinaSource}');
	        }
        }
        {/if}

        @media screen and (min-width: 48em) {
	        .has--webp #teaser--{$Data.objectId} {
	            background-image: url('{$images[1].webp.source}');
	        }
        }

        {if isset($images[1].webp.retinaSource)}
        @media screen and (min-width: 48em) and (-webkit-min-device-pixel-ratio: 2),
	       screen and (min-width: 48em) and (min-resolution: 192dpi) {
	        .has--webp #teaser--{$Data.objectId} {
	            background-image: url('{$images[1].webp.retinaSource}');
	        }
        }
        {/if}

        @media screen and (min-width: 78.75em) {
	        .is--fullscreen.has--webp #teaser--{$Data.objectId},
            .has--webp .is--fullscreen #teaser--{$Data.objectId} {
	            background-image: url('{$images[2].webp.source}');
	        }
        }

        {if isset($images[2].webp.retinaSource)}
        @media screen and (min-width: 78.75em) and (-webkit-min-device-pixel-ratio: 2),
	       screen and (min-width: 78.75em) and (min-resolution: 192dpi) {
	        .is--fullscreen.has--webp #teaser--{$Data.objectId},
            .has--webp .is--fullscreen #teaser--{$Data.objectId} {
	            background-image: url('{$images[2].webp.retinaSource}');
	        }
        }
        {/if}
    </style>
        {/if}
    {/strip}

    {$smarty.block.parent}
{/block}
