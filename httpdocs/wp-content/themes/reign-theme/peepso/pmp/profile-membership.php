<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}

$user = PeepSoUser::get_instance(PeepSoProfileShortcode::get_instance()->get_view_user_id());
if( get_current_user_id() != $user->get_id()) {
    PeepSo::redirect($user->get_profileurl());
}
?>
    <div class="peepso ps-page-profile">
        <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

        <?php PeepSoTemplate::exec_template('profile', 'focus', array('current'=>'pmp')); ?>

        <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
            <?php
            if ( 'inside' !== $header_position ) {
                do_action( 'wbcom_before_content_section' );
            }
            ?>
            <section id="component" role="article" class="ps-clearfix">
                <?php
                echo do_shortcode('[pmpro_account]');
                ?>
            </section>
            <?php
            if ( 'inside' !== $header_position ) {
                do_action( 'wbcom_after_content_section' );
            }
            ?>
        </section>
    </div>

<?php PeepSoTemplate::exec_template('activity', 'dialogs'); ?>