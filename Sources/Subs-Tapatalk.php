<?php

if (!defined('SMF'))
    die('Hacking attempt...');

//Admin Areas
function Tapatalk_add_admin_areas(&$adminAreas)
{
    global $txt;
    $adminAreas['config']['areas'] += array(
        'tapatalksettings' => array(
            'label' => $txt['tapatalktitle'],
            'file' => 'ManageTapatalk.php',
            'function' => 'ManageTapatalk',
            'icon' => 'tapatalk_settings.png',
            'subsections' => array(
                'general' => array($txt['tp_general_settings']),
                'boards' => array($txt['tp_board_settings']),
                'others' => array($txt['tp_other_settings']),
            ),
        ),
    );
}

//get ip
function exttMbqGetIP()
{
    $realip = '';
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv( "HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}

//
function Tapatalk_add_save_forum_version()
{
    if(defined('IN_MOBIQUO')){
        return;
    }
    global $modSettings, $forum_version, $smcFunc;
    if(!isset($modSettings['tt_forum_version']) ){
        $smcFunc['db_insert']('ignore',
            '{db_prefix}settings',
            array('variable' => 'string', 'value' => 'string'),
            array('tt_forum_version',$forum_version),
            array('variable')
        );
        cache_put_data('modSettings', null, 1);
        return;
    }

    if( $forum_version && ($forum_version != $modSettings['tt_forum_version'])){
        $smcFunc['db_query']('', '
			UPDATE {db_prefix}settings
			SET value = {string:forum_version}
			WHERE variable = {string:tt_forum_version}',
            array(
                'tt_forum_version' => 'tt_forum_version',
                'forum_version' => $forum_version,
            )
        );
        cache_put_data('modSettings', null, 1);
    }
}

function sourcedir_Post_function_one($msgOptions){
    global $boarddir;
    if(defined('sourcedir_Post_function_one'))
        return;

    define('sourcedir_Post_function_one',1);
    if (function_exists('tapatalk_push_reply'))
        tapatalk_push_reply($msgOptions['id']);
    else if(file_exists($boarddir . '/mobiquo/push_hook.php'))
    {
        include_once($boarddir . '/mobiquo/push_hook.php');
        tapatalk_push_reply($msgOptions['id']);
    }
}

function sourcedir_Post_function_two($msgOptions){
    if(defined('sourcedir_Post_function_two'))
        return;

    define('sourcedir_Post_function_two',1);
    global $boarddir;
    if (function_exists('tapatalk_push_new_topic'))
        tapatalk_push_new_topic($msgOptions['id']);
    else if(file_exists($boarddir . '/mobiquo/push_hook.php'))
    {
        include_once($boarddir . '/mobiquo/push_hook.php');
        tapatalk_push_new_topic($msgOptions['id']);
    }
}

function sourcedir_PersonalMessage_function_one(){
    if(defined('sourcedir_PersonalMessage_function_one'))
        return;

    define('sourcedir_PersonalMessage_function_one',1);
    global $boarddir;
    if (function_exists('tapatalk_push_pm'))
        tapatalk_push_pm();
    else if(file_exists($boarddir . '/mobiquo/push_hook.php'))
    {
        include_once($boarddir . '/mobiquo/push_hook.php');
        tapatalk_push_pm();
    }
}

function sourcedir_Notify_function_one($topic){
    if(defined('sourcedir_Notify_function_one'))
        return;

    define('sourcedir_Notify_function_one',1);
    global $boarddir;
    if (function_exists('tapatalk_push_subscirbe')){
        tapatalk_push_subscirbe($topic);
    } else if (file_exists($boarddir . '/mobiquo/push_hook.php'))
    {
        include($boarddir . '/mobiquo/push_hook.php');
        tapatalk_push_subscirbe($topic);
    }
}

function sourcedir_Notify_function_two($topic){
    if(defined('sourcedir_Notify_function_two'))
        return;

    define('sourcedir_Notify_function_two',1);
    global $boarddir;
    if (function_exists('tapatalk_push_subscirbe')){
        tapatalk_push_subscirbe($topic);
    } else if (file_exists($boarddir . '/mobiquo/push_hook.php'))
    {
        include($boarddir . '/mobiquo/push_hook.php');
        tapatalk_push_subscirbe($topic);
    }
}
