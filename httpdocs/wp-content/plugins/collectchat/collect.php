<?php
/**
 * Plugin Name: Collect.chat — Beautiful Conversational Chatbot for Lead Generation and Data Collection
 * Version: 2.1.1
 * Plugin URI: https://collect.chat
 * Description: Collect data and leads from your visitors using our automated chatbot. Zero coding involved. Engage every single visitor on your site. 
 * Author: Collect.chat Inc.
 * License: GPLv2 or later
 */


// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

define('CC_PLUGIN_DIR',str_replace('\\','/',dirname(__FILE__)));

if ( !class_exists( 'ScriptLoader' ) ) {

	class ScriptLoader {

		function __construct() {

			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'wp_head', array( &$this, 'wp_head' ) );
			add_action( 'plugins_loaded', array( &$this, 'register_embed' ) );
			
			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", array( &$this, 'admin_settings_link_collectchat' ) );	
		}
	
		function register_embed() {
			//Register shortcode
			add_shortcode( 'collect-chat', array( &$this, 'embed_bot' ) );		
		}

		//[collect-chat]
		function embed_bot( $atts ) {
			if(isset($atts['id'])) {
				if(!isset($atts['height'])){
					$atts['height'] = "500";
				}
				return '<iframe src="https://links.collect.chat/'.$atts["id"].'" width="100%" height="'.$atts["height"].'" frameBorder="0" allowfullscreen></iframe>';
			} else {
				return 'Please enter a valid Collect.chat bot id';
			}
		}
		
		function init() {
			load_plugin_textdomain( 'collectchat-settings', false, dirname( plugin_basename ( __FILE__ ) ).'/lang' );		
			
			// Register oEmbed providers
			wp_oembed_add_provider( 'https://links.collect.chat/*', 'https://app.collect.chat/forms-embed' );
		}

		function admin_settings_link_collectchat( $links ) {
			$settings_link = '<a href="options-general.php?page=collectchat">' . __( 'Settings' ) . '</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		function admin_init() {

			// register settings for sitewide script
			register_setting( 'collectchat-settings-group', 'collectchat-plugin-settings' ); 

			add_settings_field( 'script', 'Script', 'trim','collectchat' );
			add_settings_field( 'showOn', 'Show On', 'trim','collectchat' );

			// default value for settings
			$initialSettings = get_option( 'collectchat-plugin-settings' );
			if ( $initialSettings === false || !$initialSettings['showOn'] ) { 
				$initialSettings['showOn'] = 'all';
   			 	update_option( 'collectchat-plugin-settings', $initialSettings );
			}

			// add meta box to all post types
			add_meta_box('cc_all_post_meta', esc_html__('Collect.chat Script:', 'collectchat-settings'), 'cc_meta_setup', array('post','page'), 'normal', 'default');

			add_action('save_post','cc_post_meta_save');
		}



		// adds menu item to wordpress admin dashboard
		function admin_menu() {
			$page = add_submenu_page( 'options-general.php', __('Collect.chat', 'collectchat-settings'), __('Collect.chat', 'collectchat-settings'), 'manage_options', 'collectchat', array( &$this, 'cc_options_panel' ) );
		}

		function wp_head() {

			$settings = get_option( 'collectchat-plugin-settings');


			if(is_array($settings) && array_key_exists('script', $settings)) {
				$script = $settings['script'];
				$showOn = $settings['showOn'];

				// main bot
				if ( $script != '' ) {
					if(($showOn === 'all') || ($showOn === 'home' && (is_home() || is_front_page())) || ($showOn === 'nothome' && !is_home() && !is_front_page()) || !$showOn === 'none') {
						echo $script, '<script type="text/javascript">var CollectChatWordpress = true;</script>', "\n";
					}
				}	
			}

			// post and page bots
			$cc_post_meta = get_post_meta( get_the_ID(), '_inpost_head_script' , TRUE );
			if ( $cc_post_meta != '' && !is_home() && !is_front_page()) {
				echo $cc_post_meta['synth_header_script'], '<script type="text/javascript">var CollectChatWordpress = true;</script>',"\n";
			}

		}

		function cc_options_panel() {
				// Load options page
				require_once(CC_PLUGIN_DIR . '/options.php');
		}
	}

	function cc_meta_setup() {
		global $post;

		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		$meta = get_post_meta($post->ID,'_inpost_head_script',TRUE);

		// instead of writing HTML here, lets do an include
		include_once(CC_PLUGIN_DIR . '/meta.php');

		// create a custom nonce for submit verification later
		echo '<input type="hidden" name="cc_post_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}

	function cc_post_meta_save($post_id) {
		// authentication checks

		// make sure data came from our meta box
		if ( ! isset( $_POST['cc_post_meta_noncename'] )
			|| !wp_verify_nonce($_POST['cc_post_meta_noncename'],__FILE__)) return $post_id;

		// check user permissions
		if ( $_POST['post_type'] == 'page' ) {

			if (!current_user_can('edit_page', $post_id)) 
				return $post_id;

		} else {

			if (!current_user_can('edit_post', $post_id)) 
				return $post_id;

		}

		$current_data = get_post_meta($post_id, '_inpost_head_script', TRUE);

		$new_data = $_POST['_inpost_head_script'];

		cc_post_meta_clean($new_data);

		if ($current_data) {

			if (is_null($new_data)) delete_post_meta($post_id,'_inpost_head_script');

			else update_post_meta($post_id,'_inpost_head_script',$new_data);

		} elseif (!is_null($new_data)) {

			add_post_meta($post_id,'_inpost_head_script',$new_data,TRUE);

		}

		return $post_id;
	}

	function cc_post_meta_clean(&$arr) {

		if (is_array($arr)) {

			foreach ($arr as $i => $v) {

				if (is_array($arr[$i])) {
					cc_post_meta_clean($arr[$i]);

					if (!count($arr[$i])) {
						unset($arr[$i]);
					}

				} else {

					if (trim($arr[$i]) == '') {
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr)) {
				$arr = NULL;
			}
		}
	}


	$scripts = new ScriptLoader();


}
?>