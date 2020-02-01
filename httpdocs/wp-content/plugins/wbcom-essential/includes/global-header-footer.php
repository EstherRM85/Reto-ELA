<?php
/**
 * Checks if Elementor is installed and activated and loads it's own files and actions.
 *
 * @package  reign header-footer-elementor
 */
defined( 'ABSPATH' ) or exit;
/**
 * WBCOM_Elementor_Global_Header_Footer setup
 *
 * @since 1.0
 */
class WBCOM_Elementor_Global_Header_Footer {
	/**
	 * Instance of WBCOM_Elementor_Global_Header_Footer
	 *
	 * @var WBCOM_Elementor_Global_Header_Footer
	 */
	private static $_instance = null;
	/**
	 * Instance of Elemenntor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	private static $elementor_frontend;
	/**
	 * Instance of WBCOM_Elementor_Global_Header_Footer
	 *
	 * @return WBCOM_Elementor_Global_Header_Footer Instance of WBCOM_Elementor_Global_Header_Footer
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	/**
	 * Constructor
	 */
	private function __construct() {
		self::$elementor_frontend = new \Elementor\Frontend();
		// Add the default posts on plugin activation
		// register_activation_hook( WBCOM_ELEMENTOR_ADDONS_PLUGIN_FILE, array( $this, 'add_header_footer_post' ) );
		// add_action( 'init', array( $this, 'header_posttype' ) );
		// add_action( 'init', array( $this, 'footer_posttype' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 50 );
		
		add_action( 'template_redirect', array( $this, 'block_template_frontend' ) );
		add_filter( 'single_template', array( $this, 'load_canvas_template' ) );

		add_action( 'add_meta_boxes', array( $this, 'ehf_register_metabox' ) );
		add_action( 'save_post', array( $this, 'ehf_save_meta' ) );
		
		// $header_id	 = self::get_settings( 'type_header', '' );
		// $footer_id	 = self::get_settings( 'type_footer', '' );
		
		// if ( '' !== $header_id ) {
		// 	add_action( 'template_redirect', array( $this, 'wbcom_setup_header' ), 10 );
		// 	add_action( 'wbcom_masthead', array( $this, 'add_header_markup' ) );
		// }
		// if ( '' !== $footer_id ) {
		// 	add_action( 'template_redirect', array( $this, 'wbcom_setup_footer' ), 10 );
		// 	add_action( 'wbcom_footer', array( $this, 'add_footer_markup' ) );
		// }

		add_action( 'template_redirect', array( $this, 'wbcom_setup_header' ), 10 );
		
		add_action( 'wbcom_masthead', array( $this, 'add_header_markup' ), 18 );

		/**
		* support added for topbar
		* @since 1.0.1
		*/
		add_action( 'wbcom_before_masthead', array( $this, 'add_header_topbar_markup' ), 18 );

		add_action( 'template_redirect', array( $this, 'wbcom_setup_footer' ), 10 );

		add_action( 'wbcom_footer', array( $this, 'add_footer_markup' ), 18 );

		add_action( 'add_meta_boxes', array( $this, 'wbcom_default_page_template' ), 1 );
		add_action( 'admin_head', array( $this, 'custom_menu_items' ), 1 );
	}
	/**
	 * Register Post type for header footer templates
	 */
	// public function header_posttype() {
	// 	$labels = array(
	// 		'name'		 => __( 'Global Header Template', 'wbcom-essential' ),
	// 		'edit_item'	 => __( 'Edit Global Header Template', 'wbcom-essential' ),
	// 	);
	// 	$args = array(
	// 		'labels'				 => $labels,
	// 		'public'				 => true,
	// 		'rewrite'				 => false,
	// 		'show_ui'				 => true,
	// 		'show_in_menu'			 => false,
	// 		'show_in_nav_menus'		 => false,
	// 		'exclude_from_search'	 => true,
	// 		'capability_type'		 => 'post',
	// 		'capabilities'			 => array(
	// 			'create_posts'			 => 'do_not_allow',
	// 			'delete_published_posts' => 'do_not_allow',
	// 			'delete_private_posts'	 => 'do_not_allow',
	// 			'delete_posts'			 => 'do_not_allow',
	// 		),
	// 		'map_meta_cap'			 => true,
	// 		'hierarchical'			 => false,
	// 		'menu_icon'				 => 'dashicons-editor-kitchensink',
	// 		'supports'				 => array( 'elementor' ),
	// 	);
	// 	register_post_type( 'reign-elemtr-header', $args );
	// }
	/**
	 * Register Post type for header footer templates
	 */
	// public function footer_posttype() {
	// 	$labels = array(
	// 		'name'		 => __( 'Global Footer Template', 'wbcom-essential' ),
	// 		'edit_item'	 => __( 'Edit Global Footer Template', 'wbcom-essential' ),
	// 	);
	// 	$args = array(
	// 		'labels'				 => $labels,
	// 		'public'				 => true,
	// 		'rewrite'				 => false,
	// 		'show_ui'				 => true,
	// 		'show_in_menu'			 => false,
	// 		'show_in_nav_menus'		 => false,
	// 		'exclude_from_search'	 => true,
	// 		'capability_type'		 => 'post',
	// 		'capabilities'			 => array(
	// 			'create_posts'			 => 'do_not_allow',
	// 			'delete_published_posts' => 'do_not_allow',
	// 			'delete_private_posts'	 => 'do_not_allow',
	// 			'delete_posts'			 => 'do_not_allow',
	// 		),
	// 		'map_meta_cap'			 => true,
	// 		'hierarchical'			 => false,
	// 		'menu_icon'				 => 'dashicons-editor-kitchensink',
	// 		'supports'				 => array( 'elementor' ),
	// 	);
	// 	register_post_type( 'reign-elemtr-footer', $args );
	// }
	/**
	 * Add default posts when plugin is activated
	 */
	// function add_header_footer_post() {
	// 	// on activation first regsiter the post type
	// 	$this->header_posttype();
	// 	$this->footer_posttype();
	// 	// add the first and only post
	// 	$post_data_header = array(
	// 		'post_type'		 => 'reign-elemtr-header',
	// 		'post_status'	 => 'publish',
	// 		'post_author'	 => 1
	// 	);
	// 	$posts = get_posts( $post_data_header );
	// 	if ( count( $posts ) == 0 ) { //check if posts exists
	// 		wp_insert_post( $post_data_header );
	// 	}
	// 	$post_data_footer = array(
	// 		'post_type'		 => 'reign-elemtr-footer',
	// 		'post_status'	 => 'publish',
	// 		'post_author'	 => 1
	// 	);
	// 	$posts = get_posts( $post_data_footer );
	// 	if ( count( $posts ) == 0 ) { //check if posts exists
	// 		wp_insert_post( $post_data_footer );
	// 	}
	// }
	/**
	 * Register the admin menu for Header Footer builder.
	 *
	 * @since  1.0.0
	 */
	public function register_admin_menu() {
		$header_pid	 = $this->get_hf_post_id( 'reign-elemtr-header' );
		$footer_pid	 = $this->get_hf_post_id( 'reign-elemtr-footer' );

		$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		add_submenu_page(
			$theme_slug . '-settings',
			__( 'Header', 'wbcom-essential' ), 
			__( 'Header', 'wbcom-essential' ),
			'manage_options',
			'edit.php?post_type=reign-elemtr-header'
		);

		add_submenu_page(
			$theme_slug . '-settings',
			__( 'Footer', 'wbcom-essential' ),
			__( 'Footer', 'wbcom-essential' ),
			'manage_options',
			'edit.php?post_type=reign-elemtr-footer'
		);

		// add_submenu_page(
		// 'reign-settings', __( 'Global Header', 'wbcom-essential' ), __( 'Global Header', 'wbcom-essential' ), 'manage_options', basename( get_edit_post_link( $header_pid ) )
		// );
		// add_submenu_page(
		// 'reign-settings', __( 'Global Footer', 'wbcom-essential' ), __( 'Global Footer', 'wbcom-essential' ), 'manage_options', basename( get_edit_post_link( $footer_pid ) )
		// );
	}
	/**
	 * Register meta box(es).
	 */
	function ehf_register_metabox() {
		add_meta_box( 'ehf-meta-box', __( 'Header or Topbar ?', 'wbcom-essential' ), array(
			$this,
			'efh_metabox_render',
		), array( 'reign-elemtr-header' ), 'side', 'default' );
	}
	/**
	 * Render Meta field.
	 *
	 * @param  POST $post Current post object which is being displayed.
	 */
	function efh_metabox_render( $post ) {
		$selected_value = get_post_meta( $post->ID, 'reign_ele_header_topbar', true );
		$options_array = array(
			'header'	=> __( 'Header', 'wbcom-essential' ),
			'topbar'	=> __( 'Topbar', 'wbcom-essential' ),
		);
		echo '<select name="reign_ele_header_topbar">';
			foreach ( $options_array as $key => $value ) {
				echo '<option value="' . $key  . '"' . selected( $selected = $selected_value, $current = $key, $echo = true ) . '>'. $value .'</option>';
			}
		echo '</select>';
		// $values		 = get_post_custom( $post->ID );
		// $checked	 = isset( $values[ 'ehf_global' ] ) ? esc_attr( $values[ 'ehf_global' ][ 0 ] ) : '';
		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'ehf_meta_nonce', 'ehf_meta_nonce' );
	}
	/**
	 * Save meta field.
	 *
	 * @param  POST $post_id Current post object which is being displayed.
	 *
	 * @return Void
	 */
	public function ehf_save_meta( $post_id ) {
		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST[ 'ehf_meta_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'ehf_meta_nonce' ], 'ehf_meta_nonce' ) ) {
			return;
		}
		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		if ( isset( $_POST[ 'reign_ele_header_topbar' ] ) ) {
			update_post_meta( $post_id, 'reign_ele_header_topbar', esc_attr( $_POST[ 'reign_ele_header_topbar' ] ) );
		}
	}
	/**
	 * Convert the Template name to be added in the notice.
	 *
	 * @since  1.0.0
	 *
	 * @param  String $template_type Template type name.
	 *
	 * @return String $template_type Template type name.
	 */
	public function template_location( $template_type ) {
		$template_type = ucfirst( str_replace( 'type_', '', $template_type ) );
		return $template_type;
	}
	/**
	 * Don't display the elementor header footer templates on the frontend for non edit_posts capable users.
	 *
	 * @since  1.0.0
	 */
	public function block_template_frontend() {
		if ( is_singular( 'reign-elementor-hf' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}
	/**
	 * Single template function which will choose our template
	 *
	 * @since  1.0.0
	 *
	 * @param  String $single_template Single template.
	 */
	function load_canvas_template( $single_template ) {
		global $post;
		if ( 'reign-elementor-hf' == $post->post_type ) {
			return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
		}
		return $single_template;
	}
	/**
	 * Get option for the settings
	 *
	 * @param  mixed $setting Option name.
	 * @param  mixed $default Default value to be received if the option value is not stored in the option.
	 *
	 * @return mixed.
	 */
	public function get_settings( $setting = '', $default = '' ) {
		if ( 'type_header' == $setting ) {
			$header_id = $this->get_hf_post_id( 'reign-elemtr-header' );
			$global_header = get_post_meta( $header_id, 'ehf_global', true );
			return ('yes' == $global_header) ? $header_id : '';
		}
		if ( 'type_footer' == $setting ) {
			$footer_id = $this->get_hf_post_id( 'reign-elemtr-footer' );
			$global_footer = get_post_meta( $footer_id, 'ehf_global', true );
			return ('yes' == $global_footer) ? $footer_id : '';
		}
	}
	/**
	 * Prints the Header content.
	 */
	// public function get_header_content() {
	// 	// $header_id = $this->get_settings( 'type_header', '' );
	// 	$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		
	// 	global $wp_query;	
	// 	if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
	// 		$post_id = get_option( 'page_for_posts' );
	// 		$post = get_post( $post_id );
	// 	}
	// 	else {
	// 		global $post;
	// 	}

	// 	if( $post ) {
	// 		$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
	// 		$reign_ele_header = isset( $wbcom_metabox_data['header_footer']['elementor_header'] ) ? $wbcom_metabox_data['header_footer']['elementor_header'] : '';
	// 		// $reign_ele_header = get_post_meta( $post->ID , $theme_slug . '_ele_header', true );
	// 	}
	// 	// if( !empty( $reign_ele_header ) && ( $reign_ele_header != "-1" ) ) {
	// 	// 	$header_id = $reign_ele_header;
	// 	// }
	// 	if( !empty( $reign_ele_header ) && ( $reign_ele_header == "-1" ) ) {
	// 		return;
	// 	}
		
	// 	if( !empty( $reign_ele_header ) && ( $reign_ele_header != "0" ) ) {
	// 		$header_id = $reign_ele_header;
	// 	}
	// 	else {
	// 		$settings = get_option( $theme_slug . '_options', array() );
	// 		$header_id = isset( $settings[ $theme_slug . '_pages' ][ 'global_ele_header' ] ) ? $settings[ $theme_slug . '_pages' ][ 'global_ele_header' ] : '0';
	// 	}

	// 	/* code to convert slug to id */
	// 	$args = array(
	// 		'name'        => $header_id,
	// 		'post_type'   => 'reign-elemtr-header',
	// 		'post_status' => 'publish',
	// 		'numberposts' => 1
	// 	);
	// 	$topbar_posts = get_posts( $args );
	// 	if( !empty( $topbar_posts ) && is_array( $topbar_posts ) ) {
	// 		$header_id = $topbar_posts[0]->ID;
	// 	}
	// 	/* code to convert slug to id */
			
	// 	echo self::$elementor_frontend->get_builder_content_for_display( $header_id );
	// }
	/**
	 * Prints the Footer content.
	 */
	// public function get_footer_content() {
	// 	// $footer_id = $this->get_settings( 'type_footer', '' );
	// 	$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		
	// 	global $wp_query;	
	// 	if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
	// 		$post_id = get_option( 'page_for_posts' );
	// 		$post = get_post( $post_id );
	// 	}
	// 	else {
	// 		global $post;
	// 	}
		
	// 	if( $post ) {
	// 		$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
	// 		$reign_ele_footer = isset( $wbcom_metabox_data['header_footer']['elementor_footer'] ) ? $wbcom_metabox_data['header_footer']['elementor_footer'] : '';
	// 		// $reign_ele_footer = get_post_meta( $post->ID , $theme_slug . '_ele_footer', true );
	// 	}
	// 	// if( !empty( $reign_ele_footer ) && ( $reign_ele_footer != "-1" ) ) {
	// 	// 	$footer_id = $reign_ele_footer;
	// 	// }
	// 	if( !empty( $reign_ele_footer ) && ( $reign_ele_footer == "-1" ) ) {
	// 		return;
	// 	}

	// 	if( !empty( $reign_ele_footer ) && ( $reign_ele_footer != "0" ) ) {
	// 		$footer_id = $reign_ele_footer;
	// 	}
	// 	else {
	// 		$settings = get_option( $theme_slug . '_options', array() );
	// 		$footer_id = isset( $settings[ $theme_slug . '_pages' ][ 'global_ele_footer' ] ) ? $settings[ $theme_slug . '_pages' ][ 'global_ele_footer' ] : '0';
	// 	}

	// 	/* code to convert slug to id */
	// 	$args = array(
	// 		'name'        => $footer_id,
	// 		'post_type'   => 'reign-elemtr-footer',
	// 		'post_status' => 'publish',
	// 		'numberposts' => 1
	// 	);
	// 	$footer_posts = get_posts( $args );
	// 	if( !empty( $footer_posts ) && is_array( $footer_posts ) ) {
	// 		$footer_id = $footer_posts[0]->ID;
	// 	}
	// 	/* code to convert slug to id */
		

	// 	$custom_global_set = false;
	// 	if( $footer_id ) {
	// 		global $post;
	// 		if( !$post || !$post->ID ) {
	// 			$post = get_post( $footer_id );
	// 			$custom_global_set = true;
	// 		}
	// 	}
	// 	echo "<div class='footer-width-fixer'>";
	// 	echo self::$elementor_frontend->get_builder_content( $footer_id, $with_css = true );
	// 	// echo self::$elementor_frontend->get_builder_content_for_display( $footer_id );
	// 	echo '</div>';
	// 	if( $custom_global_set ) {
	// 		unset( $post );
	// 	}
	// }
	/**
	 * Disable header from the theme.
	 */
	public function wbcom_setup_header() {
		// remove_action( 'wbcom_masthead', 'wbcom_header_masthead' );
	}
	/**
	 * Display header markup.
	 */
	public function add_header_markup() {
		
		$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
		if( $reign_header_header_type ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_masthead', array( $theme_structure_ref, 'render_theme_header_desktop' ), 20 );
			}
		}

		$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		global $wp_query;	
		if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
			$post_id = get_option( 'page_for_posts' );
			$post = get_post( $post_id );
		}
		else {
			global $post;
		}

		if( $post ) {
			$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
			$reign_ele_header = isset( $wbcom_metabox_data['header_footer']['elementor_header'] ) ? $wbcom_metabox_data['header_footer']['elementor_header'] : '';
		}

		if( !empty( $reign_ele_header ) && ( $reign_ele_header == "-1" ) ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_masthead', array( $theme_structure_ref, 'render_theme_header_desktop' ), 20 );
			}
			return;
		}
		
		if( !empty( $reign_ele_header ) && ( $reign_ele_header != "0" ) ) {
			$header_id = $reign_ele_header;
		}
		else {
			if( $reign_header_header_type ) {
				$header_id = get_theme_mod( 'reign_elementor_header', '0' );
			}
			else {
				return;
			}
		}

		?>
		<div id="wbcom-ele-masthead" class="wbcom-ele-masthead-wrapper">
			<?php
			/* code to convert slug to id */
			$args = array(
				'name'        => $header_id,
				'post_type'   => 'reign-elemtr-header',
				'post_status' => 'publish',
				'numberposts' => 1
			);
			$topbar_posts = get_posts( $args );
			if( !empty( $topbar_posts ) && is_array( $topbar_posts ) ) {
				$header_id = $topbar_posts[0]->ID;
			}
			/* code to convert slug to id */
			
			if( $header_id ) {
				if( class_exists( 'Reign_Theme_Structure' ) ) {
					$theme_structure_ref = Reign_Theme_Structure::instance();
					remove_action( 'wbcom_masthead', array( $theme_structure_ref, 'render_theme_header_desktop' ), 20 );
				}
				echo self::$elementor_frontend->get_builder_content_for_display( $header_id );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Display header topbar markup.
	 */
	public function add_header_topbar_markup() {
		$topbar_enable = get_theme_mod( 'reign_header_topbar_enable', '1' );
		if( ! $topbar_enable ) {
			return;
		}

		$reign_header_topbar_type = get_theme_mod( 'reign_header_topbar_type', false );
		if( $reign_header_topbar_type ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_before_masthead', array( $theme_structure_ref, 'render_theme_topbar' ), 20 );
			}
		}
		
		$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		global $wp_query;
		if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
			$post_id = get_option( 'page_for_posts' );
			$post = get_post( $post_id );
		}
		else {
			global $post;
		}

		if( $post ) {
			$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
			$reign_ele_topbar = isset( $wbcom_metabox_data['header_footer']['elementor_topbar'] ) ? $wbcom_metabox_data['header_footer']['elementor_topbar'] : '';
		}
		
		if( !empty( $reign_ele_topbar ) && ( $reign_ele_topbar == "-1" ) ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_before_masthead', array( $theme_structure_ref, 'render_theme_topbar' ), 20 );
			}
			return;
		}

		if( !empty( $reign_ele_topbar ) && ( $reign_ele_topbar != "0" ) ) {
			$topbar_id = $reign_ele_topbar;
		}
		else {
			if( $reign_header_topbar_type ) {
				$topbar_id = get_theme_mod( 'reign_elementor_topbar', '0' );
			}
			else {
				return;
			}
		}

		if ( !empty( $topbar_id ) && ( $topbar_id != "-1" ) ) {
			/* code to convert slug to id */
			$args = array(
				'name'        => $topbar_id,
				'post_type'   => 'reign-elemtr-header',
				'post_status' => 'publish',
				'numberposts' => 1
			);
			$topbar_posts = get_posts( $args );
			if( !empty( $topbar_posts ) && is_array( $topbar_posts ) ) {
				$topbar_id = $topbar_posts[0]->ID;
			}

			if( $topbar_id ) {
				if( class_exists( 'Reign_Theme_Structure' ) ) {
					$theme_structure_ref = Reign_Theme_Structure::instance();
					remove_action( 'wbcom_before_masthead', array( $theme_structure_ref, 'render_theme_topbar' ), 20 );
				}
				/* code to convert slug to id */
				echo '<div id="wbcom-header-topbar">';
					echo self::$elementor_frontend->get_builder_content_for_display( $topbar_id );
				echo '</div>';
			}
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function wbcom_setup_footer() {
		// remove_action( 'wbcom_footer', 'wbcom_footer_html' );
	}
	/**
	 * Display footer markup.
	 */
	public function add_footer_markup() {
		$reign_footer_footer_type = get_theme_mod( 'reign_footer_footer_type', false );
		if( $reign_footer_footer_type ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_footer', array( $theme_structure_ref, 'render_theme_footer' ), 20 );
			}
		}
		
		$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		global $wp_query;	
		if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
			$post_id = get_option( 'page_for_posts' );
			$post = get_post( $post_id );
		}
		else {
			global $post;
		}
		
		if( $post ) {
			$wbcom_metabox_data = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
			$reign_ele_footer = isset( $wbcom_metabox_data['header_footer']['elementor_footer'] ) ? $wbcom_metabox_data['header_footer']['elementor_footer'] : '';
		}
		
		if( !empty( $reign_ele_footer ) && ( $reign_ele_footer == "-1" ) ) {
			if( class_exists( 'Reign_Theme_Structure' ) ) {
				$theme_structure_ref = Reign_Theme_Structure::instance();
				remove_action( 'wbcom_footer', array( $theme_structure_ref, 'render_theme_footer' ), 20 );
			}
			return;
		}

		if( !empty( $reign_ele_footer ) && ( $reign_ele_footer != "0" ) ) {
			$footer_id = $reign_ele_footer;
		}
		else {
			if( $reign_footer_footer_type ) {
				$footer_id = get_theme_mod( 'reign_elementor_footer', '0' );
			}
			else {
				return;
			}
		}
		?>
		<footer itemscope="itemscope" itemtype="http://schema.org/WPFooter">
			<?php
			/* code to convert slug to id */
			$args = array(
				'name'        => $footer_id,
				'post_type'   => 'reign-elemtr-footer',
				'post_status' => 'publish',
				'numberposts' => 1
			);
			$footer_posts = get_posts( $args );
			if( !empty( $footer_posts ) && is_array( $footer_posts ) ) {
				$footer_id = $footer_posts[0]->ID;
			}
			/* code to convert slug to id */
			
			$custom_global_set = false;
			if( $footer_id ) {
				global $post;
				if( !$post || !$post->ID ) {
					$post = get_post( $footer_id );
					$custom_global_set = true;
				}

				if( class_exists( 'Reign_Theme_Structure' ) ) {
					$theme_structure_ref = Reign_Theme_Structure::instance();
					remove_action( 'wbcom_footer', array( $theme_structure_ref, 'render_theme_footer' ), 20 );
				}
			
			}
			echo "<div class='footer-width-fixer'>";
				echo self::$elementor_frontend->get_builder_content( $footer_id, $with_css = true );
			echo '</div>';
			if( $custom_global_set ) {
				unset( $post );
			}
			?>
		</footer>
		<?php
	}
	/**
	 * Set Default page template to canvas page template
	 * 
	 * @global type $post
	 */
	function wbcom_default_page_template() {
		global $post;
		if ( ('reign-elemtr-header' == $post->post_type || 'reign-elemtr-footer' == $post->post_type ) && 0 != count( get_page_templates( $post ) ) && get_option( 'page_for_posts' ) != $post->ID // Not the page for listing posts
		&& '' == $post->page_template // Only when page_template is not set
		) {
			$post->page_template = "elementor_canvas";
			add_post_meta( $post->ID, '_wp_page_template', 'elementor_canvas' );
		}
	}
	/**
	 * Returns header footer post id
	 * 
	 * @param type $post_type
	 * @return type int
	 */
	function get_hf_post_id( $post_type ) {
		$args = array(
			'post_type'		 => $post_type,
			'post_status'	 => 'publish',
			// 'post_author'	 => 1,
			'orderby'	=> 'ID',
			'order'	=> 'DESC'
		);
		$post = get_posts( $args );
		if( !empty( $post ) && is_array( $post ) ) {
			return $post[ 0 ]->ID;
		}
		return false;
	}
	/**
	 * Custom menu items
	 * 
	 * @global type $parent_file
	 * @global type $submenu_file
	 * @global type $current_screen
	 */
	function custom_menu_items() {
		global $parent_file, $submenu_file, $current_screen;
		if( !$current_screen ) { return; }
		$screen_id = $current_screen->id;
		if ( 'reign-elemtr-header' == $screen_id ) {
			$header_pid	 = $this->get_hf_post_id( 'reign-elemtr-header' );
			$parent_file		 = 'reign-settings';
			$submenu_file		 = 'post.php?post='.$header_pid.'&amp;action=edit';
		}
		if ( 'reign-elemtr-footer' == $screen_id ) {
			$footer_pid	 = $this->get_hf_post_id( 'reign-elemtr-footer' );
			$parent_file		 = 'reign-settings';
			$submenu_file		 = 'post.php?post='.$footer_pid.'&amp;action=edit';
		}
	}
}
WBCOM_Elementor_Global_Header_Footer::instance();