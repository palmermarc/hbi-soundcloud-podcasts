<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/admin/partials
 */
$options = get_option('hbi_soundcloud_podcasts_settings');
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<?php screen_icon(); ?>
	<h2>HBI SoundCloud Podcasts Plugin Settings</h2>
	
	<p>The following settings must be completely filled out before any importing process will begin.</p>
	<p>If you have have not already created your App on SoundCloud, <a href="http://soundcloud.com/you/apps" target="_blank" title="Your Applications on SoundCloud">click here</a> to do so now</p>
	
	<form action="options.php" method="post">
		<?php 
		settings_fields( 'hbi_soundcloud_podcasts_group' ); 
		do_settings_sections( 'hbi_soundcloud_podcasts' );
		submit_button();
		?>
	</form>
</div>
