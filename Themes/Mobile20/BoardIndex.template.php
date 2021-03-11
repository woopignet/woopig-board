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
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $mbname;
	
	echo'
<div data-role="header" class="ui-btn-active" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';
	
	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if (empty($category['boards']))
			continue;

		echo '
		<div data-role="collapsible"', ($category['is_collapsed'] ? '' : ' data-collapsed="false"'), ' data-theme="b" data-content-theme="a">
			<h4>', $category['name'], '</h4>
			<ul data-role="listview">';
	
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($category['boards'] as $board)
			{
				echo '
				<li>
					<a href="', $board['href'], '" name="b', $board['id'], '">
						<h2>', $board['name'];
						
				if ($board['new'] || $board['children_new'])
					echo'
						<img src="', $settings['lang_images_url'], '/new.gif" alt="', $txt['new'], '" />';
						
				echo '
						</h2>
						<p>', $board['description'] , '</p>';
						
				/* The board's and children's 'last_post's have:
				time, timestamp (a number that represents the time.), id (of the post), topic (topic id.),
				link, href, subject, start (where they should go for the first unread post.),
				and member. (which has id, name, link, href, username in it.) */
				if (!empty($board['last_post']['id']))
					echo '
						<p><strong>', $txt['last_post'], '</strong>: ', $board['last_post']['subject'], ' ', $txt['on'], ' ', $board['last_post']['time'], '
						</p>';
						
				echo '
					</a>
				</li>';
			}
		
		echo'
			</ul>
		</div>';
	}

	template_info_center();
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '
	<div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="a">
		<h4>', $txt['forum_stats'], ' &amp; ', $txt['online_users'], '</h4>
		<ul data-role="listview">
			<li>
				<a href="', $scripturl, '?action=stats" style="font-weight: 100;">
					', $context['common_stats']['total_posts'], ' ', $txt['posts_made'], ' ', $txt['in'], ' ', $context['common_stats']['total_topics'], ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'], ' ', $txt['members'], '.
				</a>
			</li>
			<li>
				<a href="', $scripturl, '?action=who" style="font-weight: 100;">';
			
				// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
				if (!empty($context['users_online']))
				{
					$members = implode(', ', $context['list_users_online']);
					$members = strip_tags($members);
					
					echo sprintf($txt['users_active'], $modSettings['lastActive']), ': ', $members;
				}
			
	echo '
				</a>
			</li>
		</ul>
	</div>';
}