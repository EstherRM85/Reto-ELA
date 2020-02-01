<?php

function shiftnav_settings_pro_links() {
	echo '<a target="_blank" class="button" href="' . shiftnav_get_support_url() . '"><i class="fa fa-user-md"></i> Support</a>';
}

if ( !SHIFTNAV_EXTENDED )
	add_action( 'shiftnav_settings_before_title', 'shiftnav_settings_pro_links' );


/**
 * CREATE INSTANCE MANAGER
 */
add_action( 'shiftnav_settings_before', 'shiftnav_instance_manager' );

function shiftnav_instance_manager() {
	//update_option( 'shiftnav_menus' , array() );
	//$m = get_option( 'shiftnav_menus' );
	//shiftp( $m );
	?>

	<div class="shiftnav_instance_manager">

		<a class="shiftnav_instance_toggle shiftnav_instance_button">+ Add ShiftNav Instance</a>

		<div class="shiftnav_instance_wrap shiftnav_instance_container_wrap">

			<div class="shiftnav_instance_container">

				<h3>Add ShiftNav Instance</h3>

				<form class="shiftnav_instance_form">
					<input class="shiftnav_instance_input" type="text" name="shiftnav_instance_id" placeholder="menu_id" />
					<?php wp_nonce_field( 'shiftnav-add-instance' ); ?>
					<a class="shiftnav_instance_button shiftnav_instance_create_button">Create Instance</a>
				</form>

				<p class="shiftnav_instance_form_desc">Enter an ID for your new menu.  This ID will be used when printing the menu,
					and must contain only letters, hyphens, and underscores.  <a class="shiftnav_instance_notice_close" href="#">close</a></p>

				<span class="shiftnav_instance_close">&times;</span>

			</div>

		</div>


		<div class="shiftnav_instance_wrap shiftnav_instance_notice_wrap shiftnav_instance_notice_success">
			<div class="shiftnav_instance_notice">
				New menu created. <a href="<?php echo admin_url( 'themes.php?page=shiftnav-settings' ); ?>" class="shiftnav_instance_button">Refresh Page</a>
				<p>Note: Any setting changes you've made have not been saved yet.  <a class="shiftnav_instance_notice_close" href="#">close</a></p>
			</div>
		</div>

		<div class="shiftnav_instance_wrap shiftnav_instance_notice_wrap shiftnav_instance_notice_error">
			<div class="shiftnav_instance_notice">
				New menu creation failed.  <span class="shiftnav-error-message">You may have a PHP error on your site which prevents AJAX requests from completing.</span>  <a class="shiftnav_instance_notice_close" href="#">close</a>
			</div>
		</div>

		<div class="shiftnav_instance_wrap shiftnav_instance_notice_wrap shiftnav_instance_delete_notice_success">
			<div class="shiftnav_instance_notice">
				Instance Deleted.  <a class="shiftnav_instance_notice_close" href="#">close</a></p>
			</div>
		</div>

		<div class="shiftnav_instance_wrap shiftnav_instance_notice_wrap shiftnav_instance_delete_notice_error">
			<div class="shiftnav_instance_notice">
				Menu deletion failed.  <span class="shiftnav-delete-error-message">You may have a PHP error on your site which prevents AJAX requests from completing.</span>  <a class="shiftnav_instance_notice_close" href="#">close</a>
			</div>
		</div>


	</div>

	<?php
}

function shiftnav_add_instance_callback() {

	check_ajax_referer( 'shiftnav-add-instance', 'shiftnav_nonce' );

	$response = array();

	$serialized_settings = $_POST[ 'shiftnav_data' ];
	$dirty_settings		 = array();
	parse_str( $serialized_settings, $dirty_settings );

	//ONLY ALLOW SETTINGS WE'VE DEFINED
	$data = wp_parse_args( $dirty_settings, array( 'shiftnav_instance_id' ) );

	$new_id = $data[ 'shiftnav_instance_id' ];

	if ( $new_id == '' ) {
		$response[ 'error' ] = 'Please enter an ID. ';
	} else {
		$new_id = sanitize_title( $new_id );

		//update
		$menus = get_option( 'shiftnav_menus', array() );

		if ( in_array( $new_id, $menus ) ) {
			$response[ 'error' ] = 'That ID is already taken. ';
		} else {
			$menus[] = $new_id;
			update_option( 'shiftnav_menus', $menus );
		}

		$response[ 'id' ] = $new_id;
	}

	$response[ 'data' ] = $data;

	echo json_encode( $response );

	die();
}

add_action( 'wp_ajax_shiftnav_add_instance', 'shiftnav_add_instance_callback' );

add_action( 'init', 'reign_shiftnav_add_second_instance' );

function reign_shiftnav_add_second_instance() {

	$menus = get_option( 'shiftnav_menus', array() );

	$new_id = 'shiftnav-second';

	if ( !in_array( $new_id, $menus ) ) {
		$menus[] = $new_id;
		update_option( 'shiftnav_menus', $menus );
	}

	/* =======================================================================
	  =            To update toggle breakpoint if not set or blank            =
	  ======================================================================= */
	$section			 = 'togglebar';
	$option				 = 'breakpoint';
	$update_breakpoint	 = false;
	$options			 = get_option( SHIFTNAV_PREFIX . $section );
	if ( isset( $options[ $option ] ) && empty( $options[ $option ] ) ) {
		$update_breakpoint = true;
	}
	if ( !isset( $options[ $option ] ) || $update_breakpoint ) {
		$options[ $option ] = '960';
	}

	$update_htmenu = false;
	if ( isset( $options[ 'hide_theme_menu' ] ) && empty( $options[ 'hide_theme_menu' ] ) ) {
		$update_htmenu = true;
	}
	if ( !isset( $options[ 'hide_theme_menu' ] ) || $update_htmenu ) {
		$options[ 'hide_theme_menu' ] = '#masthead';
	}
	update_option( SHIFTNAV_PREFIX . $section, $options );

	/* ==============================================================
	  =            Enable Elementor Header in mobile view            =
	  ============================================================== */

	$reign_header_header_type	 = get_theme_mod( 'reign_header_header_type', false );
	$prev_assigned				 = (isset( $options[ 'hide_theme_menu' ] )) ? $options[ 'hide_theme_menu' ] : '#masthead';
	if ( $reign_header_header_type ) {
		$reign_elementor_header_mobile = get_theme_mod( 'reign_elementor_header_mobile', false );
		if ( $reign_elementor_header_mobile ) {


			if ( isset( $options[ 'hide_theme_menu' ] ) ) {
				$options[ 'hide_theme_menu' ] = '';
			} else {
				$options[ 'hide_theme_menu' ] = $prev_assigned;
			}
			$options[ 'display_toggle' ] = 'off';
			update_option( SHIFTNAV_PREFIX . $section, $options );
		}
	} else {
		$options[ 'hide_theme_menu' ]	 = $prev_assigned;
		$options[ 'display_toggle' ]	 = '1';
		update_option( SHIFTNAV_PREFIX . $section, $options );
	}

	/* =====  End of Enable Elementor Header in mobile view  ====== */


	/* start - update first toggle panel default skin. */
	$first_options	 = get_option( SHIFTNAV_PREFIX . 'shiftnav-main' );
	$update_fskin	 = false;
	if ( isset( $first_options[ 'skin' ] ) && empty( $first_options[ 'skin' ] ) ) {
		$update_fskin = true;
	}
	if ( !isset( $first_options[ 'skin' ] ) || $update_fskin ) {
		$first_options[ 'skin' ] = 'standard-dark';
	}
	update_option( SHIFTNAV_PREFIX . 'shiftnav-main', $first_options );
	/* end - update first toggle panel default skin. */

	/* start - update second toggle panel default skin. */
	$sec_options	 = get_option( SHIFTNAV_PREFIX . 'shiftnav-second' );
	$update_sskin	 = false;
	if ( isset( $sec_options[ 'skin' ] ) && empty( $sec_options[ 'skin' ] ) ) {
		$update_sskin = true;
	}
	if ( !isset( $first_options[ 'skin' ] ) || $update_sskin ) {
		$sec_options[ 'skin' ] = 'standard-dark';
	}
	update_option( SHIFTNAV_PREFIX . 'shiftnav-second', $sec_options );
	/* end - update second toggle panel default skin. */
}

function shiftnav_delete_instance_callback() {

	check_ajax_referer( 'shiftnav-delete-instance', 'shiftnav_nonce' );

	$response = array();
//echo json_encode( $_POST['shiftnav_data'] );
//die();
	//$serialized_settings = $_POST['shiftnav_data'];
	//$dirty_settings = array();
	//parse_str( $serialized_settings, $dirty_settings );

	$dirty_settings = $_POST[ 'shiftnav_data' ];

	//ONLY ALLOW SETTINGS WE'VE DEFINED
	$data = wp_parse_args( $dirty_settings, array( 'shiftnav_instance_id' ) );

	$id = $data[ 'shiftnav_instance_id' ];

	if ( $id == '' ) {
		$response[ 'error' ] = 'Missing ID';
	} else {

		$menus = get_option( 'shiftnav_menus', array() );

		if ( !in_array( $id, $menus ) ) {
			$response[ 'error' ] = 'ID not in $menus [' . $id . ']';
		} else {
			//unset( $menus[$id] );
			$i = array_search( $id, $menus );
			if ( $i !== false )
				unset( $menus[ $i ] );

			update_option( 'shiftnav_menus', $menus );
			$response[ 'menus' ] = $menus;
		}

		$response[ 'id' ] = $id;
	}

	$response[ 'data' ] = $data;

	echo json_encode( $response );

	die();
}

add_action( 'wp_ajax_shiftnav_delete_instance', 'shiftnav_delete_instance_callback' );


/**
 * CREATE PRO SETTINGS
 */
add_filter( 'shiftnav_settings_panel_sections', 'shiftnav_settings_panel_sections_pro' );
add_filter( 'shiftnav_settings_panel_fields', 'shiftnav_settings_panel_fields_pro' );

function shiftnav_settings_panel_sections_pro( $sections = array() ) {
	//$menus = get_option( 'shiftnav_menus' , array() );
	$menus = shiftnav_get_menu_configurations();

	foreach ( $menus as $menu ) {
		$sections[] = array(
			'id'	 => SHIFTNAV_PREFIX . $menu,
			'title'	 => '+' . $menu,
		);
	}

	return $sections;
}

function shiftnav_settings_panel_fields_pro( $fields = array() ) {


	/** ADD MAIN NAV PRO OPTIONS * */
	$main = SHIFTNAV_PREFIX . 'shiftnav-main';

	$fields[ $main ][ 1010 ] = array(
		'name'				 => 'submenu_type_default',
		'label'				 => __( 'Submenu Type Default', 'reign' ),
		'desc'				 => __( 'This submenu type will be used by any Menu Item whose Submenu Type is set to "Menu Default"', 'reign' ),
		'type'				 => 'radio',
		'options'			 => array(
			'always'	 => __( 'Always visible', 'reign' ),
			'accordion'	 => __( 'Accordion', 'reign' ),
			'shift'		 => __( 'Shift', 'reign' ),
		),
		'default'			 => 'always',
		'customizer'		 => true,
		'customizer_section' => 'config',
	);

	$accordion_toggle_icon_open_icons	 = array(
		'chevron-down',
		'chevron-circle-down',
		'angle-down',
		'angle-double-down',
		'arrow-circle-down',
		'arrow-down',
		'caret-down',
		'toggle-down',
		'plus',
		'plus-circle',
		'plus-square',
		'plus-square-o',
	);
	$accordion_toggle_icon_open_ops		 = array();
	foreach ( $accordion_toggle_icon_open_icons as $i ) {
		$accordion_toggle_icon_open_ops[ $i ] = '<i class="fa fa-' . $i . '"></i>';
	}

	$fields[ $main ][ 1012 ] = array(
		'name'				 => 'accordion_toggle_icon_open',
		'label'				 => __( 'Accordion Toggle Open Icon', 'reign' ),
		'desc'				 => __( 'The icon that, when tapped, will open the accordion submenu', 'reign' ),
		'type'				 => 'radio',
		'options'			 => $accordion_toggle_icon_open_ops,
		'default'			 => 'chevron-down',
		'customizer'		 => true,
		'customizer_section' => 'config',
		'customizer_control' => 'radio_html'
	);

	$accordion_toggle_icon_close_icons	 = array(
		'chevron-up',
		'chevron-circle-up',
		'angle-up',
		'angle-double-up',
		'arrow-circle-up',
		'arrow-up',
		'caret-up',
		'toggle-up',
		'minus',
		'minus-circle',
		'minus-square',
		'minus-square-o',
		'times',
		'times-circle',
		'times-circle-o',
	);
	$accordion_toggle_icon_close_ops	 = array();
	foreach ( $accordion_toggle_icon_close_icons as $i ) {
		$accordion_toggle_icon_close_ops[ $i ] = '<i class="fa fa-' . $i . '"></i>';
	}

	$fields[ $main ][ 1013 ] = array(
		'name'				 => 'accordion_toggle_icon_close',
		'label'				 => __( 'Accordion Toggle Close Icon', 'reign' ),
		'desc'				 => __( 'The icon that, when tapped, will close the accordion submenu', 'reign' ),
		'type'				 => 'radio',
		'options'			 => $accordion_toggle_icon_close_ops,
		'default'			 => 'chevron-up',
		'customizer'		 => true,
		'customizer_section' => 'config',
		'customizer_control' => 'radio_html'
	);



	$fields[ $main ][ 1020 ] = array(
		'name'				 => 'disable_menu',
		'label'				 => __( 'Disable Menu', 'reign' ),
		'desc'				 => __( 'Check this to disable the menu entirely; the panel will still be displayed and can be used for custom content', 'reign' ),
		'type'				 => 'checkbox',
		'default'			 => 'off',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);

	$fields[ $main ][ 1030 ] = array(
		'name'				 => 'image',
		'label'				 => __( 'Top Image', 'reign' ),
		'desc'				 => __( '', 'reign' ),
		'type'				 => 'image',
		'default'			 => '',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);

	$fields[ $main ][ 1040 ] = array(
		'name'				 => 'image_padded',
		'label'				 => __( 'Pad Image', 'reign' ),
		'desc'				 => __( 'Add padding to align image with menu item text.  Uncheck to expand to the edges of the panel.', 'reign' ),
		'type'				 => 'checkbox',
		'default'			 => 'on',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);

	$fields[ $main ][ 1050 ] = array(
		'name'				 => 'image_link',
		'label'				 => __( 'Image Link (URL)', 'reign' ),
		'desc'				 => __( 'Make the image a link to this URL.', 'reign' ),
		'type'				 => 'text',
		'default'			 => '',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);

	$fields[ $main ][ 1060 ] = array(
		'name'				 => 'content_before',
		'label'				 => __( 'Custom Content Before Menu', 'reign' ),
		'desc'				 => __( 'Can contain shortcodes and HTML, including <img> tags.  For eg: [reign-search] will print the search before menu.', 'reign' ),
		'type'				 => 'textarea',
		'default'			 => '',
		'sanitize_callback'	 => 'shiftnav_allow_html',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);

	$fields[ $main ][ 1070 ] = array(
		'name'				 => 'content_after',
		'label'				 => __( 'Custom Content After Menu', 'reign' ),
		'desc'				 => __( 'Can contain shortcodes and HTML, including <img> tags.  For eg: [reign-search] will print the search after menu.', 'reign' ),
		'type'				 => 'textarea',
		'default'			 => '',
		'sanitize_callback'	 => 'shiftnav_allow_html',
		'customizer'		 => true,
		'customizer_section' => 'content',
	);




	/** ADD MAIN TOGGLE OPTIONS * */
	$toggle = SHIFTNAV_PREFIX . 'togglebar';

	//$fields[$toggle][1010] = array(
	$fields[ $toggle ][ 55 ] = array(
		'name'				 => 'toggle_content_left',
		'label'				 => __( 'Toggle Content Left Edge', 'reign' ),
		//'desc'	=> __( 'For the Full Bar toggle style, this content will appear at the left edge of the toggle bar, to the right of the toggle icon.  To pad your custom content vertically, use the class <code>shiftnav-toggle-main-block</code>.' , 'reign' ),
		'desc'				 => __( 'This content will appear at the left edge of the toggle bar, to the right of the toggle icon.', 'reign' ),
		'type'				 => 'textarea',
		'default'			 => '', //get_bloginfo( 'title' )
		'sanitize_callback'	 => 'shiftnav_allow_html',
	// 'customizer'	=> true,
	// 'customizer_section'	=> 'config'
	);

	//$fields[$toggle][1020] = array(

	/* Comment below code to change textarea into select option. */
	// $fields[$toggle][56] = array(
	// 	'name'	=> 'toggle_content_right',
	// 	'label'	=> __( 'Toggle Content Right Edge' , 'reign' ),
	// 	'desc'	=> __( 'For the Full Bar toggle style, this content will appear at the right edge of the toggle bar.  To pad your custom content vertically, use the class <code>shiftnav-toggle-main-block</code>.' , 'reign' ),
	// 	'type'	=> 'textarea',
	// 	'default' => '', //get_bloginfo( 'title' )
	// 	'sanitize_callback' => 'shiftnav_allow_html',
	// 	'customizer'	=> true,
	// 	'customizer_section'	=> 'config'
	// );

	/* Toggle content right with select options in reign. */
	$fields[ $toggle ][ 56 ] = array(
		'name'				 => 'toggle_content_right',
		'label'				 => __( 'Toggle Content Right Edge', 'reign' ),
		//'desc'	=> __( 'For the Full Bar toggle style, this content will appear at the right edge of the toggle bar.  To pad your custom content vertically, use the class <code>shiftnav-toggle-main-block</code>.' , 'reign' ),
		'desc'				 => __( 'This content will appear at the right edge of the toggle bar, to the right of the toggle icon.', 'reign' ),
		'type'				 => 'select',
		'options'			 => shiftnav_get_toggle_icon_options(),
		'customizer'		 => true,
		'customizer_section' => 'config'
	);

	ksort( $fields[ $toggle ] ); //Maybe not optimal place to do this?


	/** ADD INSTANCES * */
	$menus = get_option( 'shiftnav_menus', array() );

	foreach ( $menus as $menu ) {

		$integration_code = '
			<div class="shiftnav-desc-row">
				<span class="shiftnav-code-snippet-type">PHP</span> <code class="shiftnav-highlight-code">&lt;?php shiftnav_toggle( \'' . $menu . '\' , \'Toggle Menu\' , array( \'icon\' => \'bars\' , \'class\' => \'shiftnav-toggle-button\') ); ?&gt;</code>
			</div>
			<div class="shiftnav-desc-row">
				<span class="shiftnav-code-snippet-type">Shortcode</span> <code class="shiftnav-highlight-code">[shiftnav_toggle target="' . $menu . '" class="shiftnav-toggle-button" icon="bars"]Toggle Menu[/shiftnav_toggle]</code>' .
		'</div>
			<div class="shiftnav-desc-row">
				<span class="shiftnav-code-snippet-type">HTML</span> <code class="shiftnav-highlight-code">&lt;a class="shiftnav-toggle shiftnav-toggle-button" data-shiftnav-target="' . $menu . '"&gt;&lt;i class="fa fa-bars"&gt;&lt;/i&gt; Toggle Menu &lt;/a&gt;</code>
			</div>
			<p class="shiftnav-sub-desc shiftnav-desc-mini" >Click to select, then <strong><em>&#8984;+c</em></strong> or <strong><em>ctrl+c</em></strong> to copy to clipboard</p>
			<p class="shiftnav-sub-desc shiftnav-desc-understated">Pick the appropriate code and add to your template or content where you want the toggle to appear.  The menu panel itself is loaded automatically.  You can add the toggle code as many times as you like.</p>
		';

		$fields[ SHIFTNAV_PREFIX . $menu ] = array(
			10	 => array(
				'name'	 => 'php',
				'label'	 => __( 'Integration Code', 'reign' ),
				'desc'	 => $integration_code,
				'type'	 => 'html',
			),
			20	 => array(
				'name'		 => 'instance_name',
				'label'		 => __( 'Instance Name', 'reign' ),
				'desc'		 => __( '', 'reign' ),
				'type'		 => 'text',
				'default'	 => $menu,
			// 'customizer'			=> true,
			// 'customizer_section' 	=> 'config',
			),
			25	 => array(
				'name'		 => 'automatic_generation',
				'label'		 => __( 'Automatically Generate Panel', 'reign' ),
				'desc'		 => __( 'Automatically generate this ShiftNav instance.  It\'ll be added to each page of the site', 'reign' ),
				'type'		 => 'checkbox',
				'default'	 => 'on',
			//'customizer'			=> true,
			//'customizer_section' 	=> 'config',
			),
			30	 => array(
				'name'				 => 'menu',
				'label'				 => __( 'Display Menu', 'reign' ),
				'desc'				 => 'Select the menu to display or <a href="' . admin_url( 'nav-menus.php' ) . '">create a new menu</a>.  This setting will override the Theme Location setting.',
				'type'				 => 'select',
				'options'			 => shiftnav_get_nav_menu_ops(),
				//'options' => get_registered_nav_menus()
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			40	 => array(
				'name'				 => 'theme_location',
				'label'				 => __( 'Theme Location', 'reign' ),
				'desc'				 => __( 'Select the Theme Location to display.  The Menu setting will override this setting if a menu is selected.', 'reign' ),
				'type'				 => 'select',
				//'options' => shiftnav_get_nav_menu_ops(),
				'options'			 => shiftnav_get_theme_location_ops(),
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			50	 => array(
				'name'				 => 'edge',
				'label'				 => __( 'Edge', 'reign' ),
				'desc'				 => __( 'Select which edge of your site to display the menu on', 'reign' ),
				'type'				 => 'radio',
				'options'			 => array(
					'left'	 => 'Left',
					'right'	 => 'Right',
				),
				'default'			 => 'left',
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			60 => array(
				'name'				 => 'disable_menu',
				'label'				 => __( 'Disable Menu', 'reign' ),
				'desc'				 => __( 'Check this to disable the menu entirely; the panel can be used for custom content', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'off',
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			70 => array(
				'name'				 => 'skin',
				'label'				 => __( 'Skin', 'reign' ),
				'desc'				 => __( 'Select which skin to use for this instance', 'reign' ),
				'type'				 => 'select',
				'options'			 => shiftnav_get_skin_ops(),
				//'options' => get_registered_nav_menus()
				'customizer'		 => true,
				'customizer_section' => SHIFTNAV_PRO ? 'styles_panel' : 'config',
			),
			80 => array(
				'name'				 => 'submenu_type_default',
				'label'				 => __( 'Submenu Type Default', 'reign' ),
				'desc'				 => __( 'This submenu type will be used by any Menu Item whose Submenu Type is set to "Menu Default"', 'reign' ),
				'type'				 => 'radio',
				'options'			 => array(
					'always'	 => __( 'Always visible', 'reign' ),
					'accordion'	 => __( 'Accordion', 'reign' ),
					'shift'		 => __( 'Shift', 'reign' ),
				),
				'default'			 => 'always',
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			82 => array(
				'name'				 => 'accordion_toggle_icon_open',
				'label'				 => __( 'Accordion Toggle Open Icon', 'reign' ),
				'desc'				 => __( 'The icon that, when tapped, will open the accordion submenu', 'reign' ),
				'type'				 => 'radio',
				'options'			 => $accordion_toggle_icon_open_ops,
				'default'			 => 'chevron-down',
				'customizer'		 => true,
				'customizer_section' => 'config',
				'customizer_control' => 'radio_html'
			),
			83 => array(
				'name'				 => 'accordion_toggle_icon_close',
				'label'				 => __( 'Accordion Toggle Close Icon', 'reign' ),
				'desc'				 => __( 'The icon that, when tapped, will close the accordion submenu', 'reign' ),
				'type'				 => 'radio',
				'options'			 => $accordion_toggle_icon_close_ops,
				'default'			 => 'chevron-up',
				'customizer'		 => true,
				'customizer_section' => 'config',
				'customizer_control' => 'radio_html',
			),
			90 => array(
				'name'				 => 'indent_submenus',
				'label'				 => __( 'Indent Always Visible Submenus', 'reign' ),
				'desc'				 => __( 'Check this to indent submenu items of always-visible submenus', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'off',
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			100 => array(
				'name'				 => 'toggle_content',
				'label'				 => __( 'Toggle Content', 'reign' ),
				'desc'				 => __( 'Enter the content to be displayed in the toggle, which you will insert into your template with the integration code at the top of this tab.', 'reign' ),
				'type'				 => 'textarea',
				'default'			 => '<i class="fa fa-bars"></i> Toggle', // 'Toggle '.$menu,
				'sanitize_callback'	 => 'shiftnav_allow_html',
			),
			110 => array(
				'name'				 => 'display_site_title',
				'label'				 => __( 'Display Site Title', 'reign' ),
				'desc'				 => __( 'Display the site title in the menu', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'on',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			120 => array(
				'name'				 => 'display_instance_title',
				'label'				 => __( 'Display Instance Name', 'reign' ),
				'desc'				 => __( 'Display the instance name in the menu', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'off',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			125 => array(
				'name'				 => 'display_panel_close_button',
				'label'				 => __( 'Display Panel Close Button', 'reign' ),
				'desc'				 => __( 'Display an &times; close button in the upper right of the ShiftNav panel', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'off',
				'customizer'		 => true,
				'customizer_section' => 'config',
			),
			130 => array(
				'name'				 => 'image',
				'label'				 => __( 'Top Image', 'reign' ),
				'desc'				 => __( '', 'reign' ),
				'type'				 => 'image',
				'default'			 => '',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			140 => array(
				'name'				 => 'image_padded',
				'label'				 => __( 'Pad Image', 'reign' ),
				'desc'				 => __( 'Add padding to align image with menu item text.  Uncheck to expand to the edges of the panel.', 'reign' ),
				'type'				 => 'checkbox',
				'default'			 => 'on',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			150 => array(
				'name'				 => 'image_link',
				'label'				 => __( 'Image Link (URL)', 'reign' ),
				'desc'				 => __( 'Make the image a link to this URL.', 'reign' ),
				'type'				 => 'text',
				'default'			 => '',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			160 => array(
				'name'				 => 'content_before',
				'label'				 => __( 'Custom Content Before Menu', 'reign' ),
				'desc'				 => __( '', 'reign' ),
				'type'				 => 'textarea',
				'default'			 => '',
				'sanitize_callback'	 => 'shiftnav_allow_html',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			170 => array(
				'name'				 => 'content_after',
				'label'				 => __( 'Custom Content After Menu', 'reign' ),
				'desc'				 => __( '', 'reign' ),
				'type'				 => 'textarea',
				'default'			 => '',
				'sanitize_callback'	 => 'shiftnav_allow_html',
				'customizer'		 => true,
				'customizer_section' => 'content',
			),
			/*
			  array(
			  'name' => 'display_condition',
			  'label' => __( 'Display on', 'reign' ),
			  'desc' => __( '', 'reign' ),
			  'type' => 'multicheck',
			  'options' => array(
			  'all' 	=> 'All',
			  'posts' => 'Posts',
			  'pages' => 'Pages',
			  'home' 	=> 'Home Page',
			  'blog'	=> 'Blog Page',
			  ),
			  'default' => array( 'all' => 'all' )
			  ),
			 */
			180 => array(
				'name'	 => 'delete',
				'label'	 => __( 'Delete Instance', 'reign' ),
				'desc'	 => '<a class="shiftnav_instance_button shiftnav_instance_button_delete" href="#" data-shiftnav-instance-id="' . $menu . '" data-shiftnav-nonce="' . wp_create_nonce( 'shiftnav-delete-instance' ) . '">' . __( 'Permanently Delete Instance', 'reign' ) . '</a>',
				'type'	 => 'html',
			),
		);
	}

	return $fields;
}

add_filter( 'shiftnav_settings_panel_fields', 'shiftnav_settings_panel_fields_pro_styles' );

function shiftnav_settings_panel_fields_pro_styles( $fields = array() ) {


	/** ADD INSTANCES * */
	$menus = shiftnav_get_menu_configurations( true );

	foreach ( $menus as $menu ) {

		//PANEL GENERAL

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2010 ] = array(
			'name'				 => 'panel_background_color',
			'label'				 => __( 'Panel Background Color', 'reign' ),
			'desc'				 => __( '', 'reign' ),
			'type'				 => 'color',
			//'default' => '#1D1D20',
			'custom_style'		 => 'panel_background',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2020 ] = array(
			'name'				 => 'panel_font_color',
			'label'				 => __( 'Panel Default Font Color', 'reign' ),
			'desc'				 => __( 'The default font color for custom content within the panel (menu-specific styles will override this for menu items)', 'reign' ),
			'type'				 => 'color',
			//'default' => '#1D1D20',
			'custom_style'		 => 'panel_font_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);




		//MENU TITLE / HEADER

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2030 ] = array(
			'name'				 => 'panel_header_font_color',
			'label'				 => __( 'Panel Title Font Color', 'reign' ),
			'desc'				 => __( 'The font color for the header/title within the panel.', 'reign' ),
			'type'				 => 'color',
			//'default' => '#1D1D20',
			'custom_style'		 => 'panel_header_font_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2040 ] = array(
			'name'				 => 'panel_header_font_size',
			'label'				 => __( 'Panel Title Font Size', 'reign' ),
			'desc'				 => __( 'The font size for the header/title within the panel.', 'reign' ),
			'type'				 => 'text',
			'default'			 => '30px',
			'custom_style'		 => 'panel_header_font_size',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2050 ] = array(
			'name'				 => 'panel_header_text_align',
			'label'				 => __( 'Panel Title Text Alignment', 'reign' ),
			'desc'				 => __( 'The alignment of the text in the header/title within the panel.', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''		 => __( 'Default', 'reign' ),
				'center' => __( 'Center', 'reign' ),
				'left'	 => __( 'Left', 'reign' ),
				'right'	 => __( 'Right', 'reign' ),
			),
			//'default' => '#1D1D20',
			'custom_style'		 => 'panel_header_text_align',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2060 ] = array(
			'name'				 => 'panel_header_text_align',
			'label'				 => __( 'Panel Title Font Weight', 'reign' ),
			'desc'				 => __( 'The font weight of the text in the header/title within the panel.', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''		 => __( 'Default', 'reign' ),
				'normal' => __( 'Normal', 'reign' ),
				'bold'	 => __( 'Bold', 'reign' ),
			),
			//'default' => '#1D1D20',
			'custom_style'		 => 'panel_header_font_weight',
			'customizer'		 => true,
			'customizer_section' => 'styles_panel',
		);


		//MENU
		//MENU ITEMS
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2070 ] = array(
			'name'				 => 'menu_item_background_color',
			'label'				 => __( 'Menu Item Background Color', 'reign' ),
			'desc'				 => __( 'The color of the menu item background.  Normally not necessary to set unless you want it to differ from the panel background', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_background_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2080 ] = array(
			'name'				 => 'menu_item_font_color',
			'label'				 => __( 'Menu Item Font Color', 'reign' ),
			'desc'				 => __( 'The color of the menu item text.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_font_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		//ACTIVE
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2090 ] = array(
			'name'				 => 'menu_item_background_color_active',
			'label'				 => __( 'Menu Item Background Color [Active]', 'reign' ),
			'desc'				 => __( 'The color of the menu item background when activated.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_background_color_active',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2100 ] = array(
			'name'				 => 'menu_item_font_color_active',
			'label'				 => __( 'Menu Item Font Color [Active]', 'reign' ),
			'desc'				 => __( 'The color of the menu item text when activated.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_font_color_active',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		//CURRENT
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2110 ] = array(
			'name'				 => 'menu_item_background_current',
			'label'				 => __( 'Menu Item Background Color [Current]', 'reign' ),
			'desc'				 => __( 'The background color of current menu items.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_background_current',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2120 ] = array(
			'name'				 => 'menu_item_font_color_current',
			'label'				 => __( 'Menu Item Font Color [Current]', 'reign' ),
			'desc'				 => __( 'The font color of current menu items', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_font_color_current',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		//HIGHLIGHTED TARGETS
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2140 ] = array(
			'name'				 => 'menu_item_background_highlight',
			'label'				 => __( 'Menu Item Background Color [Highlight]', 'reign' ),
			'desc'				 => __( 'The background color of highlighted menu items.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_background_highlight',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2150 ] = array(
			'name'				 => 'menu_item_font_color_highlight',
			'label'				 => __( 'Menu Item Font Color [Highlight]', 'reign' ),
			'desc'				 => __( 'The color of highlighted menu items', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_font_color_highlight',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);



		$fields[ SHIFTNAV_PREFIX . $menu ][ 2200 ] = array(
			'name'				 => 'menu_item_font_size',
			'label'				 => __( 'Menu Item Font Size', 'reign' ),
			'desc'				 => __( 'The size of the menu item text.', 'reign' ),
			'type'				 => 'text',
			'default'			 => '13px',
			'custom_style'		 => 'menu_item_font_size',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2210 ] = array(
			'name'				 => 'menu_item_font_weight',
			'label'				 => __( 'Menu Item Font Weight', 'reign' ),
			'desc'				 => __( 'The weight of the menu item text.', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''		 => __( 'Default', 'reign' ),
				'normal' => __( 'Normal', 'reign' ),
				'bold'	 => __( 'Bold', 'reign' ),
			),
			'custom_style'		 => 'menu_item_font_weight',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2215 ] = array(
			'name'				 => 'menu_item_padding',
			'label'				 => __( 'Menu Item Padding', 'reign' ),
			'desc'				 => __( 'The padding around the menu item text.', 'reign' ),
			'type'				 => 'text',
			'custom_style'		 => 'menu_item_padding',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2220 ] = array(
			'name'				 => 'menu_item_top_border_color',
			'label'				 => __( 'Menu Item Top Border Color', 'reign' ),
			'desc'				 => __( 'The color of the top border of the menu item.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_top_border_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2230 ] = array(
			'name'				 => 'menu_item_top_border_color_active',
			'label'				 => __( 'Menu Item Top Border Color [Active]', 'reign' ),
			'desc'				 => __( 'The color of the top border of an active menu item.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_top_border_color_active',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2240 ] = array(
			'name'				 => 'menu_item_bottom_border_color',
			'label'				 => __( 'Menu Item Bottom Border Color', 'reign' ),
			'desc'				 => __( 'The color of the bottom border of the menu item.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_bottom_border_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2250 ] = array(
			'name'				 => 'menu_item_bottom_border_color_active',
			'label'				 => __( 'Menu Item Bottom Border Color [Active]', 'reign' ),
			'desc'				 => __( 'The color of the bottom border of the active menu item.', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_bottom_border_color_active',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2260 ] = array(
			'name'				 => 'menu_item_disable_item_borders',
			'label'				 => __( 'Disable Menu Item Borders', 'reign' ),
			'desc'				 => __( 'Remove the borders between menu items.', 'reign' ),
			'type'				 => 'checkbox',
			'default'			 => 'off',
			'custom_style'		 => 'menu_item_disable_item_borders',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2270 ] = array(
			'name'				 => 'menu_item_disable_text_shadow',
			'label'				 => __( 'Disable Menu Item Text Shadow', 'reign' ),
			'desc'				 => __( 'Remove the text shadow on the menu items.', 'reign' ),
			'type'				 => 'checkbox',
			'default'			 => 'off',
			'custom_style'		 => 'menu_item_disable_text_shadow',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);

		//TOP LEVEL TEXT TRANSFORMATION
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2280 ] = array(
			'name'				 => 'menu_item_top_level_text_transform',
			'label'				 => __( 'Top Level Menu Item Text Transform', 'reign' ),
			'desc'				 => __( 'The font size for the header/title within the panel.', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''			 => __( 'Default', 'reign' ),
				'none'		 => __( 'None', 'reign' ),
				'uppercase'	 => __( 'Uppercase', 'reign' ),
			),
			'custom_style'		 => 'menu_item_top_level_text_transform',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);






		//ACTIVATORS (?)
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2300 ] = array(
			'name'				 => 'menu_item_activator_background',
			'label'				 => __( 'Menu Item Activator Button Background', 'reign' ),
			'desc'				 => __( 'The background color of the button used to open and close the submenus', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_activator_background',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2310 ] = array(
			'name'				 => 'menu_item_activator_background_hover',
			'label'				 => __( 'Menu Item Activator Button Background [Active]', 'reign' ),
			'desc'				 => __( 'The active background color of the button used to open and close the submenus', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_activator_background_hover',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2320 ] = array(
			'name'				 => 'menu_item_activator_color',
			'label'				 => __( 'Menu Item Activator Arrow Color', 'reign' ),
			'desc'				 => __( 'The arrow color of the button used to open and close the submenus', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_activator_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2330 ] = array(
			'name'				 => 'menu_item_activator_color_hover',
			'label'				 => __( 'Menu Item Activator Arrow Color [Active]', 'reign' ),
			'desc'				 => __( 'The active arrow color of the button used to open and close the submenus', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_item_activator_color_hover',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);










		//RETRACTORS
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2500 ] = array(
			'name'				 => 'menu_retractor_background',
			'label'				 => __( 'Submenu Retractor / Back Button Background', 'reign' ),
			'desc'				 => __( 'The background color of the submenu retractor button', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_retractor_background',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2510 ] = array(
			'name'				 => 'menu_retractor_font_color',
			'label'				 => __( 'Submenu Retractor / Back Button Font Color', 'reign' ),
			'desc'				 => __( 'The font color of the submenu retractor button', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'menu_retractor_font_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2520 ] = array(
			'name'				 => 'menu_retractor_text_align',
			'label'				 => __( 'Submenu Retractor / Back Button Alignment', 'reign' ),
			'desc'				 => __( 'The alignment of the submenu retractor button text', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''		 => __( 'Default', 'reign' ),
				'center' => __( 'Center', 'reign' ),
				'left'	 => __( 'Left', 'reign' ),
				'right'	 => __( 'Right', 'reign' ),
			),
			'custom_style'		 => 'menu_retractor_text_align',
			'customizer'		 => true,
			'customizer_section' => 'styles_activators',
		);



		//SUBMENUS
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2600 ] = array(
			'name'				 => 'submenu_background',
			'label'				 => __( 'Submenu Background Color', 'reign' ),
			'desc'				 => __( 'The background color of the submenu', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'submenu_background',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2610 ] = array(
			'name'				 => 'submenu_item_background',
			'label'				 => __( 'Submenu Item Background Color', 'reign' ),
			'desc'				 => __( 'The background color of the individual submenu items', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'submenu_item_background',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2620 ] = array(
			'name'				 => 'submenu_item_color',
			'label'				 => __( 'Submenu Item Font Color', 'reign' ),
			'desc'				 => __( 'The font color of the submenu items', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'submenu_item_font_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2630 ] = array(
			'name'				 => 'submenu_item_border_top_color',
			'label'				 => __( 'Submenu Item Top Border Color', 'reign' ),
			'desc'				 => __( 'The color of the submenu item top border', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'submenu_item_border_top_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2640 ] = array(
			'name'				 => 'submenu_item_border_bottom_color',
			'label'				 => __( 'Submenu Item Bottom Border Color', 'reign' ),
			'desc'				 => __( 'The color of the bottom border of the submenu items', 'reign' ),
			'type'				 => 'color',
			'custom_style'		 => 'submenu_item_border_bottom_color',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);

		$fields[ SHIFTNAV_PREFIX . $menu ][ 2670 ] = array(
			'name'				 => 'menu_item_disable_item_borders',
			'label'				 => __( 'Disable Menu Item Borders', 'reign' ),
			'desc'				 => __( 'Remove the borders between menu items.', 'reign' ),
			'type'				 => 'checkbox',
			'default'			 => 'off',
			'custom_style'		 => 'menu_item_disable_item_borders',
			'customizer'		 => true,
			'customizer_section' => 'styles_menu_items',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2680 ] = array(
			'name'				 => 'submenu_item_font_size',
			'label'				 => __( 'Submenu Item Font Size', 'reign' ),
			'desc'				 => __( 'The font size of the submenu items', 'reign' ),
			'type'				 => 'text',
			'default'			 => '13px',
			'custom_style'		 => 'submenu_item_font_size',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);
		$fields[ SHIFTNAV_PREFIX . $menu ][ 2690 ] = array(
			'name'				 => 'submenu_item_font_weight',
			'label'				 => __( 'Submenu Item Font Weight', 'reign' ),
			'desc'				 => __( 'The font weight of the submenu items', 'reign' ),
			'type'				 => 'radio',
			'options'			 => array(
				''		 => __( 'Default', 'reign' ),
				'normal' => __( 'Normal', 'reign' ),
				'bold'	 => __( 'Bold', 'reign' ),
			),
			'custom_style'		 => 'submenu_item_font_weight',
			'customizer'		 => true,
			'customizer_section' => 'styles_submenus',
		);



		$fields[ SHIFTNAV_PREFIX . $menu ][ 2800 ] = array(
			'name'				 => 'font_family',
			'label'				 => __( 'Font Family', 'reign' ),
			'desc'				 => __( 'The font family the panel.  This should be a system font or else the font assets should already be loaded on your site, via @font-face or Google Fonts for example.', 'reign' ),
			'type'				 => 'text',
			'custom_style'		 => 'font_family',
			'customizer'		 => true,
			'customizer_section' => 'styles_font',
		);
	}

	return $fields;
}

add_action( 'init', 'reign_mobile_header_default_values' );

/**
 *
 * Set default values for mobile header.
 *
 */
function reign_mobile_header_default_values() {

	if( !has_nav_menu( 'shiftnav' ) ){
		$locations = get_theme_mod('nav_menu_locations');
		if( isset( $locations['menu-1'] ) ) {
			$locations['shiftnav'] = $locations['menu-1'];
			set_theme_mod( 'nav_menu_locations', $locations );
		}	
	}

	/* =====================================================
	  =            Panel Settings default values            =
	  ===================================================== */
	$panel_options = get_option( SHIFTNAV_PREFIX . 'togglebar' );

	/* update panel right edge content to buddypress user menu */
	if ( class_exists( 'BuddyPress' ) ) {
		$update_right_edge = false;
		if ( isset( $panel_options[ 'toggle_content_right' ] ) && empty( $panel_options[ 'toggle_content_right' ] ) ) {
			$update_right_edge = true;
		}
		if ( !isset( $panel_options[ 'toggle_content_right' ] ) || $update_right_edge ) {
			$panel_options[ 'toggle_content_right' ] = '[shiftnav_toggle target="shiftnav-second" class="shiftnav-toggle-button" icon="bp-user-menu"][/shiftnav_toggle]';
		}
	}

	if ( class_exists( 'PeepSo' ) ) {
		$update_right_edge = false;
		if ( isset( $panel_options[ 'toggle_content_right' ] ) && empty( $panel_options[ 'toggle_content_right' ] ) ) {
			$update_right_edge = true;
		}
		if ( !isset( $panel_options[ 'toggle_content_right' ] ) || $update_right_edge ) {
			$panel_options[ 'toggle_content_right' ] = false;
		}
	}

	$update_togglebar_hamburger_size = false;
	if ( isset( $panel_options[ 'togglebar_hamburger_size' ] ) && empty( $panel_options[ 'togglebar_hamburger_size' ] ) ) {
		$update_togglebar_hamburger_size = true;
	}
	if ( !isset( $panel_options[ 'togglebar_hamburger_size' ] ) || $update_togglebar_hamburger_size ) {
		$panel_options[ 'togglebar_hamburger_size' ] = '16px';
	}

	update_option( SHIFTNAV_PREFIX . 'togglebar', $panel_options );
	/* =====  End of Panel Settings default values  ====== */


	/* =========================================================
	  =            First mobile panel default options            =
	  ========================================================= */
	$first_options = get_option( SHIFTNAV_PREFIX . 'shiftnav-main' );

	/* start update edge for first mobile panel */
	$update_edge = false;
	if ( isset( $first_options[ 'edge' ] ) && empty( $first_options[ 'edge' ] ) ) {
		$update_edge = true;
	}
	if ( !isset( $first_options[ 'edge' ] ) || $update_edge ) {
		$first_options[ 'edge' ] = 'left';
	}
	/* end update edge for first mobile panel */


	$update_panel_header_font_size = false;
	if ( isset( $first_options[ 'panel_header_font_size' ] ) && empty( $first_options[ 'panel_header_font_size' ] ) ) {
		$update_panel_header_font_size = true;
	}
	if ( !isset( $panel_options[ 'panel_header_font_size' ] ) || $update_panel_header_font_size ) {
		$first_options[ 'panel_header_font_size' ] = '30px';
	}

	$update_menu_item_font_size = false;
	if ( isset( $first_options[ 'menu_item_font_size' ] ) && empty( $first_options[ 'menu_item_font_size' ] ) ) {
		$update_menu_item_font_size = true;
	}
	if ( !isset( $first_options[ 'menu_item_font_size' ] ) || $update_menu_item_font_size ) {
		$first_options[ 'menu_item_font_size' ] = '13px';
	}

	$update_submenu_item_font_size = false;
	if ( isset( $first_options[ 'submenu_item_font_size' ] ) && empty( $first_options[ 'submenu_item_font_size' ] ) ) {
		$update_submenu_item_font_size = true;
	}
	if ( !isset( $first_options[ 'submenu_item_font_size' ] ) || $update_submenu_item_font_size ) {
		$first_options[ 'submenu_item_font_size' ] = '13px';
	}

	update_option( SHIFTNAV_PREFIX . 'shiftnav-main', $first_options );
	/* =====  End of First mobile panel default options  ====== */


	/* ============================================================
	  =            Second mobile panle default options.            =
	  ============================================================ */
	$second_options = get_option( SHIFTNAV_PREFIX . 'shiftnav-second' );

	/* start update edge for second mobile panel. */
	$update_edge = false;
	if ( isset( $second_options[ 'edge' ] ) && empty( $second_options[ 'edge' ] ) ) {
		$update_edge = true;
	}
	if ( !isset( $second_options[ 'edge' ] ) || $update_edge ) {
		$second_options[ 'edge' ] = 'right';
	}
	/* end update edge for second mobile panel. */

	$update_panel_header_font_size = false;
	if ( isset( $second_options[ 'panel_header_font_size' ] ) && empty( $second_options[ 'panel_header_font_size' ] ) ) {
		$update_panel_header_font_size = true;
	}
	if ( !isset( $panel_options[ 'panel_header_font_size' ] ) || $update_panel_header_font_size ) {
		$second_options[ 'panel_header_font_size' ] = '30px';
	}

	$update_menu_item_font_size = false;
	if ( isset( $second_options[ 'menu_item_font_size' ] ) && empty( $second_options[ 'menu_item_font_size' ] ) ) {
		$update_menu_item_font_size = true;
	}
	if ( !isset( $second_options[ 'menu_item_font_size' ] ) || $update_menu_item_font_size ) {
		$second_options[ 'menu_item_font_size' ] = '13px';
	}

	$update_submenu_item_font_size = false;
	if ( isset( $second_options[ 'submenu_item_font_size' ] ) && empty( $second_options[ 'submenu_item_font_size' ] ) ) {
		$update_submenu_item_font_size = true;
	}
	if ( !isset( $second_options[ 'submenu_item_font_size' ] ) || $update_submenu_item_font_size ) {
		$second_options[ 'submenu_item_font_size' ] = '13px';
	}

	$update_display_instance_title = false;
	if ( isset( $second_options[ 'display_instance_title' ] ) && empty( $second_options[ 'display_instance_title' ] ) ) {
		$update_display_instance_title = true;
	}
	if ( !isset( $second_options[ 'display_instance_title' ] ) || $update_display_instance_title ) {
		$second_options[ 'display_instance_title' ] = 'off';
	}

	$update_display_menu = false;
	$toggle_content_right = shiftnav_op( 'toggle_content_right', 'togglebar' );
	if ( $toggle_content_right == '[shiftnav_toggle target="shiftnav-second" class="shiftnav-toggle-button" icon="bp-user-menu"][/shiftnav_toggle]' ) {
		$update_display_menu = true;
	}
	if ( $update_display_menu ) {
		if( is_nav_menu('user-profile-menu') ) {
			$second_options[ 'menu' ] = 'user-profile-menu';
		}
	}

	update_option( SHIFTNAV_PREFIX . 'shiftnav-second', $second_options );
	/* =====  End of Second mobile panle default options.  ====== */
}
