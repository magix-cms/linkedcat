{strip}
    {if isset($row)}
        {$linkedcat = $row}
    {/if}
    {capture name="content"}
        {if $sortable}<td class="sort-handle"><span class="fas fa-arrows-alt-v"></span></td>{/if}
        <td>{$linkedcat.id_linked}</td>
        <td>{$linkedcat.name_cat}</td>
    {/capture}
{/strip}
{include file="loop/cat-rows.tpl" controller="linkedcat" sub="category" content=$smarty.capture.content idc=$id id=$linkedcat.id_linked editableRow=false}