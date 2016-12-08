<?php
/* @var pdoFetch $pdoFetch */
if (!$modx->loadClass('pdofetch', MODX_CORE_PATH . 'components/pdotools/model/pdotools/', false, true)) {return false;}
$pdoFetch = new pdoFetch($modx, $scriptProperties);

$config = array(
    'parents' => null,  // current resource default
    'depth' => 1,
    'requestVar' => 'tag',
    'targetID' => null,
    'tvID' => null,
    'sortby' => 'count', // count || tag
    'sortorder' => 'DESC', // DESC || ASC
    'limit' => 0,
    'showAll' => 1,
    'tpl' => '@INLINE <li><a href="{$uri}?{$requestVar}={$tag|urlencode}">{$tag} [{$count}]</a></li>', // специально оставил формирование урл здесь, вдруг кто захочет ЧПУ сделать
    'tplActive' => '@INLINE <li>{$tag} [{$count}]</li>',
    'tplAll' => '@INLINE <li><a href="{$uri}">{$_modx->lexicon("all")}</a></li>',
    'tplAllActive' => '@INLINE <li>{$_modx->lexicon("all")}</li>',
    'tplWrapper' => '@INLINE <ul>{$output}</ul>',
);
$config = array_merge($config, $scriptProperties);

$parents = empty($config['parents']) ? array($modx->resource->id) : explode(',', $config['parents']);
if ($config['depth'] != 1) {
    $childs = array();
    foreach($parents as $parent) {
        $childs = array_merge($childs, $modx->getChildIds($parent, $config['depth'], array('context' => $modx->context->key)));
    }
    $parents = array_merge($parents, $childs);
}
$target = empty($config['target']) ? $modx->resource->id : $config['target'];
$uri = $modx->makeUrl($target, '', '', $modx->getOption('link_tag_scheme'));

$requestTag = !empty($_REQUEST[$config['requestVar']]) ? urldecode($_REQUEST[$config['requestVar']]) : null;

$tvss = $modx->getService('tvsuperselect', 'tvsuperselect', $modx->getOption('core_path').'components/tvsuperselect/model/tvsuperselect/');
if ($pdo = $modx->getService('pdoFetch')) { $parser = &$pdo; } else { $parser = &$modx; }

$output = array();

$c = $modx->newQuery('tvssOption');
$c->innerJoin('modResource', 'mr', ('mr.id = tvssOption.resource_id'));
$where = array(
    'mr.published' => 1,
    'mr.deleted' => 0,
    'mr.parent:IN' => $parents,
);
if (!empty($config['tvID'])) {
    $where['tv_id'] = $config['tvID'];
}
$c->where($where);
$c->select('COUNT(*) AS `count`, `tvssOption`.`value` AS `tag`');
$c->groupby('tvssOption.value');
$c->sortby($config['sortby'], $config['sortorder']);
$c->limit($config['limit']);
if ($c->prepare() and $c->stmt->execute()) {
    $tags = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $tag['uri'] = $uri;
            $tag['requestVar'] = $config['requestVar'];
            $tpl = $tag['tag'] == $requestTag ? $config['tplActive'] : $config['tpl'];
            $output[] = $parser->getChunk($tpl, $tag);
        }
        if (!empty($config['showAll'])) {
            $tpl = empty($requestTag) ? $config['tplAllActive'] : $config['tplAll'];
            array_unshift($output, $parser->getChunk($tpl, array('uri' => $uri)));
        }
        $output = implode('', $output);
        if (!empty($config['tplWrapper'])) {
            $output = $parser->getChunk($config['tplWrapper'], array('output' => $output));
        }
    }
}

return $output;
