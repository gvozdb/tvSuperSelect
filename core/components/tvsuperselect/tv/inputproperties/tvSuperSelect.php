<?php
/**
 * @package    modx
 * @subpackage processors.element.tv.inputproperties
 */

$corePath = $modx->getOption('core_path') . 'components/tvsuperselect/';

$modx->lexicon->load('tvsuperselect:tv');
$lang = $modx->lexicon->fetch('tvsuperselect_', true);
$modx->smarty->assign('tvsslex', $lang);

return $modx->controller->fetchTemplate($corePath . 'tv/inputproperties/tpl/tv.tvSuperSelect.tpl');