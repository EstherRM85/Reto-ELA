<?php
/**
 * Class to add top header pages of wbcom plugin and additional features.
 *
 * @author   Wbcom Designs
 * @package  BuddyPress_Member_Reviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Wbcom_Admin_Settings' ) ) {

	/**
	 * Class to add wbcom plugin's admin settings.
	 *
	 * @author   Wbcom Designs
	 * @since    1.1.0
	 */
	class Wbcom_Admin_Settings {

		/**
		 * Wbcom_Admin_Settings Constructor.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'wbcom_admin_setting_header', array( $this, 'wbcom_admin_setting_header_html' ) );
			add_action( 'admin_menu', array( $this, 'wbcom_admin_additional_pages' ), 999 );
			add_action( 'admin_enqueue_scripts', array( $this, 'wbcom_enqueue_admin_scripts' ) );
			add_action( 'wp_ajax_wbcom_manage_plugin_installation', array( $this, 'wbcom_do_plugin_action' ) );
		}

		/**
		 * Ajax call to serve action related to plugin's install/activate/deactive.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function wbcom_do_plugin_action() {
			$action	= !empty( $_POST[ 'plugin_action' ] ) ? $_POST[ 'plugin_action' ] : false;
			$slug   = !empty( $_POST[ 'plugin_slug' ] ) ? $_POST[ 'plugin_slug' ] : false;

			if ( 'install_plugin' == $action ) {
				$this->wbcom_do_plugin_install( $slug );
			} else if ( 'activate_plugin' == $action ) {
				$this->wbcom_do_plugin_activate( $slug );
			} else {
				$this->wbcom_do_plugin_deactivate( $slug );
			}
			die;
		}

		/**
		 * Function for activate plugin.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function wbcom_do_plugin_activate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			$result			  = activate_plugin( $plugin_file_path );
		}

		/**
		 * Function for deactivate plugin.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function wbcom_do_plugin_deactivate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			$result			  = deactivate_plugins( $plugin_file_path );
		}

		/**
		 * Function for get plugin file name.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function _get_plugin_file_path_from_slug( $slug ) {
			if ( !function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins_list = get_plugins();
			$keys		  = array_keys( $plugins_list );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}
			return $slug;
		}

		/**
		 * Function for install plugin.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function wbcom_do_plugin_install( $slug ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			wp_cache_flush();

			$upgrader	= new Plugin_Upgrader();
			$plugin_zip	= $this->get_download_url( $slug );
			$installed	= $upgrader->install( $plugin_zip );
			if ( $installed ) {
				$response = array( 'status' => 'installed' );
				echo wp_send_json_success( $response );
			} else {
				return false;
			}
			exit;
		}

		/**
		 * Function for upgrade plugin.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $plugin_slug Plugin's slug.
		 */
		function upgrade_plugin( $plugin_slug ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			wp_cache_flush();

			$upgrader = new Plugin_Upgrader();
			$upgraded = $upgrader->upgrade( $plugin_slug );

			return $upgraded;
		}

		/**
		 * Function for return plugin's wordpress repo download url.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function get_download_url( $slug ) {
			return $this->get_wp_repo_download_url( $slug );
		}

		/**
		 * Function for get plugin's wordpress repo download url.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function get_wp_repo_download_url( $slug ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); // for plugins_api..
			$api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) ); //Save on a bit of bandwidth.

			if ( is_wp_error( $api ) ) {
				$status[ 'error' ] = $api->get_error_message();
				wp_send_json_error( $status );
			}

			return $api->download_link;
		}

		/**
		 * Function for get all wbcom free plugin's details.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_all_free_plugins() {
			$free_plugins = array(
				'0'	 => array(
					'name'			 => esc_html__( 'Custom Font Uploader', 'buddypress-private-community-pro' ),
					'slug'			 => 'custom-font-uploader',
					'description'	 => esc_html__( 'It also allows you to upload your own custom font to your site and use them using custom css.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'custom-font-uploader' ),
					'wp_url'		 => 'https://wordpress.org/plugins/custom-font-uploader/',
					'icon'			 => 'fa fa-upload'
				),
				'1'	 => array(
					'name'			 => esc_html__( 'BuddyPress Activity Filter', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-activity-filter',
					'description'	 => esc_html__( 'Admin can set default and customised activities to be listed on front-end.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-activity-filter' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-activity-filter/',
					'icon'			 => 'fa fa-filter'
				),
				'2'	 => array(
					'name'			 => esc_html__( 'BuddyPress Activity Social Share', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-activity-social-share',
					'description'	 => esc_html__( 'This plugin allows anyone easily share BuddyPress Activites on major social media (Facebook, Twitter, Google+, Linkedin ).', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-activity-social-share' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-activity-social-share/',
					'icon'			 => 'fa fa-share-alt'
				),
				'3'	 => array(
					'name'			 => esc_html__( 'BuddyPress Create Group Type', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-create-group-type',
					'description'	 => esc_html__( 'It will help to create group type for BuddyPress Groups.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-create-group-type' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-create-group-type/',
					'icon'			 => 'fa fa-sitemap'
				),
				'4'	 => array(
					'name'			 => esc_html__( 'BuddyPress Member Reviews', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-user-profile-reviews',
					'description'	 => esc_html__( 'This plugin allows only site members to add reviews to the buddypress members on the site and even rate the member’s profile out of 5 points with multiple review criteria.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-user-profile-reviews' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-user-profile-reviews/',
					'icon'			 => 'fa fa-user'
				),
				'5'	 => array(
					'name'			 => esc_html__( 'BuddyPress Group Reviews', 'buddypress-private-community-pro' ),
					'slug'			 => 'review-buddypress-groups',
					'description'	 => esc_html__( 'This plugin allows the BuddyPress Members to give reviews to the BuddyPress groups on the site. The review form allows the users to give text review, even rate the group on the basis of multiple criterias.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'review-buddypress-groups' ),
					'wp_url'		 => 'https://wordpress.org/plugins/review-buddypress-groups/',
					'icon'			 => 'fa fa-2x fa-users'
				),
				'6'	 => array(
					'name'			 => esc_html__( 'BuddyPress Favorite Notification', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-favorite-notification',
					'description'	 => esc_html__( 'BuddyPress Favorite Notification adds a notification for BuddyPress activity.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-favorite-notification' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-favorite-notification/',
					'icon'			 => 'fa fa-2x fa-bell'
				),
				'7'	 => array(
					'name'			 => esc_html__( 'Custom Email Options', 'buddypress-private-community-pro' ),
					'slug'			 => 'custom-email-options',
					'description'	 => esc_html__( 'Override default email options of Worpdress.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'custom-email-options' ),
					'wp_url'		 => 'https://wordpress.org/plugins/custom-email-options/',
					'icon'			 => 'fa fa-2x fa-at'
				),
				'8'	 => array(
					'name'			 => esc_html__( 'BuddyPress Checkins', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-check-in',
					'description'	 => esc_html__( 'This plugin allows BuddyPress members to share their location when they are posting activities, you can add places where you visited, nearby locations based on google places.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-check-in' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-check-in/',
					'icon'			 => 'fa fa-2x fa fa-map-marker'
				),
				'9'	 => array(
					'name'			 => esc_html__( 'BuddyPress Job Manager', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-job-manager',
					'description'	 => esc_html__( 'Incorporates BuddyPress with the WP Job Manager plugin by creating specific tabs in employer’s and candidate’s profiles.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-job-manager' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-job-manager/',
					'icon'			 => 'fa fa-2x fa-briefcase'
				),
				'10' => array(
					'name'			 => esc_html__( 'BuddyPress user ToDo List', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-user-to-do-list',
					'description'	 => esc_html__( 'This plugin allows you to create your personal task list with timestamp. You can mark them complete when you are done with them. It will also send reminder when you have any overdue task.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-user-to-do-list' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-user-to-do-list/',
					'icon'			 => 'fa fa-2x fa-list-ol'
				),
				'11' => array(
					'name'			 => esc_html__( 'Shortcodes for BuddyPress', 'buddypress-private-community-pro' ),
					'slug'			 => 'shortcodes-for-buddypress',
					'description'	 => esc_html__( 'This plugin will add an extended feature to the big name “BuddyPress” that will generate Shortcode for Listing Activity Streams , Members and Groups on any post/page in website.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'shortcodes-for-buddypress' ),
					'wp_url'		 => 'https://wordpress.org/plugins/shortcodes-for-buddypress/',
					'icon'			 => 'fa fa-2x fa-code'
				),
				'12' => array(
					'name'			 => esc_html__( 'Woo Open Graph', 'buddypress-private-community-pro' ),
					'slug'			 => 'woo-open-graph',
					'description'	 => esc_html__( 'This plugin will add an extended feature to the big name “WooCommerce” that will adds well executed and accurate Open Graph Meta Tags to your site with title,description and WooCommerce featured image.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'woo-open-graph' ),
					'wp_url'		 => 'https://wordpress.org/plugins/woo-open-graph/',
					'icon'			 => 'fa fa-bar-chart'
				),
				'13' => array(
					'name'			 => esc_html__( 'BuddyPress Lock', 'buddypress-private-community-pro' ),
					'slug'			 => 'lock-my-bp',
					'description'	 => esc_html__( 'This plugin allows the administrator to lock the certain parts of their site. It help to create private BuddyPress community by locking certain BuddyPress Components, WordPress Pages, Custom Post Types for public view without using any membership plugin.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'lock-my-bp' ),
					'wp_url'		 => 'https://wordpress.org/plugins/lock-my-bp/',
					'icon'			 => 'fa fa-2x fa-lock'
				),
				'14' => array(
					'name'			 => esc_html__( 'Woo Audio Preview', 'buddypress-private-community-pro' ),
					'slug'			 => 'woo-audio-preview',
					'description'	 => esc_html__( 'This plugin Allows playing the audio files in sample mode to prevent unauthorized downloading of the audio files. It helps to display sample files at single product page.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'woo-audio-preview' ),
					'wp_url'		 => 'https://wordpress.org/plugins/woo-audio-preview/',
					'icon'			 => 'fa fa-2x fa-volume-up'
				),
				'15' => array(
					'name'			 => esc_html__( 'WordPress System Log', 'buddypress-private-community-pro' ),
					'slug'			 => 'wp-system-log',
					'description'	 => esc_html__( 'This plugin helps administrators of the site see their environment on which the site is currently running that includes WordPress environment, tha database it requires, Server Environment and the plugins installed and activated on the site.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'wp-system-log' ),
					'wp_url'		 => 'https://wordpress.org/plugins/wp-system-log/',
					'icon'			 => 'fa fa-file-text'
				),
				'16' => array(
					'name'			 => esc_html__( 'BP Post From Anywhere', 'buddypress-private-community-pro' ),
					'slug'			 => 'bp-post-from-anywhere',
					'description'	 => esc_html__( 'This plugin will generate shortcode and widgets for post updates section for activities so you can post update from anywhere, it might be sidebar, some page or any template file.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'bp-post-from-anywhere' ),
					'wp_url'		 => 'https://wordpress.org/plugins/bp-post-from-anywhere/',
					'icon'			 => 'fa fa-2x fa-edit'
				),
				'17' => array(
					'name'			 => esc_html__( 'Woo Document Preview', 'buddypress-private-community-pro' ),
					'slug'			 => 'woo-document-preview',
					'description'	 => esc_html__( 'This will allow you to add document preview at single product page. Which helps to offer more better idea when you are selling ebooks, pdf or some documents.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'woo-document-preview' ),
					'wp_url'		 => 'https://wordpress.org/plugins/woo-document-preview/',
					'icon'			 => 'fa fa-2x fa-file'
				),
				'18' => array(
					'name'			 => esc_html__( 'WordPress Media Category', 'buddypress-private-community-pro' ),
					'slug'			 => 'media-category',
					'description'	 => esc_html__( 'This plugin helps administrators of the site categorize their wordpress media.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'media-category' ),
					'wp_url'		 => 'https://wordpress.org/plugins/media-category/',
					'icon'			 => 'fa fa-picture-o'
				),
				'19' => array(
					'name'			 => esc_html__( 'Woo Price Quotes', 'buddypress-private-community-pro' ),
					'slug'			 => 'woo-price-quote-inquiry',
					'description'	 => esc_html__( 'This plugin helps in quoting the products that admin wishes to hide its purchasing details.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'woo-price-quote-inquiry' ),
					'wp_url'		 => 'https://wordpress.org/plugins/woo-price-quote-inquiry/',
					'icon'			 => 'fa fa-usd'
				),
				'20' => array(
					'name'			 => esc_html__( 'EDD Service Extended', 'buddypress-private-community-pro' ),
					'slug'			 => 'edd-service-extended',
					'description'	 => esc_html__( 'This plugin helps administrators of the site categorize their wordpress media.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'edd-service-extended' ),
					'wp_url'		 => 'https://wordpress.org/plugins/edd-service-extended/',
					'icon'			 => 'fa fa-2x fa-product-hunt'
				),
				'21' => array(
					'name'			 => esc_html__( 'WB Ads Rotator with Split Test', 'buddypress-private-community-pro' ),
					'slug'			 => 'wb-ads-rotator-with-split-test',
					'description'	 => esc_html__( 'This plugin is designed for the SPLIT TESTING, you can check performance of your ads layout and on the basis of them you can select one of them for your regular use.', 'buddypress-private-community-pro' ),
					'status'		 => $this->wbcom_plugin_status( 'wb-ads-rotator-with-split-test' ),
					'wp_url'		 => 'https://wordpress.org/plugins/wb-ads-rotator-with-split-test/',
					'icon'			 => 'fa fa-adn'
				)
			);
			return $free_plugins;
		}

		/**
		 * Function for get all wbcom paid plugin's details.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_all_paid_plugins() {
			$paid_plugins = array(
				'0'	 => array(
					'name'			 => esc_html__( 'BuddyPress Moderation Pro', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'BuddyPress Community Moderation offers a solution for site owners to keep their communities straight. With community policing strategy, members of the community have an option for moderation sitewide by attaching flags to content created within the various components.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/',
					'icon'			 => 'fa fa-exclamation-triangle'
				),
				'1'	 => array(
					'name'			 => esc_html__( 'BuddyPress Polls', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'Use BuddyPress Polls plugin to create polls inside the activity, let your user response to your polls. Members can create pools like activities, easily votes on them.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-polls/',
					'icon'			 => 'fa fa-bar-chart'
				),
				'2'	 => array(
					'name'			 => esc_html__( 'BuddyPress Resume Manager', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'BuddyPress Resume Manager adds a separate BuddyPress Resume menu at a user’s BuddyPress Profile Page to display individual member resume. We have added predefined fields for the resumes and site admin and enable and disable them.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-resume-manager/',
					'icon'			 => 'fa fa-file'
				),
				'3'	 => array(
					'name'			 => esc_html__( 'BuddyPress Profanity', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'Use BuddyPress Profanity plugin to censor content in your community! Easily Censor all the unwanted words in activities, private messages contents by specifying a list of keywords to be filtered.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-profanity/',
					'icon'			 => 'fa fa-hand-peace-o'
				),
				'4'	 => array(
					'name'			 => esc_html__( 'BuddyPress Private Community Pro', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'This plugin offers a lockdown for BuddyPress Component and will ask users to log in go further to check profile or any other protected details.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/',
					'icon'			 => 'fa fa-user-times'
				),
				'5'	 => array(
					'name'			 => esc_html__( 'BuddyPress Profile Pro', 'buddypress-private-community-pro' ),
					'description'	 => esc_html__( 'This plugin gives you the power to extend BuddyPress Profiles with repeater fields and groups. You can easily add multiple field groups and display them at member’s profile.', 'buddypress-private-community-pro' ),
					'download_url'	 => 'https://wbcomdesigns.com/downloads/buddypress-profile-pro/',
					'icon'			 => 'fa fa-user-circle-o'
				),
			);
			return $paid_plugins;
		}

		/**
		 * Function for check plugin is installed or not.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		function wbcom_is_plugin_installed( $slug ) {
			if ( !function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins = get_plugins();
			$keys		 = array_keys( $all_plugins );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Function for check plugin's status.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_plugin_status( $slug ) {
			if ( $this->wbcom_is_plugin_installed( $slug ) ) {
				if ( $this->wbcom_is_plugin_active( $slug ) ) {
					return 'activated';
				} else {
					return 'installed';
				}
			} else {
				return 'not_installed';
			}
		}

		/**
		 * Function for check plugin is activated or not.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_is_plugin_active( $slug ) {
			if ( !function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins = get_plugins();
			$keys		 = array_keys( $all_plugins );
			$response	 = false;
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					if ( is_plugin_active( $key ) ) {
						$response = true;
					}
				}
			}
			return $response;
		}

		/**
		 * Enqueue js & css related to wbcom plugin.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_enqueue_admin_scripts() {
			if ( !wp_style_is( 'font-awesome', 'enqueued' ) ) {
				//wp_enqueue_style( 'font-awesome', '//use.fontawesome.com/releases/v5.5.0/css/all.css' );
				wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
			}
			if ( !wp_script_is( 'wbcom-admin-setting-js', 'enqueued' ) ) {

				wp_register_script(
				$handle		 = 'wbcom_admin_setting_js', $src		 = BLPRO_PLUGIN_URL . 'admin/wbcom/assets/js/wbcom-admin-setting.js', $deps		 = array( 'jquery' ), $ver		 = time(), $in_footer	 = true
				);
				wp_localize_script(
				'wbcom_admin_setting_js', 'wbcom_plugin_installer_params', array(
					'ajax_url'			 => admin_url( 'admin-ajax.php' ),
					'activate_text'		 => esc_html__( 'Activate', 'buddypress-private-community-pro' ),
					'deactivate_text'	 => esc_html__( 'Deactivate', 'buddypress-private-community-pro' )
				)
				);
				wp_enqueue_script( 'wbcom_admin_setting_js' );

			}

			if ( !wp_style_is( 'wbcom-admin-setting-css', 'enqueued' ) ) {
				wp_enqueue_style( 'wbcom-admin-setting-css', BLPRO_PLUGIN_URL . 'admin/wbcom/assets/css/wbcom-admin-setting.css' );
			}

			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();
				if ( 'toplevel_page_wbcomplugins' === $screen->base ) {
					if ( !wp_script_is( 'jquery', 'enqueued' ) ) {
						wp_enqueue_script( 'jquery' );
					}
					if ( !wp_script_is( 'jquery-ui-sortable', 'enqueued' ) ) {
						wp_enqueue_script( 'jquery-ui-sortable' );
					}
					if ( !wp_style_is( 'wbcom-selectize-css', 'enqueued' ) ) {
						wp_enqueue_style( 'wbcom-selectize-css', BLPRO_PLUGIN_URL . 'admin/css/selectize.css' );
					}
					if ( !wp_script_is( 'wbcom-selectize-js', 'enqueued' ) ) {
						wp_enqueue_script( 'wbcom-selectize-js', BLPRO_PLUGIN_URL . 'admin/js/selectize.min.js', array( 'jquery' ) );
					}
					
					if ( !wp_script_is( 'wp-color-picker', 'enqueued' ) ) {
						wp_enqueue_style( 'wp-color-picker' );
					}
					if ( !wp_script_is( 'buddypress-private-community-pro', 'enqueued' ) ) {
						wp_enqueue_script( 'buddypress-private-community-pro', BLPRO_PLUGIN_URL . 'admin/js/buddypress-lock-pro-admin.js', array( 'jquery' ) );
					}
					if ( !wp_style_is( 'buddypress-private-community-pro', 'enqueued' ) ) {
						wp_enqueue_style( 'buddypress-private-community-pro', BLPRO_PLUGIN_URL . 'admin/css/buddypress-lock-pro-admin.css', array(), time(), 'all' );
					}
				}
			}
		}

		/**
		 * Function for add plugin's admin panel header pages.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_admin_additional_pages() {
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Components', 'buddypress-private-community-pro' ), esc_html__( 'Components', 'buddypress-private-community-pro' ), 'manage_options', 'wbcom-plugins-page', array( $this, 'wbcom_plugins_submenu_page_callback' )
			);
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Themes', 'buddypress-private-community-pro' ), esc_html__( 'Themes', 'buddypress-private-community-pro' ), 'manage_options', 'wbcom-themes-page', array( $this, 'wbcom_themes_submenu_page_callback' )
			);
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Support', 'buddypress-private-community-pro' ), esc_html__( 'Support', 'buddypress-private-community-pro' ), 'manage_options', 'wbcom-support-page', array( $this, 'wbcom_support_submenu_page_callback' )
			);
		}

		/**
		 * Function for include wbcom plugins list page.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_plugins_submenu_page_callback() {
			include 'templates/wbcom-plugins-page.php';
		}

		/**
		 * Function for include themes list page.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_themes_submenu_page_callback() {
			include 'templates/wbcom-themes-page.php';
		}

		/**
		 * Function for include support page.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_support_submenu_page_callback() {
			include 'templates/wbcom-support-page.php';
		}

		/**
		 * Shortcode for display top menu header.
		 *
		 * @since 1.1.0
		 * @access public
		 */
		public function wbcom_admin_setting_header_html() {
			$page			 = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'wbcom-themes-page';
			$plugin_active	 = $theme_active = $support_active = $settings_active = '';
			switch ( $page ) {
				case 'wbcom-plugins-page': $plugin_active	 = 'is_active';
					break;
				case 'wbcom-themes-page' : $theme_active	 = 'is_active';
					break;
				case 'wbcom-support-page' : $support_active	 = 'is_active';
					break;
				case 'wbcom-license-page' : $license_active	 = 'is_active';
					break;
				default : $settings_active = 'is_active';
			}
			?>
			<div id="wb_admin_header" class="wp-clearfix">

				<div id="wb_admin_logo">
					<img src="<?php echo BLPRO_PLUGIN_URL . 'admin/wbcom/assets/imgs/logowbcom.png'; ?>">
					<div class="wb_admin_right"></div>
				</div>

				<nav id="wb_admin_nav">
					<ul>
						<li class="wb_admin_nav_item <?php echo esc_attr( $settings_active ); ?>">
							<a href="<?php echo get_admin_url() . 'admin.php?page=wbcomplugins'; ?>" id="wb_admin_nav_trigger_settings">
								<i class="fa fa-sliders" aria-hidden="true"></i>
								<h4><?php esc_html_e( 'Settings', 'buddypress-private-community-pro' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $plugin_active ); ?>">
							<a href="<?php echo get_admin_url() . 'admin.php?page=wbcom-plugins-page'; ?>" id="wb_admin_nav_trigger_extensions">
								<i class="fa fa-th" aria-hidden="true"></i>
								<h4><?php esc_html_e( 'Components', 'buddypress-private-community-pro' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $theme_active ); ?>">
							<a href="<?php echo get_admin_url() . 'admin.php?page=wbcom-themes-page'; ?>" id="wb_admin_nav_trigger_themes">
								<i class="fa fa-magic" aria-hidden="true"></i>
								<h4><?php esc_html_e( 'Themes', 'buddypress-private-community-pro' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $support_active ); ?>">
							<a href="<?php echo get_admin_url() . 'admin.php?page=wbcom-support-page'; ?>" id="wb_admin_nav_trigger_support">
								<i class="fa fa-question-circle" aria-hidden="true"></i>
								<h4><?php esc_html_e( 'Support', 'buddypress-private-community-pro' ); ?></h4>
							</a>
						</li>
						<?php do_action( 'wbcom_add_header_menu' ); ?>
					</ul>
				</nav>
			</div><?php
		}

	}

	function instantiate_wbcom_plugin_manager() {
		new Wbcom_Admin_Settings();
	}

	instantiate_wbcom_plugin_manager();
}