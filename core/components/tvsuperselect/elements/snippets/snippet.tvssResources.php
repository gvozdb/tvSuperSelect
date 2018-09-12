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

//
$sp['context'] = !empty($sp['context']) ? $sp['context'] : $modx->context->key;
if (!$tags = (((($sp['tag'] ?: $sp['tags']) ?: $_REQUEST['tag']) ?: $_REQUEST['tags']) ?: '')) {
    return false;
}
if (!$tvs = (($sp['tv'] ?: $sp['tvs']) ?: '')) {
    return false;
}
unset($sp['tag'], $sp['tags'], $sp['tv'], $sp['tvs']);

// Преобразуем список тегов в массив
if (is_string($tags)) {
    $tags = explode('||', $tags);
}
if (is_array($tags)) {
    $tags = array_map('trim', $tags);
    $tags = array_map('urldecode', $tags);
    $tags = array_diff($tags, array(''));
} else {
    return false;
}
if (empty($tags)) {
    return false;
}

// Преобразуем список ID ТВшек в массив
$tvs = explode(',', $tvs);

// Как делаем выборку, через LIKE или =
$like = isset($sp['like']) ? $sp['like'] : false;

// Подготавливаем параметры для выборки ресурсов с нужными тегами
$class = !empty($sp['class']) ? $sp['class'] : 'modResource';
$loadModels = array('tvsuperselect' => MODX_CORE_PATH . 'components/tvsuperselect/model/');
$select = array($class => '*');
$leftJoin = array();
$where = array(array());

foreach ($tvs as $tv) {
    $alias = 'tvss' . $tv;
    $orConditions = array();

    foreach ($tags as $i => $tag) {
        if ($like) {
            $orConditions[] = $alias . '.value LIKE "%' . addslashes($tag) . '%"';
        } else {
            $orConditions[] = $alias . '.value = "' . addslashes($tag) . '"';
        }
    }

    if (!empty($orConditions)) {
        $leftJoin += array(
            $alias => array(
                'class' => 'tvssOption',
                'alias' => $alias,
                'on' => $alias . '.resource_id = ' . $class . '.id AND (' . implode(' OR ', $orConditions) . ')',
            ),
        );
        $where[0][] = array(
            'OR:' . $alias . '.tv_id:=' => $tv,
        );
    }
}

// Приведение параметра loadModels к нужному нам виду (JSON)
if (!empty($sp['loadModels']) && !$modx->fromJSON($sp['loadModels'])) {
    $tmp_array = array_map('trim', explode(',', $sp['loadModels']));
    foreach ($tmp_array as $v) {
        $tmp[$v] = MODX_CORE_PATH . 'components/' . strtolower($v) . '/model/';
    }
    $sp['loadModels'] = $modx->toJSON($tmp);
}

// Обработка параметров указанных юзером, пересекающихся с параметрами сниппета
foreach (array('loadModels', 'where', 'select', 'leftJoin') as $v) {
    if (!empty($sp[$v])) {
        $tmp = !is_array($sp[$v]) ? $modx->fromJSON($sp[$v]) : $sp[$v];
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
        unset($tmp);
    }
    unset($sp[$v]);
}
$pdoFetch->addTime('Conditions prepared');

// Сливаем подготоваленные параметры с указанными юзером и запускаем
$pdoFetch->addTime('Query parameters ready');
$pdoFetch->setConfig(array_merge(array(
    'class' => $class,
    'loadModels' => $modx->toJSON($loadModels),
    'select' => $modx->toJSON($select),
    'leftJoin' => $modx->toJSON($leftJoin),
    'where' => $modx->toJSON($where),
    'groupby' => $class . '.id',
), $sp), false);

$rows = $pdoFetch->run();

return $rows;