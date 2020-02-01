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
    <?php $PeepSoGroupUser = new PeepSoGroupUser($group->id, get_current_user_id());?>

    <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
        <section id="component" role="article" class="ps-clearfix">
            <?php
                if($PeepSoGroupUser->can('post')) {
                ?>
                <div class="ps-page__actions">
                    <a class="ps-btn ps-btn-small" href="#" onclick="peepso.photos.show_dialog_album(<?php echo get_current_user_id();?>, this); return false;"><i class="ps-icon-plus"></i><?php echo __('Create Album', 'picso'); ?></a>
                </div>
                <?php
                }
            ?>

            <h4 class="ps-page-title">
                <?php if('latest' === $current) echo __('Photos', 'picso'); if('album' === $current) echo __('Albums', 'picso'); ?>
            </h4>

            <div class="ps-tabs__wrapper">
                <div class="ps-tabs ps-tabs--arrows">
                    <div class="ps-tabs__item <?php if('latest' === $current) echo 'current' ?>"><a href="<?php echo PeepSoSharePhotos::get_group_url($view_group_id, 'latest'); ?>"><?php _e('Photos', 'picso'); ?></a></div>
                    <div class="ps-tabs__item <?php if('album' === $current) echo 'current' ?>"><a href="<?php echo PeepSoSharePhotos::get_group_url($view_group_id, 'album'); ?>"><?php _e('Albums', 'picso'); ?></a></div>
                </div>
            </div>

            <div class="ps-clearfix mb-20"></div>

            <div class="ps-page-filters" style="display:none;">
                <select class="ps-select ps-full ps-js-<?php echo $type?>-sortby ps-js-<?php echo $type?>-sortby--<?php echo  apply_filters('peepso_user_profile_id', 0); ?>">
                    <option value="desc"><?php _e('Newest first', 'picso');?></option>
                    <option value="asc"><?php _e('Oldest first', 'picso');?></option>
                </select>
            </div>

            <div class="ps-clearfix mb-20"></div>
            <div class="ps-<?php echo $type?> ps-js-<?php echo $type?> ps-js-<?php echo $type?>--<?php echo  apply_filters('peepso_user_profile_id', 0); ?>"></div>
            <div class="ps-scroll ps-js-<?php echo $type?>-triggerscroll ps-js-<?php echo $type?>-triggerscroll--<?php echo  apply_filters('peepso_user_profile_id', 0); ?>">
                <img class="post-ajax-loader ps-js-<?php echo $type?>-loading" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" style="display:none" />
            </div>
            <div class="ps-clearfix mb-20"></div>

        </section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
    </section><!--end mainbody-->

    <?php } ?>
</div><!--end row-->

<?php PeepSoTemplate::exec_template('activity','dialogs'); ?>
