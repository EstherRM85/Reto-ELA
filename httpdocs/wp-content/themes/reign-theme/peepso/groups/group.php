<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
	$mainbody_class = 'wb-grid';
}
$PeepSoActivityShortcode = PeepSoActivityShortcode::get_instance();
$PeepSoGroupUser = new PeepSoGroupUser($group->id);
?>
<div class="peepso ps-page--group">
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
				<?php
				if ($PeepSoGroupUser->can('post')) {
					PeepSoTemplate::exec_template('general', 'postbox-legacy');
				} else {
					// default message for non-members
					$message = __('You must join the group to be able to participate.' ,'groupso');

                    if($group->is_readonly) {
                        $message = __('This is an announcement Group, only the Owners can create new posts.', 'groupso');
                    }

					// optional message for unpublished groups
					if(!$group->published) {
						$message = __('Currently group is unpublished.', 'groupso');
					}


					?>
					<div class="ps-box ps-box--message" >
						<div class="ps-box__body" >
							<?php echo $message;?>
						</div>
					</div>
					<?php
				}

				if(PeepSo::is_admin() || $group->is_open || $PeepSoGroupUser->is_member) { ?>

				<!-- stream activity -->
                <input type="hidden" id="peepso_context" value="group" />
				<div class="ps-stream-wrapper">
					<div id="ps-activitystream-recent" class="ps-stream-container" style="display:none"></div>
                    <div id="ps-activitystream" class="ps-stream-container" style="display:none"></div>

                    <div id="ps-activitystream-loading">
                        <?php PeepSoTemplate::exec_template('activity', 'activity-placeholder'); ?>
                    </div>

					<div id="ps-no-posts" class="ps-alert" style="display:none"><?php _e('No posts found.', 'groupso'); ?></div>
					<div id="ps-no-posts-match" class="ps-alert" style="display:none"><?php _e('No posts found.', 'groupso'); ?></div>
					<div id="ps-no-more-posts" class="ps-alert" style="display:none"><?php _e('Nothing more to show.', 'groupso'); ?></div>

					<?php
					if(get_current_user_id()) {
						PeepSoTemplate::exec_template('activity' ,'dialogs');
					}
					?>
				</div>

                <?php } ?>
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
