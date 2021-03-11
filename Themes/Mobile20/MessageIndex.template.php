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
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	
	echo'
<div data-role="header" style="overflow:hidden;" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	echo '
		<a id="top"></a>';

	if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
		echo '
		<div data-role="collapsible">
			<h4>', $txt['parent_boards'], '</h4>
			<ul data-role="listview">';
	
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($context['boards'] as $board)
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
						<p class="ui-li-aside">
							', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], '
							', $board['is_redirect'] ? '' : ' / ' . comma_format($board['topics']) . ' ' . $txt['board_topics'], '
						</p>
					</a>
				</li>';
			}
		
		echo'
			</ul>
		</div>';
	}

	// Create the button set...
	$normal_buttons = array(
		'new_topic' => array('test' => 'can_post_new', 'text' => 'new_topic', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0'),
		'post_poll' => array('test' => 'can_post_poll', 'text' => 'new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'),
		'notify' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : '') . 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'markread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
	);

	// They can only mark read if they are logged in and it's enabled!
	if (!$context['user']['is_logged'] || !$settings['show_mark_read'])
		unset($normal_buttons['markread']);

	// Allow adding new buttons easily.
	call_integration_hook('integrate_messageindex_buttons', array(&$normal_buttons));

	if (!$context['no_topic_listing'])
	{
		echo '
		<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				', $txt['pages'], ': ';
			
		echo'
				<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
				
		if (!empty($context['page_info']['num_pages']))		
		{
			for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
			{
				$page = ($i * $modSettings['defaultMaxTopics']);
				echo'
						<option value="', $scripturl,'?board=', $context['current_board'], '.', $page, '"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
			}
		}
		else
			echo'
						<option value="">1</option>';
					
		echo '
				</select>
			</div>
			<div class="ui-block-b">
				<div style="float: right;">
					', template_button_strip($normal_buttons, 'bottom'), '
				</div>
			</div>
		</fieldset>';
		
		// No topics.... just say, "sorry bub".
		if (empty($context['topics']))
			echo '
				<div>
					<strong>', $txt['msg_alert_none'], '</strong>
				</div>';

		$stickybar = false;
		$normalbar = false;
		
		echo'
			<ul data-role="listview" data-inset="true" data-divider-theme="a" data-split-icon="carat-r">';
		
		foreach ($context['topics'] as $topic)
		{
			if ($topic['is_sticky'] && !$stickybar)
			{
				echo '
					<li data-role="list-divider">', $txt['sticky_topic'], '</li>';
				$stickybar = true;
			}
			elseif (!$topic['is_sticky'] && $stickybar && !$normalbar)
			{
				echo '
					<li data-role="list-divider">', $txt['topics'], '</li>';
				$normalbar = true;
			}
			
			// Do we want to separate the sticky and lock status out?
			if (!empty($settings['separate_sticky_lock']) && strpos($topic['class'], 'sticky') !== false)
				$topic['class'] = substr($topic['class'], 0, strrpos($topic['class'], '_sticky'));
			if (!empty($settings['separate_sticky_lock']) && strpos($topic['class'], 'locked') !== false)
				$topic['class'] = substr($topic['class'], 0, strrpos($topic['class'], '_locked'));

			// Is this topic pending approval, or does it have any posts pending approval?
			if ($context['can_approve_posts'] && $topic['unapproved_posts'])
				$color_class = !$topic['approved'] ? 'approvetbg' : 'approvebg';
			// Sticky topics should get a different color, too.
			elseif ($topic['is_sticky'] && !empty($settings['separate_sticky_lock']))
				$color_class = 'windowbg3';
			// Last, but not least: regular topics.
			else
				$color_class = 'windowbg';

			// Some columns require a different shade of the color class.
			$alternate_class = 'windowbg2';
			
			echo '
				<li>
					<a href="', $topic['first_post']['href'], '">';
					
				
			echo '
				<fieldset class="ui-grid-a">
					<div class="ui-block-a">
						<p>
							', $txt['by'], ' ', $topic['first_post']['member']['name'], '
						</p>
					</div>
					<div class="ui-block-b">
						<p class="ui-li-aside">
							', comma_format($topic['replies']), ' <img src="', $settings['theme_url'],'/css/aloe/images/icons-png/comment-black.png" alt="', $txt['replies'],'" style="vertical-align: middle;" />&nbsp;&nbsp;' . comma_format($topic['views']) . ' <img src="', $settings['theme_url'],'/css/aloe/images/icons-png/eye-black.png" alt="', $txt['views'],'" style="vertical-align: middle;" />
						</p>
					</div>
				</fieldset>';
						
				echo '
						<h2>', $topic['first_post']['subject'], ($topic['new'] && $context['user']['is_logged'] ? ' <img src="' .  $settings['lang_images_url'] . '/new.gif" alt="' . $txt['new'] . '" />' : ''), '</h2>';
						
				/* The board's and children's 'last_post's have:
				time, timestamp (a number that represents the time.), id (of the post), topic (topic id.),
				link, href, subject, start (where they should go for the first unread post.),
				and member. (which has id, name, link, href, username in it.) */
				if ($topic['last_post']['id'] != $topic['first_post']['id'])
				{
					echo '
						<p>
							', $txt['last_post'], ': ', $topic['last_post']['time'], ' ', $txt['by'], ' ', $topic['last_post']['member']['name'], '
						</p>';
				}
				
				echo '
					</a>
					<a href="', $topic['last_post']['href'], '">', $txt['last_post'], '</a>
				</li>';
				
			// Show the quick moderation options?
			if (!empty($context['can_quick_mod']))
			{
				echo '
				<li>
					<div class="ui-grid-c center">';
					
					if ($topic['quick_mod']['remove'])
						echo'
		                <div class="ui-block-a"><a class="ui-shadow ui-btn ui-corner-all ui-icon-delete ui-btn-icon-notext ui-btn-inline" href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=remove;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');">', $txt['remove_topic'], '</a></div>';
		                
		            if ($topic['quick_mod']['lock'])
						echo '
		                <div class="ui-block-b"><a class="ui-shadow ui-btn ui-corner-all ui-icon-lock ui-btn-icon-notext ui-btn-inline" href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=lock;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');">', $txt['set_lock'], '</a></div>';
		                
		            if ($topic['quick_mod']['sticky'])
		            	echo '    
		                <div class="ui-block-c"><a class="ui-shadow ui-btn ui-corner-all ui-icon-star ui-btn-icon-notext ui-btn-inline" href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=sticky;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');">', $txt['set_sticky'], '</a></div>';
		                
		            if ($topic['quick_mod']['move'])
		            	echo '
		                <div class="ui-block-d"><a class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-notext ui-btn-inline" href="', $scripturl, '?action=movetopic;board=', $context['current_board'], '.', $context['start'], ';topic=', $topic['id'], '.0">', $txt['move_topic'], '</a></div>';
		                
		        echo '
		            </div>
				</li>';
			}
		}
		
		echo '
			</ul>

		<fieldset class="ui-grid-a">
			<div class="ui-block-a">
				', $txt['pages'], ': ';
			
		echo'
				<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
				
		if (!empty($context['page_info']['num_pages']))		
		{
			for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
			{
				$page = ($i * $modSettings['defaultMaxTopics']);
				echo'
						<option value="', $scripturl,'?board=', $context['current_board'], '.', $page, '"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
			}
		}
		else
			echo'
						<option value="">1</option>';
					
		echo '
				</select>
			</div>
			<div class="ui-block-b">
				<div style="float: right;">
					', template_button_strip($normal_buttons, 'bottom'), '
				</div>
			</div>
		</fieldset>';
	}
}

function theme_show_buttons()
{
	global $context, $settings, $options, $txt, $scripturl;

	$buttonArray = array();

	// If they are logged in, and the mark read buttons are enabled..
	if ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id'] . '">' . $txt['mark_read_short'] . '</a>';

	// If the user has permission to show the notification button... ask them if they're sure, though.
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');">' . $txt[$context['is_marked_notify'] ? 'unnotify' : 'notify'] . '</a>';

	// Are they allowed to post new topics?
	if ($context['can_post_new'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0">' . $txt['new_topic'] . '</a>';

	// How about new polls, can the user post those?
	if ($context['can_post_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll">' . $txt['new_poll'] . '</a>';

	// Right to left menu should be in reverse order.
	if ($context['right_to_left'])
		$buttonArray = array_reverse($buttonArray, true);

	return implode(' &nbsp;|&nbsp; ', $buttonArray);
}

?>