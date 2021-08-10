<?php

/**
 * HumanPresence.Plugin
 */
$corePath = $modx->getOption('humanpresence.core_path', null, $modx->getOption('core_path') . 'components/humanpresence/');
$humanpresence = $modx->getService(
    'humanpresence',
    'HumanPresence',
    $corePath . 'model/humanpresence/',
    array('core_path' => $corePath)
);

if (!($humanpresence instanceof HumanPresence)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[HumanPresence.Plugin] Could not load humanpresence class.');
    return;
}

$apikey = $humanpresence->getOption('apikey');
if(!$apikey) return;
switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        $register = "<script src='//script.metricode.com/wotjs/ellipsis.js?api_key=$apikey'></script>";
        $modx->resource->_output = preg_replace('/(<\/head>(?:<\/head>)?)/i', "{$register}\r\n$1", $modx->resource->_output);
}
return;
