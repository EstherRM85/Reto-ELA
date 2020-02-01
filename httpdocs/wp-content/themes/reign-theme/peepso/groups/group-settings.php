<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
    $mainbody_class = 'wb-grid';
}
?>
<div class="peepso ps-page--group-edit">
    <?php PeepSoTemplate::exec_template('general','navbar'); ?>
    <?php PeepSoTemplate::exec_template('general', 'register-panel'); ?>

    <?php if(get_current_user_id()) { ?>

        <?php
        PeepSoTemplate::exec_template('groups', 'group-header', array('group'=>$group, 'group_segment'=>$group_segment));
        $group_users = new PeepSoGroupUsers($group->id);
        $group_user = new PeepSoGroupUser($group->id);
        ?>

        <section id="mainbody" class="ps-page-unstyled <?php echo esc_attr( $mainbody_class ); ?>">
            <?php
            if ( 'inside' !== $header_position ) {
                do_action( 'wbcom_before_content_section' );
            }
            ?>
            <section id="component" role="article" class="ps-clearfix">

                <div class="ps-edit ps-edit--group">

                    <!--  Name -->
                    <div class="ps-edit__row ps-edit__row--group-name ps-js-group-name">
                        <div class="ps-edit__row-name">
                            <?php echo __('Group Name', 'groupso'); ?>
                            <span class="ps-text--danger">*</span>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-group-name-text"><?php echo $group->name;?></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                            <div class="ps-edit__editor ps-js-group-name-editor" style="display:none">
                                <input type="text" class="ps-input ps-input--small ps-full" maxlength="<?php echo PeepSoGroup::$validation['name']['maxlength'];?>" data-maxlength="<?php echo PeepSoGroup::$validation['name']['maxlength'];?>" value="<?php echo esc_attr($group->name); ?>">

                                <div class="ps-group__limit"><span class="ps-js-limit"><?php echo PeepSoGroup::$validation['name']['maxlength'];?></span> <?php echo __('Characters left', 'groupso'); ?></div>

                                <div class="ps-edit__row-actions">
                                    <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-btn-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                    <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-btn-submit">
                                        <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                        <?php echo __('Save', 'groupso'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="ps-edit__row-actions">
                                <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_name(<?php echo $group->id; ?>, this);">
                                    <?php echo __('Edit','groupso');?>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!--  Slug -->
                    <?php if ($group_user->can('manage_group') && 2 == PeepSo::get_option('groups_slug_edit', 0)) {

                    $slug = urldecode($group->slug);
                    ?>
                    <div class="ps-edit__row ps-edit__row--group-slug ps-js-group-slug">
                        <div class="ps-edit__row-name">
                            <?php echo __('Group Slug', 'groupso'); ?>
                            <span class="ps-text--danger">*</span>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-group-slug-text"><?php echo PeepSo::get_page('groups')."<strong>$slug</strong>"; ?></span>

                            <div class="ps-edit__editor ps-js-group-slug-editor" style="display:none">
                                <input size="30" class="ps-input ps-input--small" maxlength="<?php echo PeepSoGroup::$validation['name']['maxlength'];?>" data-maxlength="<?php echo PeepSoGroup::$validation['name']['maxlength'];?>" value="<?php echo $slug; ?>">
                                <br/>
                                <small><?php
                                echo __('Letters, numbers and dashes are recommended, eg my-amazing-group-123.','groupso') .'<br/>'.__('This field might be automatically adjusted  after editing.','groupso');
                                    ?></small>

                                <div class="ps-edit__row-actions">
                                    <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                    <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-submit">
                                        <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                        <?php echo __('Save', 'groupso'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="ps-edit__row-actions">
                                <button class="ps-btn ps-btn--edit ps-btn-small ps-js-group-slug-trigger" onclick="ps_group.edit_slug(<?php echo $group->id; ?>, this);">
                                    <?php echo __('Edit','groupso');?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php } ?>


                    <!-- Description -->
                    <div class="ps-edit__row ps-edit__row--group-desc ps-js-group-desc">
                        <div class="ps-edit__row-name">
                            <?php echo __('Group Description', 'groupso'); ?>
                            <span class="ps-text--danger">*</span>
                        </div>

                        <div class="ps-edit__row-content">
                            <?php
                            $description = str_replace("\n","<br/>", $group->description);
                            $description = html_entity_decode($description);
                            ?>
                            <span class="ps-js-group-desc-text" style="<?php echo empty($group->description) ? 'display:none' : '' ?>"><?php echo stripslashes($description); ?></span>
                            <span class="ps-js-group-desc-placeholder" style="<?php echo empty($group->description) ? '' : 'display:none' ?>"><i><?php echo __('No description', 'groupso'); ?></i></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                            <div class="ps-edit__editor ps-js-group-desc-editor" style="display:none">
                                <textarea class="ps-textarea" rows="10" data-maxlength="<?php echo PeepSoGroup::$validation['description']['maxlength'];?>"><?php echo html_entity_decode($group->description); ?></textarea>

                                <div class="ps-group__limit"><?php echo PeepSoGroup::$validation['description']['maxlength'];?></span> <?php echo __('Characters left', 'groupso'); ?></div>

                                <div class="ps-edit__row-actions">
                                    <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-cancel"><?php echo __('Cancel', 'groupso'); ?></button>
                                    <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-submit">
                                        <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                        <?php echo __('Save', 'groupso'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="ps-edit__row-actions">
                                <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_desc(<?php echo $group->id; ?>, this);">
                                    <?php echo __('Edit','groupso');?>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Categories -->
                    <?php if(PeepSo::get_option('groups_categories_enabled', FALSE)) { ?>

                    <div class="ps-edit__row ps-edit__row--group-cat ps-js-group-cat">
                        <div class="ps-edit__row-name">
                            <?php echo __('Categories', 'groupso'); ?>
                            <span class="ps-text--danger">*</span>
                        </div>

                        <div class="ps-edit__row-content">
                            <div class="ps-list--separate ps-js-group-cat-text">
                            <?php

                                $group_categories = PeepSoGroupCategoriesGroups::get_categories_for_group($group->id);
                                $group_categories_html = array();
                                foreach ($group_categories as $PeepSoGroupCategory) {
                                    echo "<a href=\"{$PeepSoGroupCategory->get_url()}\">{$PeepSoGroupCategory->name}</a>";
                                }

                            ?>
                            </div>

                            <?php if ($group_user->can('manage_group')) { ?>
                            <div class="ps-edit__editor ps-js-group-cat-editor" style="display:none">
                                <div class="ps-checkbox__grid">
                                <?php

                                $multiple_enabled = PeepSo::get_option('groups_categories_multiple_enabled', FALSE);
                                $input_type = ($multiple_enabled) ? 'checkbox' : 'radio';
                                $PeepSoGroupCategories = new PeepSoGroupCategories(FALSE, TRUE);
                                $categories = $PeepSoGroupCategories->categories;

                                if (count($categories)) {
                                    foreach ($categories as $id => $category) {
                                        $checked = '';
                                        if (isset($group_categories[$id])) {
                                            $checked = 'checked="checked"';
                                        }
                                        echo sprintf('<div class="ps-checkbox"><input %s type="%s" id="category_' . $id . '" name="category_id" value="%d"><label for="category_' . $id . '">%s</label></div>', $checked, $input_type, $id, $category->name);
                                    }
                                }

                                ?>
                                </div>

                                <div class="ps-edit__row-actions">
                                    <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-cancel"><?php echo __('Cancel', 'groupso'); ?></button>
                                    <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-submit">
                                        <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                        <?php echo __('Save', 'groupso'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="ps-edit__row-actions">
                                <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_cats(<?php echo $group->id; ?>, this);">
                                    <?php echo __('Edit','groupso');?>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>


                    <!-- "Join" button -->
                    <?php if(!$group->is_secret) { ?>
                    <div class="ps-edit__row ps-edit__row--group-joinable ps-js-group-is_joinable">
                        <div class="ps-edit__row-name">
                            <?php

                                if($group->is_open) { echo __('Enable "Join" button', 'groupso'); }
                                if($group->is_closed) { echo __('Enable "Request To Join" button', 'groupso'); }
                            ?>
                            <br/>
                            <small>
                                <?php echo __('Has no effect on Site Administrators','groupso'); ?>
                            </small>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-text"><?php echo ($group->is_joinable) ? __('Yes', 'groupso') : __('No', 'groupso');?></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                                <div class="ps-edit__editor ps-js-editor" style="display:none">
                                    <select name="is_joinable" class="ps-select ps-full">
                                        <option value="1"><?php echo __('Yes', 'groupso');?></option>
                                        <option value="0" <?php if(FALSE == $group->is_joinable) { echo "selected";}?>><?php echo __('No', 'groupso');?></option>
                                    </select>

                                    <div class="ps-edit__row-actions">
                                        <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-btn-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-btn-submit">
                                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                            <?php echo __('Save', 'groupso'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="ps-edit__row-actions">
                                    <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_property(this, <?php echo $group->id; ?>, 'is_joinable');">
                                        <?php echo __('Edit','groupso');?>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <!-- "Invite" button -->
                    <div class="ps-edit__row ps-edit__row--group-invitable ps-js-group-is_invitable">
                        <div class="ps-edit__row-name">
                            <?php echo __('Enable "Invite" button', 'groupso'); ?>
                            <br/>
                            <small>
                                <?php echo __('Has no effect on Owner and Site Administrators','groupso'); ?>
                            </small>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-text"><?php echo ($group->is_invitable) ? __('Yes', 'groupso') : __('No', 'groupso');?></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                                <div class="ps-edit__editor ps-js-editor" style="display:none">
                                    <select name="is_invitable" class="ps-select ps-full">
                                        <option value="1"><?php echo __('Yes', 'groupso');?></option>
                                        <option value="0" <?php if(FALSE == $group->is_invitable) { echo "selected";}?>><?php echo __('No', 'groupso');?></option>
                                    </select>

                                    <div class="ps-edit__row-actions">
                                        <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-btn-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-btn-submit">
                                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                            <?php echo __('Save', 'groupso'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="ps-edit__row-actions">
                                    <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_property(this, <?php echo $group->id; ?>, 'is_invitable');">
                                        <?php echo __('Edit','groupso');?>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Disable posting -->
                    <div class="ps-edit__row ps-edit__row--group-readonly ps-js-group-is_readonly">
                        <div class="ps-edit__row-name">
                            <?php echo __('Disable new posts', 'groupso'); ?>
                            <br/>
                            <small>
                                <?php echo __('Has no effect on Owner and Site Administrators','groupso'); ?>
                            </small>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-text"><?php echo ($group->is_readonly) ? __('Yes', 'groupso') : __('No', 'groupso');?></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                                <div class="ps-edit__editor ps-js-editor" style="display:none">
                                    <select name="is_readonly" class="ps-select ps-full">
                                        <option value="1"><?php echo __('Yes', 'groupso');?></option>
                                        <option value="0" <?php if(FALSE == $group->is_readonly) { echo "selected";}?>><?php echo __('No', 'groupso');?></option>
                                    </select>

                                    <div class="ps-edit__row-actions">
                                        <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-btn-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-btn-submit">
                                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                            <?php echo __('Save', 'groupso'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="ps-edit__row-actions">
                                    <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_property(this, <?php echo $group->id; ?>, 'is_readonly');">
                                        <?php echo __('Edit','groupso');?>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Disable new member notifications -->
                    <div class="ps-edit__row ps-edit__row--group-readonly ps-js-group-is_join_muted">
                        <div class="ps-edit__row-name">
                            <?php echo __('Disable new member notifications', 'groupso'); ?>
                            <br/>
                            <small>
                                <?php echo __('Group owners will not receive notifications about new members','groupso'); ?>
                            </small>
                        </div>

                        <div class="ps-edit__row-content">
                            <span class="ps-js-text"><?php echo ($group->is_join_muted) ? __('Yes', 'groupso') : __('No', 'groupso');?></span>

                            <?php if ($group_user->can('manage_group')) { ?>
                                <div class="ps-edit__editor ps-js-editor" style="display:none">
                                    <select name="is_join_muted" class="ps-select ps-full">
                                        <option value="1"><?php echo __('Yes', 'groupso');?></option>
                                        <option value="0" <?php if(FALSE == $group->is_join_muted) { echo "selected";}?>><?php echo __('No', 'groupso');?></option>
                                    </select>

                                    <div class="ps-edit__row-actions">
                                        <button type="button" class="ps-btn ps-btn-small ps-button-cancel ps-js-btn-cancel"><?php echo __('Cancel', 'groupso'); ?></button>

                                        <button type="button" class="ps-btn ps-btn-small ps-button-action ps-js-btn-submit">
                                            <img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" class="ps-js-loading" alt="loading" style="display:none" />
                                            <?php echo __('Save', 'groupso'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="ps-edit__row-actions">
                                    <button class="ps-btn ps-btn--edit ps-btn-small ps-js-btn-edit" onclick="ps_group.edit_property(this, <?php echo $group->id; ?>, 'is_join_muted');">
                                        <?php echo __('Edit','groupso');?>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>




                </div>
            </section>
            <?php
            if ( 'inside' !== $header_position ) {
                do_action( 'wbcom_after_content_section' );
            }
            ?>
        </section>
    <?php } ?>
</div><!--end row-->

<?php

if(get_current_user_id()) {
    PeepSoTemplate::exec_template('activity' ,'dialogs');
}
