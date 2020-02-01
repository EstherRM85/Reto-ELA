<?php
	global $wbtm_reign_settings;
	$group_header_class = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'group_header_type' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'group_header_type' ] : 'wbtm-cover-header-type-1';
	$group_header_class = apply_filters( 'wbtm_rth_manage_group_header_class', $group_header_class );

	$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
	$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
	if ( 'inside' === $header_position ) {
		$header_position_class = 'wbtm-reign-cover-position-inside';
	} else {
		$header_position_class = 'wbtm-reign-cover-position-top';
	}

	$PeepSoGroupUser = new PeepSoGroupUser($group->id);
	#$PeepSoGroup = new PeepSoGroup($group->id);
	$PeepSoGroup = $group;
	$coverUrl = $PeepSoGroup->get_cover_url();
	$has_cover = false;

	if (FALSE !== stripos($coverUrl, 'peepso/groups/'))
		$has_cover = true;

	if (FALSE === $PeepSoGroupUser->can('manage_group') || (FALSE === $has_cover)) {
		$reposition_style = 'display:none;';
		$cover_class = 'default';
	} else {
		$reposition_style = '';
		$cover_class = 'has-cover';
	}

	$group_cover_photo = get_post_meta( $group->id, 'group_cover_photo', TRUE );
	if ( empty( $group_cover_photo ) ) {
		$group_cover_photo = wbtm_render_peepso_group_cover_image();
	}

	$description = str_replace("\n","<br/>", $group->description);
	$description = html_entity_decode($description);

	$group_categories = PeepSoGroupCategoriesGroups::get_categories_for_group($group->id);
	$group_categories_html = array();

?>
<div class="ps-focus ps-focus--group <?php if(strlen($group_segment) && 0 == PeepSo::get_option('always_full_cover', 0)) { echo 'ps-focus-mini'; } ?> js-focus ps-js-focus ps-js-group-header <?php echo $group_header_class; ?> <?php echo $header_position_class; ?>">
	<div class="ps-focus-cover js-focus-cover">
		<div class="ps-focus-image">
			<img id="<?php echo $PeepSoGroup->get('id'); ?>"
				data-cover-context="profile"
				class="focusbox-image cover-image <?php echo $cover_class; ?>"
				src="<?php echo $group_cover_photo; ?>"
				alt="<?php echo $PeepSoGroup->get('name'); ?> cover photo"
				style="<?php echo $PeepSoGroup->cover_photo_position(); ?>"
			/>
		</div>

		<div class="ps-focus-image-mobile" style="background:url(<?php echo $group_cover_photo; ?>) no-repeat center center;">
		</div>

		<div class="js-focus-gradient" data-cover-context="profile" data-cover-type="cover"></div>

		<?php if ($PeepSoGroupUser->can('manage_group') && (empty($group_segment) || 1 == PeepSo::get_option('always_full_cover', 0))) { ?>

		<?php wp_nonce_field('cover-photo', '_covernonce'); ?>
		<!-- Cover options dropdown -->
		<div class="ps-focus-options ps-dropdown ps-dropdown-focus ps-js-dropdown">
			<a href="#" class="ps-dropdown__toggle ps-js-dropdown-toggle">
				<span class="ps-icon-camera"></span>
			</a>
			<div class="ps-dropdown__menu ps-js-dropdown-menu">
				<a href="#" class="ps-reposition-cover" id="profile-reposition-cover" style="<?php echo $reposition_style; ?>"
						data-cover-context="profile" onclick="ps_group.reposition_cover(); return false;">
					<i class="ps-icon-move"></i>
					<?php _e('Reposition', 'groupso'); ?>
				</a>
				<a href="#" data-cover-context="profile" onclick="ps_group.show_cover_dialog(); return false;">
					<i class="ps-icon-cog"></i>
					<?php _e('Modify', 'groupso'); ?>
				</a>
			</div>
		</div>
		<!-- Reposition cover - buttons -->
		<div class="ps-focus-change js-focus-change-cover" data-cover-type="cover">
			<div class="reposition-cover-actions" style="display: none;">
				<a href="#" class="ps-btn" onclick="ps_group.cancel_reposition_cover(); return false;"><?php _e('Cancel', 'groupso'); ?></a>
				<a href="#" class="ps-btn ps-btn-primary" onclick="ps_group.save_reposition_cover(); return false;"><?php _e('Save', 'groupso'); ?></a>
			</div>
			<div class="ps-reposition-loading" style="display: none;">
				<img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif'); ?>">
				<div> </div>
			</div>
		</div>
		<?php } ?>

		<div class="ps-focus-header js-focus-content">
			<div class="ps-avatar-focus js-focus-avatar ps-js-focus-avatar">
				<img src="<?php echo $PeepSoGroup->get_avatar_url_full(); //image'); ?>" alt="<?php echo $PeepSoGroup->get('name'); ?> avatar">
				<?php if ($PeepSoGroupUser->can('manage_group')) { ?>
					<span class="ps-avatar-change js-focus-avatar-option">
						<a href="#" class="ps-js-focus-avatar-button">
							<i class="ps-icon-camera"></i>
						</a>
					</span>
				<?php } ?>
			</div>
			<div class="ps-focus-title ps-focus__box-toggle ps-tooltip ps-tooltio--focus-box ps-js-focus-box-toggle"
					data-tooltip="<?php _e('Click to show more group details..', 'groupso'); ?>">
				<span>
					<?php echo $group->name; ?>
				</span>
				<div class="ps-focus__desc"><i class="ps-icon-info-circled"></i> <?php echo stripslashes($description); ?></div>
			</div>
			<div class="ps-focus__box ps-js-focus-box">
				<?php echo stripslashes($description); ?>
				<div class="ps-focus__box-details" aria-label="<?php _e('Group details', 'groupso'); ?>">
					<?php if(PeepSo::get_option('groups_categories_enabled', FALSE)) { ?>
					<div class="ps-focus__box-details-item ps-list--separate">
						<?php if(count($group_categories) > 1) { ?><i class="ps-icon-tags"></i> <?php _e('Group categories', 'groupso'); ?>:<?php } else { ?><i class="ps-icon-tag"></i> <?php _e('Group category', 'groupso'); ?>:<?php } ?>
						<?php

							foreach ($group_categories as $PeepSoGroupCategory) {
								echo "<a href=\"{$PeepSoGroupCategory->get_url()}\">{$PeepSoGroupCategory->name}</a>";
							}

						?>
					</div>
					<?php } ?>
					<div class="ps-focus__box-details-item">
						<i class="ps-icon-users"></i>
						<a href="<?php echo $group->get_url() . 'members/'; ?>">
							<span class="ps-js-member-count"><?php printf( _n( '%s member', '%s members', $group->members_count, 'groupso' ), number_format_i18n( $group->members_count ) ); ?></span>
						</a>
					</div>
					<?php if($group->pending_admin_members_count > 0 && $PeepSoGroupUser->can('manage_users')) { ?>
					<div class="ps-focus__box-details-item">
						<a href="<?php echo $group->get_url() . 'members/pending'; ?>">
							<span class="ps-js-member-count"><?php echo sprintf(__('(%d pending)','groupso'), $group->pending_admin_members_count); ?></span>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="ps-focus__details">
				<div class="ps-focus__details-item ps-focus__details-privacy">
					<?php if($PeepSoGroupUser->can('manage_group') && strlen($group_segment) && 'settings' == $group_segment) { ?>

					<span class="ps-dropdown ps-dropdown--group-privacy ps-js-dropdown ps-js-privacy ps-js-privacy--<?php echo $group->id; ?>">
						<button data-value="" class="ps-btn ps-btn-small ps-dropdown__toggle ps-js-dropdown-toggle">
							<span class="dropdown-value">
								<i class="<?php echo $group->privacy['icon']; ?>"></i>
								<span><?php echo $group->privacy['name']; ?></span>
							</span>
							<img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif') ?>" style="display:none" />
						</button>

						<?php echo PeepSoGroupPrivacy::render_dropdown(); ?>
					<?php } else { ?>
						<span class="ps-tooltip ps-tooltip--group-privacy" data-tooltip="<?php echo $group->privacy['desc'];?>"><i class="<?php echo $group->privacy['icon'];?>"></i> <?php echo sprintf(__('%s Group','groupso'), $group->privacy['name']);?> </span>
					<?php } ?>
				</div>
			</div>
			<div class="ps-focus-actions ps-js-group-header-actions ps-js-loading">
				<button class="ps-btn ps-btn--small">
					<img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif') ?>" />
				</button>
			</div>
		</div>
	</div>

	<div class="ps-focus-actions-mobile ps-js-group-header-actions">
		<a class="ps-btn ps-btn--small ps-js-loading">
			<img src="<?php echo PeepSo::get_asset('images/ajax-loader.gif') ?>" />
		</a>
	</div>

	<div class="ps-focus__footer">
		<div class="ps-focus__menu profile-interactions ps-js-focus-links">
			<div class="ps-focus__menu-inner"><?php

				$segments = array();
				$segments[0][] = array(
					'href' => '',
					'title'=> __('Stream', 'groupso'),
					'icon' => 'ps-icon-group',
				);

				if($PeepSoGroupUser->can('manage_group')) {
					$segments[0][] = array(
						'href' => 'settings',
						'title' => __('Settings', 'groupso'),
						'icon' => 'ps-icon-pencil',
					);
				}


				$title = __('Members', 'groupso');


				$segments[0][] = array(
					'href' => 'members',
					'title'=> $title,
					'icon' => 'ps-icon-users',
				);



				$segments = apply_filters('peepso_group_segment_menu_links', $segments);

				foreach($segments as $segment_group) {
					foreach($segment_group as $segment) {

						$can_access = $PeepSoGroupUser->can('access_segment', $segment['href']);

						$href = $group->get_url();

						if(strlen($segment['href'])) {
							$href .= $segment['href'].'/';
						}

						if($can_access) {
						?><a class="ps-focus__menu-item <?php echo($segment['href'] == $group_segment) ? 'current':'';?>" href="<?php echo $href; ?>">
							<i class="<?php echo $segment['icon']; ?>"></i>
							<span><?php echo $segment['title']; ?></span>
						</a><?php
						}
					}
				}

				?><a href="#" class="ps-focus__menu-item ps-js-focus-link-more" style="display:none">
					<i class="ps-icon-caret-down"></i>
					<span>
						<span><?php echo __('More', 'groupso'); ?></span>
						<span class="ps-icon-caret-down"></span>
					</span>
				</a>
				<div class="ps-focus__menu-more">
					<div class="ps-dropdown__menu ps-js-focus-link-dropdown" style="left:auto; right:0"></div>
				</div>
			</div><?php // this php block is intended to remove unwanted html whitespace
		?></div>

		<div class="ps-focus__interactions ps-js-focus-interactions"></div>
	</div>
</div>
<script>
jQuery(function() {
	peepsogroupsdata.group_id = +'<?php echo $group->id ?>';
});
</script>
