<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Wbcom_Bp_Profile_Search_Addon' ) ) :

/**
 * Main Wbcom_Bp_Profile_Search_Addon Class.
 *
 * @class Wbcom_Bp_Profile_Search_Addon
 * @version 1.0.0
 */
class Wbcom_Bp_Profile_Search_Addon {

    /**
     * Wbcom_Bp_Profile_Search_Addon version.
     *
     * @var string
     */
    public $version = '1.0.0';
    
    /**
     * The single instance of the class.
     *
     * @var Wbcom_Bp_Profile_Search_Addon
     * @since 1.0.0
     */
    protected static $_instance = null;

    
    /**
     * Main Wbcom_Bp_Profile_Search_Addon Instance.
     *
     * Ensures only one instance of Wbcom_Bp_Profile_Search_Addon is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see INSTANTIATE_Wbcom_Bp_Profile_Search_Addon()
     * @return Wbcom_Bp_Profile_Search_Addon - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    
    /**
     * Wbcom_Bp_Profile_Search_Addon Constructor.
     */
    public function __construct() {
        $this->init_hooks();
        do_action( 'wbcom_geodirectory_addon_loaded' );
    }

    /**
     * Hook into actions and filters.
     * @since  1.0.0
     */
    private function init_hooks() {
        // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
        // add_action( 'wp_footer', array( $this, 'wbtm_bps_select2_script' ) );
        add_filter( 'bps_templates', array( $this, 'wbtm_bps_templates' ), 10, 1 );
        add_action( 'add_meta_boxes', array( $this, 'wbtm_bps_add_meta_boxes' ), 5 );
        add_action( 'save_post', array( $this, 'wbtm_bps_update_meta' ), 10, 2 );
    }

    public function wbtm_bps_select2_script() {
        ?>
        <script type="text/javascript">
            jQuery( document ).ready( function() {
                jQuery( '.wbtm-bps-form-wrapper select').select2({minimumResultsForSearch: -1});
            } );
        </script>
        <?php
    }

    public function wbtm_bps_templates( $templates ) {
        $templates[] = 'members/bps-form-wbcomdesigns';
        return $templates;
    }

    public function wbtm_bps_add_meta_boxes () {
        add_meta_box( 'wbtm_bps_form_layout', __( 'Form Layout', 'reign' ), array( $this, 'wbtm_bps_form_layout' ), 'bps_form', 'side' );
    }

    public function wbtm_bps_form_layout ($post) {
        $wbtm_bps_form_layout = get_post_meta( $post->ID, 'wbtm_bps_form_layout', true );
        $wbtm_bps_form_label = get_post_meta( $post->ID, 'wbtm_bps_form_label', true );
        $wbtm_bps_form_placeholder = get_post_meta( $post->ID, 'wbtm_bps_form_placeholder', true );
        $wbtm_bps_form_bg_color = get_post_meta( $post->ID, 'wbtm_bps_form_bg_color', true );
        $wbtm_bps_form_txt_color = get_post_meta( $post->ID, 'wbtm_bps_form_txt_color', true );
        ?>
        <p><strong><?php _e( 'Note: ', 'reign' ); ?></strong><?php _e( 'All these options will work for "members/bps-form-wbcomdesigns" template only.', 'reign' ); ?></p>
        <p>
            <label>
                <?php _e( 'Form Layout', 'reign' ); ?>
            </label>
            <select name="wbtm_bps_form_layout" id="wbtm_bps_form_layout">
                <option value='vertical' <?php selected ($wbtm_bps_form_layout, 'vertical'); ?>><?php _e('Vertical', 'reign'); ?></option>
                <option value='horizontal' <?php selected ($wbtm_bps_form_layout, 'horizontal'); ?>><?php _e('Horizontal', 'reign'); ?></option>
            </select>
        </p>
        <p>
            <label>
                <?php _e( 'Display Label', 'reign' ); ?>
            </label>
            <select name="wbtm_bps_form_label" id="wbtm_bps_form_label">
                <option value='show' <?php selected ($wbtm_bps_form_label, 'show'); ?>><?php _e('Show', 'reign'); ?></option>
                <option value='hide' <?php selected ($wbtm_bps_form_label, 'hide'); ?>><?php _e('Hide', 'reign'); ?></option>
            </select>
        </p>
        <p>
            <label>
                <?php _e( 'Display Placeholder', 'reign' ); ?>
            </label>
            <select name="wbtm_bps_form_placeholder" id="wbtm_bps_form_placeholder">
                <option value='hide' <?php selected ($wbtm_bps_form_placeholder, 'hide'); ?>><?php _e('Hide', 'reign'); ?></option>
                <option value='show' <?php selected ($wbtm_bps_form_placeholder, 'show'); ?>><?php _e('Show', 'reign'); ?></option>
            </select>
        </p>
        <p>
            <label>
                <?php _e( 'Background Color', 'reign' ); ?>
            </label>
            <input type="text" name="wbtm_bps_form_bg_color" id="wbtm_bps_form_bg_color" value="<?php echo $wbtm_bps_form_bg_color; ?>" />
        </p>
        <p>
            <label>
                <?php esc_html_e( 'Text Color', 'reign' ); ?>
            </label>
            <input type="text" name="wbtm_bps_form_txt_color" id="wbtm_bps_form_txt_color" value="<?php echo $wbtm_bps_form_txt_color; ?>" />
        </p>
        <?php
    }

    public function wbtm_bps_update_meta( $form, $post ) {
        if ($post->post_type != 'bps_form' || $post->post_status != 'publish')  return false;
        if( isset( $_POST['wbtm_bps_form_layout'] ) ) {
            update_post_meta( $post->ID, 'wbtm_bps_form_layout', sanitize_text_field($_POST['wbtm_bps_form_layout']) );
        }
        if( isset( $_POST['wbtm_bps_form_label'] ) ) {
            update_post_meta( $post->ID, 'wbtm_bps_form_label', sanitize_text_field($_POST['wbtm_bps_form_label']) );
        }
        if( isset( $_POST['wbtm_bps_form_placeholder'] ) ) {
            update_post_meta( $post->ID, 'wbtm_bps_form_placeholder', sanitize_text_field($_POST['wbtm_bps_form_placeholder']) );
        }
        if( isset( $_POST['wbtm_bps_form_bg_color'] ) ) {
            update_post_meta( $post->ID, 'wbtm_bps_form_bg_color', sanitize_text_field($_POST['wbtm_bps_form_bg_color']) );
        }
        if( isset( $_POST['wbtm_bps_form_txt_color'] ) ) {
            update_post_meta( $post->ID, 'wbtm_bps_form_txt_color', sanitize_text_field($_POST['wbtm_bps_form_txt_color']) );
        }
        return true;
    }

    // public function enqueue_style() {
    //     wp_register_style(
    //         $handle  = 'wbcom_geo_css',
    //         $src     = Wbcom_Bp_Profile_Search_Addon_PLUGIN_DIR_URL . 'wbcom-geo.css',
    //         $deps    = array(),
    //         $ver     = time(),
    //         $media = 'all'
    //     );
    //     wp_enqueue_style( 'wbcom_geo_css' );
    // }

    /**
     * Define constant if not already set.
     *
     * @param  string $name
     * @param  string|bool $value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

}

endif;

/**
 * Main instance of Wbcom_Postmeta_Management.
 * @return Wbcom_Postmeta_Management
 */
if ( defined( 'BPS_VERSION' ) ) {
	Wbcom_Bp_Profile_Search_Addon::instance();
}