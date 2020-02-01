<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'RTM_EDD_Customization' ) ) :

/**
 * @class RTM_EDD_Customization
 */
class RTM_EDD_Customization {
	
	/**
	 * The single instance of the class.
	 *
	 * @var RTM_EDD_Customization
	 */
	protected static $_instance = null;
	
	/**
	 * Main RTM_EDD_Customization Instance.
	 *
	 * Ensures only one instance of RTM_EDD_Customization is loaded or can be loaded.
	 *
	 * @return RTM_EDD_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * RTM_EDD_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		/**
		* Backend option management in PMPRO setting section.
		*/
		add_action( 'pmpro_membership_level_after_other_settings', array( $this, 'rtm_pmpro_add_custom_settings' ), 15 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_color_picker' ) );
		add_action( 'admin_footer', array( $this, 'enqueue_custom_inline_js_code' ), 999 );
		add_action( 'pmpro_save_membership_level', array( $this, 'rtm_pmpro_save_custom_settings' ), 10, 1 );

		/**
		* Frontend design management.
		*/
		add_filter( 'pmpro_format_price', array( $this, 'rtm_pmpro_format_price' ), 10, 4 );
		add_filter( 'pmpro_level_cost_text', array( $this, 'rtm_pmpro_level_cost_text' ), 10, 4 );

		/**
		* Adding setting option to theme customizer.
		*/
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
	}


	public function rtm_pmpro_level_cost_text( $r, $level, $tags, $short ) {
		// initial payment
		if ( ! $short ) {
			$r = sprintf( __( 'The price for membership is <strong>%s</strong> now', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ) );
		} else {
			$r = sprintf( __( '<div class="rtm-pmpro-now-price">%s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ) );
		}

		// recurring part
		if ( $level->billing_amount != '0.00' ) {
			if ( $level->billing_limit > 1 ) {
				if ( $level->cycle_number == '1' ) {
					$r .= sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s per %2$s for %3$d more %4$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->billing_amount ), pmpro_translate_billing_period( $level->cycle_period ), $level->billing_limit, pmpro_translate_billing_period( $level->cycle_period, $level->billing_limit ) );
				} else {
					$r .= sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s every %2$d %3$s for %4$d more payments</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ), $level->billing_limit );
				}
			} elseif ( $level->billing_limit == 1 ) {
				$r .= sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s after %2$d %3$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
			} else {
				if ( $level->billing_amount === $level->initial_payment ) {
					if ( $level->cycle_number == '1' ) {
						if ( ! $short ) {
							$r = sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s per %2$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ), pmpro_translate_billing_period( $level->cycle_period ) );
						} else {
							$r = sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s per %2$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ), pmpro_translate_billing_period( $level->cycle_period ) );
						}
					} else {
						if ( ! $short ) {
							$r = sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s every %2$d %3$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
						} else {
							$r = sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s every %2$d %3$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->initial_payment ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
						}
					}
				} else {
					if ( $level->cycle_number == '1' ) {
						$r .= sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s per %2$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->billing_amount ), pmpro_translate_billing_period( $level->cycle_period ) );
					} else {
						$r .= sprintf( __( '<div class="rtm-pmpro-recurring-price">%1$s every %2$d %3$s</div>', 'paid-memberships-pro' ), pmpro_formatPrice( $level->billing_amount ), $level->cycle_number, pmpro_translate_billing_period( $level->cycle_period, $level->cycle_number ) );
					}
				}
			}
		} else {
			// $r .= '.';
		}

		// add a space
		$r .= ' ';
		return $r;
	}


	public function rtm_pmpro_format_price( $formatted, $price, $pmpro_currency, $pmpro_currency_symbol ) {
		$formatted = str_replace( $pmpro_currency_symbol, '<span class="rtm-pmpro-currency">' . $pmpro_currency_symbol . '</span>', $formatted );
		return $formatted;
	}


	public function rtm_pmpro_save_custom_settings( $saveid ) {
		if( isset( $_POST['rtm_pmpro_customization'] ) ) {
			$rtm_pmpro_customization = get_option( 'rtm_pmpro_customization', array() );
			$rtm_pmpro_customization[$saveid] = $_POST['rtm_pmpro_customization'];
			update_option( 'rtm_pmpro_customization', $rtm_pmpro_customization );
		}
	}

	public function enqueue_custom_inline_js_code() {
		if( is_admin() && isset( $_GET['page'] ) && ( $_GET['page'] == 'pmpro-membershiplevels' ) ) {
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					jQuery( '.rtm-pmpro-color-picker' ).wpColorPicker();
				} );
			</script>
			<?php
		}
	}

	
	public function enqueue_color_picker( $hook ) {
		if( is_admin() && isset( $_GET['page'] ) && ( $_GET['page'] == 'pmpro-membershiplevels' ) ) { 
			wp_enqueue_script( 'wp-color-picker' );
        	wp_enqueue_style( 'wp-color-picker' );
        }
	}

	
	public function rtm_pmpro_add_custom_settings() {
		$level_id = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
		$rtm_pmpro_customization = get_option( 'rtm_pmpro_customization', array() );
		$bg_color = isset( $rtm_pmpro_customization[$level_id]['bg_color'] ) ? $rtm_pmpro_customization[$level_id]['bg_color'] : '';
		$is_featured = isset( $rtm_pmpro_customization[$level_id]['is_featured'] ) ? 'checked="checked"' : '';
		?>
		<h3 class="topborder">
			<?php _e( 'Reign Custom Settings', 'paid-memberships-pro' ); ?>
		</h3>
		<p>
			<?php _e( 'This setting is used to enhance the appearance of membership level on frontend.', 'paid-memberships-pro' ); ?>
		</p>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" valign="top">
						<label>
							<?php _e( 'Color', 'paid-memberships-pro' );?>
						</label>
						<p>
							<?php _e( 'This color will be used to color the membership level block at frontend.', 'paid-memberships-pro' );?>
						</p>
					</th>
					<td>
						<input type="text" class="rtm-pmpro-color-picker" name="rtm_pmpro_customization[bg_color]" value="<?php echo $bg_color; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label>
							<?php _e( 'Is Featured ?', 'paid-memberships-pro' )?>
						</label>
						<p>
							<?php _e( 'This option can be used to mark a membership label as featured.', 'paid-memberships-pro' );?>
						</p>
					</th>
					<td>
						<input type="checkbox" name="rtm_pmpro_customization[is_featured]" <?php echo $is_featured; ?> />
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}


	public function add_panels_and_sections( $wp_customize ) {
		$wp_customize->add_section(
			'reign_pmpro_support',
			array(
				'title'       => __( 'Post Membership Pro', 'reign' ),
				'priority'    => 10,
				'panel'       => 'reign_plugin_support_panel',
				'description' => '',
			)
		);
	}

	public function add_fields( $fields ) {

		$fields[] = array(
			'type'        => 'select',
			'settings'    => 'reign_pmpro_layout',
			'label'       => esc_attr__( 'Membership Levels Layout', 'reign' ),
			'description'       => esc_attr__( 'Select your membership level layout here.', 'reign' ),
			'section'     => 'reign_pmpro_support',
			'default'  => 'default',
			'priority'    => 10,
			'choices'     => array(
				'default' => esc_attr__( 'Default Layout', 'reign' ),
				'multicolor' => esc_attr__( 'Multi-Color Layout', 'reign' ),
			),
		);

		$fields[] = array(
			'type'        => 'select',
			'settings'    => 'reign_pmpro_per_row',
			'label'       => esc_attr__( 'Membership Levels Per Row', 'reign' ),
			'description'       => esc_attr__( 'Select your membership level to show per row.', 'reign' ),
			'section'     => 'reign_pmpro_support',
			'default'  => '3-col-layout',
			'priority'    => 10,
			'choices'     => array(
				'3-col-layout' => esc_attr__( '3 Column Layout', 'reign' ),
				'4-col-layout' => esc_attr__( '4 Column Layout', 'reign' ),
			),
		);

		return $fields;
	}

}

endif;

/**
 * Main instance of RTM_EDD_Customization.
 * @return RTM_EDD_Customization
 */
RTM_EDD_Customization::instance();