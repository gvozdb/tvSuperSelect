<?php

$properties = array();

$tmp = array(
    'tag' => array(
        'type' => 'textfield',
        'value' => '',
    ),
    'tags' => array(
        'type' => 'textfield',
        'value' => '',
    ),
    'tv' => array(
        'type' => 'textfield',
        'value' => '',
    ),
    'tvs' => array(
        'type' => 'textfield',
        'value' => '',
    ),
    'snippet' => array(
        'type' => 'textfield',
        'value' => 'getTickets',
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
