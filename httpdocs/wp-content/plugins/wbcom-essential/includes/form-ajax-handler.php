<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'WBCOM_Elementor_Form_AJAX_Handler' ) ) :
/**
 * Main WBCOM_Elementor_Form_AJAX_Handler Class.
 *
 * @class WBCOM_Elementor_Form_AJAX_Handler
 */
class WBCOM_Elementor_Form_AJAX_Handler {
	/**
	 * The single instance of the class.
	 */
	protected static $_instance = null;
	/**
	 * Main WBCOM_Elementor_Form_AJAX_Handler Instance.
	 *
	 * Ensures only one instance of WBCOM_Elementor_Form_AJAX_Handler is loaded or can be loaded.
	 *
	 * @return WBCOM_Elementor_Form_AJAX_Handler - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	/**
	 * WBCOM_Elementor_Form_AJAX_Handler Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}
	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_wbcom_process_elementor_login_form', array( $this, 'wbcom_process_elementor_login_form' ) );
		add_action( 'wp_ajax_nopriv_wbcom_process_elementor_login_form', array( $this, 'wbcom_process_elementor_login_form' ) );
	}
	public function wbcom_process_elementor_login_form() {
		$result_to_output = array();
		if( isset( $_POST['serialize_form_data'] ) && !empty( $_POST['serialize_form_data'] ) ) {
			if( isset( $_POST['action_to_to'] ) && ( $_POST['action_to_to'] == 'login' ) ) {
				$serialize_form_data = $_POST['serialize_form_data'];
				parse_str( $serialize_form_data, $form_data );
				$credentials = array(
					'user_login'	=>	isset( $form_data['log'] ) ? ( $form_data['log'] ) : '',
					'user_password'	=>	isset( $form_data['pwd'] ) ? ( $form_data['pwd'] ) : '',
					'remember'	=>	isset( $form_data['rememberme'] ) ? ( $form_data['rememberme'] ) : '',
				);
				$output = wp_signon( $credentials );
				if ( is_wp_error( $output ) ) {
					$error_reason = '';
					foreach ( $output->errors as $key => $value ) {
						foreach ( $value as $_key => $_value ) {
							$error_reason = $_value;
							break;
						}
						if( !empty( $error_reason ) ) {
							break;
						}
					}
					$result_to_output = array(
						'key'	=>	'error',
						'data'	=>	$error_reason
					);
					echo json_encode( $result_to_output );
				}
				else if( isset( $output->ID ) ) {
					$result_to_output = array(
						'key'	=>	'success',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
				else {
					$result_to_output = array(
						'key'	=>	'unexpected_condition',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
			}
			else if( isset( $_POST['action_to_to'] ) && ( $_POST['action_to_to'] == 'forgot_password' ) ) {
				$serialize_form_data = sanitize_text_field( $_POST['serialize_form_data'] );
				parse_str( $serialize_form_data, $form_data );
				$user_login = isset( $form_data['log'] ) ? sanitize_text_field( $form_data['log'] ) : '';
				$output = $this->retrieve_password( $user_login );
				if ( is_wp_error( $output ) ) {
					$error_reason = '';
					foreach ( $output->errors as $key => $value ) {
						foreach ( $value as $_key => $_value ) {
							$error_reason = $_value;
							break;
						}
						if( !empty( $error_reason ) ) {
							break;
						}
					}
					$result_to_output = array(
						'key'	=>	'error',
						'data'	=>	$error_reason
					);
					echo json_encode( $result_to_output );
				}
				else if( $output ) {
					$result_to_output = array(
						'key'	=>	'success',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
				else {
					$result_to_output = array(
						'key'	=>	'unexpected_condition',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
			}
			else if( isset( $_POST['action_to_to'] ) && ( $_POST['action_to_to'] == 'registration' ) ) {
				$serialize_form_data = $_POST['serialize_form_data'];
				parse_str( $serialize_form_data, $form_data );
				$user_login = isset( $form_data['log'] ) ? sanitize_text_field( $form_data['log'] ) : '';
				$user_email = isset( $form_data['email_addr'] ) ? $form_data['email_addr'] : '';
				$output = register_new_user( $user_login, $user_email );
				if ( is_wp_error( $output ) ) {
					$error_reason = '';
					foreach ( $output->errors as $key => $value ) {
						foreach ( $value as $_key => $_value ) {
							$error_reason = $_value;
							break;
						}
						if( !empty( $error_reason ) ) {
							break;
						}
					}
					$result_to_output = array(
						'key'	=>	'error',
						'data'	=>	$error_reason
					);
					echo json_encode( $result_to_output );
				}
				else if( $output ) {
					$result_to_output = array(
						'key'	=>	'success',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
				else {
					$result_to_output = array(
						'key'	=>	'unexpected_condition',
						'data'	=>	''
					);
					echo json_encode( $result_to_output );
				}
			}
		}
		wp_die();
	}
	public function retrieve_password( $user_login = '' ) {
		$errors = new WP_Error();
		if ( empty( $user_login ) ) {
			$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.', 'wbcom-essential' ));
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
			if ( empty( $user_data ) )
				$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.', 'wbcom-essential' ));
		} else {
			$login = trim($user_login);
			$user_data = get_user_by('login', $login);
		}
		/**
		 * Fires before errors are returned from a password reset request.
		 *
		 * @since 2.1.0
		 * @since 4.4.0 Added the `$errors` parameter.
		 *
		 * @param WP_Error $errors A WP_Error object containing any errors generated
		 * by using invalid credentials.
		 */
		do_action( 'lostpassword_post', $errors );
		if ( $errors->get_error_code() )
			return $errors;
		if ( !$user_data ) {
			$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.', 'wbcom-essential' ));
			return $errors;
		}
		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key = get_password_reset_key( $user_data );
		if ( is_wp_error( $key ) ) {
			return $key;
		}
		$message = __('Someone has requested a password reset for the following account:', 'wbcom-essential' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s', 'wbcom-essential' ), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'wbcom-essential') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:', 'wbcom-essential') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
		if ( is_multisite() ) {
			$blogname = get_network()->site_name;
		} else {
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		}
		/* translators: Password reset email subject. 1: Site name */
		$title = sprintf( __('[%s] Password Reset','wbcom-essential'), $blogname );
		/**
		 * Filters the subject of the password reset email.
		 *
		 * @since 2.8.0
		 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @param string $title Default email title.
		 * @param string $user_login The username for the user.
		 * @param WP_User $user_data WP_User object.
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
		/**
		 * Filters the message body of the password reset mail.
		 *
		 * If the filtered message is empty, the password reset email will not be sent.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 *
		 * @param string $message Default mail message.
		 * @param string $key The activation key.
		 * @param string $user_login The username for the user.
		 * @param WP_User $user_data WP_User object.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
		if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) )
			wp_die( __('The email could not be sent.','wbcom-essential') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.','wbcom-essential') );
		return true;
	}
}
endif;
WBCOM_Elementor_Form_AJAX_Handler::instance();