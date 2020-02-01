<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
?>
<div class="peepso ps-page-profile ps-page--badgeos">
	<?php PeepSoTemplate::exec_template('general','navbar'); ?>

	<?php PeepSoTemplate::exec_template('profile', 'focus', array('current'=>'badges')); ?>

	<section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
		<?php
	    if ( 'inside' !== $header_position ) {
	        do_action( 'wbcom_before_content_section' );
	    }
	    ?>
		<section id="component" role="article" class="ps-clearfix">
            <div class="ps-clearfix mb-20"></div>
			<!-- <div class="ps-clearfix ps-groups ps-js-groups ps-js-groups--<?php echo apply_filters('peepso_user_profile_id', 0); ?>"></div>
			<div class="ps-groups-scroll ps-js-groups-triggerscroll ps-js-groups-triggerscroll--<?php echo apply_filters('peepso_user_profile_id', 0); ?>">
				<img class="post-ajax-loader ps-js-groups-loading" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" style="display:none" />
			</div> -->
			<?php echo $list_badges;?>

		</section><!--end component-->
		<?php
	    if ( 'inside' !== $header_position ) {
	        do_action( 'wbcom_after_content_section' );
	    }
	    ?>
	</section><!--end mainbody-->
</div><!--end row-->
<?php PeepSoTemplate::exec_template('activity', 'dialogs'); ?>
