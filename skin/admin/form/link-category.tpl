{extends file="catalog/{$smarty.get.controller}/edit.tpl"}
{block name="plugin:content"}
    {*<pre>{$cats|print_r}</pre>*}
    {include file="form/list-form.tpl" controller="linkedcat" sub="linkedcat" sortable=true controller_extend=true dir_controller="" data=$linkedcat id=$linkedcat.id class_form="col-ph-12 col-lg-6" class_table="col-ph-12 col-lg-6"}
    {include file="modal/delete.tpl" controller="linkedcat" data_type='linkedcat' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_pages_message#}}
{/block}
{block name="foot"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=
        libjs/vendor/jquery-ui-1.12.min.js,
        libjs/vendor/tabcomplete.min.js,
        libjs/vendor/livefilter.min.js,
        libjs/vendor/src/bootstrap-select.js,
        libjs/vendor/filterlist.min.js,
        {baseadmin}/template/js/table-form.min.js,
        plugins/linkedcat/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}

    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller);
                linkedcat.run("{$smarty.server.SCRIPT_NAME}?controller=linkedcat");
            }
        });
    </script>
{/block}