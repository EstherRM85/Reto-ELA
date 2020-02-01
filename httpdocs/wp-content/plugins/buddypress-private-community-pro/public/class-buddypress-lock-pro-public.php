<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddypress_Lock_Pro
 * @subpackage Buddypress_Lock_Pro/public
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
class Buddypress_Lock_Pro_Public {

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

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$locked_content_file_path = BLPRO_PLUGIN_PATH . 'public/templates/bplock-locked-content-template.php';
		$this->locked_content_template = apply_filters( 'blpro_locked_content_template', $locked_content_file_path );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Lock_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Lock_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-lock-pro-public.css', array(), time(), 'all' );

		//wp_enqueue_style( 'blpro-font-awesome-500', 'https://use.fontawesome.com/releases/v5.4.2/css/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'blpro-font-awesome-500', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Lock_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Lock_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-lock-pro-public.js', array( 'jquery' ), $this->version, false );

		wp_localize_script($this->plugin_name, 'blpro_public_obj', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);

	}

	/**
	 * Modify the template of the wordpress locked pages.
	 *
	 * @since    1.0.0
	 */
	public function blpro_lock_wordpress_pages( $template ) {
		global $post, $wp;
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );
		
		$current_url = home_url( $wp->request );
		$match		 = false;
		if ( isset($blpro_nl_settings['lock_wp_pages']) && isset($blpro_nl_settings['locked_pages']) ) {
			foreach ( $blpro_nl_settings['locked_pages'] as $pid ) {
				$slug = basename( get_permalink( $pid ) );
				if ( strpos( $current_url, $slug ) !== FALSE ) {
					$match = true;
				}
			}
		}
		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_wp_pages']) && isset($blpro_nl_settings['locked_pages']) && !empty( $post ) && in_array( $post->ID, $blpro_nl_settings['locked_pages'] ) || !is_user_logged_in() && isset($blpro_nl_settings['lock_wp_pages']) && isset($blpro_nl_settings['locked_pages']) && $match ) {

			if ( $theme_template = locate_template( 'buddypress-private-community-pro/bplock-locked-content-template.php' ) ) {
				$template = $theme_template;
			} else {
				$template = $this->locked_content_template;
			}
		}
		return $template;
	}

	/**
	 * Modify the template of the cpt pages.
	 *
	 * @since    1.0.0
	 */
	public function blpro_lock_cpt_pages( $template ){
		global $post;
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );

		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_cpt']) && isset($blpro_nl_settings['locked_cpts']) && !empty( $post ) && in_array( $post->post_type, $blpro_nl_settings['locked_cpts'] ) ) {
			if ( file_exists( $this->locked_content_template ) ) {
				if ( $theme_template = locate_template( 'buddypress-private-community-pro/bplock-locked-content-template.php' ) ) {
					$template = $theme_template;
				} else {
					$template = $this->locked_content_template;
				}
			}
		}
		return $template;
	}

	/**
	 * Modify the template of the single cpt pages.
	 *
	 * @since    1.0.0
	 */
	public function blpro_lock_cpt_single( $template ){
		global $post;
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );

		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_cpt']) && isset($blpro_nl_settings['locked_cpts']) && !empty( $post ) && in_array( $post->post_type, $blpro_nl_settings['locked_cpts'] ) ) {
			if ( file_exists( $this->locked_content_template ) ) {
				if ( $theme_template = locate_template( 'buddypress-private-community-pro/bplock-locked-content-template.php' ) ) {
					$template = $theme_template;
				} else {
					$template = $this->locked_content_template;
				}
			}
		}
		return $template;
	}

	/**
	 * Modify the template of the locked buddypress components.
	 *
	 * @since    1.0.0
	 */
	public function blpro_lock_bp_components( $found_template, $templates ) {
		global $bp, $wp;
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );
		$current_url = home_url( $wp->request );
		$match		 = false;
		if ( isset($blpro_nl_settings['lock_bp_components']) && isset($blpro_nl_settings['locked_bp_components']) ) {
			foreach ( $blpro_nl_settings['locked_bp_components'] as $url ) {
				if ( strpos( $current_url, $url ) !== FALSE ) {
					$match = true;
				}
			}
		}
		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_bp_components']) && isset($blpro_nl_settings['locked_bp_components']) && in_array( $bp->current_component, $blpro_nl_settings['locked_bp_components'] ) || !is_user_logged_in() && isset($blpro_nl_settings['lock_bp_components']) && isset($blpro_nl_settings['locked_bp_components']) && $match ) {
			if ( $theme_template = locate_template( 'buddypress-private-community-pro/bplock-locked-content-template.php' ) ) {
				$found_template = $theme_template;
			} else {
				$found_template = $this->locked_content_template;
			}
		}
		return $found_template;
	}

	public function blpro_exclude_search( $query ){
		global $bp, $wp;
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );
		$match = array();
		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_bp_components']) && isset($blpro_nl_settings['locked_bp_components']) ) {
			foreach ( $blpro_nl_settings['locked_bp_components'] as $bpc ) {
				$pid = get_page_by_path( $bpc );
				if ( $pid ) {
					$match[] = $pid->ID;
				}
			}
		}

		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_wp_pages']) && isset($blpro_nl_settings['locked_pages']) ) {
			foreach ( $blpro_nl_settings['locked_pages'] as $pid ) {
				if ( $pid ) {
					if ( !in_array( $pid, $match ) ) {
						$match[] = $pid;
					}
				}
			}
		}
		$match_cpt	 = array();
		$cpts		 = array();
		if ( !is_user_logged_in() && isset($blpro_nl_settings['lock_cpt']) && isset($blpro_nl_settings['locked_cpts']) ) {
			$args	 = array( 'public' => true, '_builtin' => false, 'exclude_from_search' => false );
			$cpts	 = get_post_types( $args, 'name' );
			if ( !empty( $cpts ) ) {
				$cpts = array_keys( $cpts );
			}
			foreach ( $blpro_nl_settings['locked_cpts'] as $cpt ) {
				if ( $cpt ) {
					$match_cpt[] = $cpt;
				}
			}
		}
		$new_cpts = array();
		if ( !empty( $match_cpt ) && !empty( $cpts ) ) {
			foreach ( $cpts as $key => $cpt ) {
				if ( !in_array( $cpt, $match_cpt ) ) {
					$new_cpts[] = $cpt;
				}
			}
		}
		if ( $query->is_search ) {
			if ( !empty( $match ) ) {
				$query->set( 'post__not_in', $match );
			}
			if ( !empty( $new_cpts ) ) {
				$query->set( 'post_type', $new_cpts );
			}
		}

		return $query;
	}

	/**
	 * The buddypress shortcode template for user login.
	 *
	 * @since    1.0.0
	 */
	public function blpro_login_form_template(){
		$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );

        $locked_content = ( isset( $blpro_nl_settings['locked_content'] ) )?stripcslashes($blpro_nl_settings['locked_content']):'';
        if ( empty( $locked_content ) ) {
            $locked_content = apply_filters( 'blpro_default_locked_message', __( 'Hey Member! Thanks for checking this page out -- however, it’s restricted to logged members only. If you’d like to access it, please login to the website.', 'buddypress-private-community-pro' ) );
        }
				?>
        <div class="bplock-content"></div>
        <p class="bplock-locked-message"><?php echo $locked_content; ?></p>
		<div class="bplock-login-form-container">
			<ul class="bplock-login-shortcode-tabs">
				<li class="tab-link current" id="bplock-login-tab" data-tab="tab-login"><i class="fa fa-sign-in"></i> <?php _e( 'Login', 'buddypress-private-community-pro' ); ?></li>
				<li class="tab-link" id="bplock-register-tab" data-tab="tab-register"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php _e( 'Register', 'buddypress-private-community-pro' ); ?></li>
			</ul>
			<div id="tab-login" class="tab-content current">
				<?php
				$file = BLPRO_PLUGIN_PATH . 'public/templates/bplock-login-form.php';
				if ( file_exists( $file ) ) {
					include_once $file;
				}
				?>
			</div>
			<div id="tab-register" class="tab-content">
				<?php
				$file = BLPRO_PLUGIN_PATH . 'public/templates/bplock-register-form.php';
				if ( file_exists( $file ) ) {
					include_once $file;
				}
				?>
			</div>
		</div>
		</div>
		<?php
	}

	/**
	 * AJAX server, to login the user
	 */
	function blpro_login() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'blpro_login' ) {
			$username	 = sanitize_text_field( $_POST[ 'username' ] );
			$password	 = sanitize_text_field( $_POST[ 'password' ] );


			$credentials	 = array(
				'user_login'	 => sanitize_text_field( $_POST[ 'username' ] ),
				'user_password'	 => sanitize_text_field( $_POST[ 'password' ] ),
				'remember'		 => true
			);
			$secure_cookie	 = true;
			$user			 = wp_signon( $credentials, $secure_cookie );
			if ( is_wp_error( $user ) ) {
				$login_success	 = 'no';
				$msg			 = $user->get_error_message();
			} else {
				$login_success	 = 'yes';
				$msg			 = __( 'Login successfull! Redirecting...', 'buddypress-private-community-pro' );
			}
			$response = array(
				'message'		 => apply_filters( 'bplock_login_success_message', $msg ),
				'login_success'	 => $login_success
			);
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * AJAX server, to register the user
	 */
	function blpro_register() {
		if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'blpro_register' ) {
			$email		 = sanitize_text_field( $_POST[ 'email' ] );
			$username	 = sanitize_text_field( $_POST[ 'username' ] );
			$password	 = sanitize_text_field( $_POST[ 'password' ] );
			$user		 = get_user_by( 'email', $email );

			if ( !empty( $user ) ) {
				$register_success	 = 'no';
				$msg				 = apply_filters( 'bplock_register_user_already_exists_message', __( 'User account already exists with the requested email!', 'buddypress-private-community-pro' ) );
			} else {
				$user_id		 = wp_create_user( $username, $password, $email );
				$credentials	 = array(
					'user_login'	 => $username,
					'user_password'	 => $password,
					'remember'		 => true
				);
				$secure_cookie	 = true;
				$loggedin_user	 = wp_signon( $credentials, $secure_cookie );
				if ( is_wp_error( $user ) ) {
					$register_success	 = 'no';
					$msg				 = $loggedin_user->get_error_message();
				} else {
					$register_success	 = 'yes';
					$msg				 = apply_filters( 'bplock_register_success_message', __( 'User registered! Logging in...', 'buddypress-private-community-pro' ) );
				}
			}
			$response = array(
				'message'			 => $msg,
				'register_success'	 => $register_success
			);
			wp_send_json_success( $response );
			die;
		}
	}

	/**
	 * Function to hide admin roles from members directory.
	 *
	 * @since    1.0.0
	 * @param    string   $ajax_querystring Current query string.
	 * @param    string   $object Current template component.
	 * @return   string   $ajax_querystring Current query string.
	 */
	public function blpro_hide_admin_plus_other_users( $qs=false, $object=false ){

		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$remove_users = array();
		$admin_users = array();
		$less_percent_member = array();
	  	if ( bp_is_members_directory() ){
	  		if( isset( $blpro_login_settings['remove_users'] ) ){
	  			$remove_users = $blpro_login_settings['remove_users'];
	  		}

	  		if( isset( $blpro_login_settings['remove_admin_roles'] ) ){
	  			$admin_users = get_users('role=administrator&fields=ID');
	  		}

	  		if( isset( $blpro_login_settings['member_after_percent'] ) && !empty($blpro_login_settings['member_after_percent'])){
	  			$min_percent = $blpro_login_settings['member_after_percent'];
	  			$blpro_users = get_users('fields=ID');
	  			if(is_array($blpro_users)){
	  				foreach ($blpro_users as $key => $id) {
	  					$profile_percent = $this->blpro_calculate_profile_percentage($id);
	  					if( $profile_percent < $min_percent ){
	  						$less_percent_member[] = $id;
	  					}
	  				}
	  			}
	  		}

	  		$exclude_users = array_merge( $remove_users, $admin_users, $less_percent_member );
	  		$excluded_user = implode(',',$exclude_users);

	  		$args = wp_parse_args($qs);

	  	  	//if(!empty($args['user_id']))
	  		//return $qs;

	  		if(!empty($args['exclude'])){
	  			$args['exclude'] = $args['exclude'].','.$excluded_user;
	  		}
	  		elseif($excluded_user){
	  			$args['exclude'] = $excluded_user;
	  		}

	  		$qs = build_query($args);
	  	}

	    return $qs;
	}

	/**
	 * Function to count the number of members.
	 *
	 * @since    1.0.0
	 * @param    int   $count The number of active members.
	 * @return   int   $count The number of active members.
	 */
	public function blpro_members_count_at_directory( $count ){
		global $wpdb;
		$bp = buddypress();
		$remove_users = array();
		$admin_users = array();
		$less_percent_member = array();
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );

		if ( bp_is_members_directory() ) {
			if( isset( $blpro_login_settings['remove_users'] ) ){
	  			$remove_users = $blpro_login_settings['remove_users'];
	  		}

	  		if( isset( $blpro_login_settings['remove_admin_roles'] ) ){
	  			$admin_users = get_users('role=administrator&fields=ID');
	  		}

	  		if( isset( $blpro_login_settings['member_after_percent'] ) && !empty($blpro_login_settings['member_after_percent']) ){
	  			$min_percent = $blpro_login_settings['member_after_percent'];
	  			$blpro_users = get_users('fields=ID');
	  			if(is_array($blpro_users)){
	  				foreach ($blpro_users as $key => $id) {
	  					$profile_percent = $this->blpro_calculate_profile_percentage($id);
	  					if( $profile_percent < $min_percent ){
	  						$less_percent_member[] = $id;
	  					}
	  				}
	  			}
	  		}
	  		$_exclude_users = array_merge( $remove_users, $admin_users, $less_percent_member );
	  		$exclude_users  = implode(',',$_exclude_users);
			
			$exclude_users_sql = !empty( $exclude_users ) ? "AND user_id NOT IN (" . implode( ',', wp_parse_id_list( $exclude_users ) ) . ")" : '';
			$count             = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(user_id) FROM {$bp->members->table_name_last_activity} WHERE component = %s AND type = 'last_activity' {$exclude_users_sql}", $bp->members->id ) );
		}

		return $count;
	}

	/**
	 * Function to display profile progress bar at member header page.
	 *
	 * @since    1.0.0
	 */
	public function blpro_bp_profile_header_meta(){
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		if(!isset($blpro_login_settings['progress_bar'])){
			return;
		}
		$profile_percent = $this->blpro_calculate_profile_percentage(bp_displayed_user_id());
		$profile_percent .= '%';
		?>
		<div class="blpro-rating-wrapper">
			<div class="blpro-rating-percent">
				<span><?php echo $profile_percent; ?></span>
			</div>
			<div class="blpro-rating-bar-wrapper">
				<div class="rating-bar-one">
					<span class="rating-bar-two" style="width:<?php echo $profile_percent; ?>"></span>
				</div>
			</div>
			<!-- <div class="blpro-rating-success">
				<span><?php esc_html_e('Profile completion rate.','buddypress-private-community-pro'); ?></span>
			</div> -->
			<div class="blrpo-rating-icon">
				<span class="rating-icon"><i class="fa fa-tasks" aria-hidden="true"></i></span>
				<i class="blpro-rating-icon-text"><?php printf( esc_html__( 'Your profile is %d%% completed.', 'buddypress-private-community-pro' ), $profile_percent ); ?></i>
			</div>
		</div>
		<?php
	}

	/**
	 * Function to calculate member profile percentage.
	 *
	 * @since    1.0.0
	 * @return   int   $profile_percent The members profile percentage.
	 */
	function blpro_calculate_profile_percentage($user_id){
		$groups     = bp_profile_get_field_groups();
		$counter = 0;
		$completed = 0;
		for ( $i = 0, $count = count( $groups ); $i < $count; ++$i ) {
			if ( !empty( $groups[ $i ]->fields ) ) {
				foreach ($groups[ $i ]->fields as $key => $value) {
					$counter++;
					$args = '';
					$r = wp_parse_args( $args, array(
							'field'   => $value->id, // Field name or ID.
							'user_id' => $user_id
						) );
					if(bp_get_profile_field_data($r)){
						$completed++;
					}
				}
			}
		}
		$profile_percent = round( $completed/$counter * 100 );
		return $profile_percent;
	}

	/**
	 * Display profile page visibility settings at profile page.
	 *
	 * @since    1.0.0
	 */
	public function blpro_profile_visibility_settings(){
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		if(!isset($blpro_login_settings['prof_visib'])){
			return;
		}
		$user_id = get_current_user_id();
		if( current_user_can('administrator') ){
			$user_id = bp_displayed_user_id();
		}
		$blpro_prof_visib = get_user_meta( $user_id, 'blpro_profile_page_visibility',true );
		?>
		<table class="profile-settings">
			<thead>
				<tr>
					<th class="title field-group-name"><?php esc_html_e( 'Profile page visibility settings', 'buddypress-private-community-pro' ); ?></th>
					<th class="title field-group-name"><?php esc_html_e( 'Visibility', 'buddypress-private-community-pro' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="field_name field_type_textbox">
					<td class="field-name"><?php _e( 'Select profile page visibility', 'buddypress-private-community-pro' ); ?></td>
					<td class="field-visibility">
						<select class="bp-xprofile-visibility" name="blpro_profile_page_visibility" id="blpro_profile_page_visibility">
							<?php foreach ( bp_xprofile_get_visibility_levels() as $level ) { ?>
								<option value="<?php echo esc_attr( $level['id'] ); ?>" <?php selected( $level['id'], $blpro_prof_visib ); ?>><?php echo esc_html( $level['label'] ); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Function to update blpro user specific profile page visibility.
	 *
	 * @since    1.0.0
	 */
	public function blpro_custom_bp_init(){
		$user_id = get_current_user_id();
		if( current_user_can('administrator') ){
			$user_id = bp_displayed_user_id();
		}
		if(isset($_POST['xprofile-settings-submit']) && isset($_POST['blpro_profile_page_visibility'])){
			$blpro_prof_visib = $_POST['blpro_profile_page_visibility'];
			update_user_meta( $user_id, 'blpro_profile_page_visibility', $blpro_prof_visib );
		}
	}

	/**
	 * Function to register and replace template stack.
	 *
	 * @since 1.1.0
	 */
	public function blpro_tol_start(){

		global $bp;
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$blpro_prof_visib = get_user_meta( $user_id, 'blpro_profile_page_visibility', true );

		if( function_exists( 'bp_register_template_stack' ) && isset( $blpro_login_settings['prof_visib'] ))
			bp_register_template_stack( array( $this, 'blpro_register_template_location' ),1 );

    	// if viewing a member page, overload the template
		if ( bp_is_user() ) {
			add_filter( 'bp_get_template_part', array($this,'blpro_replace_template'), 999, 3 );
		}
	}

	/**
	 * Register location of plugin template.
	 *
	 * @since 1.1.0
	 */
	function blpro_register_template_location() {

		$active_template = get_option('_bp_theme_package_id');
		$template = get_template();
		//print_r( wp_get_theme() );
		if( strpos($template, 'boss') !== false ){
			return BLPRO_DIR . '/templates/bp-boss-theme/';
		}else{
			if( 'legacy' == $active_template ){
			    return BLPRO_DIR . '/templates/bp-legacy/';
			}elseif( 'nouveau' == $active_template ){
				return BLPRO_DIR . '/templates/bp-nouveau/';
			}
		}
		
	}

	/**
	 * Replace member single home.php with the template overload from the plugin.
	 *
	 * @since 1.1.0
	 *
	 * @param array  $templates Array of templates located.
	 * @param string $slug      Template part slug requested.
	 * @param string $name      Template part name requested.
	 */
	function blpro_replace_template( $templates, $slug, $name ) {
		
		if( 'members/single/home' == $slug ){
			return array( 'members/single/home.php' );
		}else{
			return $templates;
		}	
	}

	/**
	 * Function to disable friendship request according to user role or users settings.
	 *
	 * @since 1.1.0
	 *
	 * @param string $button HTML markup for add friend button.
	 */
	public function blpro_bp_get_add_friend_button( $button ){

		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) && $button['id'] == 'not_friends' ) {
			if( in_array( 'friend_req', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						$button = '';
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						$button = '';
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							$button = '';
						}
					}elseif( $mtypes == 'all' ){
						$button = '';
					}
				}
			}
		}
		return $button;
	}

	/**
	 * Function to remove private message button from member header.
	 *
	 * @since 1.1.0
	 *
	 * @param string $button HTML markup for private message button.
	 */
	public function blpro_bp_get_send_message_button( $button ){
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) ) {
			if( in_array( 'private_message', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						$button = '';
						bp_core_remove_subnav_item( 'messages', 'compose' );
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						$button = '';
						bp_core_remove_subnav_item( 'messages', 'compose' );
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							$button = '';
							bp_core_remove_subnav_item( 'messages', 'compose' );
						}
					}elseif( $mtypes == 'all' ){
						$button = '';
						bp_core_remove_subnav_item( 'messages', 'compose' );
					}
				}
			}
		}
		return $button;
	}

	/**
	 * Function to remove public message button from member header.
	 *
	 * @since 1.1.0
	 *
	 * @param string $button HTML markup for pubic message button.
	 */
	public function blpro_bp_get_send_public_message_button( $button ){
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) ) {
			if( in_array( 'public_message', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						$button = '';
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						$button = '';
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							$button = '';
						}
					}elseif( $mtypes == 'all' ){
						$button = '';
					}
				}
			}
		}
		return $button;
	}

	/**
	 * Filters whether a comment can be made on an activity item.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $can_comment     Status on if activity can be commented on.
	 * @param string $activity_type   Current activity type being checked on.
	 */
	public function blpro_bp_activity_can_comment( $can_comment, $activity_type ){
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) ) {
			if( in_array( 'commenting', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						$can_comment = false;
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						$can_comment = false;
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							$can_comment = false;
						}
					}elseif( $mtypes == 'all' ){
						$can_comment = false;
					}
				}
			}
		}
		return $can_comment;
	}

	/**
	 * Function to restrict posting
	 *
	 * @since 1.1.0
	 *
	 * @param array  $templates Array of templates located.
	 * @param string $slug      Template part slug requested.
	 * @param string $name      Template part name requested.
	 */
	public function blpro_bp_get_template_part(  $templates, $slug, $name ){
		if( 'activity/post-form' != $slug )
			return $templates;

		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) ) {
			if( in_array( 'posting', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						$templates = array();
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						$templates = array();
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							$templates = array();
						}
					}elseif( $mtypes == 'all' ){
						$templates = array();
					}
				}
			}
		}
		return $templates;
	}

	/**
	 * Filters(remove) the Create a Group nav item.
	 *
	 * @since 1.0.0
	 *
	 * @param string $output HTML output for nav item.
	 */
	public function blpro_bp_get_group_create_nav_item( $output ){
		global $bp;
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );
		
		$user_id = $bp->loggedin_user->id;
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_groups_settings['create_num_member_types'] ) )?$blpro_groups_settings['create_num_member_types']:'';

		if( isset( $blpro_groups_settings['create_num'] ) && !empty( $blpro_groups_settings['create_num'] ) && !current_user_can('administrator') ){

			
			$user_groups = bp_get_user_groups( $user_id, array(
				'is_admin' => true,
			) );
			$groups_count = count( $user_groups );

			$restrict_groups_count = $blpro_groups_settings['create_num'];

			if( $groups_count >= $restrict_groups_count ){
				if( isset( $blpro_groups_settings['create_num_acc'] ) ){
					$acc_type = $blpro_groups_settings['create_num_acc'];
					if(  $acc_type == 'create_num_acc_users' ){
						if( isset( $blpro_groups_settings['create_num_users'] ) && in_array( $user_id, $blpro_groups_settings['create_num_users'] ) ){
							$output = '';
						}
					}elseif( $acc_type == 'create_num_acc_roles' ){
						if( isset( $blpro_groups_settings['create_num_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['create_num_user_roles'] ) ) ){
							$output = '';
						}
					}elseif( $acc_type == 'create_num_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$output = '';
							}
						}
					}
				}else{
					$output = '';
				}
			}
		}
		return $output;
	}

	/**
	 * Filters the HTML button for joining a group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $button HTML button for joining a group.
	 * @param object $group BuddyPress group object
	 */
	public function blpro_bp_get_group_join_button( $button, $group ){
		global $bp;

		$group_id = '';

		$to_check = false;
		if( $button['id'] == 'request_membership' || $button['id'] == 'join_group' ) {
			$to_check = true;
		}
		
		if ( bp_is_groups_directory() ){
			$group_id = $group->id;
		}elseif( bp_is_groups_component() ){
			$group_id = $group->group_id;
		}

		$user_id = $bp->loggedin_user->id;
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );
		$mtypes = ( isset( $blpro_groups_settings['can_join_member_types'] ) )?$blpro_groups_settings['can_join_member_types']:'';
		
		$can_join_limit = true;
		if( isset( $blpro_groups_settings['can_join'] ) && !empty( $blpro_groups_settings['can_join'] ) && !current_user_can('administrator') && $to_check ){
			$can_join = $blpro_groups_settings['can_join'];
			$user_groups = groups_get_user_groups( get_current_user_id() );
			$groups_count = $user_groups['total'];

			if( $groups_count >= $can_join ){
				if( isset( $blpro_groups_settings['can_join_acc'] ) ){
					$acc_type = $blpro_groups_settings['can_join_acc'];
					if(  $acc_type == 'can_join_acc_users' ){
						if( isset( $blpro_groups_settings['can_join_users'] ) && in_array( $user_id, $blpro_groups_settings['can_join_users'] ) ){
							$can_join_limit = false;
						}
					}elseif( $acc_type == 'can_join_acc_roles' ){
						if( isset( $blpro_groups_settings['can_join_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['can_join_user_roles'] ) ) ){
							$can_join_limit = false;
						}
					}elseif( $acc_type == 'can_join_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['can_join_member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['can_join_member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$can_join_limit = false;
							}
						}
					}
				}else{
					$can_join_limit = true;
				}
			}
		}
		
		$member_group_limit = true;
		if( isset( $blpro_groups_settings['member_per_group'] ) && !empty( $blpro_groups_settings['member_per_group'] ) && !current_user_can('administrator') && $to_check ){

			$restrict_groups_count = $blpro_groups_settings['member_per_group'];
			$group_members_count = groups_get_total_member_count( $group_id );

			if( $group_members_count >= $restrict_groups_count ){
				$member_group_limit = false;
			}
		}

		if( !$can_join_limit || !$member_group_limit  ){
			$button = '';
		}
		return $button;
	}

	/**
	 * Filters whether a user can send invites in a group, checks for groups limit.
	 *
	 * @since 1.5.0
	 * @since 2.2.0 Added the $user_id parameter.
	 *
	 * @param bool $can_send_invites Whether the user can send invites
	 * @param int  $group_id         The group ID being checked
	 * @param bool $invite_status    The group's current invite status
	 * @param int  $user_id          The user ID being checked
	 */
	public function blpro_bp_groups_user_can_send_invites( $can_send_invites, $group_id, $invite_status, $user_id ){
		global $bp;
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );

		if( isset( $blpro_groups_settings['member_per_group'] ) && !empty( $blpro_groups_settings['member_per_group'] ) && !current_user_can('administrator') ){

			$restrict_groups_count = $blpro_groups_settings['member_per_group'];
			$group_members_count = groups_get_total_member_count( $group_id );

			if( $group_members_count >= $restrict_groups_count ){
				$can_send_invites = false;
			}
		}

		return $can_send_invites;
	}

	/**
	 * Filters whether a user can accept group invites if the limit reached.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id    ID of the user who accepted the group invite.
	 * @param int $group_id   ID of the group being accepted to.
	 * @param int $inviter_id ID of the user who invited this user to the group.
	 */
	public function blpro_groups_accept_invite( $user_id, $group_id, $inviter_id ){
		
		global $bp;
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );

		$user = get_user_by( bp_displayed_user_id() );
		$mem_type = bp_get_member_type( bp_displayed_user_id() );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_groups_settings['can_join_member_types'] ) )?$blpro_groups_settings['can_join_member_types']:'';

		$can_join_limit = false;
		if( isset( $blpro_groups_settings['can_join'] ) && !empty( $blpro_groups_settings['can_join'] ) && !current_user_can('administrator') ){
			$restrict_groups_count = $blpro_groups_settings['can_join'];
			$user_groups = groups_get_user_groups( bp_displayed_user_id() );
			$groups_count = $user_groups['total'];
			if( $groups_count >= $restrict_groups_count ){
				if( isset( $blpro_groups_settings['can_join_acc'] ) ){
					$acc_type = $blpro_groups_settings['can_join_acc'];
					if(  $acc_type == 'can_join_acc_users' ){
						if( isset( $blpro_groups_settings['can_join_users'] ) && in_array( $user_id, $blpro_groups_settings['can_join_users'] ) ){
							$can_join_limit = true;
						}
					}elseif( $acc_type == 'can_join_acc_roles' ){
						if( isset( $blpro_groups_settings['can_join_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['can_join_user_roles'] ) ) ){
							$can_join_limit = true;
						}
					}elseif( $acc_type == 'can_join_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['can_join_mtypes'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['can_join_mtypes'] ) ) || in_array( 'all', $mtypes ) ) ){
								$can_join_limit = true;
							}
						}
					}
				}else{
					$can_join_limit = true;
				}
			}
		}
		$member_group_limit = false;
		if( isset( $blpro_groups_settings['member_per_group'] ) && !empty( $blpro_groups_settings['member_per_group'] ) && !current_user_can('administrator') ){

			$restrict_groups_count = $blpro_groups_settings['member_per_group'];
			$group_members_count = groups_get_total_member_count( $group_id );

			if( $group_members_count >= $restrict_groups_count ){
				$member_group_limit = true;
			}
		}

		if( $can_join_limit || $member_group_limit ){

			groups_remove_member( $user_id, $group_id );
			bp_core_add_message( __('Group invite could not be accepted as the maximum group joining limit is reached.', 'buddypress-private-community-pro'), 'error' );
			if ( bp_displayed_user_domain() ) {
				$user_domain = bp_displayed_user_domain();
			} elseif ( bp_loggedin_user_domain() ) {
				$user_domain = bp_loggedin_user_domain();
			}
			$slug = bp_get_groups_slug();
			$groups_link = trailingslashit( $user_domain . $slug );

			groups_invite_user( array( 'user_id' => $user_id, 'group_id' => $group_id ) );
			groups_send_invites( bp_loggedin_user_id(), $group_id );
			bp_core_redirect( $groups_link.'/invites' );

		}
	}

	/**
	 * Restriction in create group.
	 *
	 * @since 1.0.0
	 *
	 */
	public function blpro_bp_user_can_create_groups( $can_create, $restricted ){

		if ( bp_current_user_can( 'bp_moderate' ) ) {
			return $can_create = true;
		}

		global $bp;
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );

		$user_id = $bp->loggedin_user->id;
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_groups_settings['create_num_member_types'] ) )?$blpro_groups_settings['create_num_member_types']:'';
		
		if( isset( $blpro_groups_settings['create_num'] ) && !empty( $blpro_groups_settings['create_num'] ) && !current_user_can('administrator') ){
			
			$user_groups = bp_get_user_groups( $user_id, array(
				'is_admin' => true,
			) );
			$groups_count = count( $user_groups );

			$restrict_groups_count = $blpro_groups_settings['create_num'];

			$total_user_groups = groups_get_user_groups( $user_id );
			$total_groups_count = $total_user_groups['total'];

			if( $groups_count >= $restrict_groups_count ||  $total_groups_count >= $restrict_groups_count){
				if( isset( $blpro_groups_settings['create_num_acc'] ) ){
					$acc_type = $blpro_groups_settings['create_num_acc'];
					if(  $acc_type == 'create_num_acc_users' ){
						if( isset( $blpro_groups_settings['create_num_users'] ) && in_array( $user_id, $blpro_groups_settings['create_num_users'] ) ){
							$can_create = false;
						}
					}elseif( $acc_type == 'create_num_acc_roles' ){
						if( isset( $blpro_groups_settings['create_num_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['create_num_user_roles'] ) ) ){
							$can_create = false;
						}
					}elseif( $acc_type == 'create_num_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$can_create = false;
							}
						}
					}
				}else{
					$can_create = false;
				}
				
			}
			
		}

		if( isset( $blpro_groups_settings['can_join'] ) && !empty( $blpro_groups_settings['can_join'] ) && !current_user_can('administrator') ){
			$restrict_groups_count = $blpro_groups_settings['can_join'];
			$user_groups = groups_get_user_groups(  $bp->loggedin_user->id );
			$groups_count = $user_groups['total'];
			if( $groups_count >= $restrict_groups_count ){
				if( isset( $blpro_groups_settings['create_num_acc'] ) ){
					$acc_type = $blpro_groups_settings['create_num_acc'];
					if(  $acc_type == 'create_num_acc_users' ){
						if( isset( $blpro_groups_settings['create_num_users'] ) && in_array( $user_id, $blpro_groups_settings['create_num_users'] ) ){
							$can_create = false;
						}
					}elseif( $acc_type == 'create_num_acc_roles' ){
						if( isset( $blpro_groups_settings['create_num_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['create_num_user_roles'] ) ) ){
							$can_create = false;
						}
					}elseif( $acc_type == 'create_num_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$can_create = false;
							}
						}
					}
				}else{
					$can_create = false;
				}
			}
		}

		return $can_create;
	}

	/**
	 * Restriction in create group tab.
	 *
	 * @since 1.0.0
	 *
	 */
	public function blpro_bp_actions(){
		// If we're not at domain.org/groups/create/ then return false.
		if ( !bp_is_groups_component() || !bp_is_current_action( 'create' ) )
			return false;
		if ( !is_user_logged_in() )
		return false;

		if ( !bp_user_can_create_groups() ) {
			bp_core_add_message( __( 'You have reached the maximum member groups restriction limit.', 'buddypress-private-community-pro' ), 'error' );
			bp_core_redirect( bp_get_groups_directory_permalink() );
		}
	}

	/**
	 * Restriction in compose message.
	 *
	 * @since 1.0.0
	 *
	 */
	public function blpro_restrict_compose_message(){

		if ( !bp_is_current_action( 'compose' ) )
			return false;
		if ( !is_user_logged_in() )
		return false;

		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		$user_id = get_current_user_id();
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';
		if( isset( $blpro_login_settings['lock_bp_act'] ) && isset( $blpro_login_settings['locked_bp_act'] ) && isset( $blpro_login_settings['lock_acc'] ) ) {
			if( in_array( 'private_message', $blpro_login_settings['locked_bp_act'] ) ){

				$acc_type = $blpro_login_settings['lock_acc'];

				if(  $acc_type == 'lock_acc_users' ){
					if( isset( $blpro_login_settings['users'] ) && in_array( $user_id, $blpro_login_settings['users'] ) ){
						bp_core_add_message( __( 'You are not allowed to send any private message.', 'buddypress-private-community-pro' ), 'error' );
						bp_core_redirect( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) );
					}
				}elseif( $acc_type == 'lock_acc_roles' ){
					if( isset( $blpro_login_settings['user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_login_settings['user_roles'] ) ) ){
						bp_core_add_message( __( 'You are not allowed to send any private message.', 'buddypress-private-community-pro' ), 'error' );
						bp_core_redirect( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) );
					}
				}elseif( $acc_type == 'lock_acc_mtypes' ){
					if( is_array( $mtypes ) ){
						if( isset( $blpro_login_settings['member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_login_settings['member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
							bp_core_add_message( __( 'You are not allowed to send any private message.', 'buddypress-private-community-pro' ), 'error' );
							bp_core_redirect( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) );
						}
					}elseif( $mtypes == 'all' ){
						bp_core_add_message( __( 'You are not allowed to send any private message.', 'buddypress-private-community-pro' ), 'error' );
						bp_core_redirect( trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) );
					}
				}
			}
		}

	}

	/**
	 * Filters whether a user can join group.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id    ID of the user who wants to join the group.
	 */
	public function blpro_groups_member_user_id_before_save( $user_id ){
		// Bail if not a POST action.
		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
			return $user_id;
		if( !isset( $_POST['gid'] ) )
			return $user_id;
		// Cast gid as integer.
		$group_id = (int) $_POST['gid'];

		if ( groups_is_user_banned( bp_loggedin_user_id(), $group_id ) )
			return $user_id;

		if ( ! $group = groups_get_group( $group_id ) )
			return $user_id;
		check_ajax_referer( 'groups_join_group' );

		if ( current_user_can( 'administrator' ) ){
			return $user_id;
		}else{
			$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );

			$user = get_user_by($user_id);
			$mem_type = bp_get_member_type( $user_id );
			if( !is_array( $mem_type ) ){
				$mem_type = (array) $mem_type;
			}
			$mtypes = ( isset( $blpro_login_settings['member_types'] ) )?$blpro_login_settings['member_types']:'';

			$user_can_join = isset( $blpro_groups_settings['can_join'] )?$blpro_groups_settings['can_join']:'';
			
			$user_groups = groups_get_user_groups(  $user_id );
			$user_total_groups = $user_groups['total'];

			if( $user_total_groups >= $user_can_join ){
				if( isset( $blpro_groups_settings['can_join_acc'] ) ){
					$acc_type = $blpro_groups_settings['can_join_acc'];
					if(  $acc_type == 'can_join_acc_users' ){
						if( isset( $blpro_groups_settings['can_join_users'] ) && in_array( $user_id, $blpro_groups_settings['can_join_users'] ) ){
							$user_id = 0;
						}
					}elseif( $acc_type == 'can_join_acc_roles' ){
						if( isset( $blpro_groups_settings['can_join_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['can_join_user_roles'] ) ) ){
							$user_id = 0;
						}
					}elseif( $acc_type == 'can_join_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['can_join_member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['can_join_member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$user_id = 0;
							}
						}
					}
				}else{
					$user_id = 0;
				}
				
			}

		}
		return $user_id;
	}

	public function blpro_override_members_home_file( $visib ){
		
		global $bp;
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		if( $bp->loggedin_user->id != $bp->displayed_user->id && !current_user_can('administrator') && isset( $blpro_login_settings['prof_visib'] ) ){

			$blpro_prof_visib = get_user_meta( $bp->displayed_user->id, 'blpro_profile_page_visibility', true );

			if( bp_is_active( 'friends' ) ){
				$friend_status = friends_check_friendship_status( $bp->loggedin_user->id, $bp->displayed_user->id );
			}else{
				$friend_status = 'none';
			}
			
			switch ( $blpro_prof_visib ) {
				case 'public':
				$visib = true;
				break;
				case 'adminsonly':
				if( !bp_is_my_profile() ){
					$visib = false;
				}
				break;
				case 'loggedin':
				if( !is_user_logged_in() ){
					$visib = false;
				}else{
					$visib = true;
				}
				break;
				case 'friends':
				if ( $friend_status == 'is_friend' || $bp->loggedin_user->id == $bp->displayed_user->id ) {
					$visib = true;
				}else{
					$visib = false;
				}
				break;
				default:
				$visib = true;
				break;
			}
		}else{
			$visib = true;
		}
		return $visib;
	}

	public function blpro_profile_visility_home_override_plugin_code(){
		$this->blpro_privacy_lock_setting_check();
	}

	function blpro_privacy_lock_setting_check(){

		global $bp;
		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		if( $bp->loggedin_user->id != $bp->displayed_user->id && !current_user_can('administrator') && isset( $blpro_login_settings['prof_visib'] ) ){

			$blpro_prof_visib = get_user_meta( $bp->displayed_user->id, 'blpro_profile_page_visibility', true );

			if( bp_is_active( 'friends' ) ) {
				$friend_status = friends_check_friendship_status( $bp->loggedin_user->id, $bp->displayed_user->id );
			}else{
				$friend_status = 'none';
			}
			
			switch ( $blpro_prof_visib ) {
				case 'public':
				//blpro_buddypress_defined_content();
				break;
				case 'adminsonly':
				if( !bp_is_my_profile() ){
					echo "<div class='blpro-private'><i class='fa fa-lock'></i>".esc_html__('Private','buddypress-private-community-pro')."</div><div id='message' class='info'><p>".sprintf( esc_html__( '%s has kept the profile private.', 'buddypress-private-community-pro' ), $bp->displayed_user->userdata->display_name )."</p></div>";
				}
				break;
				case 'loggedin':
				if( !is_user_logged_in() ){
					echo "<div class='blpro-private'><i class='fa fa-lock'></i>".esc_html__('Private','buddypress-private-community-pro')."</div><div id='message' class='info'><p>".sprintf( esc_html__( 'You must be logged in order to view %s profile.', 'buddypress-private-community-pro' ), $bp->displayed_user->userdata->display_name )."</p></div>";
				}else{
					//blpro_buddypress_defined_content();
				}
				break;
				case 'friends':
				if ( $friend_status == 'is_friend' || $bp->loggedin_user->id == $bp->displayed_user->id ) {
					//blpro_buddypress_defined_content();
				}else{
					echo "<div class='blpro-private'><i class='fa fa-lock'></i>".esc_html__('Private','buddypress-private-community-pro')."</div><div id='message' class='info'><p>".sprintf( esc_html__( 'You must be Friends in order to access %s profile.', 'buddypress-private-community-pro' ), $bp->displayed_user->userdata->display_name )."</p></div>";
					bp_add_friend_button();
				}
				break;
				default:
				//blpro_buddypress_defined_content();
				break;
			}
		}else{
			//blpro_buddypress_defined_content();
		}

	}

	public function blpro_remove_ajax_join_group_request() {
		global $bp;
		$group_id = '';

		if ( bp_is_groups_directory() ){
			$group_id = $group->id;
		}elseif( bp_is_groups_component() ){
			$group_id = $group->group_id;
		}

		$user_id = $bp->loggedin_user->id;
		$user = wp_get_current_user();
		$mem_type = bp_get_member_type( $user_id );
		if( !is_array( $mem_type ) ){
			$mem_type = (array) $mem_type;
		}
		$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );
		$mtypes = ( isset( $blpro_groups_settings['can_join_member_types'] ) )?$blpro_groups_settings['can_join_member_types']:'';
		
		$can_join_limit = true;
		if( isset( $blpro_groups_settings['can_join'] ) && !empty( $blpro_groups_settings['can_join'] ) && !current_user_can('administrator') ){
			$can_join = $blpro_groups_settings['can_join'];
			$user_groups = groups_get_user_groups( get_current_user_id() );
			$groups_count = $user_groups['total'];

			if( $groups_count >= $can_join ){
				if( isset( $blpro_groups_settings['can_join_acc'] ) ){
					$acc_type = $blpro_groups_settings['can_join_acc'];
					if(  $acc_type == 'can_join_acc_users' ){
						if( isset( $blpro_groups_settings['can_join_users'] ) && in_array( $user_id, $blpro_groups_settings['can_join_users'] ) ){
							$can_join_limit = false;
						}
					}elseif( $acc_type == 'can_join_acc_roles' ){
						if( isset( $blpro_groups_settings['can_join_user_roles'] ) && !empty(array_intersect( $user->roles, $blpro_groups_settings['can_join_user_roles'] ) ) ){
							$can_join_limit = false;
						}
					}elseif( $acc_type == 'can_join_acc_mtypes' ){
						if( is_array( $mtypes ) ){
							if( isset( $blpro_groups_settings['can_join_member_types'] ) && (!empty(array_intersect( $mem_type, $blpro_groups_settings['can_join_member_types'] ) ) || in_array( 'all', $mtypes ) ) ){
								$can_join_limit = false;
							}
						}
					}
				}else{
					$can_join_limit = true;
				}
			}
		}
		
		$member_group_limit = true;
		if( isset( $blpro_groups_settings['member_per_group'] ) && !empty( $blpro_groups_settings['member_per_group'] ) && !current_user_can('administrator') ){

			$restrict_groups_count = $blpro_groups_settings['member_per_group'];
			$group_members_count = groups_get_total_member_count( $group_id );

			if( $group_members_count >= $restrict_groups_count ){
				$member_group_limit = false;
			}
		}

		if( !$can_join_limit || !$member_group_limit  ){

			if ( !empty( $_POST['action'] ) && 'groups_join_group' == $_POST['action'] ) {
				$response = array(
					'feedback' => sprintf(
						'<div class="bp-feedback error"><span class="bp-icon" aria-hidden="true"></span><p>%s</p></div>',
						esc_html__( 'You reached the maximum group joining limit.', 'buddypress' )
					),
				);
				wp_send_json_error( $response );
			}
			//remove_action( 'wp_ajax_groups_join_group', 'bp_nouveau_ajax_joinleave_group' );
		}
	}

	public function blpro_hide_desired_members_primary_nav(){
		
		global $bp;
		$blpro_settings = bp_get_option( 'blpro_nl_settings' );
		
		if( is_array( $blpro_settings ) && isset( $blpro_settings['primary_nav'] ) ){
			foreach ( $blpro_settings['primary_nav'] as $key => $nav_slug ) {
				if ( !is_user_logged_in() ) {
					 bp_core_remove_nav_item( $nav_slug );
					//$bp->members->nav->edit_nav( array( 'user_has_access' => false ), $nav_slug );
				}	
			}
		}

		$blpro_login_settings = bp_get_option( 'blpro_login_settings' );
		if( is_array( $blpro_login_settings ) && isset( $blpro_login_settings['primary_nav'] ) ){
			foreach ( $blpro_login_settings['primary_nav'] as $key => $nav_slug ) {
				if ( bp_is_user() ) {
					 bp_core_remove_nav_item( $nav_slug );
					//$bp->members->nav->edit_nav( array( 'user_has_access' => false ), $nav_slug );
				}	
			}
		}
		// if( is_array( $blpro_settings ) && isset( $blpro_settings['secondary_nav'] ) ){
		// 	foreach ( $blpro_settings['secondary_nav'] as $key => $parent_slug ) {
		// 		foreach ($parent_slug as $_key => $sub_nav_slug) {
		// 			if ( bp_is_user() && !is_super_admin() ) {
		// 				if( $sub_nav_slug != 'just-me' ){
		// 					bp_core_remove_subnav_item( $key, $sub_nav_slug );
		// 				}
						
		// 			}
		// 		}	
		// 	}
		// }
		
	}

}