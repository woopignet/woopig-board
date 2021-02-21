<?php
// Version: 2.0 RC4; Display

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	$ignoredMsgs = array();
	$messageIDs = array();
	

	echo'	
	<a id="top"></a>
	<div class="buttons">';
	
	
	if($context['user']['is_logged']) echo '
	
	<button id="quoting" onclick="quoting();">', (isset($_COOKIE['disablequoting'])) ? $txt['iQuoting'].' '.$txt['iOff']:$txt['iQuoting'].' '.$txt['iOn'], '</button>
	
	<button onclick="window.location.href=\''.$scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';num_replies=' . $context['num_replies'].'\';">', $txt['reply'] ,'</button>'; echo'
	<br /><br /><span style="width:100%; word-wrap:break-word; text-align:center; font-size: 20px; font-weight: bold; color: #3a3a3a; text-shadow: rgba(255, 255, 255, 0.6) 0px 1px 0;">'. $context['page_title_html_safe'] .'</span>
	<br /><br /><span style="float:left;" class="pagine">' . $txt['pages'] . ': ', $context['page_index'], '</span><span style="float:right;" class="pagine"><a class="updownscrolling" href="#lastPost"><strong>' . $txt['go_down'] . ' &#9660;</strong></a></span>
	</div><br />';
		
	echo'
	
	<ul class="posts">
	
		';

	$i=0;
	
	if($context['page_info']['num_pages']==1)
		$number = $context['total_visible_posts'];
	elseif($context['page_info']['num_pages']>1 && $context['page_info']['current_page']>1)
		$number = $context['total_visible_posts']-$context['start'];
	elseif($context['page_info']['num_pages']>1 && $context['page_info']['current_page']==1)
		$number = $context['messages_per_page'];
	
	while ($message = $context['get_message']())
	{

echo '<div>';
// Can the user modify the contents of this post?
      if ($message['can_modify'])
         echo '
                  <a href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';', $context['session_var'], '=', $context['session_id'], '"><button id="editdel"> '. $txt['modify'].' </button></a>';         
   // How about... even... remove it entirely?!
      if ($message['can_remove'])
         echo '
               <a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');"><button id="editdel"> ', $txt['remove'],' </button></a>';
echo '</div>';
	
	
		$is_first_post = !isset($is_first_post) ? true : false;
		$ignoring = false;
		$messageIDs[] = $message['id'];

		if (!in_array($message['member']['id'], $context['user']['ignoreusers']))
		{
		$i++;
	echo '', $message['first_new'] ? '<a name="new"></a>':'';			
  echo '<li>
      <div class="posterinfo" onclick="window.location.href=\'', isset($message['member']['href']) ? $message['member']['href'] : '' ,'\'"><span class="name">', $message['member']['name'] ,'</span>';
      if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']))
      	if (empty($message['member']['avatar']['image'])) {
					echo '<div id="avatar" style="background: url('.$settings['theme_url'].'/images/noavatar.png) #fff center no-repeat !important;"></div>';
				}
				else {
					echo '<div id="avatar" style="background: url('.str_replace(' ','%20', $message['member']['avatar']['href']).') #fff center no-repeat !important;"></div>';
				}
			echo '
		
		</div>
		<div class="', $number==$i ? 'last ' : '', 'message"', (!isset($_COOKIE['disablequoting'])&&$context['can_reply']) ? '  onclick="window.location.href=\''. $scripturl. '?action=post;quote='. $message['id'].
		 ';topic='. $context['current_topic'].
		  '.'. $context['start']. ';num_replies='. $context['num_replies']. ';'. $context['session_var']. '='. $context['session_id']. '\'"':'','><span style="font-weight:bold;font-size:11px;">', $message['time'] ,'</span><br />
		', short1($message['body']) ,'
		<br /><br /><br /><br style="clear:both;" />';

		// Assuming there are attachments...
		if (!empty($message['attachment']))
		{
			echo '<strong>'. $txt['iAttachments'] .'</strong><hr>
							<div id="msg_', $message['id'], '_footer" class="attachments smalltext">
								<div style="overflow: ', $context['browser']['is_firefox'] ? 'visible' : 'auto', ';">';

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
										<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a><br />';
					else
						echo '
										<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '"/><br />';
				}
				echo '
										<img style="position:relative; top:-2px;" src="' . $settings['images_url'] . '/attachment.png" align="middle" alt="*" />&nbsp;<a href="' . $attachment['href'] . '">' . $attachment['name'] . '</a> ';

				if (!$attachment['is_approved'] && $context['can_approve'])
					echo '
										[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
				echo '
										<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . ' - ' . $txt['attach_viewed'] : ' - ' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.)<br /><br />';
			}

			// If we had unapproved attachments clean up.
			if ($last_approved_state == 0)
				echo '
									</fieldset>';

			echo '
								</div>
							</div>';
		}
		echo '
		</div>
		</li>
		
		';
			
			
			
			
		}


	}
	
	echo'
	<a id="lastPost"></a>
	</ul>
	<div class="child buttons">
	<span style="float:left;" class="pagine">' . $txt['pages'] . ': ', $context['page_index'], '</span><span style="float:right;" class="pagine"><a class="updownscrolling" href="#top"><strong>' . $txt['go_up'] . ' &#9650;</strong></a></span><br />';
	if($context['user']['is_logged'])
	
	echo '	
	<br /><button onclick="window.location.href=\''.$scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';num_replies=' . $context['num_replies'].'\';">', $txt['reply'] ,'</button>'; echo '
	
	</div>

	<div class="page buttons">
	
	<button onclick="window.location.href=\'', $context['links']['prev'] ,'\';" ', $context['page_info']['current_page']==1 ? 'disabled="disabled"' : '', '>', $txt['iPrev'], '</button>
	
	<button id="pagecount">', $txt['iPage'], ' ', $context['page_info']['current_page'] ,' ', $txt['iOf'] ,' ', ($context['page_info']['num_pages']==0) ? '1' : $context['page_info']['num_pages'] ,'</button>
	
	
	<button onclick="window.location.href=\'', $context['links']['next'] ,'\';" ', ($context['page_info']['current_page']==$context['page_info']['num_pages']||$context['page_info']['num_pages']==0) ? 'disabled="disabled"' : '', '>', $txt['iNext'], '</button>
	
	
	</div>
';

}
?>