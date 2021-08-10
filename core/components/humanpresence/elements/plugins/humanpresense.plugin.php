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
    return true;
}

$modx->regClientStartupScript('//script.metricode.com/wotjs/ellipsis.js?api_key='.$humanpresence->getOption('apikey'));