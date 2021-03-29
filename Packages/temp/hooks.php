<?php

if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
    require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
    die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

// All our hooks.
$hooks = array(
    'integrate_pre_include' => '$sourcedir/Subs-RemoveLastEditMod.php',
    'integrate_actions' => 'rlem_actions',
    'integrate_load_permissions' => 'rlem_permissions',
);

// Installing or uninstalling?
if (!empty($context['uninstalling']))
    $action = 'remove_integration_function';
else
    $action = 'add_integration_function';
    
// Insert that hooks!
foreach ($hooks as $hook => $haction)
    $action($hook, $haction, true);

?>