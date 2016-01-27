<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/includes
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_SoundCloud_Podcasts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      HBI_SoundCloud_Podcasts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.3.0
	 */
	public function __construct() {
		$this->plugin_name= 'hbi-soundcloud-podcasts';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - HBI_SoundCloud_Podcasts_Loader. Orchestrates the hooks of the plugin.
	 * - HBI_SoundCloud_Podcasts_i18n. Defines internationalization functionality.
	 * - HBI_SoundCloud_Podcasts_Admin. Defines all hooks for the dashboard.
	 * - HBI_SoundCloud_Podcasts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hbi-soundcloud-podcasts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hbi-soundcloud-podcasts-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hbi-soundcloud-podcasts-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hbi-soundcloud-podcasts-public.php';

		$this->loader = new HBI_SoundCloud_Podcasts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the HBI_SoundCloud_Podcasts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new HBI_SoundCloud_Podcasts_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new HBI_SoundCloud_Podcasts_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_scripts' );
        
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_podcasting_settings' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'create_podcasts_admin_menu' );
        
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_scripts' );
        
        $this->loader->add_filter( 'manage_podcasts_posts_columns', $plugin_admin, 'add_podcast_show_column');
        $this->loader->add_action( 'manage_posts_custom_column' , $plugin_admin, 'add_post_show_column_value', 10, 2 );
        
        /**
         * Save the meta boxes
         */
        $this->loader->add_action( 'save_post_podcasts', $plugin_admin, 'hbi_soundcloud_podcasts_save_post_class_meta' );
        
        /**
         * Add function to the WYSIWYG editor
         */
        $this->loader->add_filter( 'mce_external_plugins', $plugin_admin, 'hbi_soundcloud_podcasts_add_button' );
        $this->loader->add_filter( 'mce_buttons', $plugin_admin, 'hbi_soundcloud_podcasts_register_buttons' );
        
        // Add an action link pointing to the options page.
        $this->loader->add_filter( 'plugin_action_links_' . $this->get_plugin_name(), $plugin_admin, 'add_action_links' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new HBI_SoundCloud_Podcasts_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        
        $this->loader->add_action( 'init', $plugin_public, 'register_podcasts_post_type', 0 );
        $this->loader->add_action( 'init', $plugin_public, 'register_podcast_show', 0 );
        $this->loader->add_action( 'init', $plugin_public, 'register_podcast_segment', 0 );
        $this->loader->add_action( 'import_podcasts_from_soundcloud', $plugin_public, 'import_podcasts' );
        $this->loader->add_action( 'wp_ajax_return_js_array_of_podcast_segments', $plugin_public, 'return_js_array_of_podcast_segments' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.3.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    HBI_SoundCloud_Podcasts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
