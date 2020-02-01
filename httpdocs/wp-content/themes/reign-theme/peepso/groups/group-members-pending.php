<?php
global $wbtm_reign_settings;
$mainbody_class  = '';
$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
if ( 'inside' !== $header_position ) {
	$mainbody_class = 'wb-grid';
}
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
                $PeepSoGroupUser = new PeepSoGroupUser($group->id, get_current_user_id());
                if ($PeepSoGroupUser->can('manage_users')) {
                    PeepSoTemplate::exec_template('groups', 'group-members-tabs', array('tab' => FALSE, 'PeepSoGroupUser' => $PeepSoGroupUser, 'group' => $group,'tab'=>'pending'));
                }
				?>

				<div class="ps-clearfix mb-20"></div>
				<div class="ps-members ps-clearfix ps-js-group-members"></div>
				<div class="ps-scroll ps-clearfix ps-js-group-members-triggerscroll">
					<img class="post-ajax-loader ps-js-group-members-loading" src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>" alt="" style="display:none" />
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
