<?php

/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   HBI_SoundCloud_Podcasts
 * @author    Marc Palmer <mapalmer@hbi.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Marc Palmer or Hubbard Radio
 *
 * @wordpress-plugin
 * Plugin Name:       HBI SoundCloud Podcasts
 * Plugin URI:        http://hubbardinteractivestl.com/plugins/hbi-soundcloud-podcasts
 * Description:       Plugin to handle all of the importing and displaying of Podcasts on 101Sports.com
 * Version:           1.3.1
 * Author:            Marc Palmer
 * Author URI:        http://www.hubbardinteractivestl.com/plugins/marc-palmer/
 * Text Domain:       hbi-soundcloud-podcasts-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
  * WordPress-Plugin-Boilerplate: v2.6.1
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hbi-soundcloud-podcasts-activator.php
 */
function activate_hbi_soundcloud_podcasts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hbi-soundcloud-podcasts-activator.php';
	HBI_SoundCloud_Podcasts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hbi-soundcloud-podcasts-deactivator.php
 */
function deactivate_hbi_soundcloud_podcasts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hbi-soundcloud-podcasts-deactivator.php';
	HBI_SoundCloud_Podcasts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hbi_soundcloud_podcasts' );
register_deactivation_hook( __FILE__, 'deactivate_hbi_soundcloud_podcasts' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hbi-soundcloud-podcasts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.3.0
 */
function run_hbi_soundcloud_podcasts() {
	$plugin = new HBI_SoundCloud_Podcasts();
	$plugin->run();
}

run_hbi_soundcloud_podcasts();
