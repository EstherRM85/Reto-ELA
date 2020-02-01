<?php
/**
 * Faqs support template file.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
<div class="blpro-support-setting">
	<div class="blpro-tab-header">
		<h3><?php esc_html_e( 'FAQ(s) ', 'buddypress-private-community-pro' ); ?></h3>
	</div>
	<div class="blpro-faqs-block-parent-contain">
		<div class="blpro-faqs-block-contain">
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'Does This plugin requires BuddyPress?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Yes, It needs you to have BuddyPress installed and activated.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'What are the settings for non logged-in users ?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'This plugin allows the site administrator to secure components of BuddyPress (if active), WordPress pages, custom post types from non-logged in users.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p><?php _e( 'You can lockdown WordPress Pages, any Custom Post Type and any BuddyPress Component and can have some content displayed like if you want to show any shortcode content or any simple message.', 'buddypress-private-community-pro' );?></p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'What will happen if BuddyPress \'groups\' component is locked for non logged in users?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'If BuddyPress \'groups\' component is locked by admin, then groups page will not display groups list and restrict access on the single group page too and show content which will be set by admin in Locked Content.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'What will happen if BuddyPress \'members\' component is locked for non logged in users?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'If BuddyPress \'members\' component is locked by admin, members page will be resctricted and will show content which will be set by admin in Locked Content.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'What restrictions plugin provide to list members at member directory?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can enable/disable \'Remove admin roles from members directory\' setting under logged-in user settings to exclude/include administrator roles to list at member directory page.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'Site admin can select users \'Remove users from member directory list\' setting under logged-in user settings to exclude selected users to list at member directory page.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'Site admin can set \'User profile completeness percentage to get listed in member directory\' setting under logged-in user settings to exclude users with profile percentage less than percentage set by admin.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How to display profile progress bar at members page?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can enable/disable \'Display profile progress bar\' setting under logged-in user settings to show/hide profile progress bar at member\'s page.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How to display profile progress bar at members page?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can enable/disable \'Display profile progress bar\' setting under logged-in user settings to show/hide profile progress bar at member\'s page.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How can members set their individual profile visibility?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can enable/disable \'Enable profile visibility settings at front end\' setting under logged-in user settings to show/hide profile visibility setting under Profile Visibility settings at member\'s page.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'Profile visibility setting gives options to members to select the visibility level.Suppose if member selects My Friedns then only member\'s friends will be able to see the profile and rest will not be able to access members pages and will be given a message \' You must be Friends in order to access { member\'s name } profile.\' ', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'The plugin overrides buddypress members single home page template to achieve the functionality, if your theme overrides buddypress template file then you may loss the theme members single page template structure when \'Enable profile visibility settings at front end\' setting is active.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'You will be needed to copy the members/single/home.php code from your theme directory and use it in the buddypress-private-community-pro/templates/members/single/home.php, as the plugin uses buddypress core template file for override, add below filter after do_action( \'bp_before_member_body\' );', 'buddypress-private-community-pro' ); ?>
						</p><pre>$blpro_prof_visib = apply_filters( 'blpro_profile_visility_home_override', $visib = true );</pre><p> 
							<?php esc_html_e( "Find following content in the home.php file", "buddypress-private-community-pro" ); ?>
						</p>
			<pre>
			if ( bp_is_user_front() ) :
				bp_displayed_user_front_template_part();

			elseif ( bp_is_user_activity() ) :
				bp_get_template_part( 'members/single/activity' );

			elseif ( bp_is_user_blogs() ) :
				bp_get_template_part( 'members/single/blogs'    );

			elseif ( bp_is_user_friends() ) :
				bp_get_template_part( 'members/single/friends'  );

			elseif ( bp_is_user_groups() ) :
				bp_get_template_part( 'members/single/groups'   );

			elseif ( bp_is_user_messages() ) :
				bp_get_template_part( 'members/single/messages' );

			elseif ( bp_is_user_profile() ) :
				bp_get_template_part( 'members/single/profile'  );

			elseif ( bp_is_user_forums() ) :
				bp_get_template_part( 'members/single/forums'   );

			elseif ( bp_is_user_notifications() ) :
				bp_get_template_part( 'members/single/notifications' );

			elseif ( bp_is_user_settings() ) :
				bp_get_template_part( 'members/single/settings' );

			// If nothing sticks, load a generic template
			else :
				bp_get_template_part( 'members/single/plugins'  );

			endif;</pre><p> 
							<?php esc_html_e( "Wrap above code with an if condition, the code will become:", "buddypress-private-community-pro" ); ?>
						</p>
						<pre>
		if ( $blpro_prof_visib ) {
			if ( bp_is_user_front() ) :
				bp_displayed_user_front_template_part();

			elseif ( bp_is_user_activity() ) :
				bp_get_template_part( 'members/single/activity' );

			elseif ( bp_is_user_blogs() ) :
				bp_get_template_part( 'members/single/blogs'    );

			elseif ( bp_is_user_friends() ) :
				bp_get_template_part( 'members/single/friends'  );

			elseif ( bp_is_user_groups() ) :
				bp_get_template_part( 'members/single/groups'   );

			elseif ( bp_is_user_messages() ) :
				bp_get_template_part( 'members/single/messages' );

			elseif ( bp_is_user_profile() ) :
				bp_get_template_part( 'members/single/profile'  );

			elseif ( bp_is_user_forums() ) :
				bp_get_template_part( 'members/single/forums'   );

			elseif ( bp_is_user_notifications() ) :
				bp_get_template_part( 'members/single/notifications' );

			elseif ( bp_is_user_settings() ) :
				bp_get_template_part( 'members/single/settings' );

			// If nothing sticks, load a generic template
			else :
				bp_get_template_part( 'members/single/plugins'  );

			endif;
		}</pre>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How can site admin add buddypress activities locks for logged-in members according to user roles or specific user?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can enable/disable \'Lock buddypress activities\' setting under logged-in user settings to lock/unlock buddypress several activities.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'If \'Lock buddypress activities\' setting is enabled site admin can select activities for eg. Private Message, Public Message, Posting etc to lock for users. Further site admin is required to select the type User Roles or Users to implement the functionality.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'If site admin selects \'User Role\' type then \'Select User Roles\' is available to set roles for which admin want to implement the lock.', 'buddypress-private-community-pro' ); ?>
						</p>
						<p> 
							<?php esc_html_e( 'If site admin selects \'Users\' type then \'Select users\' is available to select users for which admin want to implement the lock.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How can site admin restrict members from creating large number of groups?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can set maximium numbers \'Number of groups a member can create\' setting under member groups tab to limit members from creating large number of groups.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How can site admin restrict members from joining groups?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can set maximium numbers \'Number of groups a member can join\' setting under member groups tab to limit members from joining large number of groups.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="blpro-faq-row border">
				<div class="blpro-admin-col-12">
					<button class="blpro-accordion">
						<?php esc_html_e( 'How can site admin restrict number of members in a group?', 'buddypress-private-community-pro' ); ?>
					</button>
					<div class="blpro-panel">
						<p> 
							<?php esc_html_e( 'Site admin can set maximium numbers \'Limit number of member per group\' setting under member groups tab to limit number of members from joining groups.', 'buddypress-private-community-pro' ); ?>
						</p>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>