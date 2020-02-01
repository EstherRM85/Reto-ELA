<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
$PeepSoProfile=PeepSoProfile::get_instance();
?>
<div class="peepso ps-page-profile">
    <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>
    <?php PeepSoTemplate::exec_template('profile', 'focus'); ?>
    <section id="mainbody" class="ps-wrapper ps-clearfix <?php echo esc_attr( $mainbody_class ); ?>">
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
        <section id="component" role="article" class="ps-clearfix">
            <div id="cProfileWrapper" class="ps-clearfix">

                <div id="editLayout-stop" class="page-action" style="display: none;">
                    <a href="#" onclick="profile.editLayout.stop(); return false;"><?php _e('Finished Editing Apps Layout', 'peepso-core'); ?></a>
                </div>

                <div class="ps-body">
                    <?php
                    // widgets top
                    $widgets_profile_sidebar_top = apply_filters('peepso_widget_prerender', 'profile_sidebar_top');

                    // widgets bottom
                    $widgets_profile_sidebar_bottom = apply_filters('peepso_widget_prerender', 'profile_sidebar_bottom');
                    ?>

                    <?php
                    $sidebar = NULL;

                    if (count($widgets_profile_sidebar_top) > 0 || count($widgets_profile_sidebar_bottom) > 0) { ?>

                        <?php
                        ob_start();
                        PeepSoTemplate::exec_template('sidebar', 'sidebar', array('profile_sidebar_top'=>$widgets_profile_sidebar_top, 'profile_sidebar_bottom'=>$widgets_profile_sidebar_bottom, ));
                        $sidebar = ob_get_clean();

                        echo $sidebar;
                        ?>
                    <?php } ?>

                    <div class="ps-main <?php if (strlen($sidebar)) echo ''; else echo 'ps-main-full'; ?>">
                        <!-- js_profile_feed_top -->
                        <div class="activity-stream-front">
                            <?php
                            PeepSoTemplate::exec_template('general', 'postbox-legacy', array('is_current_user' => $PeepSoProfile->is_current_user()));
                            ?>

                            <div class="tab-pane active" id="stream">
                                <div id="ps-activitystream-recent" class="ps-stream-container" style="display:none"></div>
                                <div id="ps-activitystream" class="ps-stream-container" style="display:none"></div>

                                <div id="ps-activitystream-loading">
                                    <?php PeepSoTemplate::exec_template('activity', 'activity-placeholder'); ?>
                                </div>

                                <div id="ps-no-posts" class="ps-alert" style="display:none"><?php _e('No posts found.', 'peepso-core'); ?></div>
                                <div id="ps-no-posts-match" class="ps-alert" style="display:none"><?php _e('No posts found.', 'peepso-core'); ?></div>
                                <div id="ps-no-more-posts" class="ps-alert" style="display:none"><?php _e('Nothing more to show.', 'peepso-core'); ?></div>
                            </div>
                        </div><!-- end activity-stream-front -->

                        <?php PeepSoTemplate::exec_template('activity','dialogs'); ?>
                        <div id="apps-sortable" class="connectedSortable"></div>
                    </div><!-- cMain -->
                </div><!-- end row -->
            </div><!-- end cProfileWrapper --><!-- js_bottom -->
            <div id="ps-dialogs" style="display:none">
                <?php do_action('peepso_profile_dialogs'); // give add-ons a chance to output some HTML ?>
            </div>
        </section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
    </section><!--end mainbody-->
</div><!--end row-->
