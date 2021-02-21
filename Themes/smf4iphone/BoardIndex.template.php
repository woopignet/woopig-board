<?php
// Version: 2.0 RC4; BoardIndex

function template_main()
{

	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	
	echo '
	
	<div id="logo">
		<div id="logocover"></div>
	
	</div>
	
	';
	
	
	if($settings['number_recent_posts'] > 1)
		
		echo'
		
		<div id="switcher">
		
			<a href="javascript:void(0);" class="lpheader1" onclick="iswitch(this.parentNode.id);return false;" id="left">'. $txt['iBoards'] .'</a>
			
			<a ', (strlen($txt['iLatestPosts'])>15) ? ' class="lpheader2"' : ' class="lpheader1"', ' href="javascript:void(0);" onclick="iswitch(this.parentNode.id);return false;" id="right">'. $txt['iLatestPosts'] .'</a>
		
		</div>
		
		';
	
		
	$children = false;
	
	foreach ($context['categories'] as $category)
	{
		
		echo '
		
<h2 id="contentboardsh2">', $category['link'], '</h2>
	
	<ul class="content" id="contentboards">
	';

			$i=0;
			
			foreach ($category['boards'] as $board)
			{
				$i++;
				echo '
		<li', (!$board['new']||!$context['user']['is_logged']) ? ' class="off"' : ' class="on"', '>
		<span onclick="window.location.href=\'', $board['href'], '\';"', $i==count($category['boards']) ? ' class="last"' : '' ,'>';
		if ($context['user']['is_logged'])
			echo '<a href="', $scripturl . '?board=' . $board['id'] . '.0;"></a> ';
		echo $board['name'],'
		<div class="description">', $txt['last_post'], ' ', $board['last_post']['time']=='N/A' ? $txt['no'] . ' ' . $txt['topics'] : iPhoneTime($board['last_post']['timestamp']) . ' ' . $txt['by'] . ' ' . $board['last_post']['member']['name'], 
'</div></span>
		</li>
		';	
								
					foreach ($board['children'] as $child)
					{
					
					$children=true;
						
						echo '
		<li class="child', (!$child['new']||!$context['user']['is_logged']) ? ' off' : ' on', '">
		<span onclick="window.location.href=\'', $child['href'], '\';"', $i==count($category['boards']) ? ' class="last"' : '' ,'>';
		if ($context['user']['is_logged'])
			'</div></span>
		</li>
		';		
						
					}
				
			}
			
	echo '
	</ul>';
			
			
	}

if ($children) echo '

<div class="child buttons" id="childbuttondiv">

	
	
</div> 

';

else echo '

<div class="child buttons" id="childbuttondiv"></div>

';

$j=0;

echo '
	<h2 id="contentrecenth2">&nbsp;</h2>
	
	<ul class="content recent" id="contentrecent">
	';
if (!empty($context['latest_posts']))
foreach ($context['latest_posts'] as $post){

$j++;

echo '<li  class="off">
		<span onclick="window.location.href=\'', $post['href'], '\';"', $j==count($context['latest_posts']) ? ' class="last"' : '' ,'>', $post['subject'],'
		<div class="description">', $txt['iPosted'] ,' ', iPhoneTime($post['timestamp']) . ' ' . $txt['by'] . ' ' . $post['poster']['name'] . ' ' ,$txt['in'], ' ' . $post['board']['name'], 
'</div></span>
		</li>';

	}
echo' </ul>'; 
	if ($context['user']['is_logged']) {
		echo'<div style="width=80px">
		<button style="float:left;" id="readunread" onclick="window.location.href=\'', $scripturl, '?action=unread\';">' ,$txt['iShowUnread'], '</button>
		<button style="float:right;" id="readunread" onclick="window.location.href=\'', $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id'] ,'\';">' ,$txt['iMarkALLRead'], '</button>
		</div><br /><br />';
	}
	
	
	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
			<div class="infocenter_section">
				<h4 class="titlebg">', $txt['personal_message'], '</h4>
				<div class="windowbg">
					<p class="section">
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<img src="', $settings['images_url'], '/message_sm.gif" alt="', $txt['personal_message'], '" />', $context['allow_pm'] ? '</a>' : '', '
					</p>
					<div class="windowbg2 sectionbody">
						<strong style="font-style:normal;font-weight:bold;"><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
						<div class="smalltext">
							', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
						</div>
					</div>
				</div>
			</div>';
	}

}


?>