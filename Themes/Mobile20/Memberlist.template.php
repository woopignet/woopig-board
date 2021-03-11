<?php
/***********************************************************************************
*                                                                                 *
* SMF Mobile Theme v1.0.                                                      	  *
* Copyright (c) 2015 by SMFMobileTheme.com All rights reserved.       			  *
* Powered by www.smfmobiletheme.com                                               *
* Developed by NIBOGO for SMFMobileTheme.com                                      *
*                                                                                 *
***********************************************************************************
* THIS IS PART OF A PAID PRODUCT WHICH IS AVAILABLE AT SMFMobileTheme.COM YOU     *
* CANNOT USE IT IF YOU DOWNLOADED THIS FROM ELSEWHERE OR IF YOU DON'T HAVE A      *
* VALID LICENSE. IF YOU DID DOWNLOADED THIS MOD FROM ANOTHER WEBSITE PLEASE   	  *
* REPORT IT HERE: contact@smfmobiletheme.com									  *
***********************************************************************************/

// Displays a sortable listing of all members registered on the forum.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	
	$context['page_info'] = array(
		'current_page' => $_REQUEST['start'] / $modSettings['defaultMaxMembers'] + 1,
		'num_pages' => floor(($context['num_members'] - 1) / $modSettings['defaultMaxMembers']) + 1
	);
	
	echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	echo $txt['pages'], ': 
		<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
		
if (!empty($context['page_info']['num_pages']))		
{
	for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
	{
		$page = ($i * $modSettings['defaultMaxMembers']);
		echo'
				<option value="', $scripturl,'?action=mlist;start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>';
		
	// Assuming there are members loop through each one displaying their data.
	if (!empty($context['members']))
	{
		echo '
		<br style="clear: both;" /><br style="clear: both;" />
		<ul data-role="listview">';
	
		foreach ($context['members'] as $member)
		{
			echo '
				<li>
					<a href="', $scripturl, '?action=profile;u=', $member['id'], '">';
					
			if (!empty($member['avatar']['href']))
				echo'
						<img src="', $member['avatar']['href'], '" alt="" style="width: 80px;" />';
			else
				echo'
						<img src="', $settings['images_url'], '/no_avatar.png" alt="" />';
						
			echo '
						<h2>', $member['name'], '</h2>
						<p>', $txt['posts'], ': ', $member['posts'], '</p>
					</a>
				</li>';
		}
		
		echo'
		</ul>
		<br style="clear: both;" /><br style="clear: both;" />';
		
	}
	// No members?
	else
		echo '
		<div align="center">
			', $txt['search_no_results'], '
		</div>';

	echo $txt['pages'], ': 
		<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
		
if (!empty($context['page_info']['num_pages']))		
{
	for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
	{
		$page = ($i * $modSettings['defaultMaxMembers']);
		echo'
				<option value="', $scripturl,'?action=mlist;start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>';
}