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

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl;
	
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
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=recent;start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>
		<br style="clear: both;" /><br style="clear: both;" />
		<ul data-role="listview">';

	foreach ($context['posts'] as $post)
	{
		echo '
			<li data-role="list-divider">
				#', $post['counter'], ' ', $txt['by'], ' ', $post['poster']['name'], ' ', $txt['on'], ' ', $post['time'], '
			</li>
			<li>
				<a href="', $scripturl, '?topic=', $post['topic'],'.msg', $post['id'],'#msg', $post['id'],'">
					<div class="inner">
						', strip_tags($post['message']), '
					</div>
				</a>
			</li>';
	}

	echo'
		</ul>
		<br style="clear: both;" />
		', $txt['pages'], ': 
		<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
		
if (!empty($context['page_info']['num_pages']))		
{
	for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
	{
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=recent;start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>';
}

function template_unread()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

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
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=', $_REQUEST['action'], ';start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>
		<br style="clear: both;" /><br style="clear: both;" />';

	if (!empty($context['topics']))
	{
		echo '
		<ul data-role="listview">';
	}
	else
		echo '
		<div align="center">
			', $context['showing_all_topics'] ? $txt['msg_alert_none'] : $txt['unread_topics_visit_none'], '
		</div>';

	foreach ($context['topics'] as $topic)
	{
		echo '
			<li>
				<a href="', $topic['new_href'], '">
					<h2>', $topic['subject'], ' <img src="', $settings['lang_images_url'], '/new.gif" alt="', $txt['new'], '" /></h2>
					<p>', $txt['last_post'], ' ', $txt['by'], ' ', $topic['last_post']['member']['name'], ' ', $txt['on'],' ', $topic['last_post']['time'], '</p>
				</a>
			</li>';
	}

	if (!empty($context['topics']))
	{
		echo '
		</ul>';
	}
	
	echo '
		<br style="clear: both;" />
		',$txt['pages'], ': 
		<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
		
if (!empty($context['page_info']['num_pages']))		
{
	for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
	{
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=', $_REQUEST['action'], ';start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
				
	echo'
		</select>';
}

function template_replies()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

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
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=', $_REQUEST['action'], ';start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
			
echo '
		</select>
		<br style="clear: both;" /><br style="clear: both;" />';

	if (!empty($context['topics']))
	{
		echo '
		<ul data-role="listview">';
	}
	else
		echo '
		<div align="center">
			', $context['showing_all_topics'] ? $txt['msg_alert_none'] : $txt['unread_topics_visit_none'], '
		</div>';

	foreach ($context['topics'] as $topic)
	{
		echo '
			<li>
				<a href="', $topic['new_href'], '">
					<h2>', $topic['subject'], ' <img src="', $settings['lang_images_url'], '/new.gif" alt="', $txt['new'], '" /></h2>
					<p>', $txt['last_post'], ' ', $txt['by'], ' ', $topic['last_post']['member']['name'], ' ', $txt['on'],' ', $topic['last_post']['time'], '</p>
				</a>
			</li>';
	}

	if (!empty($context['topics']))
	{
		echo '
		</ul>';
	}
	
	echo '
		<br style="clear: both;" />
		',$txt['pages'], ': 
		<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
		
if (!empty($context['page_info']['num_pages']))		
{
	for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
	{
		$page = ($i * $context['topics_per_page']);
		echo'
				<option value="', $scripturl,'?action=', $_REQUEST['action'], ';start=', $page,'"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
	}
}
else
	echo'
				<option value="">1</option>';
				
	echo'
		</select>';
}

?>