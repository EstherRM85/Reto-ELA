<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Share_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name	 = $plugin_name;
		$this->version		 = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 * @access public
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Share_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Share_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( ! wp_style_is( 'wb-font-awesome', 'enqueued' ) ) {
			//wp_enqueue_style( 'wb-font-awesome', '//use.fontawesome.com/releases/v5.5.0/css/all.css' );
			wp_enqueue_style( 'wb-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-share-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * @access public
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Share_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Share_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-share-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Display share button in front page.
	 * @access public
	 * @since    1.0.0
	 */
	public function bp_activity_share_button_dis() {
		if ( is_user_logged_in() ) {
			add_action( 'bp_activity_entry_meta', array( $this, 'bp_share_activity_filter' ) );
		} else {
			add_action( 'bp_before_activity_entry_comments', array( $this, 'bp_share_activity_filter' ) );
		}
	}

	/**
	 * BP Share activity filter
	 * @access public
	 * @since    1.0.0
	 */
	function bp_share_activity_filter() {
		$service		 = get_site_option( 'bp_share_services' );
		$extra_options	 = get_site_option( 'bp_share_services_extra' );
		$activity_type	 = bp_get_activity_type();
		$activity_link	 = bp_get_activity_thread_permalink();
		$activity_title	 = bp_get_activity_feed_item_title(); // use for description : bp_get_activity_feed_item_description()
		$plugin_path	 = plugins_url();
		if ( !is_user_logged_in() ) {
			echo '<div class = "activity-meta" >';
		}
		$share_button_text	 = esc_html__( 'Share', 'buddypress-share' );
		$updated_text		 = apply_filters( 'bpas_share_button_text_override', $share_button_text );
		if ( isset( $updated_text ) ) {
			$share_button_text = $updated_text;
		}
		?>
		<span class="bp-share-btn">
			<a class="button item-button bp-secondary-action bp-share-button" rel="nofollow"><?php esc_html_e( "$share_button_text", 'buddypress-share' ); ?></a>
		</span>
		</div>
		<div class="service-buttons <?php echo $activity_type ?>" style="display: none;">
			<?php
			if ( !empty( $service ) ) {
				foreach ( $service as $key => $value ) {
					if ( isset( $key ) && $key == 'bp_share_facebook' && $value[ 'chb_' . $key ] == 1 ) {
						echo '<a target="blank" class="bp-share" href="https://www.facebook.com/sharer/sharer.php?u=' . $activity_link . '" rel="facebook"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_twitter' && $value[ 'chb_' . $key ] == 1 ) {
						echo '<a target="blank" class="bp-share" href="http://twitter.com/share?text=' . $activity_title . '&url=' . $activity_link . '" rel="twitter"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_pinterest' && $value[ 'chb_' . $key ] == 1 ) {
						$media	 = '';
						$video	 = '';
						echo '<a target="blank" class="bp-share" href="https://pinterest.com/pin/create/bookmarklet/?media=' . $media . '&url=' . $activity_link . '&is_video=' . $video . '&description=' . $activity_title . '" rel="penetrest"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_linkedin' && $value[ 'chb_' . $key ] == 1 ) {
						echo '<a target="blank" class="bp-share" href="http://www.linkedin.com/shareArticle?url=' . $activity_link . '&title=' . $activity_title . '"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_reddit' && $value[ 'chb_' . $key ] == 1 ) {
						echo '<a target="blank" class="bp-share" href="http://reddit.com/submit?url=' . $activity_link . '&title=' . $activity_title . '"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_wordpress' && $value[ 'chb_' . $key ] == 1 ) {
						$description = '';
						$img		 = '';
						echo '<a target="blank" class="bp-share" href="https://wordpress.com/wp-admin/press-this.php?u=' . $activity_link . '&t=' . $activity_title . '&s=' . $description . '&i= ' . $img . ' "><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_pocket' && $value[ 'chb_' . $key ] == 1 ) {
						echo '<a target="blank" class="bp-share" href="https://getpocket.com/save?url=' . $activity_link . '&title=' . $activity_title . '"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
					if ( isset( $key ) && $key == 'bp_share_email' && $value[ 'chb_' . $key ] == 1 ) {
						$email = 'mailto:?subject=' . $activity_link . '&body=Check out this site: ' . $activity_title . '" title="Share by Email';
						echo '<a class="bp-share" href="' . $email . '" attr-display="no-popup"><span class="fa-stack fa-lg"><i class="' . $value[ 'service_icon' ] . '"></i></span></a>';
					}
				}
			} else {
				esc_html_e( 'Please enable share services!', 'buddypress-share' );
			}
			do_action( 'bp_share_user_services', $services = array(), $activity_link, $activity_title );
			?>
		</div>
		<div>
			<script>
		        jQuery( document ).ready( function () {
		            var pop_active = '<?php echo isset( $extra_options[ 'bp_share_services_open' ] ) ? $extra_options[ 'bp_share_services_open' ] : '' ?>';
		            if ( pop_active == 1 ) {
		                jQuery( '.bp-share' ).addClass( 'has-popup' );
		            }
		        } );
			</script>
			<?php
			if ( !is_user_logged_in() ) {
				echo '</div>';
			}
		}

		public function bp_share_doctype_opengraph( $output ) {
			return $output . '
    xmlns:og="http://opengraphprotocol.org/schema/"
    xmlns:fb="http://www.facebook.com/2008/fbml"';
		}

		public function bp_share_opengraph() {
			global $bp, $post;
			if ( ( bp_is_active( 'activity' ) && bp_is_current_component( 'activity' ) && !empty( $bp->current_action ) && is_numeric( $bp->current_action ) && bp_is_single_activity() ) ) {
				$activity_img		 = '';
				$activity_assets	 = array();
				$activity_content	 = '';
				$first_img_src       = '';
				$activity_obj		 = new BP_Activity_Activity( $bp->current_action );
				$activity_permalink	 = bp_activity_get_permalink( $bp->current_action );
				preg_match_all( '/(src|width|height)=("[^"]*")/', $activity_obj->content, $result );

				if ( isset( $result[ 2 ] ) && !empty( $result[ 2 ] ) ) {
					$result_new = array_map( function($i) {
						return trim( $i, '"' );
					}, $result[ 2 ] );
					foreach ( $result[ 1 ] as $key => $result_key ) {
						$activity_assets[ $result_key ] = $result_new[ $key ];
					}
				}
				if ( !empty( $activity_obj->action ) ) {
					$content = $activity_obj->action;
				} else {
					$content = $activity_obj->content;
				}

				$content = explode( '<span', $content );
				$title	 = strip_tags( ent2ncr( trim( convert_chars( $content[ 0 ] ) ) ) );

				if ( ':' === substr( $title, -1 ) ) {
					$title = substr( $title, 0, -1 );
				}

				$activity_content = preg_replace('#<ul class="rtmedia-list(.*?)</ul>#', ' ', $activity_obj->content );
				
				if ( !empty( $activity_assets[ 'src' ] ) ) {
					$activity_content	 = explode( '<span>', $activity_content );
					$activity_content	 = strip_tags( ent2ncr( trim( convert_chars( $activity_content[ 1 ] ) ) ) );
				} else {
					$activity_content = $activity_obj->content;
				}
				
				preg_match_all('/<img.*?src\s*=.*?>/', $activity_obj->content, $matches );
				if ( isset( $matches[0][0] ) ) {
					preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $matches[0][0], $matches2 );
					if ( isset( $matches2[1][0] ) ) {
						$first_img_src = $matches2[1][0];
					}	
				}

				$avatar_url = get_avatar_url( $activity_obj->user_id, array( 'size' => 300 ) );

				if ( empty( $first_img_src ) ) {
					$og_image = $avatar_url;
				} else {
					$og_image = $first_img_src;
				}
				
				$activity_content = wp_strip_all_tags( $activity_content );
				$activity_content = stripslashes( $activity_content );
				$extra_options = get_site_option('bp_share_services_extra');
				$enable_user_avatar = false;
				if ( isset( $extra_options['bp_share_avatar_open_graph'] ) ) {
    				if ( $extra_options['bp_share_avatar_open_graph'] == 1 ) {
    					$enable_user_avatar = true;
    				}
    			}		
				?>
				<meta property="og:type"   content="article" />
				<meta property="og:url"    content="<?php echo esc_url( $activity_permalink ); ?>" />
				<meta property="og:title"  content="<?php echo $title; ?>" />
				<meta property="og:description" content="<?php echo $activity_content; ?>" />
				<?php if ( $enable_user_avatar ) {
					$og_image = $avatar_url;
				}
				?>
				<meta property="og:image" content="<?php echo $og_image; ?>" />
				<meta property="og:image:secure_url" content="<?php echo $og_image; ?>" />
				<meta property="og:image:width" content="400" />
				<meta property="og:image:height" content="300" />
			<?php } else {
				return;
			}
		}

	}
	