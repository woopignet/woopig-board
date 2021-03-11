<?php
/***********************************************************************************
*                                                                                 *
* SMF Mobile Theme v1.1.4                                                     	  *
* Copyright (c) 2016-2019 by SMFMobileTheme.com All rights reserved.       		  *
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
	<a href="', $scripturl, '?board=', $context['current_board'], '.0" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	// Show the anchor for the top and for the first message. If the first message is new, say so.
	echo '
<a id="top"></a>
<a id="msg', $context['first_message'], '"></a>', $context['first_new_message'] ? '<a id="new"></a>' : '';

	// Is this topic also a poll?
	if ($context['is_poll'])
	{
		echo '
<div class="tborder marginbottom" id="poll" data-role="collapsible">
	<h4 class="windowbg headerpadding" id="pollquestion">
		', $txt['poll'], ': ', $context['poll']['question'], '
	</h4>
	<div class="windowbg clearfix" id="poll_options">';

		// Are they not allowed to vote but allowed to view the options?
		if ($context['poll']['show_results'] || !$context['allow_vote'])
		{
			echo '
		<div class="options">';

			// Show each option with its corresponding percentage bar.
			foreach ($context['poll']['options'] as $option)
				echo '
			<dt class="middletext', $option['voted_this'] ? ' voted' : '', '">', $option['option'], '</dt>
			<dd class="middletext">', $context['allow_poll_view'] ? $option['bar'] . ' ' . $option['votes'] . ' (' . $option['percent'] . '%)' : '', '</dd>';

			echo '
		</div>';

		if ($context['allow_poll_view'])
			echo '
		<p><strong>', $txt['poll_total_voters'], ':</strong> ', $context['poll']['total_votes'], '</p>';

		}
		// They are allowed to vote! Go to it!
		else
		{
			echo '
		<form action="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], '" method="post" accept-charset="', $context['character_set'], '">';

			// Show a warning if they are allowed more than one option.
			if ($context['poll']['allowed_warning'])
				echo '
			<p class="smallpadding">', $context['poll']['allowed_warning'], '</p>';

			// Show each option with its button - a radio likely.
			foreach ($context['poll']['options'] as $option)
				echo '
				<div class="middletext">', $option['vote_button'], ' <label for="', $option['id'], '">', $option['option'], '</label></div>';

			echo '

			<div class="submitbutton', !empty($context['poll']['expire_time']) ? ' border' : '', '">
				<input type="submit" value="', $txt['poll_vote'], '" class="button_submit" />
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			</div>
		</form>';
		}

		// Is the clock ticking?
		if (!empty($context['poll']['expire_time']))
			echo '
		<p><strong>', ($context['poll']['is_expired'] ? $txt['poll_expired_on'] : $txt['poll_expires_on']), ':</strong> ', $context['poll']['expire_time'], '</p>';
		
		echo'
		
		<div id="pollmoderation" class="clearfix">';

		// Build the poll moderation button array.
		$poll_buttons = array(
			'vote' => array('test' => 'allow_return_vote', 'text' => 'poll_return_vote', 'image' => 'poll_options.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start']),
			'results' => array('test' => 'show_view_results_button', 'text' => 'poll_results', 'image' => 'poll_results.gif', 'lang' => true, 'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start'] . ';viewresults'),
			'change_vote' => array('test' => 'allow_change_vote', 'text' => 'poll_change_vote', 'image' => 'poll_change_vote.gif', 'lang' => true, 'url' => $scripturl . '?action=vote;topic=' . $context['current_topic'] . '.' . $context['start'] . ';poll=' . $context['poll']['id'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'lock' => array('test' => 'allow_lock_poll', 'text' => (!$context['poll']['is_locked'] ? 'poll_lock' : 'poll_unlock'), 'image' => 'poll_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lockvoting;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
			'edit' => array('test' => 'allow_edit_poll', 'text' => 'poll_edit', 'image' => 'poll_edit.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;topic=' . $context['current_topic'] . '.' . $context['start']),
			'remove_poll' => array('test' => 'can_remove_poll', 'text' => 'poll_remove', 'image' => 'admin_remove_poll.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');"', 'url' => $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		);

				template_button_strip($poll_buttons);

		echo '
		</div>
	</div>
</div><br class="clear" />';
	}

	// Build the normal button array.
	$normal_buttons = array(
		'reply' => array('test' => 'can_reply', 'text' => 'reply', 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message']),
		'add_poll' => array('test' => 'can_add_poll', 'text' => 'add_poll', 'image' => 'add_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start']),
		'notify' => array('test' => 'can_mark_notify', 'text' => ($context['is_marked_notify'] ? 'unnotify' : 'notify'), 'image' => ($context['is_marked_notify'] ? 'un' : ''). 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');"', 'url' => $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'mark_unread' => array('test' => 'can_mark_unread', 'text' => 'mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'send' => array('test' => 'can_send_topic', 'text' => 'send_topic', 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=emailuser;sa=sendtopic;topic=' . $context['current_topic'] . '.0'),
		'print' => array('text' => 'print', 'image' => 'print.gif', 'lang' => true, 'custom' => 'rel="new_win nofollow"', 'url' => $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0'),
	);

	// Allow adding new buttons easily.
	call_integration_hook('integrate_display_buttons', array(&$normal_buttons));
	
	$mod_buttons = array(
		'move' => array('test' => 'can_move', 'text' => 'move_topic', 'image' => 'admin_move.gif', 'lang' => true, 'url' => $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0'),
		'delete' => array('test' => 'can_delete', 'text' => 'remove_topic', 'image' => 'admin_rem.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['are_sure_remove_topic'] . '\');"', 'url' => $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
		'lock' => array('test' => 'can_lock', 'text' => empty($context['is_locked']) ? 'set_lock' : 'set_unlock', 'image' => 'admin_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'sticky' => array('test' => 'can_sticky', 'text' => empty($context['is_sticky']) ? 'set_sticky' : 'set_nonsticky', 'image' => 'admin_sticky.gif', 'lang' => true, 'url' => $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'merge' => array('test' => 'can_merge', 'text' => 'merge', 'image' => 'merge.gif', 'lang' => true, 'url' => $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic']),
		'calendar' => array('test' => 'calendar_post', 'text' => 'calendar_link', 'image' => 'linktocal.gif', 'lang' => true, 'url' => $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0'),
	);

	// Restore topic. eh?  No monkey business.
	if ($context['can_restore_topic'])
		$mod_buttons[] = array('text' => 'restore_topic', 'image' => '', 'lang' => true, 'url' => $scripturl . '?action=restoretopic;topics=' . $context['current_topic'] . ';' . $context['session_var'] . '=' . $context['session_id']);

	// Allow adding new mod buttons easily.
	call_integration_hook('integrate_mod_buttons', array(&$mod_buttons));
	
	$normal_buttons = array_merge($normal_buttons, $mod_buttons);

	// Show the page index... "Pages: [1]".
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
			$page = ($i * $modSettings['defaultMaxMessages']);
			
			echo'
					<option value="', $scripturl,'?topic=', $context['current_topic'], '.', $page, '"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
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

	// Show the topic information - icon, subject, etc.
	echo '
<div id="forumposts" class="tborder">
	<form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;" onsubmit="return oQuickModify.bInEditMode ? oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\') : false">
	
	<ul data-role="listview" data-inset="true" data-divider-theme="a">';

	// These are some cache image buttons we may want.
	$reply_button = create_button('quote.gif', 'reply', 'quote', 'align="middle"');
	$modify_button = create_button('modify.gif', 'modify', 'modify', 'align="middle"');
	$remove_button = create_button('delete.gif', 'remove', 'remove', 'align="middle"');
	$split_button = create_button('split.gif', 'split', 'split', 'align="middle"');
	$approve_button = create_button('approve.gif', 'approve', 'approve', 'align="middle"');
	$restore_message_button = create_button('restore_topic.gif', 'restore_message', 'restore_message', 'align="middle"');

	$ignoredMsgs = array();
	$removableMessageIDs = array();

	// Get all the messages...
	while ($message = $context['get_message']())
	{
		$is_first_post = !isset($is_first_post) ? true : false;
		$ignoring = false;
		if ($message['can_remove'])
			$removableMessageIDs[] = $message['id'];

		// Are we ignoring this message?
		if (!empty($message['is_ignored']))
		{
			$ignoring = true;
			$ignoredMsgs[] = $message['id'];
		}

		// Show the message anchor and a "new" anchor if this message is new.
		if ($message['id'] != $context['first_message'])
			echo '
			<a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';
			
		echo '
		<li data-role="list-divider">#', ($message['counter'] + 1), ' ', $txt['by'];
		
		
		// Show avatars, images, etc.?
		if (!empty($message['member']['avatar']['image']))
			echo' <img src="', $message['member']['avatar']['href'], '" alt="" style="max-height: 15px; max-width: 15px; vertical-align: middle; position: inherit;" />';
		
		echo ' ', $message['member']['name'], ' ', $txt['on'], ' ', date('d M o', $message['timestamp']), ' <a href="#popupMenuMsg', $message['id'], '" data-rel="popup" data-transition="slideup"><img src="', $message['icon_url'] . '" alt="" border="0" style="float: right;" /></a></li>
		<li>';
		
		echo '
			<div class="clearfix ', !$is_first_post ? 'topborder ' : '', $message['approved'] ? ($message['alternate'] == 0 ? 'windowbg' : 'windowbg2') : 'approvebg', ' largepadding">';

		// Show information about the poster of this message.
		echo '
				<div class="postarea">';

		// Ignoring this user? Hide the post.
		if ($ignoring)
			echo '
					<div class="ignored" id="msg_', $message['id'], '_ignored_prompt">
						', $txt['ignoring_user'], '
						<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
					</div>';

		// Show the post itself, finally!
		echo '
					<div class="post">
						<div class="inner" id="msg_', $message['id'], '"', '>', str_replace(array('class="bbc_table">', '</table>'), array('data-role="table" class="ui-responsive"><thead><tr></tr></thead><tbody>', '</tbody></table>'), $message['body']), '</div>
					</div>';

		// Assuming there are attachments...
		if (!empty($message['attachment']))
		{
			// Now for the attachments, signature, ip logged, etc...
			echo '
					<div id="msg_', $message['id'], '_footer" class="attachments smalltext" style="clear: both;">';

			$last_approved_state = 1;
			foreach ($message['attachment'] as $attachment)
			{
				// Show a special box for unapproved attachments...
				if ($attachment['is_approved'] != $last_approved_state)
				{
					$last_approved_state = 0;
					echo '
							<fieldset>
								<legend>', $txt['attach_awaiting_approve'];

					if ($context['can_approve'])
						echo '&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=all;mid=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve_all'], '</a>]';

					echo '</legend>';
				}

				if ($attachment['is_image'])
				{
					if ($attachment['thumbnail']['has_thumb'])
						echo '
								<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" border="0" /></a><br />';
					else
						echo '
								<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '" border="0" /><br />';
				}
				echo '
								<a href="' . $attachment['href'] . '"><img src="' . $settings['images_url'] . '/icons/clip.gif" align="middle" alt="*" border="0" />&nbsp;' . $attachment['name'] . '</a> ';

				if (!$attachment['is_approved'] && $context['can_approve'])
					echo '
								[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
				echo '
								<br />';
			}

			// If we had unapproved attachments clean up.
			if ($last_approved_state == 0)
				echo '
							</fieldset>';

			echo '
					</div>';
		}

		echo '
				</div>
			</div>
		</li>
		<div data-role="popup" id="popupMenuMsg', $message['id'], '" data-theme="b">
					<ul data-role="listview" data-inset="true" style="min-width:210px;">';
					
		// Guess?
		if (empty($message['member']['href']))
			echo '
							<li>', $message['member']['name'], '</li>';
		else
			echo '
							<li><a href="', $message['member']['href'], '">', $message['member']['name'], '</a></li>';

		// Maybe we can approve it, maybe we should?
		if ($message['can_approve'])
			echo '
							<li><a href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $approve_button, '</a></li>';

		// Can they reply? Have they turned on quick reply?
		if ($context['can_quote'] && !empty($options['display_quick_reply']))
			echo '
							<li><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" onclick="return oQuickReply.quote(', $message['id'], ');">', $txt['quote'], '</a></li>';

		// So... quick reply is off, but they *can* reply?
		elseif ($context['can_quote'])
			echo '
							<li><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '">', $txt['quote'], '</a></li>';

		// Can the user modify the contents of this post?
		if ($message['can_modify'])
			echo '
							<li><a href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '">', $txt['modify'], '</a></li>';

		// How about... even... remove it entirely?!
		if ($message['can_remove'])
			echo '
							<li><a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');">', $txt['remove'], '</a></li>';

		// What about splitting it off the rest of the topic?
		if ($context['can_split'] && !empty($context['real_num_replies']))
			echo '
							<li><a href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '">', $txt['split'], '</a></li>';

		// Can we restore topics?
		if ($context['can_restore_msg'])
			echo '
							<li><a href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['restore_message'], '</a></li>';
							
		// Maybe they want to report this post to the moderator(s)?
		if ($context['can_report_moderator'])
			echo '
						<li><a href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '">', $txt['report_to_mod'], '</a></li>';

		// Can we issue a warning because of this post?  Remember, we can't give guests warnings.
		if ($context['can_issue_warning'] && !$message['is_message_author'] && !$message['member']['is_guest'])
			echo '
						<li><a href="', $scripturl, '?action=profile;area=issuewarning;u=', $message['member']['id'], ';msg=', $message['id'], '">', $txt['issue_warning_post'], '</a></li>';

			echo '
						</ul>
					</div>';
	}

	echo '
		</ul>
	</form>';
	echo '
</div>
<a id="lastPost"></a>
	<fieldset class="ui-grid-a">
		<div class="ui-block-a">
			', $txt['pages'], ': ';
		
	echo'
			<select data-mini="true" data-inline="true" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
			
	if (!empty($context['page_info']['num_pages']))		
	{
		for ($i = 0; $i < $context['page_info']['num_pages']; $i++)
		{
			$page = ($i * $modSettings['defaultMaxMessages']);
			echo'
					<option value="', $scripturl,'?topic=', $context['current_topic'], '.', $page, '"', (($i + 1) == $context['page_info']['current_page'] ? ' selected="selected"' : ''), '>', ($i + 1), '</option>';
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

	if ($context['can_reply'])
	{
		echo '
		
<div data-role="footer" class="ui-btn-active ui-state-persist" data-position="fixed">
	<form action="', $scripturl, '?action=post2', empty($context['current_board']) ? '' : ';board=' . $context['current_board'], '" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);smc_saveEntities(\'postmodify\', [\'subject\', \'message\', \'guestname\', \'evtitle\', \'question\']);" style="margin: 0;">
		<input type="hidden" name="topic" value="', $context['current_topic'], '" />
		<input type="hidden" name="subject" value="', $context['response_prefix'], $context['subject'], '" />
		<input type="hidden" name="icon" value="xx" />
		<input type="hidden" name="from_qr" value="1" />
		<input type="hidden" name="notify" value="', $context['is_marked_notify'] || !empty($options['auto_notify']) ? '1' : '0', '" />
		<input type="hidden" name="not_approved" value="', !$context['can_reply_approved'], '" />
		<input type="hidden" name="goback" value="', empty($options['return_to_post']) ? '0' : '1', '" />
		<input type="hidden" name="last_msg" value="', $context['topic_last_message'], '" />
		<input id="quick_message" name="message" tabindex="', $context['tabindex']++, '" placeholder="', $txt['quick_reply'], '" />
		<input type="submit" name="post" value="', $txt['post'], '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="', $context['tabindex']++, '" />
		<input type="submit" name="preview" value="', $txt['preview'], '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="', $context['tabindex']++, '" class="button_submit" />
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />';

		if (isset($context['form_sequence_number']) && !empty($context['form_sequence_number']))
			echo'
		<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />';

	echo '
	</form>
</div><!-- /footer -->

<script>
$(\'#main-mobile-page\').addClass("ui-page-quick-reply");
</script>
		
<a id="quickreply"></a>';
	}

	echo '
<script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/topic.js"></script>
<script type="text/javascript"><!-- // --><![CDATA[';

if ($context['user']['is_guest'] || $context['require_verification'])
	echo'
	$(\'#quick_message\').click(function() {
    	window.location.href = smf_scripturl + \'?action=post;board=', $context['current_board'] , ';topic=', $context['current_topic'], '\';
    });';

	if (!empty($ignoredMsgs))
	{
		echo '
	var aIgnoreToggles = new Array();';

		foreach ($ignoredMsgs as $msgid)
		{
			echo '
	aIgnoreToggles[', $msgid, '] = new smc_Toggle({
		bToggleEnabled: true,
		bCurrentlyCollapsed: true,
		aSwappableContainers: [
			\'msg_', $msgid, '_extra_info\',
			\'msg_', $msgid, '\',
			\'msg_', $msgid, '_footer\',
			\'msg_', $msgid, '_quick_mod\',
			\'modify_button_', $msgid, '\',
			\'msg_', $msgid, '_signature\'

		],
		aSwapLinks: [
			{
				sId: \'msg_', $msgid, '_ignored_link\',
				msgExpanded: \'\',
				msgCollapsed: ', JavaScriptEscape($txt['show_ignore_user_post']), '
			}
		]
	});';
		}
	}

	echo '
	// ]]></script>';
}

?>