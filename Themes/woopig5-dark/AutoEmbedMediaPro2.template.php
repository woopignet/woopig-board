<?php
/*
Simple Audio Video Embedder
Version 1.1
by:vbgamer45
http://www.smfhacks.com

License Information:
Links to http://www.smfhacks.com must remain unless
branding free option is purchased.
*/

function template_mediapro_settings()
{
	global $context, $txt, $scripturl, $boarddir, $mediaProVersion, $modSettings;

	echo '
	<form method="post" name="frmsettings" id="frmsettings" action="', $scripturl, '?action=mediapro;sa=settings2" accept-charset="', $context['character_set'], '">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td width="50%" colspan="2"  align="center" class="catbg">
	    <b>', $txt['mediapro_admin'], '</b></td>
	  </tr>';


	echo '<tr>
	    <td width="50%" colspan="2"  align="center" class="windowbg2">',$txt['mediapro_txt_yourversion'] , $mediaProVersion, '&nbsp;',$txt['mediapro_txt_latestversion'],'<span id="lastmediapro" name="lastmediapro"></span>
	    </td>
	    </tr>';

	// Check if cache folder is writable
	if (!is_writable($boarddir . "/cache/"))
	{
		echo '<tr>
	    <td width="50%" colspan="2"  align="center" class="windowbg2">
	    ' . $txt['mediapro_err_cache'] . ' ' . $boarddir . '/cache/mediaprocache.php
	    </td>
	    </tr>';

	}
	
		echo '<tr>
	    <td width="50%" colspan="2"  align="center" class="windowbg2"><strong>' . $txt['mediapro_txt_settings'] . '</strong>
	    </td>
	    </tr>
	    <tr>
	    	<td class="windowbg2" align="right" valign="top">' . $txt['mediapro_txt_default_height'] .'</td>
	    	<td class="windowbg2"><input type="text" size="5" name="mediapro_default_height" value="' . $modSettings['mediapro_default_height'] . '" />
	    	<br /><span class="smalltext">' .$txt['mediapro_txt_default_info'] . '</span>
	    	</td>
	    </tr>
	    <tr>
	    	<td class="windowbg2" align="right" valign="top">' . $txt['mediapro_txt_default_width'] .'</td>
	    	<td class="windowbg2"><input type="text" size="5" name="mediapro_default_width" value="' . $modSettings['mediapro_default_width'] . '" />
	    	<br /><span class="smalltext">' .$txt['mediapro_txt_default_info'] . '</span>
	    	</td>
	    </tr>
	    ';

	// Show all the sites
	echo '<tr>
	<td colspan="2" class="windowbg2" align="center">
	<input type="checkbox" onclick="invertAll(this, this.form, \'site\');" class="check" /> ' . $txt['mediapro_txt_checkall'] . '
	</td>
	</tr>
	<tr>
	
	    <td  colspan="2" class="windowbg2" align="center">
	    <table align="center">';

		$siteLevel = 0;
		foreach($context['mediapro_sites'] as $site)
		{
			if ($siteLevel == 0)
				echo '<tr>';

			echo '<td><input type="checkbox" name="site[' . $site['id'] . ']" ' . ($site['enabled'] ? ' checked="checked" ' : '')  . ' />' . $site['title'] . '</td>';

			if ($siteLevel == 0 || $siteLevel == 1)
				$siteLevel++;
			else
			{
				echo '</tr>';
				$siteLevel = 0;
			}
		}

		if ($siteLevel == 1)
		{
			echo '
			<td></td>
			<td></td>
			</tr>';
			$siteLevel = 0;
		}

		if ($siteLevel == 2)
		{
			echo '<td></td>
			</tr>';
			$siteLevel = 0;
		}

	echo '
	    </table>
	    </td>
	  </tr>
	  <tr>
	    <td colspan="2" class="windowbg2" align="center">
	     <input type="hidden" name="sc" value="', $context['session_id'], '" />
	    <input type="submit" name="addpage" value="',$txt['mediapro_save_settings'],'" /></td>
	  </tr>
	  
	  <tr>
	  	<td colspan="2" class="windowbg2" align="center"><br /><a href="', $scripturl, '?action=mediapro;sa=mega">' . $txt['mediapro_megamegatribute'] . '</a></td>
	  </tr>
	  
	  </table>
  	</form>

<script language="JavaScript" type="text/javascript" src="http://www.smfhacks.com/versions/autoembedmediapro.js?t=' . time() . '"></script>
			<script language="JavaScript" type="text/javascript">

			function MediaProCurrentVersion()
			{
				if (!window.autoemdedmediaproVersion)
					return;

				mediaprospan = document.getElementById("lastmediapro");

				if (window.autoemdedmediaproVersion != "' . $mediaProVersion . '")
				{
					setInnerHTML(mediaprospan, "<b><font color=\"red\">" + window.autoemdedmediaproVersion + "</font>&nbsp;' . $txt['mediapro_txt_version_outofdate'] . '</b>");
				}
				else
				{
					setInnerHTML(mediaprospan, "' . $mediaProVersion . '");
				}
			}

			// Override on the onload function
			window.onload = function ()
			{
				MediaProCurrentVersion();
			}
			</script>

  ';

}

function template_mega()
{
	global $scripturl, $txt;

	echo '

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	    <td width="50%" colspan="2"  align="center" class="catbg">
	    <b>', $txt['mediapro_megauploadsong'], '</b></td>
	  </tr>
	  
	  <tr class="windowbg2">
	  	<td align="center">
	  	<iframe width="560" height="315" src="http://www.youtube.com/embed/o0Wvn-9BXVc" frameborder="0" allowfullscreen></iframe>
	  	<br />
	  	
	  	',$txt['mediapro_dedication'], '</td>
	  </tr>
	  
	  
	  <tr>
	    <td width="50%" colspan="2"  align="center" class="catbg">
	    <b>', $txt['mediapro_megamegacopterandfire'], '</b></td>
	  </tr>
	 
	  <tr class="windowbg2">
	  	<td align="center">
<iframe width="560" height="315" src="http://www.youtube.com/embed/x3mMY4QjefE" frameborder="0" allowfullscreen></iframe>
	  	</td>
	  </tr>
	   
	  
	  <tr>
	    <td width="50%" colspan="2"  align="center" class="catbg">
	    <b>', $txt['mediapro_megaracer'], '</b></td>
	  </tr>
	  	  <tr class="windowbg2">
	  	<td align="center">
<iframe width="560" height="315" src="http://www.youtube.com/embed/l-ltcCF_cAQ" frameborder="0" allowfullscreen></iframe>
	  	
	  	</td>
	  </tr>
	  
	  </table>
	  ';

	
	
}


?>