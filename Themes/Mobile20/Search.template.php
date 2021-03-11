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

<div role="main" class="ui-content">';

	echo '
	<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">';

	if (!empty($context['search_errors']))
		echo '
		<p id="search_error" class="error">', implode('<br />', $context['search_errors']['messages']), '</p>';

	// Simple Search?
	echo '
		<fieldset id="simple_search">
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<div id="search_term_input">
					<strong>', $txt['search_for'], ':</strong>
					<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input_text" />
					', $context['require_verification'] ? '' : '&nbsp;<input type="submit" name="submit" value="' . $txt['search'] . '" data-icon="search" />
				</div>';

		if ($context['require_verification'])
			echo '
				<div class="verification>
					<strong>', $txt['search_visual_verification_label'], ':</strong>
					<br />', template_control_verification($context['visual_verification_id'], 'all'), '<br />
					<input type="submit" name="submit" value="' . $txt['search'] . '" data-icon="search" />
				</div>';

		echo '
				<input type="hidden" name="advanced" value="0" />
			</div>
			<span class="lowerframe"><span></span></span>
		</fieldset>
	</form>';
}

function template_results()
{
	global $context, $settings, $options, $txt, $scripturl, $message;
	
	echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	if (isset($context['did_you_mean']) || empty($context['topics']))
	{
		echo '
	<div id="search_results">
		<div class="cat_bar">
			<h3 class="catbg">
				', $txt['search_adjust_query'], '
			</h3>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe">';

		// Did they make any typos or mistakes, perhaps?
		if (isset($context['did_you_mean']))
			echo '
			<p>', $txt['search_did_you_mean'], ' <a href="', $scripturl, '?action=search2;params=', $context['did_you_mean_params'], '">', $context['did_you_mean'], '</a>.</p>';

		echo '
			<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
				<strong>', $txt['search_for'], ':</strong>
				<input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input_text" />
				<input type="submit" name="submit" value="', $txt['search_adjust_submit'], '" class="button_submit" />
				<input type="hidden" name="searchtype" value="', !empty($context['search_params']['searchtype']) ? $context['search_params']['searchtype'] : 0, '" />
				<input type="hidden" name="userspec" value="', !empty($context['search_params']['userspec']) ? $context['search_params']['userspec'] : '', '" />
				<input type="hidden" name="show_complete" value="', !empty($context['search_params']['show_complete']) ? 1 : 0, '" />
				<input type="hidden" name="subject_only" value="', !empty($context['search_params']['subject_only']) ? 1 : 0, '" />
				<input type="hidden" name="minage" value="', !empty($context['search_params']['minage']) ? $context['search_params']['minage'] : '0', '" />
				<input type="hidden" name="maxage" value="', !empty($context['search_params']['maxage']) ? $context['search_params']['maxage'] : '9999', '" />
				<input type="hidden" name="sort" value="', !empty($context['search_params']['sort']) ? $context['search_params']['sort'] : 'relevance', '" />';

		if (!empty($context['search_params']['brd']))
			foreach ($context['search_params']['brd'] as $board_id)
				echo '
				<input type="hidden" name="brd[', $board_id, ']" value="', $board_id, '" />';

		echo '
			</form>
		</div>
		<span class="lowerframe"><span></span></span>
	</div><br />';
	}

	if ($context['compact'])
	{
		// Quick moderation set to checkboxes? Oh, how fun :/.
		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
			echo '
	<form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="topicForm">';

	echo '
		<div class="pagesection">
			<span>', $txt['pages'], ': ', $context['page_index'], '</span>
		</div>
		
		<ul data-role="listview" data-inset="true" data-divider-theme="a">';

		while ($topic = $context['get_topics']())
		{
			foreach ($topic['matches'] as $message)
			{
				echo '
				<li data-role="list-divider">
					#', $message['counter'], ' ', $txt['by'], ' ', $message['member']['name'], ' ', $txt['on'], ' ', $message['time'], '
				</li>
				
				<li><a href="', $scripturl, '?topic=', $topic['id'],'.msg', $message['id'],'#msg', $message['id'],'">';

				if ($message['body_highlighted'] != '')
					echo $message['body_highlighted'];
					
				echo'
					</a>
				</li>';
			}
		}
		
		echo'
		</ul>';
		
		if (!empty($context['topics']))
		echo '
		<div class="pagesection">
			<span>', $txt['pages'], ': ', $context['page_index'], '</span>
		</div>';

		if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
			echo '
			<input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
		</form>';

	}
	else
	{
		echo '
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="ie6_header floatleft"><img src="' . $settings['images_url'] . '/buttons/search.gif" alt="" />&nbsp;', $txt['mlist_search_results'],':&nbsp;',$context['search_params']['search'],'</span>
			</h3>
		</div>
		<div class="pagesection">
			<span>', $txt['pages'], ': ', $context['page_index'], '</span>
		</div>';

		if (empty($context['topics']))
			echo '
		<div class="information">(', $txt['search_no_results'], ')</div>';

		while ($topic = $context['get_topics']())
		{
			foreach ($topic['matches'] as $message)
			{
				echo '
			<div class="search_results_posts">
				<div class="', $message['alternate'] == 0 ? 'windowbg' : 'windowbg2', ' core_posts">
					<span class="topslice"><span></span></span>
					<div class="content">
						<div class="counter">', $message['counter'], '</div>
						<div class="topic_details">
							<h5>', $topic['board']['link'], ' / <a href="', $scripturl, '?topic=', $topic['id'], '.', $message['start'], ';topicseen#msg', $message['id'], '">', $message['subject_highlighted'], '</a></h5>
							<span class="smalltext">&#171;&nbsp;', $txt['message'], ' ', $txt['by'], ' <strong>', $message['member']['link'], ' </strong>', $txt['on'], '&nbsp;<em>', $message['time'], '</em>&nbsp;&#187;</span>
						</div>
						<div class="list_posts">', $message['body_highlighted'], '</div>';

				if ($topic['can_reply'] || $topic['can_mark_notify'])
					echo '
						<div class="quickbuttons_wrap">
							<ul class="reset smalltext quickbuttons">';

				// If they *can* reply?
				if ($topic['can_reply'])
					echo '
								<li class="reply_button"><a href="', $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'], '">', $txt['reply'], '</a></li>';

				// If they *can* quote?
				if ($topic['can_quote'])
					echo '
								<li class="quote_button"><a href="', $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'] . ';quote=' . $message['id'] . '">', $txt['quote'], '</a></li>';

				// Can we request notification of topics?
				if ($topic['can_mark_notify'])
					echo '
								<li class="notify_button"><a href="', $scripturl . '?action=notify;topic=' . $topic['id'] . '.' . $message['start'], '">', $txt['notify'], '</a></li>';

				if ($topic['can_reply'] || $topic['can_mark_notify'])
					echo '
							</ul>
						</div>';
				echo '
						<br class="clear" />
					</div>
					<span class="botslice"><span></span></span>
				</div>
			</div>';
			}
		}

		echo '
		<div class="pagesection">
			<span>', $txt['pages'], ': ', $context['page_index'], '</span>
		</div>';
	}
}