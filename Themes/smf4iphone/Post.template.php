<?php
// Version: 2.0 RC4; Post

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	echo '<form action="', $scripturl, '?action=', $context['destination'], ';', empty($context['current_board']) ? '' : 'board=' . $context['current_board'], '" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="', ($context['becomes_approved'] ? '' : 'alert(\'' . $txt['js_post_will_require_approval'] . '\');'), 'submitonce(this);saveEntities();" enctype="multipart/form-data" style="margin: 0;">';
				
if(!empty($context['post_error']['messages']) && count($context['post_error']['messages']))		
echo '<h4 id="errors"><br />', implode('<br /><br />', $context['post_error']['messages']), '<br /></h4>';

echo'
	
	<h2></h2>	
	
	<ul class="login">
		<li>
			<div class="', !$context['require_verification'] ? 'last ':'' ,'field">
				<div class="fieldname">', $txt['iSubject'] ,'</div>
				<div class="fieldinfo"><input type="text" name="subject"', $context['subject'] == '' ? '' : ' value="' . $context['subject'] . '"', ' tabindex="', $context['tabindex']++, '" size="80" maxlength="80" /></div>
			</div>
		</li>
		<li style="display:none;">
						<div>
				<dl>
										<dt>
							', $txt['message_icon'], ':
						</dt>
						<dd>
							<select name="icon" id="icon" onchange="showimage()">';

	// Loop through each message icon allowed, adding it to the drop down list.
	foreach ($context['icons'] as $icon)
		echo '
								<option value="', $icon['value'], '"', $icon['value'] == $context['icon'] ? ' selected="selected"' : '', '>', $icon['name'], '</option>';

	echo '
							</select>
							<img src="', $context['icon_url'], '" name="icons" hspace="15" alt="" />
						</dd>
						</dl>
						</div>
		</li>
		';
		
	if($context['require_verification'])
echo'
		<li>
			<div class="verification field">
				<div class="fieldname">code</div>
				<div class="fieldinfo">',template_control_verification($context['visual_verification_id'], 'all'),'</div>
			</div>
		</li>
		
		<li>
			<div class="last field">
				<div class="fieldname">verify</div>
				<div class="fieldinfo"><input type="text" name="post_vv[code]" value="" size="30" tabindex="5" />
</div>
			</div>
		</li>	';	
	
		
		echo'
		
	</ul>
			
	<h4>', $txt['iMessage'] ,'</h4>
	
	<ul class="posts">
	
		<li>
			<div class="last message">
			
			',template_control_richedit($context['post_box_name'], 'message'),'
			
			
			</div>
		</li>
	
	</ul>

	
	<div class="child buttons">
	
	<button type="submit">', $txt['iPost'] ,'</button>
	
	</div>';

	if (isset($context['num_replies']))
		echo '
			<input type="hidden" name="num_replies" value="', $context['num_replies'], '" />';

	echo '
			<input type="hidden" name="additional_options" value="', $context['show_additional_options'] ? 1 : 0, '" />
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
			<input type="hidden" name="topic" value="', $context['current_topic'], '" />
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			
		</form>';

	
	
}

?>