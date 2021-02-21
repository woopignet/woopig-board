<?php
// Version: 2.0 RC4; Recent

function template_unread()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	
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
			<li', (!$board['new']&&$context['user']['is_logged']) ? ' class="off"' : '', '>
		<a href="', $board['href'], '"', ($q==$board['id']) ? ' class="last"' : '' ,'>', $board['name'],'<div class="description">', $txt['last_post'], ' ', $board['last_post']['time']=='N/A' ? $txt['no'] . ' ' . $txt['topics'] : iPhoneTime($board['last_post']['timestamp']) . ' ' . $txt['by'] . ' ' . $board['last_post']['member']['name'], 
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
	
		<li onclick="window.location.href=\''. $topic['first_post']['href'] .'\'">';
		if (strlen($topic['first_post']['subject'])<=16)
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==$topic_sticky_count ? ' class="last"':'', '>', $topic['first_post']['subject'] ,'</a>';
		else
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==$topic_sticky_count ? ' class="last"':'', '>', substr($topic['first_post']['subject'],0,16) ,'...</a>';
			echo '<form action="'. $topic['new_href'] .'" method="post"><input style="position:relative; top:-3px; left:5px; font-family:Arial; font-size:12px; color:white; height:21px; width:auto; border-width:0px; background-color:transparent; background-image:url(\''. $settings['images_url'] .'/new_button.png\'); background-repeat:repeat-x;" type="submit" value="'. $txt['new_button'] .'" /></form>';
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
	$somma = $i;	
	if(count($context['topics'])-$topic_sticky_count){
	echo'
	
	<ul class="content2">';
	
	$i = 0;
	
		foreach ($context['topics'] as $topic)
		{if(!$topic['is_sticky']){
		
		$i++;
		
		
			echo'
	
		<li onclick="window.location.href=\''. $topic['first_post']['href'] .'\'">';
		if (strlen($topic['first_post']['subject'])<=16)
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==count($context['topics'])-$topic_sticky_count ? ' class="last"':'', '>', $topic['first_post']['subject'] ,'</a>';
		else
		echo '
		<a href="', $topic['first_post']['href'] ,'"', $i==count($context['topics'])-$topic_sticky_count ? ' class="last"':'', '>', substr($topic['first_post']['subject'],0,16) ,'...</a>';
			echo '<form action="'. $topic['new_href'] .'" method="post"><input style="position:relative; top:-3px; left:5px; font-family:Arial; font-size:12px; color:white; height:21px; width:auto; border-width:0px; background-color:transparent; background-image:url(\''. $settings['images_url'] .'/new_button.png\'); background-repeat:repeat-x;" type="submit" value="'. $txt['new_button'] .'" /></form>';
		echo '
		<div class="description">';
		if ($topic['pages'])
			echo '<small id="pages' . $topic['first_post']['id'] . '">' . $txt['pages'] . ': ', $topic['pages'], '</small><br />';
		echo '', ($topic['is_locked']) ? $txt['locked_topic'] : $txt['last_post']. ' '. iPhoneTime($topic['last_post']['timestamp']).' '. $txt['by']. ' '. $topic['last_post']['member']['name'], '</div>
		</li>';
		
		
		}}
		$somma = count($somma + $i);
	echo
		'
	
	</ul>


	';	
	}
	if ($somma==0)
		echo '
				<div id="unreadlink">
					', $context['showing_all_topics'] ? $txt['msg_alert_none'] : $txt['unread_topics_visit_none'], '
				</div>';

	echo'	
	
	<div class="page buttons">
	
	<button onclick="window.location.href=\'', $context['links']['prev'] ,'\';" ', $context['page_info']['current_page']==1 ? 'disabled="disabled"' : '', '>', $txt['iPrev'], '</button>
	
	<button id="pagecount">', $txt['iPage'], ' ', $context['page_info']['current_page'] ,' ', $txt['iOf'] ,' ', ($context['page_info']['num_pages']==0) ? '1' : $context['page_info']['num_pages'] ,'</button>
	
	
	<button onclick="window.location.href=\'', $context['links']['next'] ,'\';" ', ($context['page_info']['current_page']==$context['page_info']['num_pages']||$context['page_info']['num_pages']==0) ? 'disabled="disabled"' : '', '>', $txt['iNext'], '</button>
	
	
	</div>
';
}

?>