<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
$user    = PeepSoUser::get_instance( $displayed_user );
$options = PeepSoConfigSettings::get_instance();
?>
<div class="peepso ps-page-profile">
	<?php PeepSoTemplate::exec_template( 'general', 'navbar' ); ?>
	<?php PeepSoTemplate::exec_template( 'profile', 'focus', array( 'current' => $main_tab ) ); ?>
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
                	foreach ( $submenus as $key => $value ) {
						$enable = $options->get_option( 'wpforo_enable_' . $key );
						$enable = apply_filters( 'wpforo_show_tab_on_peepso_profile', $enable, $key, $submenus );
						if ( $enable ) { ?>
							<div class="ps-tabs__item <?php if( $key === $current) echo 'current' ?>"><a href="<?php echo $user->get_profileurl(). $main_tab . '/'. $value['href']; ?>"><?php echo esc_html( $value['label'] ); ?></a></div>
						<?php }
					}
					?>
                   
                </div>
            </div>
			<div class="wbpwpforoi-peepo-wpforo-wrapper">
			<?php
				include_once wpftpl( 'index.php' );
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