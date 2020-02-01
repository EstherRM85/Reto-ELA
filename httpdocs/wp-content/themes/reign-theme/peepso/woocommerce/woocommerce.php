<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
?>
<div class="peepso ps-page-profile">
	<?php PeepSoTemplate::exec_template('general','navbar'); ?>

	<?php PeepSoTemplate::exec_template('profile','focus', array( 'current' => $current ) ); ?>

	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
		<section id="component" role="article" class="ps-clearfix">
			<?php
            if ( get_current_user_id() ) {
            	?>
				<div class="wbpwi-peepo-woo-wrapper">
					<?php if ( 'endpoint' === $type ) { ?>
						<div class="woocommerce">
							<?php wc_print_notices(); ?>
							<div class="woocommerce-MyAccount-content">
								<?php
								if ( has_action( 'woocommerce_account_' . $endpoint_key . '_endpoint' ) ) {
									do_action( 'woocommerce_account_' . $endpoint_key . '_endpoint', $endpoint_val );
								}
								?>
							</div>
						</div>
					<?php } elseif ( 'cart' === $type ) {
						wc_print_notices();
						echo do_shortcode( '[woocommerce_cart]' );
					} elseif ( 'checkout' === $type ) {
						wc_print_notices();
						if ( is_page( wc_get_page_id( 'checkout' ) ) && wc_get_page_id( 'checkout' ) !== wc_get_page_id( 'cart' ) && WC()->cart->is_empty() && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ) {
							echo do_shortcode( '[woocommerce_cart]' );
						} else {
							echo do_shortcode( '[woocommerce_checkout]' );
						}		
					} elseif ( 'order_tracking' === $type ) {
						wc_print_notices();
						echo do_shortcode( '[woocommerce_order_tracking]' );
						wc_enqueue_js( 'jQuery( ".wbpwi-peepo-woo-wrapper .track_order" ).attr("action","");' );
					}
					?>
				</div>
			<?php	
            } else {
                PeepSoTemplate::exec_template( 'general', 'login-profile-tab' );
            } ?>
		</section><!--end component-->
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
	</section><!--end mainbody-->
</div><!--end row-->
<?php PeepSoTemplate::exec_template('activity','dialogs'); ?>