<?php
/* Woopig 5.0 Theme Index Template
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

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
	$settings['use_tabs'] = false;

	/* Use plain buttons - as oppossed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status seperate from topic icons? */
	$settings['seperate_sticky_lock'] = true;
	$settings['message_index_preview'] = true;

	// extra strings
	loadLanguage('ThemeStrings');

	 if(!$context['user']['is_guest'] && isset($_POST['options']['mypic']))
	{
		include_once($GLOBALS['sourcedir'] . '/Profile-Modify.php');
			makeThemeChanges($context['user']['id'], $settings['theme_id']);
			$options['mypic'] = $_POST['options']['mypic'];
	}
	elseif ($context['user']['is_guest'])
	{
			if (isset($_POST['options']['mypic']))
			{
				$_SESSION['mypic'] = $_POST['options']['mypic'];
				$options['mypic'] = $_SESSION['mypic'];
			}
			elseif (isset($_SESSION['mypic']))
				$options['mypic'] = $_SESSION['mypic'];
	}

}

// The main sub template above the content.
function template_main_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	
	echo $context['tapatalk_body_hook'];

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';
	
	// Apple Touch Icons
	echo'	<link rel="apple-touch-icon" sizes="57x57" href="apple-icon-57x57.png" />
			<link rel="apple-touch-icon" sizes="72x72" href="apple-icon-72x72.png" />
			<link rel="apple-touch-icon" sizes="114x114" href="apple-icon-114x114.png" />
			<link rel="apple-touch-icon" sizes="144x144" href="apple-icon-144x144.png" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';
	
	echo '
	<link rel="stylesheet" type="text/css" media="only screen and (min-device-width : 375px) and (max-device-width : 667px)" href="', $settings['theme_url'], '/css/mobile.css" />
	<link rel="stylesheet" type="text/css" media="only screen and (min-device-width : 768px) and (max-device-width : 1024px)" href="', $settings['theme_url'], '/css/mobile.css" />
	<link rel="stylesheet" type="text/css" media="only screen and (min-device-width : 320px) and (max-device-width : 568px)" href="', $settings['theme_url'], '/css/mobile.css" />
	';
	//xxSHxx - changing above trying to fix mobile font size
	//<style> 	
	//@media only screen and (min-device-width : 769px) and (max-device-width : 1024px) { html, body { font-size: 110% } h3, ul { font-size: 100% } } 
	//@media only screen and (min-device-width : 569px) and (max-device-width : 768px) { html, body { font-size: 105% } } 
	//@media only screen and (min-device-width : 320px) and (max-device-width : 568px) { html, body { font-size: 100% } } 
	//</style>
	// Mobile Arrays
	
	$context['browser']['is_mobile'] = $context['browser']['is_iphone'] || $context['browser']['is_ie_mobile'] || $context['browser']['is_android_phone'] || $context['browser']['is_blackberry'] || $context['browser']['is_symbian'] || $context['browser']['is_netfront'] || $context['browser']['is_palm'] || $context['browser']['is_web_os'] || $context['browser']['is_opera_mobi'] || $context['browser']['is_opera_mini'] || $context['browser']['is_fennec'];


	$context['browser']['is_tablet'] = $context['browser']['is_ipad'] || $context['browser']['is_android_tablet'];

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';
	
	echo $context['tapatalk_body_hook'];

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript" src="http://woopig.net/board/Themes/woopig5/scripts/countdown.js" defer="defer"></script>
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

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="viewport" content="width=1260" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	//<meta name="viewport" content="width=1260">
	//<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
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
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

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

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];
echo '</head>
<body>';

	userbox();
	/*searchbox();*/
	/*newsbox();*/
	
	/*echo '
<!-- <div id="maintable">
	<div id="picture_choose">
	<form class="middletext" name="FormName" action="',$scripturl,'" method="post" style="margin: 0; padding: 0;">
<select size="1" name="options[mypic]" onChange="submit()">
  <option value="" ', (!empty($options['mypic']) && $options['mypic']=='') ? 'selected' : '' ,'>Helmet</option>
  <option value="1"  ', (!empty($options['mypic']) && $options['mypic']=='1') ? 'selected' : '' ,'>RRS</option>
  <option value="10"  ', (!empty($options['mypic']) && $options['mypic']=='10') ? 'selected' : '' ,'>Baum</option>
  <option value="13"  ', (!empty($options['mypic']) && $options['mypic']=='13') ? 'selected' : '' ,'>Bielema 1</option>
  <option value="14"  ', (!empty($options['mypic']) && $options['mypic']=='14') ? 'selected' : '' ,'>Bielema 2</option>
  <option value="15"  ', (!empty($options['mypic']) && $options['mypic']=='15') ? 'selected' : '' ,'>RBI Girls</option>
  <option value="17"  ', (!empty($options['mypic']) && $options['mypic']=='17') ? 'selected' : '' ,'>Hog Baseball</option>
  <option value="18"  ', (!empty($options['mypic']) && $options['mypic']=='18') ? 'selected' : '' ,'>Qualls Dunk</option>
  <option value="21"  ', (!empty($options['mypic']) && $options['mypic']=='21') ? 'selected' : '' ,'>WMS</option>
  <option value="22"  ', (!empty($options['mypic']) && $options['mypic']=='22') ? 'selected' : '' ,'>Hog Tennis</option>
  <option value="23"  ', (!empty($options['mypic']) && $options['mypic']=='23') ? 'selected' : '' ,'>The Shot</option>
  <option value="24"  ', (!empty($options['mypic']) && $options['mypic']=='24') ? 'selected' : '' ,'>Champion</option>
  <option value="36"  ', (!empty($options['mypic']) && $options['mypic']=='36') ? 'selected' : '' ,'>Mike Anderson</option>
  <option value="37"  ', (!empty($options['mypic']) && $options['mypic']=='37') ? 'selected' : '' ,'>Bobby Portis</option>
  <option value="55"  ', (!empty($options['mypic']) && $options['mypic']=='55') ? 'selected' : '' ,'>Mike Anderson 2</option>
  <option value="45"  ', (!empty($options['mypic']) && $options['mypic']=='45') ? 'selected' : '' ,'>Angry Van Horn</option>
  <option value="53"  ', (!empty($options['mypic']) && $options['mypic']=='53') ? 'selected' : '' ,'>Hog Football</option>
  <option value="54"  ', (!empty($options['mypic']) && $options['mypic']=='54') ? 'selected' : '' ,'>Hog Basketball</option>

  </select>
			</form></div> -->
	<!-- <div id="logo"><span class="logotext"><a href="',$scripturl,'">' , $context['forum_name'], '</a></span>
	<br /><span class="smalltext" style="color: #000000">' ,$context['current_time'],'</span></div>; -->*/

if ($context['browser']['is_ie8'] || $context['browser']['is_ie7'] || $context['browser']['is_ie6'])
	echo '<div><img src="http://woopig.net/board/teh_woopig_sad.png" style="position:relative; left:280px"></div>';
else
	echo '

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td></td>
	<td>
	<div id="mainmenu">' , template_menu() , '</div><div class="css-slideshow"> 
  		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1a.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1b.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1c.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1d.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1e.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1f.png"/> 
		</figure>
		<figure> 
    		<img src="',$settings['images_url'],'/rotate/WP1g.png"/> 
		</figure>
	</div></td>
	<td><div class="topRrepeat"></div></td></tr>

	';
	
echo '
	
	<div class="customPMbox"></div>
	<div class="WoopigLogo"><img src="',$settings['images_url'],'/img/woopig_logo.png" alt="" /></div>

	
	<!-- <table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td><img src="',$settings['images_url'],'/img/topl.png" alt="" /></td>
			<td width="99%" style="background: url(',$settings['images_url'],'/img/topm.png); margin-left: auto; margin-right: auto;"></td>
			<td><img src="',$settings['images_url'],'/img/topr', isset($options['mypic']) ? $options['mypic'] : '' , '.jpg" alt="" /></td>
			<td><img src="',$settings['images_url'],'/img/topr.png" alt="" /></td>
		<tr>
	</table> -->
	<table class="maincontent" cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td style="background: url(',$settings['images_url'],'/img/left-up.png) no-repeat;"></td>
			<td class="TDLinkTree"><div id="linktree">' , theme_linktree2() , '</div></td>
			<td style="background: url(',$settings['images_url'],'/img/right-up.png) no-repeat;"></td>
		</tr>
		<tr>
			<td style="background: url(',$settings['images_url'],'/img/left.png) repeat-y;"><img src="',$settings['images_url'],'/img/left.png" alt="" /></td>
			<td width="100%">';

}

function template_main_below()
{
	global $context, $settings, $options, $scripturl, $txt , $user_info;



	// This is an interesting bug in Internet Explorer AND Safari. Rather annoying, it makes overflows just not tall enough.
	if (($context['browser']['is_ie'] && !$context['browser']['is_ie4']) || $context['browser']['is_mac_ie'] || $context['browser']['is_safari'] || $context['browser']['is_firefox'])
	{
		// The purpose of this code is to fix the height of overflow: auto div blocks, because IE can't figure it out for itself.
		echo '
		<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[';

		// Unfortunately, Safari does not have a "getComputedStyle" implementation yet, so we have to just do it to code...
		if ($context['browser']['is_safari'])
			echo '
			window.addEventListener("load", smf_codeFix, false);

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if ((codeFix[i].className == "code" || codeFix[i].className == "post" || codeFix[i].className == "signature") && codeFix[i].offsetHeight < 20)
						codeFix[i].style.height = (codeFix[i].offsetHeight + 20) + "px";
				}
			}';
		elseif ($context['browser']['is_firefox'])
			echo '
			window.addEventListener("load", smf_codeFix, false);
			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = 0; i < codeFix.length; i++)
				{
					if (codeFix[i].className == "code" && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0))
						codeFix[i].style.overflow = "scroll";
				}
			}';			
		else
			echo '
			var window_oldOnload = window.onload;
			window.onload = smf_codeFix;

			function smf_codeFix()
			{
				var codeFix = document.getElementsByTagName ? document.getElementsByTagName("div") : document.all.tags("div");

				for (var i = codeFix.length - 1; i > 0; i--)
				{
					if (codeFix[i].currentStyle.overflow == "auto" && (codeFix[i].currentStyle.height == "" || codeFix[i].currentStyle.height == "auto") && (codeFix[i].scrollWidth > codeFix[i].clientWidth || codeFix[i].clientWidth == 0) && (codeFix[i].offsetHeight != 0 || codeFix[i].className == "code"))
						codeFix[i].style.height = (codeFix[i].offsetHeight + 36) + "px";
				}

				if (window_oldOnload)
				{
					window_oldOnload();
					window_oldOnload = null;
				}
			}';

		echo '
		// ]]></script>';
	}

	echo ''/*</td>
			<td style="background: url(',$settings['images_url'],'/img/right.png) repeat-y;"><img src="',$settings['images_url'],'/img/right.png" alt="" /></td>
		</tr>
		<tr>
			<td><img src="',$settings['images_url'],'/img/botleft.png" alt="" /></td>
			'<td width="100%"><img style="float:right;" src="',$settings['images_url'],'/img/cup_required.png" alt="" /-->'*/;

	echo '<p></p><div class="smalltext" id="copyw">' , theme_copyright(), '</div>
				';

	// Show the load time?
	if ($context['show_load_time'])
		echo '<p class="smalltext" style="color:#FAFAFA;">', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '</td>
			<td valign="middle"><img src="',$settings['images_url'],'/img/botright.png" alt="" /></td>
		</tr>
	</table></div>';
	
	echo '
	<div id="ajax_in_progress" style="display: none;', $context['browser']['is_ie'] && !$context['browser']['is_ie7'] ? 'position: absolute;' : '', '">', $txt['ajax_in_progress'], '</div>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree()
{
	return;
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree2()
{
	global $context, $settings, $options;

	/*
	//from Sources/Recent.php
	$context['linktree'][] = array(
		'url' => $scripturl . '?action=' . $_REQUEST['action'] . sprintf($context['querystring_board_limits'], 0) . $context['querystring_sort_limits'],
		'name' => $_REQUEST['action'] == 'unread' ? $txt['unread_topics_visit'] : $txt['unread_replies']
	);
	*/
	
	echo '<div class="nav">';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		//xxSHxx 20180317 removing RECENT UNREAD TOPICS
		//this is a real hack and I don't like it but I can't figure out the best way to 
		//keep this array from storing the unread topics item without breaking something
		//echo $tree['name'];
		//if ( !in_array(strtolower($tree['name']), array('recent unread topics'), true) ) 
		//{
		
			// Show something before the link?
			if (isset($tree['extra_before']))
				echo $tree['extra_before'];

			// Show the link, including a URL if it should have one.
			echo '', $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '" class="nav">' . $tree['name'] . '</a>' : $tree['name'], '';

			// Show something after the link...?
			if (isset($tree['extra_after']))
				echo $tree['extra_after'];

			// Don't show a separator for the last one.
			if ($link_num != count($context['linktree']) - 1)
				echo '&nbsp;-&nbsp;';
		//}
	}

	echo '</div>';
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Work out where we currently are.
	$current_action = 'home';
	if (in_array($context['current_action'], array('admin', 'ban', 'boardrecount', 'cleanperms', 'detailedversion', 'dumpdb', 'featuresettings', 'featuresettings2', 'findmember', 'maintain', 'manageattachments', 'manageboards', 'managecalendar', 'managesearch', 'membergroups', 'modlog', 'news', 'optimizetables', 'packageget', 'packages', 'permissions', 'pgdownload', 'postsettings', 'regcenter', 'repairboards', 'reports', 'serversettings', 'serversettings2', 'smileys', 'viewErrorLog', 'viewmembers')))
		$current_action = 'admin';
	if (in_array($context['current_action'], array('search', 'admin', 'calendar', 'profile', 'mlist', 'register', 'login', 'help', 'pm')))
		$current_action = $context['current_action'];
	if ($context['current_action'] == 'search2')
		$current_action = 'search';
	if ($context['current_action'] == 'theme')
		$current_action = isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'pick' ? 'profile' : 'admin';

	// Are we using right-to-left orientation?
	if ($context['right_to_left'])
	{
		$first = 'last';
		$last = 'first';
	}
	else
	{
		$first = 'first';
		$last = 'last';
	}

	// Show the start of the tab section.
	echo '
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="maintab_' , $first , '">&nbsp;</td>';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		if($button['active_button'])
			echo '<td class="maintab_active_first">&nbsp;</td>
				<td  class="maintab_active_back">
					<a href="', $button['href'], '">' , $button['title'] , '</a>
				</td><td class="maintab_active_last">&nbsp;</td>';
		else
			echo '
				<td  class="maintab_back">
					<a href="', $button['href'], '">' , $button['title'] , '</a>
				</td>';

	}

	// The end of tab section.
	echo '
				<td class="maintab_' , $last , '">&nbsp;</td>
			</tr>
		</table>';

}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<li>', '<li class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

// userbox with countdown timer
function userbox()
{
			 global $context, $settings, $options, $scripturl, $txt, $modSettings;

//			<span class="boxlinks">&nbsp;&nbsp;<a href="', $scripturl, '?action=unread;all;start=0">ALL UNREAD TOPICS</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="', $scripturl, '?action=unreadreplies">ALL UNREAD REPLIES</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="', $scripturl, '?action=recenttopics">RECENT TOPICS FEED</a></div></span>';

		//xxSHxx 20180327 - setup next event calendar info and link
		$calendar_php = dirname(dirname(__FILE__)) . '/../../calsports/events_next_func.php';
		require_once ($calendar_php);
		$next_event = get_next_event();

		echo '<div id="pmbox">';
		if($context['user']['is_logged']){
			echo ' <b>', $context['user']['name'], '</b>&nbsp;<!-- countdown timer <img style="margin-left:20px" src="',$settings['images_url'],'/rbfb.gif"></img>&nbsp;<span class="countdown" id="countdown1">2015-09-05 14:30:00 GMT-05:00</span> -->
			<span class="boxlinks">&nbsp;&nbsp;<a href="', $scripturl, '?action=unread;all;start=0">ALL UNREAD TOPICS</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="'
			, $scripturl
			, '?action=unreadreplies">ALL UNREAD REPLIES</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="'
			, $scripturl, '?action=recenttopics">RECENT TOPICS FEED</a>&nbsp;&nbsp;|'
			//. $calendar_php . '</div></span>';
			. '&nbsp;' . $next_event . '</div></span>';
			
		}
		else
			echo $txt['welcome_guest'];

		echo '</div>';

			echo'<div id="userbox"><table width="100%" cellpadding="0" cellspacing="5" border="0"><tr>';
		  echo '<td width="28%" valign="top" class="smalltext" style="font-family: "LatoRegular", verdana, arial, sans-serif;">';

		if($context['user']['is_logged']){

		// Is the forum in maintenance mode?
		if ($context['in_maintenance'] && $context['user']['is_admin'])
			echo '
								<br /><b>', $txt['maintain_mode_on'], '</b>';

		// Are there any members waiting for approval?
		if (!empty($context['unapproved_members']))
			echo '
								<br />', $context['unapproved_members'] == 1 ? $txt['approve_thereis'] : $txt['approve_thereare'], ' <a href="', $scripturl, '?action=viewmembers;sa=browse;type=approve">', $context['unapproved_members'] == 1 ? $txt['approve_member'] : $context['unapproved_members'] . ' ' . $txt['approve_members'], '</a> ', $txt['approve_members_waiting'], '';
		/*echo '
								<div class="boxlinks">&nbsp;&nbsp;<a href="', $scripturl, '?action=unread;all;start=0">ALL UNREAD TOPICS</a>
								&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="', $scripturl, '?action=unreadreplies">UNREAD REPLIES</a>
								&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="', $scripturl, '?action=recenttopics">RECENT TOPICS FEED</a></div>';*/
								


		  }
		  // Otherwise they're a guest - so politely ask them to register or login.
		  else
		  {
					 echo ' <span class="blockmain">
																		  <form style="margin-top: 0" class="blockmain" action="', $scripturl, '?action=login2" accept-charset="', $context['character_set'], '" method="post" ', empty($context['disable_login_hashing']) ? '  onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', ' >
																					 ';

		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';

		echo '
					<input type="hidden" name="hash_passwrd" value="" />
																		  </form>';
		  }

		  echo '
																</td></tr></table></div>';

}

/*function countdown_clock()
{
		  global $context, $settings, $options, $scripturl, $txt, $modSettings;
	{	
		echo '<div id="countdownbox" align="center">
				<object type="application/x-shockwave-flash" data="http://woopig.net/main/images/fl_countdown_v3_3.swf?yr=2013&amp;mo=8&amp;da=31&amp;ho=18&amp;co=cc0000" width="230" height="25" wmode="transparent">
						<param name="movie" value="http://woopig.net/main/images/fl_countdown_v3_3.swf?yr=2010&amp;mo=9&amp;da=4&amp;ho=18&amp;co=cc0000" />
						<param name=wmode value=transparent> 
						<p><!--alternative content-->If you had Flash installed you would see a countdown clock here.</p>
					</object>
				</div>';
	}	  
}*/

function newsbox()
{
		  global $context, $settings, $options, $scripturl, $txt, $modSettings;

		  // Show a random news item? (or you could pick one from news_lines...)
		  if (!empty($settings['enable_news']))
					 echo '<div id="newsbox" style="padding: 5px;" >', $context['random_news_line'], '</div>
													 ';
}

function statsbox()
{
		 global $context, $settings, $options, $scripturl, $txt, $modSettings;
		 echo '
						<div id="statsbox" style="padding: 5px;" class="smalltext">
						 </div>';

}
function searchbox()
{
	 global $context, $settings, $options, $txt , $scripturl;

	echo '<div id="searchbox">
				<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
					<input type="text" name="search" value="" class="input_text" />&nbsp;
					<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
					<input type="hidden" name="advanced" value="0" />';

	// Search within current topic?
	if (!empty($context['current_topic']))
		echo '
					<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
		echo '
					<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

	echo '</form>
		</div>';

}


?>