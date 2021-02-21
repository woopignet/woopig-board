<?php
// Version: 2.0 RC4; Profile

function template_profile_above(){}
function template_profile_below(){}

function template_summary()
{
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
	
	echo ($context['member']['avatar']['href']) ? '
	<style type="text/css">
	#avatar{
		background: url('.str_replace(' ','%20', $context['member']['avatar']['href']).') #fff center no-repeat !important;}</style>' : '', '

	<div id="profileheader">
	
		<div id="avatar"></div>
		
		<div id="username"><h3>', $context['member']['name'] ,'</h3><h4>', $context['member']['group'] ,'</h4></div>
	
	</div>
	
	
	<ul class="profile">
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iPosts'] .'</div>
			<div class="fieldinfo">', $context['member']['posts'] .'</div>
			</div>
		</li>',$context['member']['show_email'] == 'yes' || $context['member']['show_email'] == 'yes_permission_override' ? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iEmail'] .'</div>
			<div class="fieldinfo">'. $context['member']['email']. '</div>
			</div>
		</li>':'',(!empty($modSettings['titlesEnable']) && !empty($context['member']['title']))?'
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iTitle'] .'</div>
			<div class="fieldinfo">'. $context['member']['title'] .'</div>
			</div>
		</li>':'',($context['member']['blurb']!='')?'
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iText'] .'</div>
			<div class="fieldinfo">'. $context['member']['blurb']. '</div>
			</div>
		</li>':'', ($modSettings['karmaMode'] == '1') ? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iKarma'] . ' </div>
			<div class="fieldinfo">'.($context['member']['karma']['good'] - $context['member']['karma']['bad']).'</div>
			</div>
		</li>':'', ($modSettings['karmaMode'] == '2') ? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $modSettings['karmaLabel']. ' </div>
			<div class="fieldinfo">+'. $context['member']['karma']['good']. '/-'. $context['member']['karma']['bad']. '</div>
			</div>
		</li>':'', (!isset($context['disabled_fields']['gender']) && !empty($context['member']['gender']['name'])) ? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iGender'] .'</div>
			<div class="fieldinfo">'.$context['member']['gender']['name'].'</div>
			</div>
		</li>':'', ($context['member']['age']>0)? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iAge'] .'</div>
			<div class="fieldinfo">'. $context['member']['age'].'</div>
			</div>
		</li>':'',(!isset($context['disabled_fields']['location']) && !empty($context['member']['location']))?'
		
		<li><div class="field">
			<div class="fieldname">'. $txt['iLocation'] .'</div>
			<div class="fieldinfo">'.$context['member']['location'].'</div>
			</div>
		</li>':'','	
	
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iRegistered'] .'</div>
			<div class="fieldinfo">', $context['member']['registered'] ,' </div>
			</div>
		</li>
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iLastActive'] .'</div>
			<div class="fieldinfo">', $context['member']['last_login'], '</div>
			</div>
		</li>',($context['can_see_ip'])?'
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iIP'] .'</div>
			<div class="fieldinfo"><a href="#" style="color:#000">'.$context['member']['ip'].'</a></div>
			</div>
		</li>':'',(empty($modSettings['disableHostnameLookup']) && !empty($context['member']['ip']) && $context['can_see_ip']) ? '
		
		<li>
			<div class="field">
			<div class="fieldname">'. $txt['iHostname'] .'</div>
			<div class="fieldinfo">'. $context['member']['hostname']. '</div>
			</div>
		</li>':'','
		
		<li><div class="last field">
			<div class="fieldname">'. $txt['iLocalTime'] .'</div>
			<div class="fieldinfo">', $context['member']['local_time'], '</div>
			</div>
		</li>
		
	</ul>

	<div class="profilebuttons">
	
	<button', ($context['can_send_pm']) ? '':' disabled="disabled"' ,' onclick="window.location.href=\'index.php?action=pm;sa=send;u=', $context['member']['id'] ,'\';">', $txt['iSendPM'] ,'</button>
	
	<button', ($context['member']['show_email'] == 'yes' || $context['member']['show_email'] == 'yes_permission_override') ? ' onclick="window.location=\'mailto:'. $context['member']['email'] .'\';"':' disabled="disabled"','>', $txt['iSendEmail'] ,'</button></button>

	
	</div>
	
';

}

?>