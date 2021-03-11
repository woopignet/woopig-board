<?php
/***********************************************************************************
*                                                                                 *
* SMF Mobile Theme v1.1.5                                                    	  *
* Copyright (c) 2016-2020 by SMFMobileTheme.com All rights reserved.   			  *
* Powered by www.smfmobiletheme.com                                               *
* Developed by NIBOGO for SMFMobileTheme.com                                      *
*                                                                                 *
***********************************************************************************
* THIS IS PART OF A PAID PRODUCT WHICH IS AVAILABLE AT SMFMobileTheme.COM YOU     *
* CANNOT USE IT IF YOU DOWNLOADED THIS FROM ELSEWHERE OR IF YOU DON'T HAVE A      *
* VALID LICENSE. IF YOU DID DOWNLOADED THIS MOD FROM ANOTHER WEBSITE PLEASE   	  *
* REPORT IT HERE: contact@smfmobiletheme.com									  *
***********************************************************************************/

use MatthiasMullie\Minify;

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt, $modSettings, $disableEzPortal, $boarddir, $boardurl, $compressed_name;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = false;
	
	/* Theme variant */
	$settings['theme_variants'] = array('aloe', 'candy', 'melon', 'mint', 'royal', 'sand', 'slate', 'water');
	
	/* Theme Strings */
	if (loadLanguage('MobileTheme') == false)
		loadLanguage('MobileTheme', 'english');
	
	// Now disable PortaMx
	$modSettings['pmxportal_disabled'] = 1;
	$_SESSION['pmx_paneloff'] = $modSettings['pmx_paneloff'] = 'head,top,left,right,bottom,foot,front,pages';
	
	// SimplePortal too...
	$settings['disable_sp'] = $context['disable_sp'] = true;
	$modSettings['sp_portal_mode'] = 0;
	
	// EzPortal is just easy...
	$disableEzPortal = true;

	// TinyPortal
	if (function_exists('tp_hidebars'))
		tp_hidebars();

	// $_SESSION['pmx_paneloff'] = $modSettings['pmx_paneloff'] = array('head', 'top', 'left', 'right', 'bottom', 'foot', 'front', 'pages' => 'Pages');

	// We'll be using this array a lot later
	$context['footer_controls'] = array();

	// Disable the Ad. management mod
	$modSettings['ads_quickDisable'] = true;

	// Whether to use native menu or not(never)
	$context['use_native_menu'] = 'false';

	// Disable SMFPacks Advanced Editor
	$modSettings['advanced_editor_master'] = false;
	
	// Compress Javascript
	$compressed_name = 'compressed.js';
	$js_path = $settings['actual_theme_dir'] . '/scripts/';
	$files = array('jquery.js', 'jquery.mobile-1.4.5.js', 'theme.js');
	$filemtime = file_exists($js_path . $compressed_name) ? filemtime($js_path . $compressed_name) : 0;
	$compress_js = false;
	foreach ($files as $file)
		if (filemtime($js_path . $file) > $filemtime)
			$compress_js = true;
	
	if ($compress_js)
	{
		require_once($settings['actual_theme_dir'] . '/compressor/src/Minify.php');
		require_once($settings['actual_theme_dir'] . '/compressor/src/JS.php');
		load_minify_library($settings['actual_theme_dir']);
		
		$sourcePath = $js_path . '/jquery.js';
		$minifier = new Minify\JS($sourcePath);

		foreach ($files as $file)
		{
			if ($file == 'jquery.js')
				continue;
				
			$minifier->add($js_path . '/' . $file);
		}

		// Save minified file to disk
		$minifiedPath = $js_path . $compressed_name;
		$minifier->minify($minifiedPath);
		@chmod($js_path . $compressed_name, 0755);
	}
	
	// Tidy up
	unset($js_path, $compress_js, $files);
}

// The main sub template above the content.
function template_html_above()
{
	// TinyPortal must be killed here!
	if (function_exists('tp_hidebars'))
		tp_hidebars('all');
		
	global $context, $settings, $options, $scripturl, $txt, $modSettings, $compressed_name;
	
	// Compress CSS (must be done here for the theme variant)
	$compressed_name = substr($context['theme_variant'], 1) . '_compressed.css';
	$css_path = $settings['actual_theme_dir'] . '/css/';
	$files = array('index.css', substr($context['theme_variant'], 1) . '/jquery.mobile-1.4.2.css');
	
	$filemtime = file_exists($css_path . $compressed_name) ? filemtime($css_path . $compressed_name) : 0;
	$compress_css = false;
	foreach ($files as $file)
		if (filemtime($css_path . $file) > $filemtime)
			$compress_css = true;
	
	if ($compress_css)
	{
		require_once($settings['actual_theme_dir'] . '/compressor/src/Minify.php');
		load_minify_library($settings['actual_theme_dir']);
		require_once($settings['actual_theme_dir'] . '/compressor/src/CSS.php');
		
		$sourcePath = $css_path . '/index.css';
		$minifier = new Minify\CSS($sourcePath);

		foreach ($files as $file)
		{
			if ($file == 'index.css')
				continue;
				
			$minifier->add($css_path . '/' . $file);
		}

		// Save minified file to disk
		$minifiedPath = $css_path . $compressed_name;
		$minifier->minify($minifiedPath);
		@chmod($css_path . $compressed_name, 0755);
	}
	
	// Tidy up
	unset($compress_css, $filemtime, $files, $css_path);

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '><head>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="' . $scripturl . '?action=search" />
	<link rel="contents" href="', $scripturl, '" />
	<meta name="viewport" content="width=device-width, initial-scale=1">';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// We'll have to use the cookie to remember the header...
	if ($context['user']['is_guest'])
	{
		$options['collapse_header'] = !empty($_COOKIE['upshrink']);
		$options['collapse_header_ic'] = !empty($_COOKIE['upshrinkIC']);
	}
	
	// Load the CSS and JS content
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/' . $compressed_name . '" />
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/compressed.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $settings, $context;

	echo '
<div id="main-mobile-page" data-role="page">';

	if (!empty($settings['ads_below_header']) && trim($settings['ads_below_header']) != '')
	echo'
	<div class="container">
		', $settings['ads_below_header'],'
	</div>';	
	
	if (isset($_GET['action']) && $_GET['action'] == 'admin')
	echo'
<div data-role="header" class="ui-btn-active" data-position="fixed">
	<h1>', $context['page_title'], '</h1>
	<a href="#rightpanel2" class="ui-btn-right" data-icon="bars" data-iconpos="notext"></a>
</div><!-- /header -->

	<div role="main" class="ui-content">';
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings, $user_info;
	
	if (!empty($settings['ads_above_footer']) && trim($settings['ads_above_footer']) != '')
	echo'
	<div class="container">
		', $settings['ads_above_footer'],'
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	</div><!-- /content -->
	<div data-role="footer">
		<fieldset class="ui-grid-a">
			<div class="ui-block-a"><a href="', $scripturl, '?nomobile" class="ui-shadow ui-btn ui-corner-all">', $txt['desktop_view'], '</a></div>
		</fieldset>
	</div><!-- /footer -->
	
	<!-- rightpanel2  -->
	<div data-role="panel" id="rightpanel2" data-position="right" data-display="push" data-theme="b">';
	
	if (!$user_info['is_guest'])
		echo'
        <h3>', $txt['hello_member_ndt'], ' ', $context['user']['name'], '</h3>';
    else
    	echo'
    	<h3>', $txt['hello_member_ndt'], ' ', $txt['guest_title'], '</h3>';
        
    echo '
        <ul data-role="listview" data-count-theme="a">
			<li><a href="', $scripturl, '">', $txt['home'], '</a></li>';
			
	if (allowedTo('pm_read'))
		echo'
			<li><a href="', $scripturl, '?action=pm">', $txt['pm_short'], ($context['user']['unread_messages'] > 0 ? ' <span class="ui-li-count">' . $context['user']['unread_messages'] . '</span>' : ''), '</a></li>';
			
	if (allowedTo('search_posts'))
		echo'
			<li><a href="', $scripturl, '?action=search">', $txt['search'], '</a></li>';
			
	if (allowedTo('view_mlist'))
		echo'
			<li><a href="', $scripturl, '?action=mlist">', $txt['members_title'], '</a></li>';
			
	echo'
			<li><a href="', $scripturl, '?action=recent">', $txt['recent_posts'], '</a></li>';
			
	if ($context['allow_admin'])
		echo'
			<li><a href="', $scripturl, '?action=admin">', $txt['admin'], '</a></li>';
			
	if (!$user_info['is_guest'])
		echo'
			<li><a href="', $scripturl, '?action=profile">', $txt['profile'], '</a></li>
			<li><a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a></li>
			<li><a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a></li>
			<li><a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '">', $txt['logout'], '</a></li>';
	else
		echo'
			<li><a href="', $scripturl, '?action=register">', $txt['register'], '</a></li>
			<li><a href="', $scripturl, '?action=login">', $txt['login'], '</a></li>';		
	
	echo '
		</ul>
	</div><!-- /rightpanel2 -->

</div><!-- /page -->';
}

function template_html_below()
{
	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<ul class="linktree" id="linktree_', empty($shown_linktree) ? 'upper' : 'lower', '">';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
		<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
			<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &gt;';

		echo '
		</li>';
	}
	echo '
	</ul>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	<div class="main_menu">
		<ul class="reset clearfix">';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		$classes = array();
		if (!empty($button['active_button']))
			$classes[] = 'active';
		if (!empty($button['is_last']))
			$classes[] = 'last';
		/* IE6 can't do multiple class selectors */
		if ($context['browser']['is_ie6'] && !empty($button['active_button']) && !empty($button['is_last']))
			$classes[] = 'lastactive';

		$classes = implode(' ', $classes);

		echo '
			<li id="button_', $act, '"', !empty($classes) ? ' class="' . $classes . '"' : '', '>
				<a title="', !empty($button['alttitle']) ? $button['alttitle'] : $button['title'], '" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
					<span>', ($button['active_button'] ? '<em>' : ''), $button['title'], ($button['active_button'] ? '</em>' : ''), '</span>
				</a>
			</li>';
	}

	echo '
		</ul>
	</div>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// Right to left menu should be in reverse order.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li' . (isset($value['active']) ? ' class="active"' : '') . '><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . (isset($value['active']) ? '<em>' . $txt[$value['text']] . '</em>' : $txt[$value['text']]) . '</span></a></li>';

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$list_item = array('<li>', '<li class="active">');
	$active_item = array('<li class="last">', '<li class="active last lastactive">');

	$buttons[count($buttons) - 1] = str_replace($list_item, $active_item, $buttons[count($buttons) - 1]);

	$num = rand(10000, 99999);
	echo '
	<a href="#popupMenu', $num, '" data-rel="popup" data-transition="slideup" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-bars ui-btn-icon-left ui-btn-a">', $txt['actions'], '</a>

	<div data-role="popup" id="popupMenu', $num, '" data-theme="b">
			<ul data-role="listview" data-inset="true" style="min-width:210px;">
				<li data-role="list-divider">', $txt['choose_an_action'], '</li>',
				implode('', $buttons), '
			</ul>
	</div>';
}

function load_minify_library($path)
{
	require_once($path . '/compressor/src/Exception.php');
	require_once($path . '/compressor/src/Exceptions/BasicException.php');
	require_once($path . '/compressor/src/Exceptions/FileImportException.php');
	require_once($path . '/compressor/src/Exceptions/IOException.php');
	require_once($path . '/compressor/converter/ConverterInterface.php');
	require_once($path . '/compressor/converter/Converter.php');
}

?>