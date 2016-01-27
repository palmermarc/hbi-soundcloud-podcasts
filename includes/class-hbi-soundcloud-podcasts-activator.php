<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.3.0
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.3.0
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_SoundCloud_Podcasts_Activator {

	/**
	 * Register the post type and flush the re-write thens, then schedule the first import from SoundCloud.
	 *
	 * @since    1.3.0
	 */
	public static function activate() {
        HBI_SoundCloud_Podcasts_Public::register_podcasts_post_type();
        flush_rewrite_rules();
        
        if( !wp_next_scheduled( 'import_podcasts_from_soundcloud' ) ) {
            wp_schedule_event( time(), 'hourly', 'import_podcasts_from_soundcloud' );
        }
	}

}
