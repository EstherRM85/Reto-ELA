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
				do_action( 'bbp_before_main_content' ); ?>

				<div id="bbp-user-<?php bbp_displayed_user_id(); ?>" class="bbp-single-user wbpbi-bbpress-page">
					<div class="entry-content">

						<?php bbp_get_template_part( 'content', 'single-user' ); ?>

					</div>
				</div>

				<?php do_action( 'bbp_after_main_content' );
            } else {
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