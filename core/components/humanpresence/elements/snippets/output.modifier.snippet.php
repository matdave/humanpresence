<?php

/**
 * humanpresence Output Modifier
 */
if(empty($input)) return;
$corePath = $modx->getOption('humanpresence.core_path', null, $modx->getOption('core_path') . 'components/humanpresence/');
$humanpresence = $modx->getService(
    'humanpresence',
    'HumanPresence',
    $corePath . 'model/humanpresence/',
    array('core_path' => $corePath)
);
$false = $options ?? "<!-- -->";

if (!($humanpresence instanceof HumanPresence)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[HumanPresence.Output.Modifier] Could not load humanpresence class.');
    return $false;
}

$presence = $humanpresence->checkHumanPresence();
if ($presence) {
    return $input;
}
return $false;
