<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
$PeepSoActivity = PeepSoActivity::get_instance();
$PeepSoPhotos = PeepSoPhotos::get_instance();
$PeepSoUser = PeepSoUser::get_instance();
$empty_desc = empty($the_album->pho_album_desc);

?>
<div class="peepso ps-page-profile">
    <?php PeepSoTemplate::exec_template('general', 'navbar'); ?>

	<?php PeepSoTemplate::exec_template('profile', 'focus', array('current'=>'photos')); ?>

    <section id="mainbody" class="ps-page--album ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_before_content_section' );
        }
        ?>
        <section id="component" role="article" class="ps-clearfix">
            <div class="ps-page__actions">
                <a class="ps-btn ps-btn-small" href="<?php echo $photos_url; ?>"><i class="ps-icon-angle-left"></i> <?php echo __('Back', 'picso'); ?></a>
                <?php
                $can_upload = $can_edit;
                if($the_album->pho_owner_id != get_current_user_id() && $can_edit) {
                    $can_upload = false;
                }

                $can_upload = apply_filters('peepso_permissions_photos_upload', $can_upload);

                if ($can_upload) {
                ?>
                <a class="ps-btn ps-btn-small" href="#" onclick="peepso.photos.show_dialog_add_photos(<?php echo get_current_user_id() . ',' . $album_id; ?>); return false;"><i class="ps-icon-plus"></i> <?php echo __('Add Photos', 'picso'); ?></a>
                <?php } ?>
            </div>
            <h4 class="ps-page-title ps-album__name ps-js-album-name">
                <span class="ps-js-album-name-text"><?php echo $the_album->pho_album_name; ?></span>
                <?php if ($can_edit) { ?>
                <div class="ps-album__name-edit ps-js-album-name-editor" style="display:none">
                    <div class="ps-album__edit-wrapper"><input type="text" class="ps-input ps-input--small" maxlength="50" value="<?php echo esc_attr($the_album->pho_album_name); ?>"></div>
                    <div class="ps-album__edit-wrapper"><button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-cancel"><?php echo __('Cancel', 'picso'); ?></button></div>
                    <div class="ps-album__edit-wrapper">
                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-submit">
                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="margin-right:5px;display:none" />
                            <?php echo __('Save', 'picso'); ?>
                        </button>
                    </div>
                </div>
                <a href="#" class="ps-icon-edit ps-js-album-name-trigger" onclick="peepso.album.edit_name(<?php echo $the_album->pho_album_id; ?>, <?php echo $the_album->pho_owner_id; ?>, this); return false;"></a>
                <?php } ?>
            </h4>

            <div data-album-delete-id="<?php echo $album_id; ?>" class="delete-content" style="display: none;">
                <?php
                    echo __(
                        'Are you sure you want to delete this album?',
                        'picso'
                    );
                ?>
            </div>
            <?php wp_nonce_field('photo-delete-album', '_delete_album_nonce'); ?>
            <?php if ($can_edit || !$empty_desc || $can_delete) {
                // get selected privacy
                $selected_value = FALSE;
                $selected_icon = FALSE;
                $selected_label = FALSE;

                foreach ($access_settings as $key => $value) {
                    if (( $selected_value === FALSE ) || ( $key == $the_album->pho_album_acc )) {
                        $selected_value = $key;
                        $selected_icon = $value['icon'];
                        $selected_label = $value['label'];
                    }
                }
            ?>
            <div class="ps-album__description ps-js-album-desc">
                    <div class="ps-album__description-title">
                        <?php echo __('Album description', 'picso'); ?>
                        <?php if ($can_edit) { ?>
                        <a href="#" onclick="peepso.album.edit_desc(<?php echo $the_album->pho_album_id; ?>, <?php echo $the_album->pho_owner_id; ?>, <?php echo $the_album->pho_owner_id; ?>, this); return false;">
                            <i class="ps-icon-edit"></i>
                        </a>
                        <?php } ?>

                        <?php if ($can_edit) { ?>
                        <div class="ps-dropdown ps-album__description-privacy ps-js-dropdown ps-js-dropdown--privacy">
                            <input type="hidden" value="<?php echo $selected_value; ?>">
                            <a type="button" class="ps-dropdown__toggle ps-js-dropdown-toggle">
                                <span class="dropdown-value"><i class="ps-icon-<?php echo $selected_icon; ?>"></i></span>
                            </a>
                            <div class="ps-dropdown__menu ps-js-dropdown-menu">
                                <?php foreach ($access_settings as $key => $value) { ?>
                                <a href="#" data-option-value="<?php echo $key; ?>" onclick="peepso.album.change_acc(<?php echo $the_album->pho_album_id; ?>, <?php echo $the_album->pho_owner_id; ?>, <?php echo $key; ?>, this); return false;">
                                    <i class="ps-icon-<?php echo $value['icon']; ?>"></i>
                                    <span><?php echo $value['label'] ?></span>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="ps-privacy-dropdown ps-album__description-privacy ps-js-dropdown">
                            <span class="dropdown-value"><i class="ps-icon-<?php echo $selected_icon; ?>"></i></span>
                        </div>
                        <?php } ?>

                        <?php if ($can_delete) { ?>
                        <a href="#" onclick="peepso.photos.show_dialog_delete_album(<?php echo $the_album->pho_owner_id . ',' . $album_id; ?>); return false;"><i class="ps-icon-trash"></i></a>
                        <?php } ?>
                    </div>
                    <div class="ps-js-album-desc-text" style="<?php echo $empty_desc ? 'display:none' : '' ?>"><?php echo stripslashes($the_album->pho_album_desc); ?></div>
                    <div class="ps-js-album-desc-placeholder" style="<?php echo $empty_desc ? '' : 'display:none' ?>"><i><?php echo __('No description', 'picso'); ?></i></div>
                    <?php if ($can_edit) { ?>
                    <div class="ps-album__description-edit ps-js-album-desc-editor" style="display:none">
                        <textarea class="ps-textarea"><?php echo stripslashes($the_album->pho_album_desc); ?></textarea>
                        <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-cancel"><?php echo __('Cancel', 'picso'); ?></button>
                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-submit">
                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="margin-right:5px;display:none" />
                            <?php echo __('Save description', 'picso'); ?>
                        </button>
                    </div>
                    <?php } ?>
            </div>
            <?php } ?>
            <?php
            // adding capability to print extra fields for other plugins
            $PeepSoPhotos->photo_album_show_extra_fields($the_album->pho_post_id, $can_edit);
            ?>
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

            <?php
            if($the_album->pho_post_id && (!$the_album->pho_system_album)) {
            ?>
            <!-- post actions -->
            <div class="ps-stream-actions stream-actions" data-type="stream-action"><?php $PeepSoActivity->post_actions(); ?></div>

            <?php if ($likes = $PeepSoActivity->has_likes($act_id)) { ?>
            <div id="act-like-<?php echo $act_id; ?>" class="ps-stream-status cstream-likes ps-js-act-like--<?php echo $act_id; ?>" data-count="<?php echo $likes ?>">
                <?php $PeepSoActivity->show_like_count($likes); ?>
            </div>
            <?php } else { ?>
            <div id="act-like-<?php echo $act_id; ?>" class="ps-stream-status cstream-likes ps-js-act-like--<?php echo $act_id; ?>" data-count="0" style="display:none"></div>
            <?php } ?>
            <?php do_action('peepso_post_before_comments'); ?>
            <div class="ps-comment cstream-respond wall-cocs" id="wall-cmt-<?php echo $act_id; ?>">
                <div class="ps-comment-container comment-container ps-js-comment-container ps-js-comment-container--<?php echo $act_id; ?>" data-act-id="<?php echo $act_id; ?>">
                    <?php if( $PeepSoActivity->has_comments()) { ?>
                            <?php $PeepSoActivity->show_recent_comments(); ?>
                    <?php } ?>
                </div>

                <?php if (is_user_logged_in() ) { ?>
                <div id="act-new-comment-<?php echo $act_id; ?>" class="ps-comment-reply cstream-form stream-form wallform ps-js-newcomment-<?php echo $act_id; ?> ps-js-comment-new" data-type="stream-newcomment" data-formblock="true">
                    <a class="ps-avatar cstream-avatar cstream-author" href="<?php echo $PeepSoUser->get_profileurl(); ?>">
                        <img data-author="<?php echo get_current_user_id(); ?>" src="<?php echo $PeepSoUser->get_avatar(); ?>" alt="" />
                    </a>
                    <div class="ps-textarea-wrapper cstream-form-input">
                        <textarea
                            data-act-id="<?php echo $act_id;?>"
                            class="ps-textarea cstream-form-text"
                            name="comment"
                            oninput="return activity.on_commentbox_change(this);"
                            placeholder="<?php _e('Write a comment...', 'picso');?>"></textarea>
                        <?php
                        // call function to add button addons for comments
                        $PeepSoActivity->show_commentsbox_addons();
                        ?>
                    </div>
                    <div class="ps-comment-send cstream-form-submit" style="display:none;">
                        <div class="ps-comment-loading" style="display:none;">
                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" />
                            <div> </div>
                        </div>
                        <div class="ps-comment-actions" style="display:none;">
                            <button onclick="return activity.comment_cancel(<?php echo $act_id; ?>);" class="ps-btn ps-button-cancel"><?php _e('Clear', 'picso'); ?></button>
                            <button onclick="return activity.comment_save(<?php echo $act_id; ?>, this);" class="ps-btn ps-btn-primary ps-button-action" disabled><?php _e('Post', 'picso'); ?></button>
                        </div>
                    </div>
                </div>
                <?php } // is_user_loggged_in ?>
            </div>
            <?php } // have post_id and empty?>


        </section><!--end component-->
        <?php
        if ( 'inside' !== $header_position ) {
            do_action( 'wbcom_after_content_section' );
        }
        ?>
    </section><!--end mainbody-->
</div><!--end row-->
<?php PeepSoTemplate::exec_template('activity','dialogs'); ?>
