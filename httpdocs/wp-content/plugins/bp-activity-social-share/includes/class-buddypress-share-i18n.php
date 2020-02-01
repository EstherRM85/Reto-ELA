<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Share_i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function bp_share_load_plugin_textdomain() {

        $this->load_plugin_textdomain(
            'buddypress-share',
            false,
            dirname(dirname(plugin_basename(__FILE__)))  . '/languages'
        );

    }
	
	function load_plugin_textdomain( $domain, $deprecated = false, $plugin_rel_path = false ) {
		/**
		 * Filters a plugin's locale.
		 *
		 * @since 3.0.0
		 *
		 * @param string $locale The plugin's current locale.
		 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
		 */
		$locale = apply_filters( 'plugin_locale', is_admin() ? get_user_locale() : get_locale(), $domain );
		$mofile = $domain . '-' . $locale . '.mo';
		// Try to load from the languages directory first.
		if ( load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $mofile ) ) {
			return true;
		}
	 
		if ( false !== $plugin_rel_path ) {
			$path = WP_PLUGIN_DIR . '/' . trim( $plugin_rel_path, '/' );
		} elseif ( false !== $deprecated ) {
			_deprecated_argument( __FUNCTION__, '2.7.0' );
			$path = ABSPATH . trim( $deprecated, '/' );
		} else {
			$path = WP_PLUGIN_DIR;
		}
		return load_textdomain( $domain, $path . '/' . $mofile );
	}
}
