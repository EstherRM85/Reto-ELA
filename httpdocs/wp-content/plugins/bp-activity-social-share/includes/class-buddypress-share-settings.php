<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Share
 * @subpackage Buddypress_Share/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */

class Buddypress_Share_Options_Page {

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
     * @since   1.0.0
     * @access  public
     * @param   string $plugin_name The name of this plugin.
     * @param   string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * function for add plugin menu.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_plugin_menu() {
        if ( empty ( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
            add_menu_page( esc_html__( 'WB Plugins', 'buddypress-share' ), esc_html__( 'WB Plugins', 'buddypress-share' ), 'manage_options', 'wbcomplugins', array( $this, 'bp_share_plugin_options' ), 'dashicons-lightbulb', 59 );
            add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-share' ), esc_html__( 'General', 'buddypress-share' ), 'manage_options', 'wbcomplugins' );
        }
        add_submenu_page( 'wbcomplugins', esc_html__( 'BuddyPress Share', 'buddypress-share' ), esc_html__( 'BuddyPress Share', 'buddypress-share' ), 'manage_options', $this->plugin_name, array( $this, 'bp_share_plugin_options' ) );
    }

    /**
     * Intialize plugin admin settings.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_settings_init() {
        register_setting('bp_share_services_extra', 'bp_share_services_extra');
        add_settings_section(
                'bp_share_extra_options', esc_html__( 'Extra Options', 'buddypress-share' ), array( $this, 'bp_share_settings_section_callback' ), 'bp_share_services_extra'
        );
        add_settings_field(
                'bp_share_services_open', esc_html__('Open as popup window', 'buddypress-share' ), array( $this, 'bp_share_checkbox_open_services_render' ), 'bp_share_services_extra', 'bp_share_extra_options'
        );
        add_settings_field(
                'bp_share_avatar_open_graph', esc_html__('Use avatar as open graph image', 'buddypress-share' ), array( $this, 'bp_share_checkbox_avatar_opengraph_render' ), 'bp_share_services_extra', 'bp_share_extra_options'
        );
    }

    /**
     * Intialize setting to show share in popup or new page.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_checkbox_open_services_render() {
        $extra_options = get_site_option('bp_share_services_extra');
        ?>
        <input type='checkbox' name='bp_share_services_open' <?php if ( $extra_options['bp_share_services_open'] == 1 ) { echo 'checked="checked"'; } ?> value='1'>
        <?php
    }

    public function bp_share_checkbox_avatar_opengraph_render() {
    	$extra_options = get_site_option('bp_share_services_extra');
    	$avatar_enable = 0;
    	if ( isset( $extra_options['bp_share_avatar_open_graph'] ) ) {
    		if ( $extra_options['bp_share_avatar_open_graph'] == 1 ) {
    			$avatar_enable = 1;    			
    		}
    	}
        ?>
        <input type='checkbox' name='bp_share_avatar_open_graph' <?php if ( $avatar_enable == 1 ) { echo 'checked="checked"'; } ?> value='1'>
        <?php
    }

    /**
     * Intialize bp_share_settings_section_callback.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_settings_section_callback() {
        echo '<div class="bp_share_settings_section_callback_class">';
        esc_html_e( 'Default is set to open window in popup. If this option is disabled then services open in new tab instead popup.  ', 'buddypress-share' );
    }

    /**
     * Build the admin options page.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_plugin_options() {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'bpas_general_settings';
        // admin check
        if ( ! current_user_can('manage_options')) {
            wp_die( esc_html__('You do not have sufficient permissions to access this page.', 'buddypress-share' ));
        }
        ?>
            <div class="wrap">
                <div class="bpss-header">
                    <?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
                    <h1 class="wbcom-plugin-heading">
                        <?php esc_html_e( 'BuddyPress Activity Social Share Settings', 'buddypress-share' ); ?>
                    </h1>
                </div>
                <div class="wbcom-admin-settings-page">
                    <?php
                    settings_errors();
                    $this->bpas_plugin_settings_tabs( $tab );
                    settings_fields( $tab );
                    do_settings_sections( $tab );
                    ?>
                </div>
            </div>
    <?php }

   public function bpas_plugin_settings_tabs( $current ) {
        $bpas_tabs = array(
            'bpas_general_settings' => esc_html__( 'General Settings', 'buddypress-share' )
        );
        $tab_html =  '<div class="wbcom-tabs-section"><h2 class="nav-tab-wrapper">';
        foreach( $bpas_tabs as $bpas_tab => $bpas_name ) {
            $class = ($bpas_tab == $current) ? 'nav-tab-active' : '';
            $tab_html .=  '<a class="nav-tab '.$class.'" href="admin.php?page=buddypress-share&tab=' . $bpas_tab . '">' . $bpas_name . '</a>';
        }
        $tab_html .= '</h2></div>';
        echo $tab_html;
        $this->bpas_include_admin_setting_tabs( $current );
    }

    public function bpas_include_admin_setting_tabs( $bpas_tab ) {
        $bpas_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $bpas_tab;
        switch( $bpas_tab ) {
            case 'bpas_general_settings'    :   $this->bpas_general_setting_section();
                                                break;
            default                         :   $this->bpas_general_setting_section();
                                                break;
        }
    }

    public function bpas_general_setting_section() { ?>
    	<div class="wbcom-tab-content">
    		<form method="post" action="<?php echo admin_url('options.php'); ?>" id="bp_share_form"> 
                <?php wp_nonce_field('update-options'); ?>
                    <h3><?php esc_html_e( 'Add Social Services', 'buddypress-share' ); ?></h3>
                    <table cellspacing="0" class="add_share_services widefat fixed plugins">
                        <thead>
                            <tr>
                                <th class="manage-column column-cb check-column" id="cb" scope="col">&nbsp;</th>
                                <th class="manage-column column-name" id="name" scope="col" style="width: 190px;"><?php esc_html_e( 'Component', 'buddypress-share' ); ?></th>
                                <th class="manage-column column-select_services" id="select_services" scope="col"><?php esc_html_e( 'Select Service', 'buddypress-share' ); ?></th>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <tr>
                                <th scope="row"></th>
                                <td class="plugin-title" style="width: 190px;">
                                    <strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Social Sites', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
                                <td class="column-description desc">
                                    <div class="plugin-description">
                                        <select name="social_services_selector" id="social_services_selector_id" class="social_services_selector">
                                            <option value="">-<?php esc_html_e( 'select', 'buddypress-share' ); ?>-</option>
                                            <option value="bp_share_facebook"><?php esc_html_e( 'Facebook', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_twitter"><?php esc_html_e( 'Twitter', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_pinterest"><?php esc_html_e( 'Pinterest', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_linkedin"><?php esc_html_e( 'Linkedin', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_reddit"><?php esc_html_e( 'Reddit', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_wordpress"><?php esc_html_e( 'WordPress', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_pocket"><?php esc_html_e( 'Pocket', 'buddypress-share' ); ?></option>
                                            <option value="bp_share_email"><?php esc_html_e( 'Email', 'buddypress-share' ); ?></option>
                                        </select>
                                    </div>
                                    <p class="error_service error_service_selector"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td class="plugin-title" style="width: 190px;">
                                    <strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Font Awesome Icon Class', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
                                <td class="column-faw-icon desc">
                                    <div class="plugin-faw-icon">
                                        <input class="faw_class_input" name="faw_class_input" type="text">
                                        <p class="error_service error_service_faw-icon"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td class="plugin-title" style="width: 190px;">
                                    <strong style="margin-top: 3px; float: left;"><?php esc_html_e( 'Description', 'buddypress-share' ); ?></strong><span class="bp_share_req">*</span></td>
                                <td class="column-description desc">
                                    <div class="plugin-description">
                                        <textarea name="bp_share_description" class="bp_share_description"></textarea>
                                        <p class="error_service error_service_description"><?php esc_html_e( 'This field is required!', 'buddypress-share' ); ?></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td class="plugin-title" style="width: 190px;">
                                </td>
                                <td class="add_services_btn_td">
                                    <input type="button" class="add_services_btn" name="add_services_btn" value="<?php esc_html_e( 'Add Services', 'buddypress-share' ); ?>">
                                    <p class="spint_action"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></p>
                                </td>
                            </tr>
                        </tbody>
                    </table><!--END: add_share_services table-->
                    <br/>
                    <table cellspacing="0" class="widefat fixed plugins">
                        <thead>
                            <tr>
                                <th class="manage-column column-cb check-column" id="cb"
                                    scope="col">&nbsp;</th>
                                <th class="manage-column column-name" id="name" scope="col"
                                    style="width: 190px;"><?php esc_html_e( 'Social Sites', 'buddypress-share' ); ?></th>
                                <th class="manage-column column-description" id=
                                    "description" scope="col"><?php esc_html_e( 'Description', 'buddypress-share' ); ?></th>
                                <th class="manage-column column-services-action" id="services-action" scope="col"><?php esc_html_e( 'Action', 'buddypress-share' ); ?></th>
                            </tr>
                        </thead>
                        <tbody id="the-list" class="bp_share_social_list">
                            <?php
                            $social_options = get_site_option('bp_share_services');
                            if ( ! empty($social_options)) {
                                foreach ($social_options as $service_key => $social_option) {
                                    ?>
                                    <tr class="bp-share-services-row" id="<?php echo 'tr_' . $service_key; ?>">
                                        <th scope="row" id="bp_share_chb" class="bp-share-td">
                                            <input type="checkbox" name="<?php echo 'chb_' . $service_key; ?>" value="1" <?php
                                            if ($social_options[$service_key]['chb_' . $service_key] == 1) {
                                                echo 'checked="checked"';
                                            }
                                            ?>/>
                                        </th>
                                        <td class="bp-share-title bp-share-td" style="width: 190px;">
                                            <strong style="margin-top: 3px;"><i class="<?php echo $social_option['service_icon']; ?> fa-lg"></i> <?php echo $social_option['service_name']; ?></strong>
                                            <div class="row-actions-visible"></div>
                                        </td>
                                        <td class="bp-share-column-description desc bp-share-td">
                                            <div class="plugin-description">
                                                <p><?php echo $social_option['service_description']; ?></p>
                                            </div>
                                            <div class="active second plugin-version-author-uri">
                                            </div>
                                        </td>
                                        <td class="service_delete bp-share-td"><p class="service_delete_icon" data-bind="<?php echo $service_key; ?>"><i class="fa fa-window-close"></i></p></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table><!--END:social options table-->
                    <div class="bp-share-services-extra">
                        <?php
                        do_settings_sections('bp_share_services_extra');
                        echo '</div>';
                        ?>
                    </div>
                <!--save the settings-->
                <input type="hidden" name="action" value="update" />
                <?php
                $social_options = get_site_option('bp_share_services');
                if ( ! empty($social_options)) {
                    $social_key_string = "";
                    foreach ($social_options as $service_key => $social_option) {
                        if (count($social_options) != 1) {
                            $social_key_string .= $service_key . ',';
                        } else {
                            $social_key_string = $service_key;
                        }
                    }
                    if (count($social_options) != 1) {
                        $social_key_string = rtrim($social_key_string, ', ');
                    }
                    ?>
                    <input type="hidden" name="page_options" value="<?php echo $social_key_string; ?>" />
                    <?php
                }
                ?>
                <p class="submit">
                    <input type="submit" class="button-primary bp_share_option_save" value="<?php esc_html_e('Save Changes', 'buddypress-share' ) ?>" />
                </p>
            </form>
            <?php  do_action('bp_share_add_services_options', $arg1 = '', $arg2 = ''); ?>
        </div>
    <?php }

    /**
     * Display already inserted services.
     *
     * @access public
     * @author 	Wbcom Designs
     * @since    1.0.0
     */
    public function bp_share_insert_services_ajax() {
        $service_name = sanitize_text_field( $_POST['service_name'] );
        $service_faw = sanitize_text_field( $_POST['service_faw'] );
        $service_key = $service_value = sanitize_text_field( $_POST['service_value'] );
        $service_description = sanitize_text_field( $_POST['service_description'] );

        $option_name = 'bp_share_services';
        $html_view_arr = array();
        $html_view = "";
        $html_view_arr['status'] = false;
        if( ! empty( $_POST ) && check_admin_referer( 'bp_share_nonce', 'nonce' ) ) {
            if (get_site_option($option_name) !== false) {
                $services = get_site_option($option_name);
                if (empty($services)) {
                    $new_service = array(
                        "$service_value" => array(
                        "chb_$service_value" => 1,
                        "service_name" => "$service_name",
                        "service_icon" => "$service_faw",
                        "service_description" => "$service_description"
                    ));
                    update_site_option($option_name, $new_service);
                    $html_view .= '<tr id = "tr_' . $service_key . '" class="bp-share-services-row">';
                    $html_view .= '<th scope="row" id="bp_share_chb" class="bp-share-td">';
                    $html_view .= '<input type="checkbox" name="chb_' . $service_key . '" value="1" checked="checked"/>';
                    $html_view .= '</th>';
                    $html_view .= '<td class="bp-share-title bp-share-td" style="width: 190px;">';
                    $html_view .= '<strong style="margin-top: 3px;"><i class="' . $service_faw . ' fa-lg"></i> ' . $service_name . '</strong>';
                    $html_view .= '<div class="row-actions-visible"></div>';
                    $html_view .= '</td>';
                    $html_view .= '<td class="bp-share-column-description desc bp-share-td">';
                    $html_view .= '<div class="plugin-description">';
                    $html_view .= '<p>' . $service_description . '</p>';
                    $html_view .= '</div>';
                    $html_view .= '<div class="active second plugin-version-author-uri">';
                    $html_view .= '</div>';
                    $html_view .= '</td>';
                    $html_view .= '<td class="service_delete bp-share-td"><p class="service_delete_icon" data-bind="' . $service_key . '"><i class="fa fa-window-close"></i></p></td>';
                    $html_view .= '</tr>';
                } else {
                    $new_value = array(
                        "chb_$service_value" => 1,
                        "service_name" => "$service_name",
                        "service_icon" => "$service_faw",
                        "service_description" => "$service_description"
                    );

                    foreach ($services as $s_key => $s_value) {
                        if ($s_key == $service_value) {
                            $html_view_arr['status'] = true;
                        }
                    }
                    foreach ($services as $key => $value) {
                        $services[$service_value] = $new_value;
                    }
                    update_site_option($option_name, $services);
                    $html_view .= '<tr id = "tr_' . $service_key . '" class="bp-share-services-row">';
                    $html_view .= '<th scope="row" id="bp_share_chb" class="bp-share-td">';
                    $html_view .= '<input type="checkbox" name="chb_' . $service_key . '" value="1" checked="checked"/>';
                    $html_view .= '</th>';
                    $html_view .= '<td class="bp-share-title bp-share-td" style="width: 190px;">';
                    $html_view .= '<strong style="margin-top: 3px;"><i class="' . $service_faw . ' fa-lg"></i> ' . $service_name . '</strong>';
                    $html_view .= '<div class="row-actions-visible"></div>';
                    $html_view .= '</td>';
                    $html_view .= '<td class="bp-share-column-description desc bp-share-td">';
                    $html_view .= '<div class="plugin-description">';
                    $html_view .= '<p>' . $service_description . '</p>';
                    $html_view .= '</div>';
                    $html_view .= '<div class="active second plugin-version-author-uri">';
                    $html_view .= '</div>';
                    $html_view .= '</td>';
                    $html_view .= '<td class="service_delete bp-share-td"><p class="service_delete_icon" data-bind="' . $service_key . '"><i class="fa fa-window-close"></i></p></td>';
                    $html_view .= '</tr>';
                }
            } else {
                $new_service = array(
                    "$service_value" => array(
                        "chb_$service_value" => 1,
                        "service_name" => "$service_name",
                        "service_icon" => "$service_faw",
                        "service_description" => "$service_description")
                );
            // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                $deprecated = null;
                $autoload = 'no';
                update_site_option( $option_name, $new_service, $deprecated, $autoload );
            }
            $html_view_arr['view'] = $html_view;
            echo json_encode($html_view_arr);
        }
        die();
    }

   /**
    * Ajax Call when delete any inserted services.
    *
    * @access public
    * @author 	Wbcom Designs
    * @since    1.0.0
    */
    public function bp_share_delete_services_ajax() {
        if( ! empty( $_POST ) && check_admin_referer( 'bp_share_nonce', 'nonce' ) ) {
            $option_name = 'bp_share_services';
            $service_name = $_POST['service_name'];
            $services = get_site_option($option_name);
            if ( ! empty($services)) {
                foreach ($services as $service_key => $value) {
                    if ($service_key == $service_name) {
                        unset($services[$service_key]);
                        update_site_option($option_name, $services);
                        echo $service_key;
                    }
                }
            }
        }
        die();
    }

   /**
    * bp_share_delete_user_services_ajax.
    *
    * @access public
    * @author 	Wbcom Designs
    * @since    1.0.0
    */
    public function bp_share_delete_user_services_ajax() {
        $option_name = 'bp_share_services';
        $service_array = $_POST['service_array'];
        $services = get_site_option($option_name);
        if ( ! empty($service_array)) {
            foreach ($service_array as $service_array_key => $service_array_value) {
                foreach ($services as $service_key => $value) {
                    if ($service_key == $service_array_value) {
                        unset($services[$service_key]);
                        update_site_option($option_name, $services);
                    }
                }
            }
        }
        die();
    }

   /**
    * bp_share_chb_services_ajax.
    *
    * @access public
    * @author 	Wbcom Designs
    * @since    1.0.0
    */
    public function bp_share_chb_services_ajax() {
        if( ! empty( $_POST ) && check_admin_referer( 'bp_share_nonce', 'nonce' ) ) {
            $option_name = 'bp_share_services';
            $active_services = isset( $_POST['active_chb_array'] )? wp_unslash( $_POST['active_chb_array'] ) :array();
            $extras_options = isset( $_POST['active_chb_extras'] )? wp_unslash( $_POST['active_chb_extras'] ) :array();
            $extra_option_new = array();
            if ( ! empty( $extras_options ) ) {
                if ( in_array( 'bp_share_services_open', $extras_options ) ) {
                    $extra_option_new['bp_share_services_open'] = 1;
                }
                if ( in_array( 'bp_share_avatar_open_graph', $extras_options ) ) {
                    $extra_option_new['bp_share_avatar_open_graph'] = 1;
                }
            } else {
                $extra_option_new['bp_share_services_open'] = 0;
                $extra_option_new['bp_share_avatar_open_graph'] = 0;
            }
            update_site_option( 'bp_share_services_extra', $extra_option_new );
            $services = get_site_option('bp_share_services');
            if ( ! empty($services)) {
                if ( ! empty($active_services)) {
                    foreach ($services as $service_key => $value) {
                        if (in_array('chb_' . $service_key, $active_services)) {
                            $services[$service_key]['chb_' . $service_key] = 1;
                            update_site_option($option_name, $services);
                        } else {
                            $services[$service_key]['chb_' . $service_key] = 0;
                            update_site_option($option_name, $services);
                        }
                    }
                } else {
                    foreach ($services as $service_key => $value) {
                        $services[$service_key]['chb_' . $service_key] = 0;
                        update_site_option($option_name, $services);
                    }
                }
            }
        }
        die();
    }

   /**
    * bp_share_add_options.
    *
    * @access public
    * @author 	Wbcom Designs
    * @since    1.0.0
    */
    public function bp_share_add_options($activity_url, $activity_title) {
        $services = apply_filters('bp_share_add_services', $services = array(), $activity_url = '', $activity_title = '');
        if ( ! empty($services)) {
            $options_key = array();
            foreach ($services as $key => $value) {
                $options_key['bp_share_' . strtolower($key)] = $key;
            }
        }
        if (isset($options_key) && $options_key != '') {
            ?>
            <script>
                var customOptions = '<?php echo json_encode($options_key); ?>';
                var optionObj = jQuery.parseJSON(customOptions);
                var select = document.getElementById("social_services_selector_id");
                for (index in optionObj) {
                    select.options[select.options.length] = new Option(optionObj[index], index);
                }
            </script>
            <?php
        } else {
            $services = get_site_option('bp_share_services');
            if ( ! empty($services)) {
                $services_options_key = array();
                foreach ($services as $key => $value) {
                    $services_options_key[] = $key;
                }
            }
            if ( ! empty($services_options_key)) {
                ?>
                <script>
                    var selected = [];
                    jQuery("#social_services_selector_id option").each(function ()
                    {
                        if (jQuery(this).val() != '') {
                            selected.push(jQuery(this).val());
                        }
                    });
                    var all_options = '<?php echo json_encode($services_options_key); ?>';
                    var all_options = jQuery.parseJSON(all_options);
                    var difference = [];

                    jQuery.grep(all_options, function (el) {
                        if (jQuery.inArray(el, selected) == -1)
                            difference.push(el);
                    });
                    if (difference.length != 0) {
                        for (option in difference) {
                            jQuery('#tr_' + difference[option]).remove();
                        }
                        var data = {
                            'action': 'bp_share_delete_user_services_ajax',
                            'service_array': difference,
                        };
                        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                        jQuery.post(my_ajax_object.ajax_url, data, function (response) {
                            //                            console.log(response);
                        });
                    }
                </script>
                <?php
            }
        }
    }

   /**
    * bp_share_user_added_services.
    *
    * @access public
    * @author 	Wbcom Designs
    * @since    1.0.0
    */
    public function bp_share_user_added_services($services, $activity_url, $activity_title) {
        $user_services = apply_filters('bp_share_add_services', $services, $activity_url, $activity_title);
        $service = get_site_option('bp_share_services');
        if ( ! empty($user_services)) {
            $options_values = array();
            foreach ($user_services as $key => $value) {
                $options_values['bp_share_' . strtolower($key)] = $value;
            }
            if ( ! empty($service)) {
                foreach ($options_values as $options_key => $options_value) {
                    foreach ($service as $key => $value) {
                        if (isset($key) && $key == $options_key && $value['chb_' . $key] == 1) {
                            echo '<a target="blank" class="bp-share" href="' . $options_value . '" rel="' . $options_key . '"><span class="fa-stack fa-lg"><i class="' . $value['service_icon'] . '"></i></span></a>';
                        }
                    }
                }
            }
        }
    }

// END:build the admin options page
}