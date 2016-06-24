<?php

$properties = array();

$tmp = array(
    'id' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'tv' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'pageId' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'tpl' => array(
        'type' => 'textfield',
        'value' => '@INLINE <a href="[[+link]]">[[+tag]]</a>',
    ),
    'tplWrapper' => array(
        'type' => 'textfield',
        'value' => '@INLINE [[+output]]',
    ),
    'outputSeparator' => array(
        'type' => 'textfield',
        'value' => ', ',
    ),
    'toPlaceholder' => array(
        'type' => 'combo-boolean',
        'value' => false,
    ),
    'scheme' => array(
        'type' => 'textfield',
        'value' => '-1',
    ),
);

foreach ($tmp as $k => $v) {
    $properties[] = array_merge(
        array(
            'name' => $k,
            'desc' => PKG_NAME_LOWER.'_prop_'.$k,
            'lexicon' => PKG_NAME_LOWER.':properties',
        ), $v
    );
}

return $properties;
