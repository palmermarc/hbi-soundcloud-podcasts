<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.3.0
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.3.0
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_SoundCloud_Podcasts_Deactivator {
	/**
	 * Remove any existing cron jobs and flush the rewrite rules
	 *
	 * @since    1.3.0
	 */
	public static function deactivate() {
        $timestamp = wp_next_scheduled( 'import_podcasts_from_soundcloud' );
        wp_unschedule_event( $timestamp, 'import_podcasts_from_soundcloud' );

        flush_rewrite_rules();
	}
}