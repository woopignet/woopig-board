<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function IgnoreUser() {
	global $context, $scripturl, $txt, $modSettings;
	
	$user = (int) $_GET['u'];
	$topic = (int) $_GET['rt'];
	$msg = (int) $_GET['rm'];
	
	if($topic == 0 || $msg == 0)
		$return = '';
	else
		$return = 'topic=' . $topic . '.msg' . $msg . '#msg' . $msg;

	if($modSettings['enable_buddylist'] && $context['user']['id'] != $user)
	{
		$ignoreArray = $context['user']['ignoreusers'];
		foreach ($ignoreArray as $k => $dummy)
			if ($dummy == '')
				unset($ignoreArray[$k]);

		if(in_array($user, $ignoreArray))
		{
			// remove
			foreach ($ignoreArray as $key => $id_remove)
				if ($id_remove == $user)
					unset($ignoreArray[$key]);
		}
		else
		{
			$ignoreArray[] = $user;
		}
		$user_profile[$context['user']['id']]['pm_ignore_list'] = implode(',', $ignoreArray);
		updateMemberData($context['user']['id'], array('pm_ignore_list' => $user_profile[$context['user']['id']]['pm_ignore_list']));
	}
	
	redirectexit($return);
}
?>