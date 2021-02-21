<?php
// Version: 2.0 RC4; Search

function template_main(){}

function template_results()
{
	global $context, $settings, $options, $txt, $scripturl;

		if (empty($context['topics'])){
			echo '<h3>',$txt['search_no_results'],'</h3><style type="text/css">#searchbar{

	display: block;

}</style>';
			
		}
		
		else
		
		{$i=0;
	
			while ($topic = $context['get_topics']())
			{
		
				foreach ($topic['matches'] as $message)
				{ $i++;
				
				echo'
	<ul class="content">
		<li class="searchli">
				<a href="', $scripturl, '?topic=', $topic['id'], '.msg', $message['id'], '#msg', $message['id'],'" class="last">', $message['subject_highlighted'], '
		<div class="description">', $message['body_highlighted'], '</div></a>
		</li>
	</ul>
	';
			

				}
			}
			
		}

	
}

?>