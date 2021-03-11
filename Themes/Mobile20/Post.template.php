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

// The main template for the post page.
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $counter;
	
echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '?board=', $context['current_board'], '.0" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	// Start the javascript... and boy is there a lot.
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[';

	// When using Go Back due to fatal_error, allow the form to be re-submitted with changes.
	if ($context['browser']['is_firefox'])
		echo '
			function reActivate()
			{
				document.forms.postmodify.message.readOnly = false;
			}
			window.addEventListener("pageshow", reActivate, false);';

	// Start with message icons - and any missing from this theme.
	echo '
			var icon_urls = {';
	foreach ($context['icons'] as $icon)
		echo '
				\'', $icon['value'], '\': \'', $icon['url'], '\'', $icon['is_last'] ? '' : ',';
	echo '
			};';

	// The actual message icon selector.
	echo '
			function showimage()
			{
				document.images.icons.src = icon_urls[document.forms.postmodify.icon.options[document.forms.postmodify.icon.selectedIndex].value];
			}';

	// If this is a poll - use some javascript to ensure the user doesn't create a poll with illegal option combinations.
	if ($context['make_poll'])
		echo '
			function pollOptions()
			{
				var expire_time = document.getElementById(\'poll_expire\');

				if (isEmptyText(expire_time) || expire_time.value == 0)
				{
					document.forms.postmodify.poll_hide[2].disabled = true;
					if (document.forms.postmodify.poll_hide[2].checked)
						document.forms.postmodify.poll_hide[1].checked = true;
				}
				else
					document.forms.postmodify.poll_hide[2].disabled = false;
			}

			var pollOptionNum = 0, pollTabIndex;
			function addPollOption()
			{
				if (pollOptionNum == 0)
				{
					for (var i = 0, n = document.forms.postmodify.elements.length; i < n; i++)
						if (document.forms.postmodify.elements[i].id.substr(0, 8) == \'options-\')
						{
							pollOptionNum++;
							pollTabIndex = document.forms.postmodify.elements[i].tabIndex;
						}
				}
				pollOptionNum++

				setOuterHTML(document.getElementById(\'pollMoreOptions\'), ', JavaScriptEscape('<li><label for="options-'), ' + pollOptionNum + ', JavaScriptEscape('">' . $txt['option'] . ' '), ' + pollOptionNum + ', JavaScriptEscape('</label>: <input type="text" name="options['), ' + pollOptionNum + ', JavaScriptEscape(']" id="options-'), ' + pollOptionNum + ', JavaScriptEscape('" value="" size="80" maxlength="255" tabindex="'), ' + pollTabIndex + ', JavaScriptEscape('" class="input_text" /></li><li id="pollMoreOptions"></li>'), ');
			}';

	// End of the javascript, start the form and display the link tree.
	echo '
		// ]]></script>
		<form action="', $scripturl, '?action=', $context['destination'], ';', empty($context['current_board']) ? '' : 'board=' . $context['current_board'], '" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" class="flow_hidden" onsubmit="', ($context['becomes_approved'] ? '' : 'alert(\'' . $txt['js_post_will_require_approval'] . '\');'), 'submitonce(this);smc_saveEntities(\'postmodify\', [\'subject\', \'', $context['post_box_name'], '\', \'guestname\', \'evtitle\', \'question\'], \'options\');" enctype="multipart/form-data">';

	// If the user wants to see how their message looks - the preview section is where it's at!
	echo '
			<div id="preview_section"', isset($context['preview_message']) ? '' : ' style="display: none;"', '>
				<div class="cat_bar">
					<h3 class="catbg">
						<span id="preview_subject">', empty($context['preview_subject']) ? '' : $context['preview_subject'], '</span>
					</h3>
				</div>
				<div class="windowbg">
					<span class="topslice"><span></span></span>
					<div class="content">
						<div class="post" id="preview_body">
							', empty($context['preview_message']) ? '<br />' : $context['preview_message'], '
						</div>
					</div>
					<span class="botslice"><span></span></span>
				</div>
			</div><br />';

	if ($context['make_event'] && (!$context['event']['new'] || !empty($context['current_board'])))
		echo '
			<input type="hidden" name="eventid" value="', $context['event']['id'], '" />';

	// Start the main table.
	echo '
			<div>
				<span class="upperframe"><span></span></span>
				<div class="roundframe">', isset($context['current_topic']) ? '
					<input type="hidden" name="topic" value="' . $context['current_topic'] . '" />' : '';

	// If an error occurred, explain what happened.
	echo '
					<div class="errorbox"', empty($context['post_error']['messages']) ? ' style="display: none"' : '', ' id="errors">
						<dl>
							<dt>
								<strong style="', empty($context['error_type']) || $context['error_type'] != 'serious' ? 'display: none;' : '', '" id="error_serious">', $txt['error_while_submitting'], '</strong>
							</dt>
							<dt class="error" id="error_list">
								', empty($context['post_error']['messages']) ? '' : implode('<br />', $context['post_error']['messages']), '
							</dt>
						</dl>
					</div>';

	// If this won't be approved let them know!
	if (!$context['becomes_approved'])
	{
		echo '
					<p class="information">
						<em>', $txt['wait_for_approval'], '</em>
						<input type="hidden" name="not_approved" value="1" />
					</p>';
	}

	// If it's locked, show a message to warn the replyer.
	echo '
					<p class="information"', $context['locked'] ? '' : ' style="display: none"', ' id="lock_warning">
						', $txt['topic_locked_no_reply'], '
					</p>';

	// The post header... important stuff
	echo '
					<dl id="post_header">';

	// Guests have to put in their name and email...
	if (isset($context['name']) && isset($context['email']))
	{
		echo '
						<dt>
							<span', isset($context['post_error']['long_name']) || isset($context['post_error']['no_name']) || isset($context['post_error']['bad_name']) ? ' class="error"' : '', ' id="caption_guestname">', $txt['name'], ':</span>
						</dt>
						<dd>
							<input type="text" name="guestname" size="25" value="', $context['name'], '" tabindex="', $context['tabindex']++, '" class="input_text" />
						</dd>';

		if (empty($modSettings['guest_post_no_email']))
			echo '
						<dt>
							<span', isset($context['post_error']['no_email']) || isset($context['post_error']['bad_email']) ? ' class="error"' : '', ' id="caption_email">', $txt['email'], ':</span>
						</dt>
						<dd>
							<input type="text" name="email" size="25" value="', $context['email'], '" tabindex="', $context['tabindex']++, '" class="input_text" />
						</dd>';
	}

	// Now show the subject box for this post.
	echo '
						<div>
							<span', isset($context['post_error']['no_subject']) ? ' class="error"' : '', ' id="caption_subject">', $txt['subject'], ':</span>
						</div>
						<div>
							<input type="text" name="subject"', $context['subject'] == '' ? '' : ' value="' . $context['subject'] . '"', ' tabindex="', $context['tabindex']++, '" size="80" maxlength="80" class="input_text" />
						</div>
						<div class="clear_left">
							', $txt['message_icon'], ':
						</div>
						<div>
							<select name="icon" id="icon" onchange="showimage()">';

	// Loop through each message icon allowed, adding it to the drop down list.
	foreach ($context['icons'] as $icon)
		echo '
								<option value="', $icon['value'], '"', $icon['value'] == $context['icon'] ? ' selected="selected"' : '', '>', $icon['name'], '</option>';

	echo '
							</select>
							<img src="', $context['icon_url'], '" name="icons" hspace="15" alt="" />
						</div>
					</dl>';

	// If this is a poll then display all the poll options!
	if ($context['make_poll'])
	{
		echo '
					<div id="edit_poll">
						<fieldset id="poll_main">
							<legend><span ', (isset($context['poll_error']['no_question']) ? ' class="error"' : ''), '>', $txt['poll_question'], '</span></legend>
							<input type="text" name="question" value="', isset($context['question']) ? $context['question'] : '', '" tabindex="', $context['tabindex']++, '" size="80" class="input_text" />
							<ul class="poll_main">';

		// Loop through all the choices and print them out.
		foreach ($context['choices'] as $choice)
		{
			echo '
								<li>
									<label for="options-', $choice['id'], '">', $txt['option'], ' ', $choice['number'], '</label>:
									<input type="text" name="options[', $choice['id'], ']" id="options-', $choice['id'], '" value="', $choice['label'], '" tabindex="', $context['tabindex']++, '" size="80" maxlength="255" class="input_text" />
								</li>';
		}

		echo '
								<li id="pollMoreOptions"></li>
							</ul>
							<strong><a href="javascript:addPollOption(); void(0);">(', $txt['poll_add_option'], ')</a></strong>
						</fieldset>
						<fieldset id="poll_options">
							<legend>', $txt['poll_options'], '</legend>
							<dl class="settings poll_options">
								<dt>
									<label for="poll_max_votes">', $txt['poll_max_votes'], ':</label>
								</dt>
								<dd>
									<input type="text" name="poll_max_votes" id="poll_max_votes" size="2" value="', $context['poll_options']['max_votes'], '" class="input_text" />
								</dd>
								<dt>
									<label for="poll_expire">', $txt['poll_run'], ':</label><br />
									<em class="smalltext">', $txt['poll_run_limit'], '</em>
								</dt>
								<dd>
									<input type="text" name="poll_expire" id="poll_expire" size="2" value="', $context['poll_options']['expire'], '" onchange="pollOptions();" maxlength="4" class="input_text" /> ', $txt['days_word'], '
								</dd>
								<dt>
									<label for="poll_change_vote">', $txt['poll_do_change_vote'], ':</label>
								</dt>
								<dd>
									<input type="checkbox" id="poll_change_vote" name="poll_change_vote"', !empty($context['poll']['change_vote']) ? ' checked="checked"' : '', ' class="input_check" />
								</dd>';

		if ($context['poll_options']['guest_vote_enabled'])
			echo '
								<dt>
									<label for="poll_guest_vote">', $txt['poll_guest_vote'], ':</label>
								</dt>
								<dd>
									<input type="checkbox" id="poll_guest_vote" name="poll_guest_vote"', !empty($context['poll_options']['guest_vote']) ? ' checked="checked"' : '', ' class="input_check" />
								</dd>';

		echo '
								<dt>
									', $txt['poll_results_visibility'], ':
								</dt>
								<dd>
									<input type="radio" name="poll_hide" id="poll_results_anyone" value="0"', $context['poll_options']['hide'] == 0 ? ' checked="checked"' : '', ' class="input_radio" /> <label for="poll_results_anyone">', $txt['poll_results_anyone'], '</label><br />
									<input type="radio" name="poll_hide" id="poll_results_voted" value="1"', $context['poll_options']['hide'] == 1 ? ' checked="checked"' : '', ' class="input_radio" /> <label for="poll_results_voted">', $txt['poll_results_voted'], '</label><br />
									<input type="radio" name="poll_hide" id="poll_results_expire" value="2"', $context['poll_options']['hide'] == 2 ? ' checked="checked"' : '', empty($context['poll_options']['expire']) ? 'disabled="disabled"' : '', ' class="input_radio" /> <label for="poll_results_expire">', $txt['poll_results_after'], '</label>
								</dd>
							</dl>
						</fieldset>
					</div>';
	}

	echo '
					<label for="message">', $txt['message'], ':</label>
					<textarea name="message" id="message">', $context['message'], '</textarea>
					<fieldset data-role="collapsible">
	                    <legend>', $txt['post_additionalopt'], '</legend>
	
	                    <div data-role="controlgroup">
	                    ', $context['can_notify'] ? '
	                    	<input type="hidden" name="notify" value="0" />
	                    	<input type="checkbox" name="notify" id="check_notify"' . ($context['notify'] || !empty($options['auto_notify']) ? ' checked="checked"' : '') . ' value="1">
	                        <label for="check_notify">' . $txt['notify_replies'] . '</label>' : '', '
	                        
	                    ', $context['can_lock'] ? '
	                    	<input type="hidden" name="lock" value="0" />
	                    	<input type="checkbox" name="lock" id="check_lock"' . ($context['locked'] ? ' checked="checked"' : '') . ' value="1">
	                        <label for="check_lock">' . $txt['lock_topic'] . '</label>' : '', '
	                    
	                        <input type="checkbox" name="goback" id="check_back"' . ($context['back_to_topic'] || !empty($options['return_to_post']) ? ' checked="checked"' : '') . ' value="1">
	                        <label for="check_back">' . $txt['back_to_topic'] . '</label>
	                        
	                    ', $context['can_sticky'] ? '
	                    	<input type="hidden" name="sticky" value="0" />
	                    	<input type="checkbox" name="sticky" id="check_sticky"' . ($context['sticky'] ? ' checked="checked"' : '') . ' value="1">
	                        <label for="check_sticky">' . $txt['sticky_after'] . '</label>' : '', '
	                        
	                        <input type="checkbox" name="ns" id="check_smileys"', $context['use_smileys'] ? '' : ' checked="checked"', ' value="NS">
	                        <label for="check_smileys">', $txt['dont_use_smileys'], '</label>
	                        
	                    ', $context['can_move'] ? '
	                    	<input type="hidden" name="move" value="0" />
	                    	<input type="checkbox" name="move" id="check_move" value="1"' . (!empty($context['move']) ? ' checked="checked" ' : '') . '>
	                        <label for="check_move">' . $txt['move_after2'] . '</label>' : '', '
	                        
	                    ', $context['can_announce'] && $context['is_first_post'] ? '
	                    	<input type="checkbox" name="announce_topic" id="check_announce" value="1" class="input_check" ' . (!empty($context['announce']) ? 'checked="checked" ' : '') . '>
	                        <label for="check_announce">' . $txt['announce_topic'] . '</label>' : '', '
	                        
	                    ', $context['show_approval'] ? '
	                    	<input type="checkbox" name="approve" id="approve" value="2" class="input_check" ' . ($context['show_approval'] === 2 ? 'checked="checked"' : '') . ' />
	                    	<label for="approve">' . $txt['approve_this_post'] . '</label>' : '', '
	                    </div>';

	// If this post already has attachments on it - give information about them.
	if (!empty($context['current_attachments']))
	{
		echo '
					<dl id="postAttachment">
						<div>
							', $txt['attached'], ' (', $txt['uncheck_unwatchd_attach'], '):
						</div>
						<input type="hidden" name="attach_del[]" value="0" />';
		foreach ($context['current_attachments'] as $attachment)
			echo '
						<div class="smalltext">
							<label for="attachment_', $attachment['id'], '"><input type="checkbox" id="attachment_', $attachment['id'], '" name="attach_del[]" value="', $attachment['id'], '"', empty($attachment['unchecked']) ? ' checked="checked"' : '', ' class="input_check" /> ', $attachment['name'], (empty($attachment['approved']) ? ' (' . $txt['awaiting_approval'] . ')' : ''), '</label>
						</div>';
		echo '
					</dl>';
	}

	// Is the user allowed to post any additional ones? If so give them the boxes to do it!
	if ($context['can_post_attachment'])
	{
		echo '
					<dl id="postAttachment2">
						<div>
							', $txt['attach'], ':
						</div>
						<div class="smalltext">
							<fieldset class="ui-grid-a">
								<div class="ui-block-a">
									<input type="file" size="60" name="attachment[]" id="attachment1" class="input_file" />
								</div>
								<div class="ui-block-b">
									<a href="javascript:void(0);" onclick="cleanFileInput(\'attachment1\');" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext">
										', $txt['clean_attach'], '
									</a>
								</div>
							</fieldset>';

		// Show more boxes only if they aren't approaching their limit.
		if ($context['num_allowed_attachments'] > 1)
			echo '
							<script type="text/javascript"><!-- // --><![CDATA[
								var allowed_attachments = ', $context['num_allowed_attachments'], ';
								var current_attachment = 1;

								function addAttachment()
								{
									allowed_attachments = allowed_attachments - 1;
									current_attachment = current_attachment + 1;
									if (allowed_attachments <= 0)
										return alert("', $txt['more_attachments_error'], '");

									setOuterHTML(document.getElementById("moreAttachments"), \'<fieldset class="ui-grid-a"><div class="ui-block-a"><div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input type="file" size="60" name="attachment[]" id="attachment\' + current_attachment + \'" class="input_file" /></div></div><div class="ui-block-b"><a href="javascript:void(0);" onclick="cleanFileInput(\\\'attachment\' + current_attachment + \'\\\');" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext">', $txt['clean_attach'], '</a>\' + \'</div></fieldset><span id="moreAttachments"></span>\');

									return true;
								}
							// ]]></script>
						</div>
						<span id="moreAttachments"></span>
						<a href="#" onclick="addAttachment(); return false;" class="ui-btn ui-icon-plus ui-btn-icon-left">
							', $txt['more_attachments'], '
						</a>';

		echo '
						<div class="smalltext">';

		// Show some useful information such as allowed extensions, maximum size and amount of attachments allowed.
		if (!empty($modSettings['attachmentCheckExtensions']))
			echo '
							', $txt['allowed_types'], ': ', $context['allowed_extensions'], '<br />';

		if (!empty($context['attachment_restrictions']))
			echo '
							', $txt['attach_restrictions'], ' ', implode(', ', $context['attachment_restrictions']), '<br />';

		if (!$context['can_post_attachment_unapproved'])
			echo '
							<span class="alert">', $txt['attachment_requires_approval'], '</span>', '<br />';

		echo '
						</div>
					</dl>';
	}
	
	echo'
					</fieldset>';

	// Is visual verification enabled?
	if ($context['require_verification'])
	{
		echo '
					<div class="post_verification">
						<span', !empty($context['post_error']['need_qr_verification']) ? ' class="error"' : '', '>
							<strong>', $txt['verification'], ':</strong>
						</span>
						', template_control_verification($context['visual_verification_id'], 'all'), '
					</div>';
	}

	// Finally, the submit buttons.
	echo '
					<p id="post_confirm_buttons" class="righttext">
						', template_control_richedit_buttons($context['post_box_name']), '
					</p>
				</div>
				<span class="lowerframe"><span></span></span>
			</div>
			<br class="clear" />';

	// Assuming this isn't a new topic pass across the last message id.
	if (isset($context['topic_last_message']))
		echo '
			<input type="hidden" name="last_msg" value="', $context['topic_last_message'], '" />';

	echo '
			<input type="hidden" name="additional_options" id="additional_options" value="', $context['show_additional_options'] ? '1' : '0', '" />
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
		</form>';

	echo '
		<script type="text/javascript"><!-- // --><![CDATA[';

	// The functions used to preview a posts without loading a new page.
	echo '
			var current_board = ', empty($context['current_board']) ? 'null' : $context['current_board'], ';
			var make_poll = ', $context['make_poll'] ? 'true' : 'false', ';
			var txt_preview_title = "', $txt['preview_title'], '";
			var txt_preview_fetch = "', $txt['preview_fetch'], '";
			var new_replies = new Array();
			var reply_counter = ', empty($counter) ? 0 : $counter, ';
			function previewPost()
			{';
	if ($context['browser']['is_firefox'])
		echo '
				// Firefox doesn\'t render <marquee> that have been put it using javascript
				if (document.forms.postmodify.elements[', JavaScriptEscape($context['post_box_name']), '].value.indexOf(\'[move]\') != -1)
				{
					return submitThisOnce(document.forms.postmodify);
				}';
	echo '
				if (window.XMLHttpRequest)
				{
					// Opera didn\'t support setRequestHeader() before 8.01.
					if (\'opera\' in window)
					{
						var test = new XMLHttpRequest();
						if (!(\'setRequestHeader\' in test))
							return submitThisOnce(document.forms.postmodify);
					}
					// !!! Currently not sending poll options and option checkboxes.
					var x = new Array();
					var textFields = [\'subject\', ', JavaScriptEscape($context['post_box_name']), ', \'icon\', \'guestname\', \'email\', \'evtitle\', \'question\', \'topic\'];
					var numericFields = [
						\'board\', \'topic\', \'last_msg\',
						\'eventid\', \'calendar\', \'year\', \'month\', \'day\',
						\'poll_max_votes\', \'poll_expire\', \'poll_change_vote\', \'poll_hide\'
					];
					var checkboxFields = [
						\'ns\'
					];

					for (var i = 0, n = textFields.length; i < n; i++)
						if (textFields[i] in document.forms.postmodify)
						{
							x[x.length] = textFields[i] + \'=\' + document.forms.postmodify[textFields[i]].value.replace(/&#/g, \'&#38;#\').php_to8bit().php_urlencode();
						}
					for (var i = 0, n = numericFields.length; i < n; i++)
						if (numericFields[i] in document.forms.postmodify && \'value\' in document.forms.postmodify[numericFields[i]])
							x[x.length] = numericFields[i] + \'=\' + parseInt(document.forms.postmodify.elements[numericFields[i]].value);
					for (var i = 0, n = checkboxFields.length; i < n; i++)
						if (checkboxFields[i] in document.forms.postmodify && document.forms.postmodify.elements[checkboxFields[i]].checked)
							x[x.length] = checkboxFields[i] + \'=\' + document.forms.postmodify.elements[checkboxFields[i]].value;

					sendXMLDocument(smf_prepareScriptUrl(smf_scripturl) + \'action=post2\' + (current_board ? \';board=\' + current_board : \'\') + (make_poll ? \';poll\' : \'\') + \';preview;xml\', x.join(\'&\'), onDocSent);

					document.getElementById(\'preview_section\').style.display = \'\';
					setInnerHTML(document.getElementById(\'preview_subject\'), txt_preview_title);
					setInnerHTML(document.getElementById(\'preview_body\'), txt_preview_fetch);

					return false;
				}
				else
					return submitThisOnce(document.forms.postmodify);
			}
			function onDocSent(XMLDoc)
			{
				if (!XMLDoc)
				{
					document.forms.postmodify.preview.onclick = new function ()
					{
						return true;
					}
					document.forms.postmodify.preview.click();
				}

				// Show the preview section.
				var preview = XMLDoc.getElementsByTagName(\'smf\')[0].getElementsByTagName(\'preview\')[0];
				setInnerHTML(document.getElementById(\'preview_subject\'), preview.getElementsByTagName(\'subject\')[0].firstChild.nodeValue);

				var bodyText = \'\';
				for (var i = 0, n = preview.getElementsByTagName(\'body\')[0].childNodes.length; i < n; i++)
					bodyText += preview.getElementsByTagName(\'body\')[0].childNodes[i].nodeValue;

				setInnerHTML(document.getElementById(\'preview_body\'), bodyText);
				document.getElementById(\'preview_body\').className = \'post\';

				// Show a list of errors (if any).
				var errors = XMLDoc.getElementsByTagName(\'smf\')[0].getElementsByTagName(\'errors\')[0];
				var errorList = new Array();
				for (var i = 0, numErrors = errors.getElementsByTagName(\'error\').length; i < numErrors; i++)
					errorList[errorList.length] = errors.getElementsByTagName(\'error\')[i].firstChild.nodeValue;
				document.getElementById(\'errors\').style.display = numErrors == 0 ? \'none\' : \'\';
				document.getElementById(\'error_serious\').style.display = errors.getAttribute(\'serious\') == 1 ? \'\' : \'none\';
				setInnerHTML(document.getElementById(\'error_list\'), numErrors == 0 ? \'\' : errorList.join(\'<br />\'));

				// Show a warning if the topic has been locked.
				document.getElementById(\'lock_warning\').style.display = errors.getAttribute(\'topic_locked\') == 1 ? \'\' : \'none\';

				// Adjust the color of captions if the given data is erroneous.
				var captions = errors.getElementsByTagName(\'caption\');
				for (var i = 0, numCaptions = errors.getElementsByTagName(\'caption\').length; i < numCaptions; i++)
					if (document.getElementById(\'caption_\' + captions[i].getAttribute(\'name\')))
						document.getElementById(\'caption_\' + captions[i].getAttribute(\'name\')).className = captions[i].getAttribute(\'class\');

				if (errors.getElementsByTagName(\'post_error\').length == 1)
					document.forms.postmodify.', $context['post_box_name'], '.style.border = \'1px solid red\';
				else if (document.forms.postmodify.', $context['post_box_name'], '.style.borderColor == \'red\' || document.forms.postmodify.', $context['post_box_name'], '.style.borderColor == \'red red red red\')
				{
					if (\'runtimeStyle\' in document.forms.postmodify.', $context['post_box_name'], ')
						document.forms.postmodify.', $context['post_box_name'], '.style.borderColor = \'\';
					else
						document.forms.postmodify.', $context['post_box_name'], '.style.border = null;
				}

				// Set the new last message id.
				if (\'last_msg\' in document.forms.postmodify)
					document.forms.postmodify.last_msg.value = XMLDoc.getElementsByTagName(\'smf\')[0].getElementsByTagName(\'last_msg\')[0].firstChild.nodeValue;

				// Remove the new image from old-new replies!
				for (i = 0; i < new_replies.length; i++)
					document.getElementById(\'image_new_\' + new_replies[i]).style.display = \'none\';
				new_replies = new Array();

				var ignored_replies = new Array(), ignoring;
				var newPosts = XMLDoc.getElementsByTagName(\'smf\')[0].getElementsByTagName(\'new_posts\')[0] ? XMLDoc.getElementsByTagName(\'smf\')[0].getElementsByTagName(\'new_posts\')[0].getElementsByTagName(\'post\') : {length: 0};
				var numNewPosts = newPosts.length;
				if (numNewPosts != 0)
				{
					var newPostsHTML = \'<span id="new_replies"><\' + \'/span>\';
					for (var i = 0; i < numNewPosts; i++)
					{
						new_replies[new_replies.length] = newPosts[i].getAttribute("id");

						ignoring = false;
						if (newPosts[i].getElementsByTagName("is_ignored")[0].firstChild.nodeValue != 0)
							ignored_replies[ignored_replies.length] = ignoring = newPosts[i].getAttribute("id");

						newPostsHTML += \'<div class="windowbg\' + (++reply_counter % 2 == 0 ? \'2\' : \'\') + \' core_posts"><span class="topslice"><span></span></span><div class="content" id="msg\' + newPosts[i].getAttribute("id") + \'"><div class="floatleft"><h5>', $txt['posted_by'], ': \' + newPosts[i].getElementsByTagName("poster")[0].firstChild.nodeValue + \'</h5><span class="smalltext">&#171;&nbsp;<strong>', $txt['on'], ':</strong> \' + newPosts[i].getElementsByTagName("time")[0].firstChild.nodeValue + \'&nbsp;&#187;</span> <img src="\' + smf_images_url + \'/', $context['user']['language'], '/new.gif" alt="', $txt['preview_new'], '" id="image_new_\' + newPosts[i].getAttribute("id") + \'" /></div>\';';

	if ($context['can_quote'])
		echo '
						newPostsHTML += \'<ul class="reset smalltext quickbuttons" id="msg_\' + newPosts[i].getAttribute("id") + \'_quote"><li class="quote_button"><a href="#postmodify" onclick="return insertQuoteFast(\\\'\' + newPosts[i].getAttribute("id") + \'\\\');"><span>',$txt['bbc_quote'],'</span><\' + \'/a></li></ul>\';';

	echo '
						newPostsHTML += \'<br class="clear" />\';

						if (ignoring)
							newPostsHTML += \'<div id="msg_\' + newPosts[i].getAttribute("id") + \'_ignored_prompt" class="smalltext">', $txt['ignoring_user'], '<a href="#" id="msg_\' + newPosts[i].getAttribute("id") + \'_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a></div>\';

						newPostsHTML += \'<div class="list_posts smalltext" id="msg_\' + newPosts[i].getAttribute("id") + \'_body">\' + newPosts[i].getElementsByTagName("message")[0].firstChild.nodeValue + \'<\' + \'/div></div><span class="botslice"><span></span></span></div>\';
					}
					setOuterHTML(document.getElementById(\'new_replies\'), newPostsHTML);
				}

				var numIgnoredReplies = ignored_replies.length;
				if (numIgnoredReplies != 0)
				{
					for (var i = 0; i < numIgnoredReplies; i++)
					{
						aIgnoreToggles[ignored_replies[i]] = new smc_Toggle({
							bToggleEnabled: true,
							bCurrentlyCollapsed: true,
							aSwappableContainers: [
								\'msg_\' + ignored_replies[i] + \'_body\',
								\'msg_\' + ignored_replies[i] + \'_quote\',
							],
							aSwapLinks: [
								{
									sId: \'msg_\' + ignored_replies[i] + \'_ignored_link\',
									msgExpanded: \'\',
									msgCollapsed: ', JavaScriptEscape($txt['show_ignore_user_post']), '
								}
							]
						});
					}
				}

				if (typeof(smf_codeFix) != \'undefined\')
					smf_codeFix();
			}';

	echo '
		// ]]></script>';
}

// The template for the spellchecker.
function template_spellcheck()
{
	global $context, $settings, $options, $txt;

	// The style information that makes the spellchecker look... like the forum hopefully!
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<title>', $txt['spell_check'], '</title>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css" />
		<style type="text/css">
			body, td
			{
				font-size: small;
				margin: 0;
				background: #f0f0f0;
				color: #000;
				padding: 10px;
			}
			.highlight
			{
				color: red;
				font-weight: bold;
			}
			#spellview
			{
				border-style: outset;
				border: 1px solid black;
				padding: 5px;
				width: 95%;
				height: 314px;
				overflow: auto;
				background: #ffffff;
			}';

	// As you may expect - we need a lot of javascript for this... load it form the separate files.
	echo '
		</style>
		<script type="text/javascript"><!-- // --><![CDATA[
			var spell_formname = window.opener.spell_formname;
			var spell_fieldname = window.opener.spell_fieldname;
		// ]]></script>
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/spellcheck.js"></script>
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js"></script>
		<script type="text/javascript"><!-- // --><![CDATA[
			', $context['spell_js'], '
		// ]]></script>
	</head>
	<body onload="nextWord(false);">
		<form action="#" method="post" accept-charset="', $context['character_set'], '" name="spellingForm" id="spellingForm" onsubmit="return false;" style="margin: 0;">
			<div id="spellview">&nbsp;</div>
			<table border="0" cellpadding="4" cellspacing="0" width="100%"><tr class="windowbg">
				<td width="50%" valign="top">
					', $txt['spellcheck_change_to'], '<br />
					<input type="text" name="changeto" style="width: 98%;" class="input_text" />
				</td>
				<td width="50%">
					', $txt['spellcheck_suggest'], '<br />
					<select name="suggestions" style="width: 98%;" size="5" onclick="if (this.selectedIndex != -1) this.form.changeto.value = this.options[this.selectedIndex].text;" ondblclick="replaceWord();">
					</select>
				</td>
			</tr></table>
			<div class="righttext" style="padding: 4px;">
				<input type="button" name="change" value="', $txt['spellcheck_change'], '" onclick="replaceWord();" class="button_submit" />
				<input type="button" name="changeall" value="', $txt['spellcheck_change_all'], '" onclick="replaceAll();" class="button_submit" />
				<input type="button" name="ignore" value="', $txt['spellcheck_ignore'], '" onclick="nextWord(false);" class="button_submit" />
				<input type="button" name="ignoreall" value="', $txt['spellcheck_ignore_all'], '" onclick="nextWord(true);" class="button_submit" />
			</div>
		</form>
	</body>
</html>';
}

function template_quotefast()
{
	global $context, $settings, $options, $txt;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
		<title>', $txt['retrieving_quote'], '</title>
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js"></script>
	</head>
	<body>
		', $txt['retrieving_quote'], '
		<div id="temporary_posting_area" style="display: none;"></div>
		<script type="text/javascript"><!-- // --><![CDATA[';

	if ($context['close_window'])
		echo '
			window.close();';
	else
	{
		// Lucky for us, Internet Explorer has an "innerText" feature which basically converts entities <--> text. Use it if possible ;).
		echo '
			var quote = \'', $context['quote']['text'], '\';
			var stage = \'createElement\' in document ? document.createElement("DIV") : document.getElementById("temporary_posting_area");

			if (\'DOMParser\' in window && !(\'opera\' in window))
			{
				var xmldoc = new DOMParser().parseFromString("<temp>" + \'', $context['quote']['mozilla'], '\'.replace(/\n/g, "_SMF-BREAK_").replace(/\t/g, "_SMF-TAB_") + "</temp>", "text/xml");
				quote = xmldoc.childNodes[0].textContent.replace(/_SMF-BREAK_/g, "\n").replace(/_SMF-TAB_/g, "\t");
			}
			else if (\'innerText\' in stage)
			{
				setInnerHTML(stage, quote.replace(/\n/g, "_SMF-BREAK_").replace(/\t/g, "_SMF-TAB_").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				quote = stage.innerText.replace(/_SMF-BREAK_/g, "\n").replace(/_SMF-TAB_/g, "\t");
			}

			if (\'opera\' in window)
				quote = quote.replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, \'"\').replace(/&amp;/g, "&");

			window.opener.oEditorHandle_', $context['post_box_name'], '.InsertText(quote);

			window.focus();
			setTimeout("window.close();", 400);';
	}
	echo '
		// ]]></script>
	</body>
</html>';
}

function template_announce()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<div id="announcement">
		<form action="', $scripturl, '?action=announce;sa=send" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">', $txt['announce_title'], '</h3>
			</div>
			<div class="information">
				', $txt['announce_desc'], '
			</div>
			<div class="windowbg2">
				<span class="topslice"><span></span></span>
				<div class="content">
					<p>
						', $txt['announce_this_topic'], ' <a href="', $scripturl, '?topic=', $context['current_topic'], '.0">', $context['topic_subject'], '</a>
					</p>
					<ul class="reset">';

	foreach ($context['groups'] as $group)
		echo '
						<li>
							<label for="who_', $group['id'], '"><input type="checkbox" name="who[', $group['id'], ']" id="who_', $group['id'], '" value="', $group['id'], '" checked="checked" class="input_check" /> ', $group['name'], '</label> <em>(', $group['member_count'], ')</em>
						</li>';

	echo '
						<li>
							<label for="checkall"><input type="checkbox" id="checkall" class="input_check" onclick="invertAll(this, this.form);" checked="checked" /> <em>', $txt['check_all'], '</em></label>
						</li>
					</ul>
					<div id="confirm_buttons">
						<input type="submit" value="', $txt['post'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						<input type="hidden" name="topic" value="', $context['current_topic'], '" />
						<input type="hidden" name="move" value="', $context['move'], '" />
						<input type="hidden" name="goback" value="', $context['go_back'], '" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</form>
	</div>
	<br />';
}

function template_announcement_send()
{
	global $context, $settings, $options, $txt, $scripturl;

	echo '
	<div id="announcement">
		<form action="' . $scripturl . '?action=announce;sa=send" method="post" accept-charset="', $context['character_set'], '" name="autoSubmit" id="autoSubmit">
			<div class="windowbg2">
				<span class="topslice"><span></span></span>
				<div class="content">
					<p>', $txt['announce_sending'], ' <a href="', $scripturl, '?topic=', $context['current_topic'], '.0" target="_blank" class="new_win">', $context['topic_subject'], '</a></p>
					<p><strong>', $context['percentage_done'], '% ', $txt['announce_done'], '</strong></p>
					<div id="confirm_buttons">
						<input type="submit" name="b" value="', $txt['announce_continue'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						<input type="hidden" name="topic" value="', $context['current_topic'], '" />
						<input type="hidden" name="move" value="', $context['move'], '" />
						<input type="hidden" name="goback" value="', $context['go_back'], '" />
						<input type="hidden" name="start" value="', $context['start'], '" />
						<input type="hidden" name="membergroups" value="', $context['membergroups'], '" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
		</form>
	</div>
	<br />
		<script type="text/javascript"><!-- // --><![CDATA[
			var countdown = 2;
			doAutoSubmit();

			function doAutoSubmit()
			{
				if (countdown == 0)
					document.forms.autoSubmit.submit();
				else if (countdown == -1)
					return;

				document.forms.autoSubmit.b.value = "', $txt['announce_continue'], ' (" + countdown + ")";
				countdown--;

				setTimeout("doAutoSubmit();", 1000);
			}
		// ]]></script>';
}

?>