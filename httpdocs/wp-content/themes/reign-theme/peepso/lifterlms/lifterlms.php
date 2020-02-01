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
	<?php PeepSoTemplate::exec_template( 'general', 'navbar' ); ?>
	<?php PeepSoTemplate::exec_template( 'profile', 'focus', array( 'current' => $main_slug ) );
	$PeepSoUser = PeepSoUser::get_instance( PeepSoProfileShortcode::get_instance()->get_view_user_id() );
    ?>
	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
		<section id="component" role="article" class="clearfix">
			<div class="ps-tabs__wrapper">
                <div class="ps-tabs ps-tabs--arrows">
                <?php
	                foreach( $submenus as $key => $tab ) {
	                    ?>
			            <div class="ps-tabs__item <?php if ( $key == $current_tab ) echo "current"; ?>">
			            	<a href="<?php echo $tab['link']; ?>" class="<?php echo $tab['icon']; ?>" title="<?php echo $tab['label']; ?>" ><span class="peepso-lifter-lms-link"><?php echo $tab['label']; ?></span></a>
			            </div>
	                    <?php
	                }
	            ?>
                </div>
            </div>    	
			<div class="peepso-lifterlms-wrapper">
				<?php
				do_action( 'lifterlms_before_student_dashboard' );
				if( 'view-certificates' === $current_tab ) {
					lifterlms_template_student_dashboard_my_certificates();
				}
				elseif( 'view-memberships' === $current_tab ) {
					lifterlms_template_student_dashboard_my_memberships();
				}
				else {
					call_user_func( $content_shortcode );
				}
				do_action( 'lifterlms_after_student_dashboard' );
				?>
			</div>
		</section><!--end component-->
		<?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
	</section><!--end mainbody-->
</div><!--end row-->
<style type="text/css">
	.peepso-lifterlms-wrapper .llms-notification {
		z-index: 10;
	}
</style>