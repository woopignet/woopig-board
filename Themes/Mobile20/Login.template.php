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

// This is just the basic "login" form.
function template_login()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	
	echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	echo '
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

		<form action="', $scripturl, '?action=login2" name="frmLogin" id="frmLogin" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
		<div class="tborder login">
			<div class="roundframe">';

	// Did they make a mistake last time?
	if (!empty($context['login_errors']))
		foreach ($context['login_errors'] as $error)
			echo '
				<p class="error">', $error, '</p>';

	// Or perhaps there's some special description for this time?
	if (isset($context['description']))
		echo '
				<p class="description">', $context['description'], '</p>';

	// Now just get the basic information - username, password, etc.
	echo '
				<div>', $txt['username'], ':</div>
				<div><input type="text" name="user" size="20" value="', $context['default_username'], '" class="input_text" /></div>
				<div>', $txt['password'], ':</div>
				<div><input type="password" name="passwrd" value="', $context['default_password'], '" size="20" class="input_password" /></div>';

	if (!empty($modSettings['enableOpenID']))
		echo '<p><strong>&mdash;', $txt['or'], '&mdash;</strong></p>
				<div>', $txt['openid'], ':</div>
				<div><input type="text" name="openid_identifier" class="input_text openid_login" size="17" />&nbsp;<em><a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a></em></div><hr />';

	echo '
				<div>', $txt['mins_logged_in'], ':</div>
				<div><input type="text" name="cookielength" id="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '"', $context['never_expire'] ? ' disabled="disabled"' : '', ' class="input_text" /></div>
				
				<label for="cookieneverexp">', $txt['always_logged_in'], ':</label>
	            <select name="cookieneverexp" id="cookieneverexp" data-role="slider">
	                <option value="0">Off</option>
	                <option value="1"', $context['never_expire'] ? ' selected="selected"' : '', '>On</option>
	            </select>';
// If they have deleted their account, give them a chance to change their mind.
if (isset($context['login_show_undelete']))
	echo '
				<div class="alert">', $txt['undelete_account'], ':</div>
				<div><input type="checkbox" name="undelete" class="input_check" /></div>';
	echo '
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><input type="submit" value="', $txt['login'], '" data-theme="a"></div>
					<div class="ui-block-b"><a href="', $scripturl, '?action=reminder" data-theme="b" class="ui-btn ui-btn-b">', $txt['forgot_your_password'], '</a></div>
				</fieldset>
				<input type="hidden" name="hash_passwrd" value="" />
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			</div>
			<span class="lowerframe"><span></span></span>
		</div></form>';

	// Focus on the correct input - username or password.
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.', isset($context['default_username']) && $context['default_username'] != '' ? 'passwrd' : 'user', '.focus();
			
			
			$("#cookieneverexp").change(function() {
				if ($("#cookieneverexp").val() == \'0\')
				{
			  		$("#cookielength").prop(\'disabled\', false);
			  	}
			  	else
			  	{
			  		$("#cookielength").prop(\'disabled\', true);
			  	}
			}).change();
			
		// ]]></script>';
}

// Tell a guest to get lost or login!
function template_kick_guest()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	
	echo'
<div data-role="header" style="overflow:hidden;" class="ui-btn-active ui-state-persist" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="', $scripturl, '" data-icon="back" data-direction="reverse" class="ui-btn-left" data-iconpos="notext"></a>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

<div role="main" class="ui-content">';

	// This isn't that much... just like normal login but with a message at the top.
	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
	<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
		<h3 class="catbg">', $txt['warning'], '</h3>';

	// Show the message or default message.
	echo '
			<p class="information centertext">
				', empty($context['kick_message']) ? $txt['only_members_can_access'] : $context['kick_message'], '<br />
				', $txt['login_below'], ' <a href="', $scripturl, '?action=register">', $txt['register_an_account'], '</a> ', sprintf($txt['login_with_forum'], $context['forum_name_html_safe']), '
			</p>';

	// And now the login information.
	echo '
			<div>', $txt['username'], ':</div>
			<div><input type="text" name="user" size="20" class="input_text" /></div>
			<div>', $txt['password'], ':</div>
			<div><input type="password" name="passwrd" size="20" class="input_password" /></div>';

	if (!empty($modSettings['enableOpenID']))
		echo '
			<p><strong>&mdash;', $txt['or'], '&mdash;</strong></p>
			<div>', $txt['openid'], ':</div>
			<div><input type="text" name="openid_identifier" class="input_text openid_login" size="17" /></div>
			<hr />';

	echo '
			<div>', $txt['mins_logged_in'], ':</div>
			<div><input type="text" name="cookielength" id="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '" class="input_text" /></div>
			<label for="cookieneverexp">', $txt['always_logged_in'], ':</label>
            <select name="cookieneverexp" id="cookieneverexp" data-role="slider">
                <option value="0">Off</option>
                <option value="1">On</option>
            </select>
			<fieldset class="ui-grid-a">
				<div class="ui-block-a"><input type="submit" value="', $txt['login'], '" data-theme="a"></div>
				<div class="ui-block-b"><a href="', $scripturl, '?action=reminder" data-theme="b" class="ui-btn ui-btn-b">', $txt['forgot_your_password'], '</a></div>
			</fieldset>
			<input type="hidden" name="hash_passwrd" value="" />
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
	</form>';

	// Do the focus thing...
	echo '
		<script type="text/javascript"><!-- // --><![CDATA[
			document.forms.frmLogin.user.focus();
			
			$("#cookieneverexp").change(function() {
				if ($("#cookieneverexp").val() == \'0\')
				{
			  		$("#cookielength").prop(\'disabled\', false);
			  	}
			  	else
			  	{
			  		$("#cookielength").prop(\'disabled\', true);
			  	}
			}).change();
		// ]]></script>';
}

// This is for maintenance mode.
function template_maintenance()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Display the administrator's message at the top.
	echo '
<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>
	<div class="tborder login" id="maintenance_mode">
		<div class="cat_bar">
			<h3 class="catbg">', $context['title'], '</h3>
		</div>
		<p class="description">
			<img class="floatleft" src="', $settings['images_url'], '/construction.png" width="40" height="40" alt="', $txt['in_maintain_mode'], '" />
			', $context['description'], '<br class="clear" />
		</p>
		<div class="title_bar">
			<h4 class="titlebg">', $txt['admin_login'], '</h4>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe">
			<dl>
				<dt>', $txt['username'], ':</dt>
				<dd><input type="text" name="user" size="20" class="input_text" /></dd>
				<dt>', $txt['password'], ':</dt>
				<dd><input type="password" name="passwrd" size="20" class="input_password" /></dd>
				<dt>', $txt['mins_logged_in'], ':</dt>
				<dd><input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '" class="input_text" /></dd>
				<dt>', $txt['always_logged_in'], ':</dt>
				<dd><input type="checkbox" name="cookieneverexp" class="input_check" /></dd>
			</dl>
			<p class="centertext"><input type="submit" value="', $txt['login'], '" class="button_submit" /></p>
		</div>
		<span class="lowerframe"><span></span></span>
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		<input type="hidden" name="hash_passwrd" value="" />
	</div>
</form>';
}

// This is for the security stuff - makes administrators login every so often.
function template_admin_login()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Since this should redirect to whatever they were doing, send all the get data.
	echo '
<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

<form action="', $scripturl, $context['get_data'], '" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin" onsubmit="hashAdminPassword(this, \'', $context['user']['username'], '\', \'', $context['session_id'], '\');">
	<div class="tborder login" id="admin_login">
		<div class="cat_bar">
			<h3 class="catbg">
				<span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/login_sm.gif" alt="" class="icon" /> ', $txt['login'], '</span>
			</h3>
		</div>
		<span class="upperframe"><span></span></span>
		<div class="roundframe centertext">';

	if (!empty($context['incorrect_password']))
		echo '
			<div class="error">', $txt['admin_incorrect_password'], '</div>';

	echo '
			<strong>', $txt['password'], ':</strong>
			<input type="password" name="admin_pass" size="24" class="input_password" />
			<a href="', $scripturl, '?action=helpadmin;help=securityDisable_why" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" /></a><br />
			<input type="submit" style="margin-top: 1em;" value="', $txt['login'], '" class="button_submit" />';

	// Make sure to output all the old post data.
	echo $context['post_data'], '
		</div>
		<span class="lowerframe"><span></span></span>
	</div>
	<input type="hidden" name="admin_hash_pass" value="" />
</form>';

	// Focus on the password box.
	echo '
<script type="text/javascript"><!-- // --><![CDATA[
	document.forms.frmLogin.admin_pass.focus();
// ]]></script>';
}

// Activate your account manually?
function template_retry_activate()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<form action="', $scripturl, '?action=activate;u=', $context['member_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="title_bar">
				<h3 class="titlebg">', $context['page_title'], '</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">';

	// You didn't even have an ID?
	if (empty($context['member_id']))
		echo '
				<dl>
					<dt>', $txt['invalid_activation_username'], ':</dt>
					<dd><input type="text" name="user" size="30" class="input_text" /></dd>';

	echo '
					<dt>', $txt['invalid_activation_retry'], ':</dt>
					<dd><input type="text" name="code" size="30" class="input_text" /></dd>
				</dl>
				<p><input type="submit" value="', $txt['invalid_activation_submit'], '" class="button_submit" /></p>
			</div>
			<span class="lowerframe"><span></span></span>
		</form>';
}

// Activate your account manually?
function template_resend()
{
	global $context, $settings, $options, $txt, $scripturl;

	// Just ask them for their code so they can try it again...
	echo '
		<form action="', $scripturl, '?action=activate;sa=resend" method="post" accept-charset="', $context['character_set'], '">
			<div class="title_bar">
				<h3 class="titlebg">', $context['page_title'], '</h3>
			</div>
			<span class="upperframe"><span></span></span>
			<div class="roundframe">
				<dl>
					<dt>', $txt['invalid_activation_username'], ':</dt>
					<dd><input type="text" name="user" size="40" value="', $context['default_username'], '" class="input_text" /></dd>
				</dl>
				<p>', $txt['invalid_activation_new'], '</p>
				<dl>
					<dt>', $txt['invalid_activation_new_email'], ':</dt>
					<dd><input type="text" name="new_email" size="40" class="input_text" /></dd>
					<dt>', $txt['invalid_activation_password'], ':</dt>
					<dd><input type="password" name="passwd" size="30" class="input_password" /></dd>
				</dl>';

	if ($context['can_activate'])
		echo '
				<p>', $txt['invalid_activation_known'], '</p>
				<dl>
					<dt>', $txt['invalid_activation_retry'], ':</dt>
					<dd><input type="text" name="code" size="30" class="input_text" /></dd>
				</dl>';

	echo '
				<p><input type="submit" value="', $txt['invalid_activation_resend'], '" class="button_submit" /></p>
			</div>
			<span class="lowerframe"><span></span></span>
		</form>';
}

?>