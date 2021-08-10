<?php

/**
 * HumanPresence.FormIt.Hook
 */
$debug = $modx->getOption('humanpresenceDebug', $hook->formit->config, false);
$corePath = $modx->getOption('humanpresence.core_path', null, $modx->getOption('core_path') . 'components/humanpresence/');
$humanpresence = $modx->getService(
    'humanpresence',
    'HumanPresence',
    $corePath . 'model/humanpresence/',
    array('core_path' => $corePath)
);

if (!($humanpresence instanceof HumanPresence)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[HumanPresence.FormIt.Hook] Could not load humanpresence class.');
    if ($debug) {
        $hook->addError('humanpresence', 'Could not load HumanPresence class.');
        return false;
    } else {
        return true;
    }
}

if ($humanpresence->checkHumanPresence()) {
    return true;
}
$hook->addError('humanpresence', 'Unable to determine human presence level.');
return false;