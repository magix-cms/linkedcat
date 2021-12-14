<?php
function smarty_function_linkedcat_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
    $collection = new plugins_linkedcat_public($modelTemplate);
    $assign = isset($params['assign']) ? $params['assign'] : 'linkedcat';
    $smarty->assign($assign,$collection->getLinkedcat($params));
}