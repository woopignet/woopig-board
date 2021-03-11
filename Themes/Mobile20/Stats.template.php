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
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	
	echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['general_stats'], '</h2>
		<ul data-role="listview">
			<li>', $txt['total_members'], ': ', $context['num_members'], '</li>
			<li>', $txt['total_posts'], ': ', $context['num_posts'], '</li>
			<li>', $txt['total_topics'], ': ', $context['num_topics'], '</li>
			<li>', $txt['total_cats'], ': ', $context['num_categories'], '</li>
			<li>', $txt['users_online'], ': ', $context['users_online'], '</li>
			<li>', $txt['most_online'], ': ', $context['most_members_online']['number'], ' - ', $context['most_members_online']['date'], '</li>
			<li>', $txt['users_online_today'], ': ', $context['online_today'], '</li>
			<li>', $txt['average_members'], ': ', $context['average_members'], '</li>
			<li>', $txt['average_posts'], ': ', $context['average_posts'], '</li>
			<li>', $txt['average_topics'], ': ', $context['average_topics'], '</li>
			<li>', $txt['total_boards'], ': ', $context['num_boards'], '</li>
			<li>', $txt['latest_member'], ': ', $context['common_stats']['latest_member']['name'], '</li>
			<li>', $txt['average_online'], ': ', $context['average_online'], '</li>
			<li>', $txt['gender_ratio'], ': ', $context['gender']['ratio'], '</li>';

	if (!empty($modSettings['hitStats']))
		echo '
			<li>', $txt['num_hits'], ': ', $context['num_hits'], '</li>
			<li>', $txt['average_hits'], ': ', $context['average_hits'], '</li>';
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['top_posters'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_posters'] as $poster)
	{
		echo '
			<li><a href="', $poster['href'], '">', $poster['name'], ' <span class="ui-li-count">', $poster['num_posts'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['top_boards'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_boards'] as $board)
	{
		echo '
			<li><a href="', $board['href'], '">', $board['name'], ' <span class="ui-li-count">', $board['num_posts'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['top_topics_replies'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_topics_replies'] as $topic)
	{
		echo '
			<li><a href="', $topic['href'], '">', $topic['subject'], ' <span class="ui-li-count">', $topic['num_replies'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['top_topics_views'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_topics_views'] as $topic)
	{
		echo '
			<li><a href="', $topic['href'], '">', $topic['subject'], ' <span class="ui-li-count">', $topic['num_views'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['top_starters'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_starters'] as $poster)
	{
		echo '
			<li><a href="', $poster['href'], '">', $poster['name'], ' <span class="ui-li-count">', $poster['num_topics'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>
	<div data-role="collapsible" data-theme="b" data-content-theme="a">
		<h2>', $txt['most_time_online'], '</h2>
		<ul data-role="listview">';
		
	foreach ($context['top_time_online'] as $poster)
	{
		echo '
			<li><a href="', $poster['href'], '">', $poster['name'], ' <span class="ui-li-count">', $poster['time_online'], '</span></a></li>';
	}
			
	echo '
		</ul>
	</div>';
}

?>