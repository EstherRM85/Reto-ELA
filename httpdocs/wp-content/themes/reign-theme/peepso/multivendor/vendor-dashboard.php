<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}

$displayed_user = PeepSoProfileShortcode::get_instance()->get_view_user_id();
$user           = PeepSoUser::get_instance( $displayed_user );
$peepso_user    = PeepSoUser::get_instance( PeepSoProfileShortcode::get_instance()->get_view_user_id() );
?>
<div class="peepso ps-page-profile">
	<?php PeepSoTemplate::exec_template('general','navbar'); ?>

	<?php PeepSoTemplate::exec_template('profile','focus', array( 'current' => 'vendor-dashboard' ) ); ?>

	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
		<section id="component" role="article" class="clearfix <?php echo esc_attr( $template_class ); ?>">
			<div class="ps-tabs__wrapper">
                <div class="ps-tabs ps-tabs--arrows">
                	<?php
                	if ( get_current_user_id() === $peepso_user->get_id() ) {
	                	foreach ( $submenus as $key => $value ) {
							?>
							<div class="ps-tabs__item <?php if( $key === $current ) echo 'current' ?>"><a href="<?php echo $user->get_profileurl(). 'vendor-dashboard/'. $key; ?>"><?php echo esc_html( $value['label'] ); ?></a></div>
						<?php
						}
					}	
					?>
                </div>
            </div>
			<?php
            if ( get_current_user_id() === $peepso_user->get_id() ) { ?>
				<div class="wbpm-peepo-multivendor-wrapper">
					<?php
					switch( $current ) {
						case 'dashboard' : dokan_get_template_part( 'dashboard/dashboard' );
		         			break;
						case 'products' : wbpm_peepso_multivendor_get_template_part( 'products-listing', '', '' );
		                    break;
			            case 'favorite-products' :wbpm_peepso_multivendor_get_template_part( 'favorite-products', '', '' );
		                    break;
			            case 'commission' :wbpm_peepso_multivendor_get_template_part( 'vendor-commission' );
					        break;
			            case 'report' : dokan_get_template_part( 'report/reports', '', array( 'pro' => true ) );
						    break;
			            case 'order-received' :wbpm_peepso_multivendor_get_template_part( 'vendor-orders-listing' );
						    break;
			            default : $current_template = dokan_get_template_part( 'dashboard/dashboard' );
					}			
					?>
				</div>
            <?php } else {
                PeepSoTemplate::exec_template('general','login-profile-tab');
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