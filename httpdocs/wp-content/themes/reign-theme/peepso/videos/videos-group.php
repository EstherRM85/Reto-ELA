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
    <?php PeepSoTemplate::exec_template('general', 'register-panel'); ?>

    <?php if(get_current_user_id()) { ?>

    <?php PeepSoTemplate::exec_template('groups', 'group-header', array('group'=>$group, 'group_segment'=>$group_segment)); ?>

    <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
        <section id="component" role="article" class="ps-clearfix">
            <div class="ps-page-filters">
                <select class="ps-select ps-full ps-js-videos-sortby">
                    <option value="desc"><?php _e('Newest first', 'vidso');?></option>
                    <option value="asc"><?php _e('Oldest first', 'vidso');?></option>
                </select>
            </div>

            <div class="ps-video mb-20"></div>
            <div class="ps-video ps-js-videos"></div> &nbsp;
            <div class="ps-video ps-js-videos-triggerscroll">
                <img class="post-ajax-loader ps-js-videos-loading" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" style="display:none" />
            </div>
        </section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
    </section><!--end mainbody-->

    <?php } ?>

</div><!--end row-->
<?php PeepSoTemplate::exec_template('activity', 'dialogs'); ?>
