<?php
// Version: 2.0 RC4; MessageIndex

function template_main()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	if($context['user']['is_guest'])
	echo '	

	
	<div class="buttons">
	
	</div>';
	else
	echo '	

	
	<div class="child buttons">
	
	<button onclick="window.location.href=\'', $scripturl , '?action=post;board=' , $context['current_board'] , '.0	\';">', $txt['new_topic'], '</button>
	
	</div>';
	
	if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
		echo '
		<h2 id="contentboardsh2">', $txt['parent_boards'], '</h2>
	<ul class="content" id="contentboards">';
	$q=0;
		foreach ($context['boards'] as $board)
		{
			$q++;
			echo '
			<li', (!$board['new']||!$context['user']['is_logged']) ? ' class="off"' : ' class="on"', '>
		<span onclick="window.location.href=\'', $board['href'], '\';"', ($q==$board['id']) ? ' class="last"' : '' ,'>';
		if ($context['user']['is_logged'])
			echo '<a href="', $scripturl . '?action=unread;board=' . $board['id'] . '.0;children"><img src="'. $settings['images_url'] .'/unread.png"</a> ';
		echo  $board['name'],'<div class="description">', $txt['last_post'], ' ', $board['last_post']['time']=='N/A' ? $txt['no'] . ' ' . $txt['topics'] : iPhoneTime($board['last_post']['timestamp']) . ' ' . $txt['by'] . ' ' . $board['last_post']['member']['name'], 
'</div></a>
		</li>';
	}
	echo '</ul>';
}
	
	$topic_sticky_count = 0;
	foreach ($context['topics'] as $topic)
		{if($topic['is_sticky']){
		$topic_sticky_count++;
		}}
	
	$i = 0;
		if($topic_sticky_count)
		foreach ($context['topics'] as $topic)
		{if($topic['is_sticky']){
		
		$i++;
		
		if ($i==1)echo'
	
	<ul class="content2">';

				echo'
	
		<li', $topic['new'] ? '' : ' class="off"' ,' onclick="window.location.href=\''. $topic['first_post']['href'] .'\';">';
		if (strlen($topic['first_post']['subject'])<=16)
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==$topic_sticky_count ? ' class="last"':'', ($topic['new']) ? ' id="anew" onclick="window.location.href=\''. $topic['new_href'] .'\'"':'', '>', $topic['first_post']['subject'] ,'</a>';
		else
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==$topic_sticky_count ? ' class="last"':'', ($topic['new']) ? ' id="anew" onclick="window.location.href=\''. $topic['new_href'] .'\'"':'', '>', substr($topic['first_post']['subject'],0,16) ,'...</a>';
		if ($topic['new']&&$context['user']['is_logged']) {
			echo '<form action="'. $topic['new_href'] .'" method="post"><input style="position:relative; top:-3px; font-family:Arial; font-size:12px; color:white; height:21px; width:auto; border-width:0px; background-color:transparent; background-image:url(\''. $settings['images_url'] .'/new_button.png\'); background-repeat:repeat-x;" type="submit" value="'. $txt['new_button'] .'" /></form>';
		}
		echo'
		<div class="description">';
		if ($topic['pages'])
			echo '<small id="pages' . $topic['first_post']['id'] . '">' . $txt['pages'] . ': ', $topic['pages'], '</small><br />';
		echo '', ($topic['is_locked']) ? $txt['locked_topic'] : $txt['last_post']. ' '. iPhoneTime($topic['last_post']['timestamp']).' '. $txt['by']. ' '. $topic['last_post']['member']['name'], '</div>
		</li>';
		
		
		}

		}
		if ($i==$topic_sticky_count)
		echo
		'
	
	</ul>
	
	';		
	
	if(count($context['topics'])-$topic_sticky_count){
	echo'
	
	<ul class="content2">';
	
	$i = 0;
	
		foreach ($context['topics'] as $topic)
		{if(!$topic['is_sticky']){
		
		$i++;
		
		
			echo'
	
		<li', $topic['new'] ? '' : ' class="off"' ,' onclick="window.location.href=\''. $topic['first_post']['href'] .'\'">';
		if (strlen($topic['first_post']['subject'])<=16)
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==count($context['topics'])-$topic_sticky_count ? ' class="last"':'', ($topic['new']) ? ' id="anew" onclick="window.location.href=\''. $topic['new_href'] .'\'"':'', '>', $topic['first_post']['subject'] ,'</a>';
		else
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==count($context['topics'])-$topic_sticky_count ? ' class="last"':'', ($topic['new']) ? ' id="anew" onclick="window.location.href=\''. $topic['new_href'] .'\'"':'', '>', substr($topic['first_post']['subject'],0,16) ,'...</a>';
		if ($topic['new']&&$context['user']['is_logged']) {
			echo '<form action="'. $topic['new_href'] .'" method="post"><input style="position:relative; top:-3px; font-family:Arial; font-size:12px; color:white; height:21px; width:auto; border-width:0px; background-color:transparent; background-image:url(\''. $settings['images_url'] .'/new_button.png\'); background-repeat:repeat-x;" type="submit" value="'. $txt['new_button'] .'" /></form>';
		}
		echo '
		<div class="description">';
		if ($topic['pages'])
			echo '<small id="pages' . $topic['first_post']['id'] . '">' . $txt['pages'] . ': ', $topic['pages'], '</small><br />';
		echo '', ($topic['is_locked']) ? $txt['locked_topic'] : $txt['last_post']. ' '. iPhoneTime($topic['last_post']['timestamp']).' '. $txt['by']. ' '. $topic['last_post']['member']['name'], '</div>
		</li>';
		
		
		}}
		
	echo
		'
	
	</ul>


	';	
		if ($context['user']['is_logged']) {
			echo'<div style="width=80px">
			<button style="float:right;" id="readunread2" onclick="window.location.href=\'', $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id'] ,'\';">' ,$txt['iMarkALLRead'], '</button>
			</div><br /><br />';
		}
	}
	
	echo'	
	
	<div class="page buttons">
	
	<button onclick="window.location.href=\'', $context['links']['prev'] ,'\';" ', $context['page_info']['current_page']==1 ? 'disabled="disabled"' : '', '>', $txt['iPrev'], '</button>
	
	<button id="pagecount">', $txt['iPage'], ' ', $context['page_info']['current_page'] ,' ', $txt['iOf'] ,' ', ($context['page_info']['num_pages']==0) ? '1' : $context['page_info']['num_pages'] ,'</button>
	
	
	<button onclick="window.location.href=\'', $context['links']['next'] ,'\';" ', ($context['page_info']['current_page']==$context['page_info']['num_pages']||$context['page_info']['num_pages']==0) ? 'disabled="disabled"' : '', '>', $txt['iNext'], '</button>
	
	
	</div>
';
}

?>