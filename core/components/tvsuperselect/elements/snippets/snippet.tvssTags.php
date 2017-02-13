<?php
/* @var modX $modx */
/* @var pdoFetch $pdoFetch */

$sp = &$scriptProperties;

$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
$path = $modx->getOption('pdotools_class_path', null, MODX_CORE_PATH . 'components/pdotools/model/', true);
if ($pdoClass = $modx->loadClass($fqn, $path, false, true)) {
    $pdoFetch = new $pdoClass($modx, $sp);
    $pdoFetch->addTime('pdoTools loaded');
} else {
    return false;
}
if (!$modx->addPackage('tvsuperselect', MODX_CORE_PATH . 'components/tvsuperselect/model/')) {
    return false;
}

// Получаем параметры
$id = $sp['id'] ?: 0;
$tv = $sp['tv'] ?: 0;
$pageId = $sp['pageId'] ?: 0;
$tpl = $sp['tpl'] ?: '@INLINE <a href="[[+link]]">[[+tag]]</a>';
$tplWrapper = $sp['tplWrapper'] ?: '@INLINE [[+output]]';
$outputSeparator = isset($sp['outputSeparator']) ? $sp['outputSeparator'] : ', ';
$toPlaceholder = $sp['toPlaceholder'] ?: false;
$scheme = $sp['scheme'] ?: '-1';
$count = $sp['count'] ?: 0;
$sortby = $sp['sortby'] ?: 'tag';
$sortdir = $sp['sortdir'] ?: 'DESC';
$limit = $sp['limit'] ?: 10;

if (!$tv || !$tpl) {
    return false;
}

$qp = array(
    'tv_id' => $tv,
);

if ($id) {
    $qp['resource_id'] = $id;
}

// Выборка тегов
$q = $modx->newQuery('tvssOption', $qp);
if ($count) {
    $qsel = 'value as tag, COUNT(value) as count';
    $q->groupby('tag');
} else {
    $qsel = 'value as tag';
}
$q->select($qsel);
$q->sortby($sortby, $sortdir);
$q->limit($limit);
// $q->prepare(); print_r($q->toSQL());

$output = '';
$items = array();
if ($q->prepare()->execute()) {
    if ($rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
        foreach ($rows as $row) {
            $row['tagLink'] = urlencode($row['tag']);
            $row['link'] = $pageId ? $modx->makeUrl($pageId, '', array('tag' => $row['tagLink']), $scheme) : '';

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
