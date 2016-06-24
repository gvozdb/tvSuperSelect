<?php

/* @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
$path = $modx->getOption('pdotools_class_path', null, MODX_CORE_PATH.'components/pdotools/model/', true);
if ($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoFetch = new $pdoClass($modx, $scriptProperties);
} else {
    return false;
}
$pdoFetch->addTime('pdoTools loaded');

if (!$modx->addPackage('tvsuperselect', MODX_CORE_PATH.'components/tvsuperselect/model/')) {
    return false;
}

// Получаем параметры
$id = $scriptProperties['id'] ?: 0;
$tv = $scriptProperties['tv'] ?: 0;
$pageId = $scriptProperties['pageId'] ?: 0;
$tpl = $scriptProperties['tpl'] ?: '@INLINE <a href="[[+link]]">[[+tag]]</a>';
$tplWrapper = $scriptProperties['tplWrapper'] ?: '@INLINE [[+output]]';
$outputSeparator = isset($scriptProperties['outputSeparator']) ? $scriptProperties['outputSeparator'] : ', ';
$toPlaceholder = $scriptProperties['toPlaceholder'] ?: false;
$scheme = $scriptProperties['scheme'] ?: '-1';
if (!$id || !$tv || !$tpl) {
    return false;
}

// Выборка тегов
$q = $modx->newQuery('tvssOption', array(
    'resource_id' => $id,
    'tv_id' => $tv,
));
$q->select('value as tag');
// $q->prepare(); print_r($q->toSQL());

$output = '';
$items = array();
if ($q->prepare() && $q->stmt->execute()) {
    if ($rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
        foreach ($rows as $row) {
            $row['tagLink'] = urlencode($row['tag']);
            $row['link'] = $pageId
            ? $modx->makeUrl($pageId, '', array('tag' => $row['tagLink']), $scheme)
            : '';

            $items[] = $pdoFetch->getChunk($tpl, $row);
        }
    }
}

if (!empty($items)) {
    $output = $pdoFetch->getChunk($tplWrapper, array(
        'output' => implode($outputSeparator, $items),
    ));
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
} else {
    return $output;
}
