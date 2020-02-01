<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}

$PeepSoUrlSegments   = PeepSoUrlSegments::get_instance();
$key                 = $PeepSoUrlSegments->get( 2 );
$key_view            = $PeepSoUrlSegments->get( 3 );
$current             = $key;
$view_order_endpoint = get_option( 'woocommerce_myaccount_view_order_endpoint', 'view-order' );
if ( $key_view == $view_order_endpoint ) {
	$key     = 'view-order';
	$current = 'orders';
}

if ( ( $key_view ) && ! empty( $PeepSoUrlSegments->get( 4 ) ) ) {
	$value = $PeepSoUrlSegments->get( 4 );
} else {
	$value = '';
}

$user = PeepSoUser::get_instance(PeepSoProfileShortcode::get_instance()->get_view_user_id());

$can_edit = FALSE;
if($user->get_id() == get_current_user_id() || current_user_can('edit_users')) {
	$can_edit = TRUE;
}

?>			

<div class="peepso ps-page-profile">
	<?php PeepSoTemplate::exec_template( 'general', 'navbar' ); ?>
	<?php PeepSoTemplate::exec_template( 'profile', 'focus', array( 'current' => $current ) ); ?>
	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
	    if ( 'inside' !== $header_position ) {
	        do_action( 'wbcom_before_content_section' );
	    }
	    ?>
		<section id="component" role="article" class="clearfix">
			<?php if($can_edit) { PeepSoTemplate::exec_template('profile', 'profile-wc-order-tabs', array('tabs'=>$tabs, 'current_tab'=> $current));} ?>

			<div class="wbpwi-peepo-woo-wrapper">
				<div class="woocommerce">
					<?php wc_print_notices(); ?>
					<div class="woocommerce-MyAccount-content">
						<?php
						if ( has_action( 'woocommerce_account_' . $key . '_endpoint' ) ) {
							do_action( 'woocommerce_account_' . $key . '_endpoint', $value );
						}
						?>
						</div>
					</div>
				</div>
		</section><!--end component-->
		<?php
	    if ( 'inside' !== $header_position ) {
	        do_action( 'wbcom_after_content_section' );
	    }
	    ?>
	</section><!--end mainbody-->
</div><!--end row-->