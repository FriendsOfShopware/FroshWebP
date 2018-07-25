{extends file="parent:frontend/detail/config_variant.tpl"}

{block name='frontend_detail_configurator_variant_group_option_label_image'}
    <span class="image--element">
<span class="image--media">
{if isset($media.thumbnails)}
    {if isset($media.thumbnails[0].webp)}
        <source srcset="{$media.thumbnails[0].webp.sourceSet}" type="image/webp">
    {/if}
    <img srcset="{$media.thumbnails[0].sourceSet}" alt="{$option.optionname}"/>

{else}
    <img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$option.optionname}">
{/if}
</span>
</span>
{/block}