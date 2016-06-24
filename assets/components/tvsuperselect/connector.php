<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var tvSuperSelect $tvSuperSelect */
$tvSuperSelect = $modx->getService('tvsuperselect', 'tvSuperSelect', $modx->getOption('tvsuperselect_core_path', null, $modx->getOption('core_path') . 'components/tvsuperselect/') . 'model/tvsuperselect/');
$modx->lexicon->load('tvsuperselect:default');

// handle request
$corePath = $modx->getOption('tvsuperselect_core_path', null, $modx->getOption('core_path') . 'components/tvsuperselect/');
$path = $modx->getOption('processorsPath', $tvSuperSelect->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));