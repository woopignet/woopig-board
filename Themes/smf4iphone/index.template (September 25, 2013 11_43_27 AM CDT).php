<?php
// Version: 2.0 RC4; Index

$linguaggio = $settings['theme_dir'].'/languages/iPhone.language.' . $context['user']['language'] . '.php';
if (file_exists($linguaggio))
	require($settings['theme_dir'].'/languages/iPhone.language.' . $context['user']['language'] . '.php');
else
	require($settings['theme_dir'].'/languages/iPhone.language.english.php');


global $txt;

function template_init()
{
	global $context, $settings, $options, $txt;

	$settings['theme_version'] = '1.1';
	
	// Portal disabling mafia
	// SimplePortal
	$settings['disable_sp'] = true;

	// TinyPortal
	if (function_exists('tp_hidebars'))
		tp_hidebars();

	// PortaMX
	$_SESSION['pmx_paneloff'] = array('head', 'top', 'left', 'right', 'bottom', 'foot', 'front', 'pages' => 'Pages');
}

function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo

'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>', $context['page_title_html_safe'], '</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<link rel="stylesheet" type="text/css" href="', $settings['theme_url'] ,'/style.css" media="screen" />

<style type="text/css">';

if ((!empty($_COOKIE['childboards'])) && ($_COOKIE['childboards'])) {
	echo 'ul .child{ display:block; }';
}
else {
	echo '';
}

if ($context['user']['unread_messages']) {
	echo '

	#tabbar #tabmessages{

	background: url(', $settings['theme_url'] ,'/images/tabBar/messagesNew.png) no-repeat center;

}';
}
else {
echo '';
}
echo '

</style>

<script type="application/x-javascript">

', (isset($_COOKIE['disablequoting'])) ? 'var aquoting = 1;

' : 'var aquoting = 0;

', 

'addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);

function hideURLbar(){
if (location.href.indexOf(\'#\')<1){
window.scrollTo(0,1);
}
}

var showchildboards = "', $txt['iShow'] ,' ', $txt['parent_boards'], '";
var hidechildboards = "', $txt['iHide'] ,' ', $txt['parent_boards'], '";
var quotingoff = "', $txt['iQuoting'],' ',$txt['iOff'], '";
var loading = "', $txt['iLoading'],'...";

</script>

<script type="application/x-javascript" src="'. $settings['theme_url'] .'/iphone.js"></script>',
((!empty($_GET['topic'])) && ($_GET['topic'])) ? '
<script type="application/x-javascript" src="'. $settings['theme_url'] .'/quote.js"></script>' : '';


	
	if ($context['user']['is_guest'])
	{
		$options['collapse_header'] = !empty($_COOKIE['upshrink']);
		$options['collapse_header_ic'] = !empty($_COOKIE['upshrinkIC']);
	}

	echo '
</head>
<body', !$_GET ? ' onload="setDefaults();"' : '', '><div id="wrapper">';
}

function iPhoneTitle(){

	global $context;

	$title = str_replace($context['forum_name_html_safe'].' - ','',$context['page_title_html_safe']);
	
	if($title=='Index')
		$title=$context['forum_name_html_safe'];
	
	$title = str_replace('View the profile of ','',$title);
	
	$title = str_replace('Set Search Parameters','Search',$title);
	
		return $title;
	
	}

function template_body_above()
{
	

global $txt, $_GET, $context, $modSettings, $settings, $user_info, $scripturl;

$backname = $backlink = '';

if ((empty($_GET['action']) || $_GET['action']=='iphone') && empty($_GET['board']) && empty($_GET['topic'])){
		if (!empty($modSettings['id_default_theme']))
			$backlink = 'index.php?theme=' . $modSettings['id_default_theme'];
		else
				$backlink = 'index.php?theme='. $modSettings['theme_guests'];
		$backname = $txt['iClassic'];
	}
else{
	$backlink = 'javascript:history.go(-1);';
	$backname = $txt['iBack'];
	}

echo '

<div id="topbar">';
if((!empty($_GET['action'])) && (($_GET['action']=='login') || ($_GET['action']=='register'))) {
	$loginregister=' style="display:none;"';
	}
else
	$loginregister='';
	echo'

	<h1 id="pageTitle">';
	
	if((!empty($_GET['action']))&&($_GET['action']=='login'||$_GET['action']=='register'||$_GET['action']=='login2'||$_GET['action']=='register2'))
		echo'
		<div id="titleSwitcher"><a href="index.php?action=login" class="titleLeft', ((!empty($_GET['action'])) && ($_GET['action']=='login')) ? 'A' : '' ,'">'.$txt['login'].'</a><a href="index.php?action=register" class="titleRight', ((!empty($_GET['action'])) && ($_GET['action']=='login')) ? 'A' : '' ,'">'.$txt['register'].'</a></div>';
	else
		echo'
		<div id="theTitle">', iPhoneTitle(), '</div>';
		
		
	
echo'</h1><a class="back" href="'. $backlink .'">', $backname ,'</a>', $context['user']['is_guest'] ? '
	<a class="button" href="index.php?action=login" id="menubutton"'.$loginregister.'>'.$txt['login']. '</a>':'
	<a class="button" href="#" id="menubutton" onclick="window.scrollTo(0,99999); return false">'.$txt['iMenu'].'</a>','

</div>';

	
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '<div id="copyright"><h4>', theme_copyright(), '<br />SMF4iPhone Theme by <a href="http://www.onima.org/">Fabius</a></h4></div>';

if ($context['user']['is_logged']){

	
	$array = array('search', 'pm', 'profile');
	$ishome = '';
	$issearch = '';
	$ispm = '';
	$isprofile = '';
	$home = true;
	foreach ($array as $arr){
		if ((!empty($_GET['action'])) && (strstr($_GET['action'],$arr))){
			$var = 'is' . $arr; 
			$$var = ' class="active"';
			$home = false;
			}
		}
	if ($home)
		$ishome = ' class="active"';
			
echo '
</div>

	

	<div id="searchbar">

	<form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
		
	<div align="center">

	<input type="text" style="font-size:20px;-webkit-appearance:searchfield" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', !empty($context['search_string_limit']) ? ' maxlength="' . $context['search_string_limit'] . '"' : '', '" tabindex="', $context['tabindex']++, '" />
	<img src="'. $settings['images_url'] .'/search_button1.png" style="font-family:Arial; font-size:12px; height:27px; width:13px; border-style:none; background-color:transparent;" onclick="if(document.searchform.search.value.length<3){alert(\'', $txt['iAlert'], '\');return false;}" /><input type="submit" value="'. $txt['search_button'] .'" style="font-family:Arial; font-size:12px; color:white; height:27px; width:auto; border-width:0px; background-color:transparent; background-image:url(\''. $settings['images_url'] .'/search_button2.png\'); background-repeat:repeat-x;" onclick="if(document.searchform.search.value.length<3){alert(\'', $txt['iAlert'], '\');return false;}" /><img src="'. $settings['images_url'] .'/search_button3.png" style="font-family:Arial; font-size:12px; height:27px; width:13px; border-width:0px; background-color:transparent; background-repeat:repeat-x;" onclick="if(document.searchform.search.value.length<3){alert(\'', $txt['iAlert'], '\');return false;}" />

	</div>
		
	</form>

	</div>
	
	<div id="tabbar">';
	echo '
		<ul>
		
		<li onclick="go(\'home\');" id="tabhome"', $ishome ,'>
		<a href="#">', $txt['iHome'] ,'</a>
		</li>
		
		<li onclick="if(document.getElementById(\'searchbar\').style.display==\'block\'){document.getElementById(\'searchbar\').style.display=\'none\';}else{document.getElementById(\'searchbar\').style.display=\'block\';document.searchform.search.focus();}" id="tabsearch"', $issearch ,'>
		<a href="#">', $txt['iSearch'] ,'</a>
		</li>
		
		<li onclick="go(\'pm\');" id="tabmessages"', $ispm ,'>';
		if (($context['user']['unread_messages'] > 0) && ($context['user']['unread_messages'] < 10))
			echo '
				<div id="count">',$context['user']['unread_messages'],'<span style="font-size:15px;">&nbsp;&nbsp;<span><br id="miobr" /><br id="miobr" /></div>';
		elseif ($context['user']['unread_messages'] > 10)
			echo '
				<div id="count">',$context['user']['unread_messages'],'<span style="font-size:12px;">&nbsp;&nbsp;<span><br id="miobr" /><br id="miobr" /></div>';
		echo '
		<a href="#">', $txt['iMessages'] ,'</a>
		</li>
		
		<li onclick="go(\'profile\');" id="tabprofile"', $isprofile ,'>
		<a href="#">', $txt['iProfile'] ,'</a>
		</li>
		
		<li onclick="go(\'logout;sesc=', $context['session_id'] ,'\');" id="tablogout">
		<a href="#">', $txt['iLogout'] ,'</a>
		</li>
	
		</ul>
		
	</div>';}


}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body>
</html>';
}

function theme_linktree(){

	return false;

}

function iPhoneTime($time){

	global $txt;
	
	$diff = forum_time() - $time;
	
	if($diff<60)
		return $diff . ' ' . $txt['iSecondsAgo'];
	elseif(round($diff/60)==1)
		return '1 '. $txt['iMinuteAgo'];
	elseif($diff>59 && $diff<3600)	
		return round($diff/60) . ' '. $txt['iMinutesAgo'];
	elseif(round($diff/60/60)==1)
		return '1 '. $txt['iHourAgo'];
	elseif(round($diff/60/60)>1 && round($diff/60/60)<24)
		return round($diff/60/60) . ' '. $txt['iHoursAgo'];
	elseif(round($diff/60/60/24)==1)
		return '1 '. $txt['iDayAgo'];
	elseif(round($diff/60/60/24)>1&&round($diff/60/60/24)<7)
		return round($diff/60/60/24) . ' '. $txt['iDaysAgo'];
	elseif(round($diff/60/60/24/7)==1)
		return '1 '. $txt['iWeekAgo'];
	elseif(round($diff/60/60/24/7)>1)
		return round($diff/60/60/24/7) . ' '. $txt['iWeeksAgo'];
	elseif(round($diff/60/60/24/7/4)==1)
		return '1 '. $txt['iMonthAgo'];
	elseif(round($diff/60/60/24/7/4)>1)
		return round($diff/60/60/24/7) . ' '. $txt['iMonthsAgo'];
	else return $diff;
	
}


function short1($ret)
{
	$ret = ' ' . $ret;
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1<a href='$2'>$2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "$1<a href='http://$2'>$2</a>", $ret);
	short2($ret);
	$ret = preg_replace("#(\s)([a-z0-9\-_.]+)@([^,< \n\r]+)#i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $ret);	
	$ret = substr($ret, 1);
	return($ret);
}


function short2(&$ret)
{
   
   $links = explode('<a', $ret);
   $countlinks = count($links);
   for ($i = 0; $i < $countlinks; $i++)
   {
      $link = $links[$i];
      
      
      $link = (preg_match('#(.*)(href=")#is', $link)) ? '<a' . $link : $link;

      $begin = strpos($link, '>') + 1;
      $end = strpos($link, '<', $begin);
      $length = $end - $begin;
      $urlname = substr($link, $begin, $length);

$chunked = (strlen(str_replace('http://','',$urlname)) > 28 && preg_match('#^(http://|ftp://|www\.)#is', $urlname)) ? substr_replace(str_replace('http://','',$urlname), '.....', 12, -12) : $urlname;
$ret = str_replace('>' . $urlname . '<', '>' . $chunked . '<', $ret); 
   }
} 

function template_button_strip()
{
	return;
}

?>