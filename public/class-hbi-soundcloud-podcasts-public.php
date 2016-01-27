<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.3
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    HBI_SoundCloud_Podcasts
 * @subpackage HBI_SoundCloud_Podcasts/public
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_SoundCloud_Podcasts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $hbi_soundcloud_podcasts;

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
	 * @var      string    $hbi_soundcloud_podcasts       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $hbi_soundcloud_podcasts, $version ) {

		$this->plugin_name= $hbi_soundcloud_podcasts;
		$this->version = $version;
        
        add_shortcode( 'podcasts', array( $this, 'podcasts_shortcode' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.3.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'hbi-soundcloud-podcasts', plugin_dir_url( __FILE__ ) . 'css/hbi-soundcloud-podcasts-public-1.2.css', array(), null, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'soundcloud-api', 'http://w.soundcloud.com/player/api.js' );
		wp_enqueue_script( 'hbi-soundcloud-podcasts', plugin_dir_url( __FILE__ ) . 'js/hbi-soundcould-podcasts-public-1.3.min.js', array( 'jquery', 'soundcloud-api' ), null, true );
	}
    
    /**
     * Register the Podcasts post type
     * 
     * @since   1.0.0
     */
    function register_podcasts_post_type() {
        $labels = array(
            'name'                => 'Podcasts',
            'singular_name'       => 'Podcast',
            'menu_name'           => 'Podcasts',
            'parent_item_colon'   => 'Parent Item:',
            'all_items'           => 'All Items',
            'view_item'           => 'View Item',
            'add_new_item'        => 'Add New Item',
            'add_new'             => 'Add New',
            'edit_item'           => 'Edit Item',
            'update_item'         => 'Update Item',
            'search_items'        => 'Search Item',
            'not_found'           => 'Not found',
            'not_found_in_trash'  => 'Not found in Trash',
        );
        
        $args = array(
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'comments' ),
            'taxonomies'          => array( 'post_tag' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-megaphone',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'register_meta_box_cb' => array( 'HBI_SoundCloud_Podcasts_Admin', 'hbi_soundcloud_podcasts_post_meta_boxes_setup' ),
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );
        
        register_post_type( 'podcasts', $args );
    }
    
    /**
     * Register the Podcast Show taxonomy
     * 
     * @since   1.0.0
     */
    function register_podcast_show() {
        $labels = array(
            'name'                       => _x( 'Podcast Shows', 'Taxonomy General Name' ),
            'singular_name'              => _x( 'Podcast Show', 'Taxonomy Singular Name' ),
            'menu_name'                  => __( 'Podcast Shows' ),
            'all_items'                  => __( 'All Items' ),
            'parent_item'                => __( 'Parent Item' ),
            'parent_item_colon'          => __( 'Parent Item:' ),
            'new_item_name'              => __( 'New Item Name' ),
            'add_new_item'               => __( 'Add New Item' ),
            'edit_item'                  => __( 'Edit Item' ),
            'update_item'                => __( 'Update Item' ),
            'separate_items_with_commas' => __( 'Separate items with commas' ),
            'search_items'               => __( 'Search Items' ),
            'add_or_remove_items'        => __( 'Add or remove items' ),
            'choose_from_most_used'      => __( 'Choose from the most used items' ),
            'not_found'                  => __( 'Not Found' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => false,
            'show_ui'                    => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        
        register_taxonomy( 'podcast_show', array( 'podcasts' ), $args );
    }
    
    /**
     * Register the Podcast Segments
     * 
     * @since   1.0.0
     */
    function register_podcast_segment() {
    
        $labels = array(
            'name'                       => _x( 'Podcast Segments', 'Taxonomy General Name' ),
            'singular_name'              => _x( 'Podcast Segment', 'Taxonomy Singular Name' ),
            'menu_name'                  => __( 'Podcast Segments' ),
            'all_items'                  => __( 'All Items' ),
            'parent_item'                => __( 'Parent Item' ),
            'parent_item_colon'          => __( 'Parent Item:' ),
            'new_item_name'              => __( 'New Item Name' ),
            'add_new_item'               => __( 'Add New Item' ),
            'edit_item'                  => __( 'Edit Item' ),
            'update_item'                => __( 'Update Item' ),
            'separate_items_with_commas' => __( 'Separate items with commas' ),
            'search_items'               => __( 'Search Items' ),
            'add_or_remove_items'        => __( 'Add or remove items' ),
            'choose_from_most_used'      => __( 'Choose from the most used items' ),
            'not_found'                  => __( 'Not Found' ),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        
        register_taxonomy( 'podcast_segment', array( 'podcasts' ), $args );
    }
    
    /**
     * Podcasts shortcode for displaying audio on a page/post
     * 
     * @since 1.1.0
     */
    function podcasts_shortcode( $atts ) {
    
        global $wp_query;
        $paged = ( $wp_query->query_vars['paged'] ) ? $wp_query->query_vars['paged'] : 1;
        
        // Attributes
        extract( shortcode_atts(
            array(
                'podcast_show' => '',
                'podcast_show_relation' => 'OR',
                'podcast_segment' => '',
                'podcast_tag' => '',
                'tag_compare' => 'tag_slug__in',
                'limit' => '10'
            ), $atts )
        );
        
        /* Define the array to make sure we're at least pulling podcasts */
        
        $atts = array( 
            'post_type' => 'podcasts',
            'posts_per_page' => $limit,
            'paged' => $paged
        );
        
        
        if( '' != $podcast_show ) {
            $atts['meta_key'] = 'podcast-show';
            
            $shows = explode( ',', $podcast_show );
            
            $atts['meta_query'] = array();
            
            $shows = explode( ',', $podcast_show );
            
            if( 1 < count( $shows ) ) {
                $atts['meta_query']['relation'] = $podcast_show_relation; 
            }
            
            foreach( explode( ',', $podcast_show ) as $show ) {
                $atts['meta_query'][] = array(
                    'key' => 'podcast-show',
                    'value' => $show
                );
            }
        }
        
        /****************************************************************************************************
         * The quicktag needs to handle how we're comparing the tags.                                       *
         * Possible values are:                                                                             *
         *  tag__and (array) - use tag ids.                                                                 *
         *  tag__in (array) - use tag ids.                                                                  *
         *  tag__not_in (array) - use tag ids.                                                              *
         *  tag_slug__and (array) - use tag slugs.                                                          *
         *  tag_slug__in (array) - use tag slugs.                                                           *
         *                                                                                                  *
         * Review http://codex.wordpress.org/Class_Reference/WP_Query#Tag_Parameters for more information   *
         ***************************************************************************************************/
        if( '' != $podcast_tag ) {
            $atts['tag_slug__in'] = explode( ',', $podcast_tag );
        }
        
        if( '' != $podcast_segment ) {
            $segments = explode( ',', $podcast_segment );
            
            $atts['tax_query'] = array();
            
            foreach( $segments as $segment ) {
                $atts['tax_query'][] = array(
                    'taxonomy' => 'podcast_segment',
                    'field' => 'slug',
                    'terms' => sanitize_title( $segment )
                );
            }
            
            $this->display_podcasts_by_segment( $atts );
        } else {
            
            $atts['tax_query'] = array();
            
            $hourly_atts = $atts;
            $segment_atts = $atts;
            
            $hourly_atts['tax_query'][] = array(
                'taxonomy' => 'podcast_segment',
                'field' => 'slug',
                'terms' => 'podcast'
            );
            
            $segment_atts['tax_query'][] = array(
                'taxonomy' => 'podcast_segment',
                'field' => 'slug',
                'terms' => 'podcast',
                'operator' => 'NOT IN'
            );
            
            $this->display_podcasts_with_tabs( $hourly_atts, $segment_atts );
        }
        
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Display audio only from segments
     * 
     * @since 1.2.0
     */
    function display_podcasts_by_segment( $atts ) {
        
        $podcasts = new WP_Query( $atts );
        ob_start();
        
        if( $podcasts->have_posts() ) :
            echo '<div class="podcast_bin">';
            echo '  <div class="podcast_placeholder"></div>';
            echo '  <div class="podcasts">';
            echo '<div class="audio_by_segment">';
            while( $podcasts->have_posts() ) :
                $podcasts->the_post();
                $post_segment = wp_get_post_terms( get_the_ID(), 'podcast_segment' );
                ?>
                <a class="podcast" href="<?php the_permalink(); ?>" data-soundcloud_embed="<?php echo htmlentities( do_shortcode( get_post_meta( get_the_ID(), 'soundcloud-shortcode', TRUE ) )  ); ?>"">
                    <div class="podcast_segment"><?php echo $post_segment[0]->name; ?></div>
                    <div class="podcast_title"><?php the_title(); ?></div>
                </a>
                <?php
            endwhile;
                echo "</div>";
                echo "</div>";
            echo "</div>";
            
            if( is_home() ) {
                echo '<div class="pagination"><span id="tie-next-page"><a style="font-weight: bold; font-size: 120%; text-align: center; border-bottom: 1px solid #D8D8D8; margin: 0px auto; display: block; width: 100%;" href="/podcasts/">More Audio</a></span></div>';
            }
            if( is_single() || is_page() ) {
                echo tie_pagenavi( $podcasts, 5 );
            }
            
        endif;
    }
    
    /**
     * Print the podcasts with tabs for hourly and segment audio
     * 
     * @since   1.2.0
     */
    public static function display_podcasts_with_tabs( $hourly_atts, $segment_atts ) {
        
        ob_start();
        
        echo '<div class="podcast_bin">';
            echo '  <div class="podcast_placeholder"></div>';
            echo '<div class="podcast_tabs">Select an audio category: ';
                echo '<div class="podcast_tab" data-segment="segments">Segment Audio</div>';
                echo '<div class="podcast_tab" data-segment="hourly">Hourly Audio</div>';
            echo '</div>';
            echo '  <div class="podcasts">';
                if( isset( $_GET['podcast_tab'] ) && ( $_GET['podcast_tab'] == 'segments' || $_GET['podcast_tab'] == 'hourly' ) ) {
                    ?>
                    <style>
                        #segments, #hourly { display: none; }
                        #<?php echo esc_attr( $_GET['podcast_tab']); ?> { display: block; }
                    </style>
                    <?php
                } else {
                    ?>
                    <style>
                        #segments, #hourly { display: none; }
                        #segments { display: block; }
                    </style>
                    <?php
                }
                echo '<div id="hourly">';
                    $hourly_clips = new WP_Query( $hourly_atts );
                    if( $hourly_clips->have_posts() ) :
                        while( $hourly_clips->have_posts() ) :
                            $hourly_clips->the_post();
                            $post_segment = wp_get_post_terms( get_the_ID(), 'podcast_segment' );
                            $soundcloud_embed = do_shortcode( get_post_meta( get_the_ID(), 'soundcloud-shortcode', TRUE ) );
                            $soundcloud_embed = str_replace( '&visual=true', '', $soundcloud_embed );
                            $soundcloud_embed = str_replace( '&amp;visual=true', '', $soundcloud_embed );
                            ?>
                            <a class="podcast" href="<?php the_permalink(); ?>" data-soundcloud_embed="<?php echo htmlentities( $soundcloud_embed ); ?>"">
                                <div class="podcast_segment"><?php echo $post_segment[0]->name; ?></div>
                                <div class="podcast_title"><?php the_title(); ?></div>
                            </a>
                            <?php
                        endwhile;
                    endif;
                    wp_reset_query();
                    wp_reset_postdata();
                echo '</div>';
                echo '<div id="segments">';
                    $segment_clips = new WP_Query( $segment_atts );
                    if( $segment_clips->have_posts() ) :
                        while( $segment_clips->have_posts() ) :
                            $segment_clips->the_post();
                            $post_segment = wp_get_post_terms( get_the_ID(), 'podcast_segment' );
                            $soundcloud_embed = do_shortcode( get_post_meta( get_the_ID(), 'soundcloud-shortcode', TRUE ) );
                            $soundcloud_embed = str_replace( '&visual=true', '', $soundcloud_embed );
                            $soundcloud_embed = str_replace( '&amp;visual=true', '', $soundcloud_embed );
                            ?>
                            <a class="podcast" href="<?php the_permalink(); ?>" data-soundcloud_embed="<?php echo htmlentities( $soundcloud_embed ); ?>">
                                <div class="podcast_segment"><?php echo $post_segment[0]->name; ?></div>
                                <div class="podcast_title"><?php the_title(); ?></div>
                            </a>
                            <?php
                        endwhile;
                    endif;
                    wp_reset_query();
                    wp_reset_postdata();
                echo '</div>';
            echo "</div>";
        echo "</div>";
        
        if( is_home() ) {
            echo '<div class="pagination"><span id="tie-next-page"><a class="load_more_audio" style="font-weight: bold; font-size: 120%; text-align: center; border-bottom: 1px solid #D8D8D8; margin: 0px auto; display: block; width: 100%;" href="/podcasts/">More Audio</a></span></div>';
        }
        if( is_single() || is_page() ) {
            echo tie_pagenavi( $podcasts, 5 );
        }
    }
        
    /**
     * Returns a JSON array of possible podcast segments for the Podcast editor button
     * 
     * @since   1.0.0
     */
    function return_js_array_of_podcast_segments() {
        
        $segments = array();
        
        foreach( get_terms( 'podcast_segment' ) as $segment ) :
            $mysegment = array(); 
            $mysegment['value'] = $segment->slug;
            $mysegment['text'] = $segment->name;
            $segments[] = $mysegment;
        endforeach;
        
        die( json_encode( $segments ) ); // this is required to return a proper result
    }
    
    /**
     * Import the podcasts using the SoundCloud API
     * 
     * @since   1.0.0
     */
    
    public function import_podcasts() {
        require_once plugin_dir_path( __FILE__ ) . 'assets/php-soundcloud-master/Services/Soundcloud.php';
        
        $output = '';
		$options = get_option( 'hbi_soundcloud_podcasts_settings' );
        
        $client = new Services_Soundcloud(
            $options['soundcloud_client_id'],
            $options['soundcloud_client_secret'],
            $options['soundcloud_redirect_uri']
        );
		
		$soundcloud_user_id = $options['soundcloud_user_id'];
        
        $access_token = $client->setAccessToken();
        
        $current_time = current_time( 'timestamp' ) - 21600;
    
        // grab the tracks
        $tracks = json_decode( $client->get( 'users/' . $soundcloud_user_id . '/tracks', array( 'created_at[from]' => date( 'Y-m-d', $current_time ) ) ) );
		
        if( count( $tracks ) ) :
            $output .= "Currently processing " . count( $tracks ) . " tracks.\r\n";
            $totalimport = 0;
            
            // loop through the tracks
            foreach( $tracks as $track )
            {
                if( $track->downloadable != 1 ) {
                    $output .= "Track " . $track->id . " is not set to downloadable. \r\n";
                    $downloadable = <<<EOH
<track>
    <downloadable>true</downloadable>
</track>
EOH;
                try {
                    $client->put(
                            'tracks/' . $track->id,
                            $downloadable,
                            array(CURLOPT_HTTPHEADER => array('Content-Type: application/xml'))
                        );
                        $output .= "Updated Track " . $track->id ." in SoundCloud to make sure it was downloadable.\r\n\r\n";
                    } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
                        $output .= "Error setting track " . $track->id . "to be downloadable. " . $e->getMessage() . "\r\n\r\n";
                    }
                }
                    
                $args = array(
                    'post_type' => 'podcasts',
                    'meta_key' => 'soundcloud-track-id',
                    'post_status' => 'any',
                    'meta_value' => $track->id
                );
                
                $test = get_posts( $args );
                
                if( count( $test ) == 0 ) : 
                    $downloadURL = 'http://feeds.soundcloud.com/stream/'.$track->id.'-101sports-'.$track->permalink.'.mp3';
                    
                    $show = 'zach-and-rammer';
					// Added in trim to try to fix all of the wrong things happening from extra spaces
                    $release = str_replace( ' ', '-', strtolower( trim( $track->release ) ) );
                    $release = str_replace( '&', 'and', $release );
                    
                    if( $release != '' ) { $show = $release; }
                    
                    $content = array(
                        'post_type' => 'podcasts',
                        'post_status' => 'publish',
                        'post_title' => $track->title,
                        'post_content' => $track->description,
                        'tax_input' => array( 'podcast-segment' => array( trim( strtolower( $track->label_name ) ) ) ),
                    );
                    
                    $post_id = wp_insert_post( $content );
                    $term = term_exists( $track->label_name, 'podcast_segment' ); 
                    wp_set_post_terms( $post_id, $term['term_id'], 'podcast_segment' );
                    
                    add_post_meta( $post_id, 'soundcloud-file', $downloadURL );
                    add_post_meta( $post_id, 'use-soundcloud', 1 );
                    add_post_meta( $post_id, 'soundcloud-shortcode', '[soundcloud url="http://api.soundcloud.com/tracks/'.$track->id.'" params="" width=" 100%" height="166" iframe="true" /]' );
                    add_post_meta( $post_id, 'soundcloud-track-id', $track->id );
                    add_post_meta( $post_id, 'podcast-show', $show );
                    $totalimport++;
                    $output .= "Imported " . $track->title . "(" . $track->id. ") into the system.\r\n";
                    
                endif;
            }
            $output .= "Finished processing " . count( $tracks ) . " tracks. " . $totalimport . " have been imported into the system.\r\n";
        endif;
    }
}