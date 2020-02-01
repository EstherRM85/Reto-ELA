=== BuddyPress Private Community Pro ===
Contributors: wbcomdesigns
Donate link: https://www.wbcomdesigns.com
Tags: buddypress, community, member, private
Requires at least: 3.0.1
Tested up to: 5.2.1
Stable tag: 1.7.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes your BuddyPress community private. You can control which areas of your site are accessible to logged out and logged in users in two different settings.Site admin can also configure groups restrictions settings.

== Description ==

This plugin makes your BuddyPress community private. You can control which areas of your site are accessible to logged out and logged in users in two different settings.Site admin can also configure groups restrictions settings.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Download the zip file and extract it.
2. Upload `buddypress-private-community-pro` directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the \'Plugins\' menu.
4. Enjoy
If you need additional help you can contact us for [Custom Development](https://wbcomdesigns.com/hire-us/).

== Frequently Asked Questions ==

= Does This plugin requires BuddyPress? =

Yes, It needs you to have BuddyPress installed and activated.

= What are the settings for non logged-in users? =

This plugin allows the site administrator to secure components of BuddyPress (if active), WordPress pages, custom post types from non-logged in users.You can lockdown WordPress Pages, any Custom Post Type and any BuddyPress Component and can have some content displayed like if you want to show any shortcode content or any simple message.

= What will happen if BuddyPress groups component is locked for non logged in users? =

If BuddyPress groups component is locked by admin, then groups page will not display groups list and restrict access on the single group page too and show content which will be set by admin in Locked Content.

= What will happen if BuddyPress members component is locked for non logged in users? =

If BuddyPress members component is locked by admin, members page will be resctricted and will show content which will be set by admin in Locked Content.

= What restrictions plugin provide to list members at member directory? =

Site admin can enable/disable Remove admin roles from members directory setting under logged-in user settings to exclude/include administrator roles to list at member directory page.

Site admin can select users Remove users from member directory list setting under logged-in user settings to exclude selected users to list at member directory page.

Site admin can set User profile completeness percentage to get listed in member directory setting under logged-in user settings to exclude users with profile percentage less than percentage set by admin.

= How to display profile progress bar at members page? =

Site admin can enable/disable Display profile progress bar setting under logged-in user settings to show/hide profile progress bar at member\'s page.

= How can members set their individual profile visibility? =

Site admin can enable/disable Enable profile visibility settings at front end setting under logged-in user settings to show/hide profile visibility setting under Profile Visibility settings at members page.

= How can site admin add buddypress activities locks for logged-in members according to user roles or specific user? =

Site admin can enable/disable Lock buddypress activities setting under logged-in user settings to lock/unlock buddypress several activities.

If Lock buddypress activities setting is enabled site admin can select activities for eg. Private Message, Public Message, Posting etc to lock for users. Further site admin is required to select the type User Roles or Users to implement the functionality.

If site admin selects User Role type then Select User Roles is available to set roles for which admin want to implement the lock.

If site admin selects Users type then Select users is available to select users for which admin want to implement the lock.

= How can site admin restrict members from creating large number of groups? =

Site admin can set maximium numbers Number of groups a member can create setting under member groups tab to limit members from creating large number of groups.

= How can site admin restrict members from joining groups? =

Site admin can set maximium numbers Number of groups a member can join setting under member groups tab to limit members from joining large number of groups.

= How can site admin restrict number of members in a group? =

Site admin can set maximium numbers Limit number of member per group setting under member groups tab to limit number of members from joining groups.

== Changelog ==

= 1.7.3 =
* Fix: boss theme template loading issue.
* Fix: Admin settings select box height issue. #30
* Fix: Member group creation fix. #29

= 1.7.2 =
* Enhancement: hide buddypress primary nav settings

= 1.7.1 =
* Fix: #28 - added fontawesome 4.7.0.

= 1.7.0 =
* Enhancement- BP 4.3.0 compatibility.
* Fix: #25 - bp component check warning.
* Fix: #26 - sql database error fix at members page.
* Fix: #27 - unnecessary script run.

= 1.6.0 =
* Fix: #24 - add limit for buddypress nouveau ajax call for group join

= 1.6.0 =
* Fix: #24 - add limit for buddypress nouveau ajax call for group join

= 1.5.0 =
* Fix: (#24) User is able to join more than restricted limit.

= 1.4.0 =
* Fix: (#23) Member groups restrictions issue

= 1.3.0 =
* Enhancement: Add tempalte to override login and register forms

= 1.2.0 =
* Enhancement: Added member types restrictions for bp components.
* Enhancement: Added member types restrictions for groups creation and joining.

= 1.1.0 =
* Enhancement: Plugin backend settings ui enhancement.
* Fix : BP 4.1.0 compatibility.

= 1.0.2 =
* Fix : BP 3.0.2 compatibility.
* Fix : Login & Register Forms with Reign

= 1.0.1 =
* BP nouveau template pack support.
* Added plugin license code

= 1.0.0 =
* first version.
