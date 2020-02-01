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
    <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

    <?php PeepSoTemplate::exec_template('profile', 'focus', array('current'=>'photos')); ?>

    <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
        <section id="component" role="article" class="ps-clearfix">
            <div class="ps-page__actions">
                <a class="ps-btn ps-btn-small" href="<?php echo $photos_url; ?>"><i class="ps-icon-angle-left"></i> <?php echo __('Back to Photos', 'picso'); ?></a>
            </div>
            <h4 class="ps-page-title">
                <?php echo sprintf (__('%s Album', 'picso'), __($the_album->pho_album_name, 'picso')); ?>
            </h4>
            <div class="ps-page-filters">
                <select class="ps-select ps-full ps-js-photos-sortby">
                    <option value="desc"><?php _e('Newest first', 'picso');?></option>
                    <option value="asc"><?php _e('Oldest first', 'picso');?></option>
                </select>
            </div>

            <div class="ps-clearfix mb-20"></div>
            <div class="ps-photos ps-js-photos ps-js-photos--<?php echo  apply_filters('peepso_user_profile_id', 0); ?>"></div>
            <div class="ps-scroll ps-js-photos-triggerscroll ps-js-photos-triggerscroll--<?php echo  apply_filters('peepso_user_profile_id', 0); ?>">
                <img class="post-ajax-loader ps-js-photos-loading" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" style="display:none" />
            </div>
            <div class="ps-clearfix mb-20"></div>
        </section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
    </section><!--end mainbody-->
</div><!--end row-->
<?php PeepSoTemplate::exec_template('activity', 'dialogs'); ?>
