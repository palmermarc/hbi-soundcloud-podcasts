<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/admin
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_SoundCloud_Podcasts_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
	 * @var      string    $hbi_soundcloud_podcasts       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name= $plugin_name;
		$this->version = $version;

	}

    public function register_podcasting_settings() {
		
 		register_setting( 
 			'hbi_soundcloud_podcasts_group',
 			'hbi_soundcloud_podcasts_settings',
 			array( $this, 'sanitize_podcasting_settings' )
		);
 
		add_settings_section(
			'hbi_soundcloud_podcasts_section',
			'Soundcloud Settings',
			null,
		    'hbi_soundcloud_podcasts'
		);
		 
		add_settings_field(
			'soundclient_client_id',
			'Soundcloud Client ID',
			array( $this, 'soundcloud_client_id_callback' ),
			'hbi_soundcloud_podcasts',
			'hbi_soundcloud_podcasts_section'
		);
		 
		add_settings_field(
			'soundcloud_client_secret',
			'Soundcloud Client Secret',
			array( $this, 'soundcloud_client_secret_callback' ),
			'hbi_soundcloud_podcasts',
			'hbi_soundcloud_podcasts_section'
		);
		 
		add_settings_field(
			'soundcloud_redirect_uri',
			'Soundcloud Redirect URI',
			array( $this, 'soundcloud_redirect_uri_callback' ),
			'hbi_soundcloud_podcasts',
			'hbi_soundcloud_podcasts_section'
		);
		
		add_settings_field(
			'soundcloud_user_id',
			'Soundcloud User ID',
			array( $this, 'soundcloud_user_id_callback' ),
			'hbi_soundcloud_podcasts',
			'hbi_soundcloud_podcasts_section'
		);
		
		add_settings_field(
			'soundcloud_access_token',
			'Soundcloud Access Code',
			array( $this, 'soundcloud_access_token_callback' ),
			'hbi_soundcloud_podcasts',
			'hbi_soundcloud_podcasts_section'
		);
    }
    
    /**
     * Sanitize the settings before saving
     * 
     * @since 1.0.0
     */
    public function sanitize_podcasting_settings( $input ) {
        $new_input = array();

        if( isset( $input['soundcloud_client_id'] ) )
            $new_input['soundcloud_client_id'] = sanitize_text_field( $input['soundcloud_client_id'] );
            
        if( isset( $input['soundcloud_client_secret'] ) )
            $new_input['soundcloud_client_secret'] = sanitize_text_field( $input['soundcloud_client_secret'] );
            
		if( isset( $input['soundcloud_redirect_uri'] ) )
			$new_input['soundcloud_redirect_uri'] = sanitize_text_field( $input['soundcloud_redirect_uri'] );
			
		if( isset( $input['soundcloud_user_id'] ) )
			$new_input['soundcloud_user_id'] = absint( $input['soundcloud_user_id'] );
			
		if( isset( $input['soundcloud_access_code'] ) ) {
			require_once plugin_dir_path( __FILE__ ) . '../public/assets/php-soundcloud-master/Services/Soundcloud.php';
        
			$options = get_option( 'hbi_soundcloud_podcasts_settings' );
			
			$client = new Services_Soundcloud(
			    $options['soundcloud_client_id'],
			    $options['soundcloud_client_secret'],
			    $options['soundcloud_redirect_uri']
			);
			
			try {
				$new_input['soundcloud_access_token'] = $client->accessToken( $input['soundcloud_access_code'] ); 
			} catch( Services_Soundcloud_Invalid_Http_Response_Code_Exception $e ) {
				exit( $e->getMessage() );
			}
		}
		
        return $new_input;
    }
    
    public function soundcloud_client_id_callback() {
    	$options = get_option('hbi_soundcloud_podcasts_settings');
        printf( '<input autocomplete="off" type="password" name="hbi_soundcloud_podcasts_settings[soundcloud_client_id]" value="%s" />', 
            isset( $options['soundcloud_client_id'] ) ? esc_attr( $options['soundcloud_client_id'] )  : '' );
    }
    
    public function soundcloud_client_secret_callback() {
    	$options = get_option('hbi_soundcloud_podcasts_settings');
        printf( '<input autocomplete="off" type="password" name="hbi_soundcloud_podcasts_settings[soundcloud_client_secret]" value="%s" />', 
            isset( $options['soundcloud_client_secret'] ) ? esc_attr( $options['soundcloud_client_secret'] )  : '' );
    }
	
	public function soundcloud_redirect_uri_callback() {
		$options = get_option('hbi_soundcloud_podcasts_settings');
		$redirect_location =  plugin_dir_url( __FILE__ ) . 'soundcloud_redirect_location.php';
		echo $redirect_location; 
		echo '<input type="hidden" name="hbi_soundcloud_podcasts_settings[soundcloud_redirect_uri]" value="' . $redirect_location .'" />';
	}
	
    public function soundcloud_user_id_callback() {
		$options = get_option('hbi_soundcloud_podcasts_settings');
		printf( '<input autocomplete="off" type="number" name="hbi_soundcloud_podcasts_settings[soundcloud_user_id]" value="%s" />',
			isset( $options['soundcloud_user_id'] ) ? esc_attr( $options['soundcloud_user_id'] ) : '' );
		echo '<span class="description">The Soundcloud User Id controls which user the system will import tracks for.</span>'; 
	}
	
	public function soundcloud_access_token_callback() {
		require_once plugin_dir_path( __FILE__ ) . '../public/assets/php-soundcloud-master/Services/Soundcloud.php';
        
		$options = get_option( 'hbi_soundcloud_podcasts_settings' );
		
		if( empty( $options['soundcloud_client_id'] ) || empty( $options['soundcloud_client_secret'] ) ) {
			echo "<em>Please enter your Client ID and Secret before attempting to create your access token.</em>";
			return; 
		}
			
		
		$client = new Services_Soundcloud(
		    $options['soundcloud_client_id'],
		    $options['soundcloud_client_secret'],
		    $options['soundcloud_redirect_uri']
		);
		
		echo '<p class="description">Click the Connect button below if you have not already created an access token, or if you need to re-create your acccess token.</p>';
		echo "<a id='soundcloud_connect' target='_blank' href='{$client->getAuthorizeUrl()}'><img width='242' height='29' src='http://connect.soundcloud.com/2/btn-connect-sc-l.png' alt='' title='' /></a>";
		echo '<p><input size="50" placeholder="Paste Access Code Here" id="soundcloud_access_code" type="text" name="hbi_soundcloud_podcasts_settings[soundcloud_access_code]" value="" /></p>';
		
	}
	
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function create_podcasts_admin_menu() {
        add_options_page( __( 'HBI SoundCloud Podcasts', $this->plugin_name), __( 'Podcasting Settings', $this->plugin_name), 'manage_options', 'hbi_soundcloud_podcasts', array( &$this, 'display_plugin_admin_page' ) );
        add_submenu_page( 'edit.php?post_type=podcasts', 'Manually Import Podcasts from SoundCloud', 'Manually Import', 'manage_options', 'manually-import-podcasts', array( &$this, 'manually_import_soundcloud_podcasts' ) );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'partials/hbi-soundcloud-podcasts-admin-display.php' );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {
        return array_merge( array( 'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>' ), $links );
    }
    /**
	 * Enqueue the admin styles
	 * 
	 * @since	1.0.0
	 */
    public function enqueue_admin_styles() {
        wp_enqueue_style( $this->plugin_name. '-plugin-styles', plugin_dir_url( __FILE__ ) . 'css/hbi-soundcloud-podcasts-admin.css', array( 'dashicons' ) );
    }
    
	/**
	 * Register and enqueue the javascript needed on the admin side of the plugin
	 * 
	 * @since	1.0.0
	 */
    public function enqueue_admin_scripts() {
    	$current_screen = get_current_screen();
		
		// Only load the JavaScript when we need it
		if ( 'post' == $current_screen->base && 'podcasts' == $current_screen->id ) :
	        wp_enqueue_script( $this->plugin_name . '-admin-script', plugin_dir_url( __FILE__ ) . 'js/hbi-soundcloud-podcasts-admin.js', array( 'jquery' ), '', TRUE );
			wp_localize_script( $this->plugin_name . '-admin-script', 'hbi_soundcloud_podcasts_shows', $this->localize_podcast_shows() );
			wp_localize_script( $this->plugin_name . '-admin-script', 'hbi_soundcloud_podcasts_segments', $this->localize_podcast_segments() );
		endif;
		if( 'settings_page_hbi_soundcloud_podcasts' == $current_screen->base ) {
			wp_enqueue_script( $this->plugin_name . '-admin-script', plugin_dir_url( __FILE__ ) . 'js/hbi-soundcloud-podcasts-admin-settings.js', array( 'jquery' ), '', TRUE );
		}
    }
	
	public function localize_podcast_shows() {
		$podcast_shows = get_terms( 'podcast_show', array( 'hide_empty' => false ) );
		$shows = array();
		$shows[] = array( 'value' => '', 'text' => 'All Shows' );
		
		if ( ! empty( $podcast_shows ) && ! is_wp_error( $podcast_shows ) ) :
			foreach( $podcast_shows as $podcast_show ) :
				$shows[] = array(
					'value' => $podcast_show->slug,
					'text' => $podcast_show->name
				);
			endforeach;
		endif;
		
		return $shows;
		
	}

	public function localize_podcast_segments() {
		
		$segments = array();
		
		$segments[] = array( 'value' => '', 'text' => 'All Shows' );
		$podcast_segments = get_terms( 'podcast_segment', array( 'hide_empty' => false ) );
		if ( ! empty( $podcast_segments ) && ! is_wp_error( $podcast_segments ) ) :
			foreach( $podcast_segments as $podcast_segment ) :
				$segments[] = array(
					'value' => $podcast_segment->slug,
					'text' => $podcast_segment->name
				);
			endforeach;
		endif;
		
		return $segments;
		
	}
    
    /**
      * Add the Podcasts button to TinyMCE
      * 
      * @since 1.0.0
      */
    function hbi_soundcloud_podcasts_register_buttons($buttons) {
        array_push($buttons, "separator", "podcasts");
        return $buttons;
    }
     
     /**
      * Add the Podcasts button to TinyMCE
      * 
      * @since 1.0.0
      */
    function hbi_soundcloud_podcasts_add_button( $plugin_array ) {
        $plugin_array['podcasts'] = plugin_dir_url( __FILE__ ) . 'js/hbi-soundcloud-podcasts-admin.js';
        return $plugin_array;
    }
    
    /**
     * Admin page to allow for manually importing the podcasts. Useful if something as posted and is time sensitive.
     * 
     * @since   1.3.0
     */
    function manually_import_soundcloud_podcasts() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/manually-import-podcasts.php';
    }
    
    /**
     * Load the meta box for podcasts
     */
    public function hbi_soundcloud_podcasts_post_meta_boxes_setup() {
        add_meta_box( 'hbi-soundcloud-podcasts', 'SoundCloud File Information', array( 'HBI_SoundCloud_Podcasts_Admin', 'hbi_soundcloud_podcasts_meta_box' ), $post->post_type );
    }
    
    /**
     * The Podcasts meta box
     * 
     * @since 1.0.0
     */
    public function hbi_soundcloud_podcasts_meta_box( $object, $box) {
        wp_nonce_field( 'update_meta_box', 'hbi-soundcloud-podcasts' ); //$action, $name, $referer, $echo );
        require_once plugin_dir_path( __FILE__ ) . 'partials/hbi-soundcloud-podcasts-metabox.php';
    }
    
    /**
     * Save the post meta on Podcasts post type
     * 
     * @since   1.0.0
     */
    public function hbi_soundcloud_podcasts_save_post_class_meta( $post_id ) {
        
        if ( ! isset( $_POST['hbi-soundcloud-podcasts'] ) || ! wp_verify_nonce( $_POST['hbi-soundcloud-podcasts'], 'update_meta_box' ) ) {
            return $post_id;
        }
        
        /**
         * Removed the sanitization functions from this, as add/update_post_meta wants raw data
         * 
         * Codex: http://codex.wordpress.org/Function_Reference/update_post_meta
         */
        $meta_keys = array( 'use-soundcloud', 'soundcloud-track-id', 'soundcloud-shortcode', 'soundcloud-file', 'podcast-show' );
        foreach( $meta_keys as $meta_key ) :
            
            $new_meta_value = ( isset( $_POST[ $meta_key ] ) ) ? $_POST[ $meta_key ] : '';
            $meta_value = get_post_meta( $post_id, $meta_key, true );
            
            if ( $new_meta_value && '' == $meta_value ):
                add_post_meta( $post_id, $meta_key, $new_meta_value, true );
            elseif ( $new_meta_value && $new_meta_value != $meta_value ) :
                update_post_meta( $post_id, $meta_key, $new_meta_value );
            elseif ( '' == $new_meta_value && $meta_value ) :
                delete_post_meta( $post_id, $meta_key, $meta_value );
            endif;
            
        endforeach;
    }

    function add_podcast_show_column($columns) {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title' ),
            'podcast_show' => __( 'Podcast Show' ),
            'taxonomy-podcast_segment' => __( 'Podcast Segments' ),
            'date' => __( 'Date' ),
        );
        return $new_columns; 
    }
        

    public function add_post_show_column_value( $column, $post_id ) {
        switch ( $column ) {
            case 'podcast_show' :
                $podcast_show_slug = get_post_meta( $post_id, 'podcast-show', TRUE );
                
                $podcast_show_term = get_term_by( 'slug', $podcast_show_slug, 'podcast_show' );
                if( $podcast_show_term )
                    echo $podcast_show_term->name;
                else
                    echo $podcast_show_slug;
            }
        }
}