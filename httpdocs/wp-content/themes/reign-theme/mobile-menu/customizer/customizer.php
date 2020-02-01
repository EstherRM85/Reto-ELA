<?php
require_once( SHIFTNAV_DIR . 'customizer/customizer.styles.generator.php' );
require_once( SHIFTNAV_DIR . 'customizer/customizer.styles.manager.php' );
require_once( SHIFTNAV_DIR . 'customizer/customizer.styles.menu-item.php' );

function shiftnav_register_customizers( $wp_customize ) {

	require_once( SHIFTNAV_DIR . 'customizer/customizer.controls.php' );

	//shiftnav_register_customizer( 'togglebar' , 'togglebar' , $wp_customize );

	$section_id	 = $panel_id	 = 'shiftnav_config_togglebar'; //.$variation_string;
	$wp_customize->add_panel( $panel_id, array(
		'title'		 => __( 'Mobile Panel Settings', 'reign' ),
		'priority'	 => 30,
	) );
	$wp_customize->add_section( $panel_id . '_config', array(
		'title'		 => __( 'Configuration', 'reign' ),
		'priority'	 => 10,
		'panel'		 => $panel_id,
	) );
	$wp_customize->add_section( $panel_id . '_styles', array(
		'title'		 => __( 'Styles', 'reign' ),
		'priority'	 => 20,
		'panel'		 => $panel_id,
	) );

	$wp_customize->add_section( $panel_id . '_mobile_header_icons', array(
		'title'			 => __( 'Mobile Header Icons', 'reign' ),
		'description'	 => esc_html__( 'Select icons for display in mobile device\'s header.', 'reign-theme' ),
		'priority'		 => 30,
		'panel'			 => $panel_id,
	) );

	$all_fields			 = shiftnav_get_settings_fields();
	$togglebar_fields	 = $all_fields[ 'shiftnav_togglebar' ];
	shiftnav_build_customizer_settings( $wp_customize, $togglebar_fields, SHIFTNAV_PREFIX . 'togglebar', $panel_id );


	$configs = shiftnav_get_menu_configurations( true );
	foreach ( $configs as $config_id ) {
		//echo $config_id;
		shiftnav_register_customizer( $config_id, $config_id, $wp_customize );
		//shiftnav_register_theme_customizer( $instance.'_responsive' , $instance , $wp_customize );
	}
}

add_action( 'customize_register', 'shiftnav_register_customizers' );

function shiftnav_register_customizer( $config_id, $config_id_root, $wp_customize ) {

	$config_tag				 = SHIFTNAV_PREFIX . $config_id;
	$prefixed_config_id_root = SHIFTNAV_PREFIX . $config_id_root;

	//$section_id =
	$panel_id = 'shiftnav_config_' . $config_id; //.$variation_string;

	if ( $config_id == 'shiftnav-main' ) {
		$toggle_panel_title = __( 'First Mobile Panel', 'reign' );
	} elseif ( $config_id == 'shiftnav-second' ) {
		$toggle_panel_title = __( 'Second Mobile Panel', 'reign' );
	} else {
		$toggle_panel_title = __( 'Panel', 'reign' );
	}
	$wp_customize->add_panel( $panel_id, array(
		//'title'			=> __( 'Toggle - Panel', 'reign' ) . ' ['.$config_id.']',
		'title'		 => $toggle_panel_title,
		'priority'	 => 35,
	) );


	$wp_customize->add_section( $panel_id . '_config', array(
		'title'		 => __( 'Configuration', 'reign' ),
		'priority'	 => 10,
		'panel'		 => $panel_id,
	) );

	$wp_customize->add_section( $panel_id . '_content', array(
		'title'		 => __( 'Content', 'reign' ),
		'priority'	 => 20,
		'panel'		 => $panel_id,
	) );

	if ( SHIFTNAV_PRO ) {
		$wp_customize->add_section( $panel_id . '_styles_panel', array(
			'title'		 => __( 'Styles - Panel', 'reign' ),
			'priority'	 => 30,
			'panel'		 => $panel_id,
		) );
		$wp_customize->add_section( $panel_id . '_styles_menu_items', array(
			'title'		 => __( 'Styles - Menu Items', 'reign' ),
			'priority'	 => 40,
			'panel'		 => $panel_id,
		) );
		$wp_customize->add_section( $panel_id . '_styles_activators', array(
			'title'		 => __( 'Styles - Activators &amp; Retractors', 'reign' ),
			'priority'	 => 50,
			'panel'		 => $panel_id,
		) );
		$wp_customize->add_section( $panel_id . '_styles_submenus', array(
			'title'		 => __( 'Styles - Submenus', 'reign' ),
			'priority'	 => 60,
			'panel'		 => $panel_id,
		) );
		$wp_customize->add_section( $panel_id . '_styles_font', array(
			'title'		 => __( 'Styles - Font', 'reign' ),
			'priority'	 => 70,
			'panel'		 => $panel_id,
		) );
	}

	// $wp_customize->add_section( $panel_id.'_top_level', array(
	// 	'title'		=> __( 'Top Level Styles', 'reign' ),
	// 	'priority'	=> 30,
	// 	'panel'		=> $panel_id,
	// ) );
	// $wp_customize->add_section( $panel_id.'_submenu', array(
	// 	'title'		=> __( 'Submenu Styles', 'reign' ),
	// 	'priority'	=> 40,
	// 	'panel'		=> $panel_id,
	// ) );
	// $wp_customize->add_section( $panel_id.'_font', array(
	// 	'title'		=> __( 'Fonts', 'reign' ),
	// 	'priority'	=> 50,
	// 	'panel'		=> $panel_id,
	// ) );
	// $wp_customize->add_section( $panel_id.'_markup', array(
	// 	'title'		=> __( 'Markup', 'reign' ),
	// 	'priority'	=> 10,
	// 	'panel'		=> $panel_id,
	// ) );



	$setting_op	 = $config_tag;
	$all_fields	 = shiftnav_get_settings_fields();
	// echo 'GOGO'. $prefixed_config_id_root;
	// foreach( $all_fields as $_id => $blah ){
	// 	echo "\n".$_id;
	// }
	//echo shiftp( $all_fields );
	$fields		 = $all_fields[ $prefixed_config_id_root ];

	shiftnav_build_customizer_settings( $wp_customize, $fields, $setting_op, $panel_id, 0 );

	//shiftp( $fields );
}

function shiftnav_build_customizer_settings( $wp_customize, $fields, $setting_op, $panel_id, $priority = 0 ) {

	foreach ( $fields as $field ) {
		//shiftp($field);
		$priority += 10;

		if ( isset( $field[ 'customizer' ] ) && $field[ 'customizer' ] ) {
			$setting_id = $setting_op . '[' . $field[ 'name' ] . ']';

			$default = isset( $field[ 'default' ] ) ? $field[ 'default' ] : '';
			if ( $field[ 'type' ] == 'checkbox' ) {
				//$default = $default == 'on' ? true : false;
				//$default = $default == 'on' ? 'on' : 'off';
			}
			$wp_customize->add_setting(
			$setting_id, array(
				'default'	 => $default,
				'type'		 => 'option',
			)
			);


			$field_section_id = $panel_id; //$section_id;
			if ( isset( $field[ 'customizer_section' ] ) ) {
				$field_section_id = $panel_id . '_' . $field[ 'customizer_section' ]; //shiftnav_config_{config_id}_{section}
			}

			$args = array(
				'label'		 => $field[ 'label' ],
				'section'	 => $field_section_id,
				'settings'	 => $setting_id,
				'priority'	 => $priority,
			);

			if ( isset( $field[ 'desc' ] ) ) {
				$args[ 'description' ] = $field[ 'desc' ];
			}

			switch ( $field[ 'type' ] ) {

				case 'text':

					$args[ 'type' ] = 'text';
					$wp_customize->add_control(
					$setting_id, $args
					);
					break;

				case 'textarea':
					$args[ 'type' ] = 'textarea';
					$wp_customize->add_control(
					$setting_id, $args
					);
					break;

				case 'checkbox':

					// $args['type'] = 'checkbox';
					// $wp_customize->add_control(
					// 	$setting_id,
					// 	$args
					// );
					// add_filter( 'customize_sanitize_js_'.$setting_id , 'shiftnav_adapt_checkbox_values' , 10 , 2 );
					// break;

					$args[ 'type' ] = 'checkbox';

					//add_filter( 'customize_sanitize_js_'.$setting_id , 'shiftnav_adapt_checkbox_values' , 10 , 2 );
					// $wp_customize->add_control(
					// 	new WP_Customize_Control_ShiftNav_Checkbox(
					// 		$wp_customize,
					// 		$setting_id,
					// 		$args
					// 	)
					// );
					$wp_customize->add_control(
					$setting_id, $args
					);

					break;

				case 'select':

					$args[ 'type' ]	 = 'select';
					$ops			 = $field[ 'options' ];
					if ( !is_array( $ops ) && function_exists( $ops ) ) {
						$ops = $ops();
					}
					$args[ 'choices' ] = $ops;
					$wp_customize->add_control(
					$setting_id, $args
					);
					break;

				case 'radio':

					$args[ 'type' ]	 = 'radio';
					$args[ 'choices' ] = $field[ 'options' ];

					if ( isset( $field[ 'customizer_control' ] ) && $field[ 'customizer_control' ] == 'radio_html' ) {
						$wp_customize->add_control(
						new WP_Customize_Control_ShiftNav_Radio_HTML(
						$wp_customize, $setting_id, $args
						)
						);
					} else {
						$wp_customize->add_control(
						$setting_id, $args
						);
					}
					break;



				case 'color':

					$wp_customize->add_control(
					new WP_Customize_Color_Control(
					$wp_customize, $setting_id, $args
					)
					);
					break;

				case 'image':

					$wp_customize->add_control(
					new WP_Customize_Image_Control(
					$wp_customize, $setting_id, $args
					)
					);
			}
		}
	}
}

function shiftnav_adapt_checkbox_values( $value, $setting ) {
	//echo '[[[['.$value;
	$value = $value == 'on' ? true : false;
	return $value;
}

function shiftnav_customizer_assets() {
	wp_enqueue_style( 'shiftnav-font-awesome', SHIFTNAV_URL . 'assets/css/fontawesome/css/font-awesome.min.css' );
}

add_action( 'customize_controls_enqueue_scripts', 'shiftnav_customizer_assets' );

function shiftnav_customizer_css() {

	//echo shiftnav_generate_custom_styles();

	global $wp_customize;
	if ( isset( $wp_customize ) ):
		?>
		<style type="text/css">
		<?php
		echo shiftnav_generate_all_menu_preview_styles();
		?>
		</style>
	<?php
	endif;
}

add_action( 'wp_head', 'shiftnav_customizer_css' );

function shiftnav_generate_all_menu_preview_styles() {

	$all_styles = array();

	//$all_styles['main'] = shiftnav_generate_menu_preview_styles( 'main' );

	$all_styles[ 'togglebar' ] = shiftnav_generate_menu_preview_styles( 'togglebar' );
	$configs				 = shiftnav_get_menu_configurations( true );
	foreach ( $configs as $config_id ) {
		$all_styles[ $config_id ] = shiftnav_generate_menu_preview_styles( $config_id );
	}

	return shiftnav_generate_all_menu_styles( $all_styles );
}

function shiftnav_generate_menu_preview_styles( $config_id, $fields = false ) {

	$menu_key = SHIFTNAV_PREFIX . $config_id;

	if ( !$fields ) {
		$all_fields	 = shiftnav_get_settings_fields();
		$fields		 = $all_fields[ $menu_key ];
	}

	$menu_styles = array();

	foreach ( $fields as $field ) {

		if ( isset( $field[ 'custom_style' ] ) ) {
			$callback = 'shiftnav_get_menu_style_' . $field[ 'custom_style' ];

			if ( function_exists( $callback ) ) {
				$callback( $field, $config_id, $menu_styles );
			}
		}
	}

	return $menu_styles;
}

// Add mobile header icons.
add_filter( 'kirki/fields', 'reign_add_mobile_header_icon_fields', 10, 1 );

function reign_add_mobile_header_icon_fields( $fields ) {

	$default_value_set	 = reign_get_customizer_default_value_set();
	$panel_id			 = 'shiftnav_config_togglebar';
	$fields[]			 = array(
		'type'				 => 'sortable',
		'settings'			 => $panel_id . '_mobile_header_icons_setting',
		'label'				 => esc_attr__( 'Manage Icons Options', 'reign' ),
		'description'		 => '',
		'section'			 => $panel_id . '_mobile_header_icons',
		'priority'			 => 10,
		'default'			 => $default_value_set[ 'shiftnav_config_togglebar_mobile_header_icon_set' ],
		'choices'			 => array(
			'message'		 => esc_html__( 'Message', 'reign' ),
			'notification'	 => esc_html__( 'Notification', 'reign' ),
			'login'			 => esc_html__( 'Login', 'reign' ),
			'register-menu'	 => esc_html__( 'Register', 'reign' ),
		),
		/* comment below to make layout meun icon section available for elementor header */
		'active_callback'	 => array(
			array(
				'setting'	 => 'reign_header_header_type',
				'operator'	 => '!==',
				'value'		 => true,
			),
		),
		'partial_refresh'	 => array(
			'reign_header_icons_set' => array(
				'selector'			 => '#masthead .reign-fallback-header .search-wrap',
				'render_callback'	 => function() {

				},
			),
		),
	);

	$fields[] = array(
		'type'				 => 'color',
		'settings'			 => 'reign_mobile_header_icon_color',
		'label'				 => esc_attr__( 'Icon Color', 'reign' ),
		'description'		 => esc_attr__( 'Allows you to choose icon color.', 'reign' ),
		'section'			 => $panel_id . '_mobile_header_icons',
		'default'			 => '#ffffff',
		'priority'			 => 10,
		'choices'			 => array( 'alpha' => true ),
		'transport'			 => 'postMessage',
		'output'			 => array(
			array(
				'element'	 => '#shiftnav-toggle-main .rg-search-icon:before, #shiftnav-toggle-main .rg-icon-wrap span:before, #shiftnav-toggle-main .rg-icon-wrap, #shiftnav-toggle-main .user-link-wrap .user-link, #shiftnav-toggle-main .ps-user-name, #shiftnav-toggle-main .ps-dropdown--userbar .ps-dropdown__toggle, #shiftnav-toggle-main .ps-widget--userbar__logout>a',
				'property'	 => 'color',
			),
			array(
				'element'	 => '.wbcom-nav-menu-toggle span',
				'property'	 => 'background',
			),
		),
		'js_vars'			 => array(
			array(
				'function'	 => 'css',
				'element'	 => '#shiftnav-toggle-main .rg-search-icon:before, #shiftnav-toggle-main .rg-icon-wrap span:before, #shiftnav-toggle-main .rg-icon-wrap, #shiftnav-toggle-main .user-link-wrap .user-link, #shiftnav-toggle-main .ps-user-name, #shiftnav-toggle-main .ps-dropdown--userbar .ps-dropdown__toggle, #shiftnav-toggle-main .ps-widget--userbar__logout>a',
				'property'	 => 'color',
			),
			array(
				'function'	 => 'css',
				'element'	 => '.wbcom-nav-menu-toggle span',
				'property'	 => 'background',
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'reign_header_header_type',
				'operator'	 => '!==',
				'value'		 => true,
			),
		),
	);

	$fields[] = array(
		'type'				 => 'color',
		'settings'			 => 'reign_mobile_header_icon_hover_color',
		'label'				 => esc_attr__( 'Icon Hover Color', 'reign' ),
		'description'		 => esc_attr__( 'Allows you to choose icon hover color.', 'reign' ),
		'section'			 => $panel_id . '_mobile_header_icons',
		'default'			 => '#ffffff',
		'priority'			 => 10,
		'choices'			 => array( 'alpha' => true ),
		'transport'			 => 'postMessage',
		'output'			 => array(
			array(
				'element'	 => '#shiftnav-toggle-main .rg-search-icon:hover:before, #shiftnav-toggle-main .rg-icon-wrap span:hover:before, #shiftnav-toggle-main .rg-icon-wrap:hover, #shiftnav-toggle-main .user-link-wrap .user-link:hover, #shiftnav-toggle-main .ps-user-name:hover, #shiftnav-toggle-main .ps-dropdown--userbar .ps-dropdown__toggle:hover, #shiftnav-toggle-main .ps-widget--userbar__logout>a:hover',
				'property'	 => 'color',
			),
		),
		'js_vars'			 => array(
			array(
				'function'	 => 'css',
				'element'	 => '#shiftnav-toggle-main .rg-search-icon:hover:before, #shiftnav-toggle-main .rg-icon-wrap span:hover:before, #shiftnav-toggle-main .rg-icon-wrap:hover, #shiftnav-toggle-main .user-link-wrap .user-link:hover, #shiftnav-toggle-main .ps-user-name:hover, #shiftnav-toggle-main .ps-dropdown--userbar .ps-dropdown__toggle:hover, #shiftnav-toggle-main .ps-widget--userbar__logout>a:hover',
				'property'	 => 'color',
			),
		),
		'active_callback'	 => array(
			array(
				'setting'	 => 'reign_header_header_type',
				'operator'	 => '!==',
				'value'		 => true,
			),
		),
	);
	return $fields;
}
