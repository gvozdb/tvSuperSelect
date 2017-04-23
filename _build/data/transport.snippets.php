<?php

$snippets = array();

$tmp = array(
    'tvssResources' => array(
        'file' => 'tvssResources',
        'description' => '',
    ),
    'tvssTickets' => array(
        'file' => 'tvssTickets',
        'description' => '',
    ),
    'tvssTags' => array(
        'file' => 'tvssTags',
        'description' => '',
    ),
    'tvssCloud' => array(
        'file' => 'tvssCloud',
        'description' => '',
    ),
);

foreach ($tmp as $k => $v) {
    /* @avr modSnippet $snippet */
    $snippet = $modx->newObject('modSnippet');
    $snippet->fromArray(array(
        'id' => 0,
        'name' => $k,
        'description' => @$v['description'],
        'snippet' => getSnippetContent($sources['source_core'] . '/elements/snippets/snippet.' . $v['file'] . '.php'),
        'static' => BUILD_SNIPPET_STATIC,
        'source' => 1,
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/snippets/snippet.' . $v['file'] . '.php',
    ), '', true, true);

    $properties = include $sources['build'] . 'properties/properties.' . $v['file'] . '.php';
    $snippet->setProperties($properties);

    $snippets[] = $snippet;
}

unset($tmp, $properties);

return $snippets;
