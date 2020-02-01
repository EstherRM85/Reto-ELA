<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */

$bp_pages = get_option( 'bp-pages' );					
if ( bp_is_current_component( 'groups' ) ){
	$post	 = get_post( $bp_pages['groups'] );
} elseif ( bp_is_current_component( 'members' )  || bp_is_user()) {
	$post	 = get_post( $bp_pages['members'] );
} elseif ( bp_is_current_component( 'activity' ) ) {
	$post	 = get_post( $bp_pages['activity'] );
}
$theme_slug			 = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
$wbcom_metabox_data	 = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
$site_layout		 = isset( $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] ) ? $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] : '';

if ( ( $site_layout == 'both_sidebar' ) || ( $site_layout == 'right_sidebar' ) ) {
	$sidebar_id = $wbcom_metabox_data['layout']['primary_sidebar'];	
}elseif( $site_layout == '0' ){
	$sidebar_id = '0';
} else if ( !bp_is_user() ) {	
	return;
}


if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() && !bp_is_group_create() ) {
	if ( function_exists( 'bp_get_theme_package_id' ) ) {
		$theme_package_id = bp_get_theme_package_id();
	} else {
		$theme_package_id = 'legacy';
	}
	if ( 'legacy' === $theme_package_id ) {
		$class = 'widget-area member-index-widget-area sm-wb-grid-1-3 md-wb-grid-1-5';
	} else {
		$class = 'widget-area member-index-widget-area md-wb-grid-1-3';
		$sidebar_id = ( $sidebar_id != '0' ) ? $sidebar_id : 'group-index';		
		if( ! is_active_sidebar( $sidebar_id ) ) {
			return;
		}
	}
	?>
	<aside id="left" class="<?php echo $class; ?>" role="complementary">
		<div class="widget-area-inner">
			<?php do_action( 'wbcom_begin_group_index_sidebar' ); ?>
			<?php dynamic_sidebar( $sidebar_id ); ?>
			<?php do_action( 'wbcom_end_group_index_sidebar' ); ?>
		</div>
	</aside>
	<?php
} elseif ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
	if ( function_exists( 'bp_get_theme_package_id' ) ) {
		$theme_package_id = bp_get_theme_package_id();
	} else {
		$theme_package_id = 'legacy';
	}
	if ( 'legacy' === $theme_package_id ) {
		$class = 'widget-area member-index-widget-area sm-wb-grid-1-3 md-wb-grid-1-5';
	} else {
		$class = 'widget-area member-index-widget-area md-wb-grid-1-3';
		$sidebar_id = ( $sidebar_id != '0' ) ? $sidebar_id : 'member-index';
		if( ! is_active_sidebar( $sidebar_id ) ) {
			return;
		}
	}
	?>
	<aside id="left" class="<?php echo $class; ?>" role="complementary">
		<div class="widget-area-inner">
	<?php do_action( 'wbcom_begin_member_index_sidebar' ); ?>
			<?php dynamic_sidebar( $sidebar_id ); ?>
			<?php do_action( 'wbcom_end_member_index_sidebar' ); ?>
		</div>
	</aside>
	<?php
} elseif ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
	
	$sidebar_id = ( $sidebar_id != '0' ) ? $sidebar_id : 'activity-index';
	
	if (   is_active_sidebar( $sidebar_id ) ) {
		?>
		<aside id="secondary" class="widget-area activity-index-widget-area sm-wb-grid-1-3" role="complementary">
			<div class="widget-area-inner">
				<?php do_action( 'wbcom_begin_activity_index_sidebar' ); ?>
				<?php dynamic_sidebar( $sidebar_id ); ?>
				<?php do_action( 'wbcom_end_activity_index_sidebar' ); ?>
			</div>
		</aside>
		<?php
	}
} elseif ( is_active_sidebar( 'group-single' ) && bp_is_group() && !bp_is_group_create() ) {	
	?>
	<aside id="secondary" class="widget-area group-single-widget-area sm-wb-grid-1-1 md-wb-grid-1-1 lg-wb-grid-1-3" role="complementary">
		<div class="widget-area-inner">
	<?php do_action( 'wbcom_begin_group_single_sidebar' ); ?>
			<?php dynamic_sidebar( 'group-single' ); ?>
			<?php do_action( 'wbcom_end_group_single_sidebar' ); ?>
		</div>
	</aside>
	<?php
} elseif ( is_active_sidebar( 'member-profile' ) && bp_is_user() ) {
	?>
	<aside id="secondary" class="widget-area member-profile-widget-area sm-wb-grid-1-1 md-wb-grid-1-1 lg-wb-grid-1-3" role="complementary">
		<div class="widget-area-inner">
	<?php do_action( 'wbcom_begin_member_profile_sidebar' ); ?>
			<?php dynamic_sidebar( 'member-profile' ); ?>
			<?php do_action( 'wbcom_end_member_profile_sidebar' ); ?>
		</div>
	</aside>
	<?php
}