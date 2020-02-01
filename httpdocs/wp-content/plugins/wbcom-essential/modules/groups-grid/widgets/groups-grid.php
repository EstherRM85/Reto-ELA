<?php

namespace WbcomElementorAddons\Modules\GroupsGrid\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class GroupsGrid extends Widget_Base {

	protected $nav_menu_index = 1;

	public function get_name() {
		return 'wbcom-groups-grid';
	}

	public function get_title() {
		return __( 'Groups Grid', 'wbcom-essential' );
	}

	protected function _register_skins() {
		add_action( 'bp_enqueue_scripts', array( $this, 'reign_group_grid_enqueue_scripts' ) );
	}

	public function reign_group_grid_enqueue_scripts() {
		wp_enqueue_script( 'bp-widget-members' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'wbcom-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_groups_grid',
			[
				'label' => esc_html__( 'Settings', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Sort', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => [
					'newest'       => esc_html__( 'Newest', 'wbcom-essential' ),
					'active'       => esc_html__( 'Most Active', 'wbcom-essential' ),
					'popular'      => esc_html__( 'Most Popular', 'wbcom-essential' ),
					'random'       => esc_html__( 'Random', 'wbcom-essential' ),
					'alphabetical' => esc_html__( 'Alphabetical', 'wbcom-essential' ),
				]
			]
		);

		$this->add_control(
			'total',
			[
				'label'       => esc_html__( 'Total groups', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '12',
				'placeholder' => esc_html__( 'Total groups', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => [
					'3'  => '3',
					'4'  => '4',
				]
			]
		);

		$this->add_control(
			'rg-grp-grid-layout',
			[
				'label'   => esc_html__( 'Layout', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'wbtm-group-directory-type-2',
				'options' => [
					'wbtm-group-directory-type-1'  => 'Layout 1',
					'wbtm-group-directory-type-2'  => 'Layout 2',
					'wbtm-group-directory-type-3'  => 'Layout 3',
					'wbtm-group-directory-type-4'  => 'Layout 4',
				]
			]
		);

		$this->end_controls_section();

		do_action( 'reign_wp_groups_grid_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		if ( ! function_exists( 'bp_is_active' ) ) {
			esc_html_e( 'You need BuddyPress plugin to be active!', 'seeko' );

			return;
		}

		if ( ! bp_is_active( 'groups' ) ) {
			esc_html_e( 'Groups component needs to be active!', 'seeko' );

			return;
		}

		$settings = $this->get_settings();
		$rand     = mt_rand( 99, 999 );

		global $groups_template;

		$group_directory_type = $settings['rg-grp-grid-layout'];
	
		$addition_class			 = '';
		if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
			$addition_class = 'lg-wb-grid-1-'.$settings['columns'];
		}
		if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
			$img_class = 'img-card';
		}
					
		$query_string = '&type=' . $settings['type'] . '&per_page=' . $settings['total'] . '&max=' . $settings['total'];

		$active_template = get_option('_bp_theme_package_id');
		echo '<div id="buddypress" class="buddypress-wrap reign-groups-grid-widget">';
		if( 'legacy' == $active_template ){ ?>
			<div class="groups dir-list">
				<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . $query_string ) ) : ?>
				<?php
				/**
				 * Fires before the listing of the groups list.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_directory_groups_list' );
				?>
				<ul id="groups-list" class="item-list wb-grid rg-group-list <?php echo $group_directory_type; ?>" aria-live="assertive" aria-atomic="true" aria-relevant="all">

					<?php
					while ( bp_groups() ) :
						bp_the_group();
						?>

						<li <?php bp_group_class( array( "wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-".$settings['columns']." ".$addition_class ) ); ?>>
							<div class="bp-group-inner-wrap">

								<?php
								/**
								 * Fires inside the listing of an individual group listing item.
								 * Added by Reign Theme
								 * @since 1.0.7
								 */
								if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
									$args			 = array(
										'object_dir' => 'groups',
										'item_id'	 => $group_id	 = bp_get_group_id(),
										'type'		 => 'cover-image',
									);
									$cover_img_url	 = bp_attachments_get_attachment( 'url', $args );
									if ( empty( $cover_img_url ) ) {
										global $wbtm_reign_settings;
										$cover_img_url	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
										if( empty( $cover_img_url ) ) {
											$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
										}
									}
									echo '<div class="wbtm-group-cover-img"><img src="' . $cover_img_url . '" /></div>';
								}
								?>
								<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
									<?php
									if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
										echo '<figure class="img-dynamic aspect-ratio avatar">';
									}
									?>
									<a class="item-avatar-group <?php echo $img_class; ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( '' ); ?></a>
									<?php
									if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
										echo '</figure>';
									}
									?>
								<?php endif; ?>

								<div class="group-content-wrap">

									<div class="item">
										<div class="item-title"><?php bp_group_link(); ?></div>
										<?php
										/**
										 * Fires inside the listing of an individual group listing item.
										 *
										 * @since 1.1.0
										 */
										do_action( 'bp_directory_groups_item' );
										?>

									</div>

									<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

									<div class="group-admins-wrap">
										<?php reign_bp_group_list_admins(); ?>
									</div>
									<?php
									if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
										echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
									}
									?>
									<div class="action">

										<?php
										/**
										 * Fires inside the action section of an individual group listing item.
										 *
										 * @since 1.1.0
										 */
										do_action( 'bp_directory_groups_actions' );
										?>


									</div>
									<?php
									if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
										echo '</div>';
									}
									?>
								</div>
							</div>
						</li>
					<?php endwhile; ?>
				</ul>

				<?php
				/**
				 * Fires after the listing of the groups list.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_directory_groups_list' );
				?>
				<?php else : ?>

					<div id="message" class="info">
						<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
					</div>

				<?php endif; ?>
			</div>
		<?php }elseif( 'nouveau' == $active_template ) { ?>
			<div id="groups-dir-list" class="groups dir-list">
				<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . $query_string ) ) : 
					$col_class = $settings['columns'];
					if( $col_class == '4' ){
						$_col_class = 'four';
					}elseif( $col_class == '3' ){
						$_col_class = 'three';
					}
					?>
					<ul id="groups-list" class="rg-group-list item-list groups-list bp-list grid <?php echo $_col_class.' '.$group_directory_type;?>">

						<?php
						while ( bp_groups() ) :
							bp_the_group();
							?>

						<li <?php bp_group_class( array( 'item-entry' ) ); ?> data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups">
								<div class="list-wrap">

								<?php
								/**
								 * Fires inside the listing of an individual group listing item.
								 * Added by Reign Theme
								 * @since 1.0.7
								 */
								//do_action( 'wbtm_before_group_avatar_group_directory' );
								if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
									$args			 = array(
										'object_dir' => 'groups',
										'item_id'	 => $group_id	 = bp_get_group_id(),
										'type'		 => 'cover-image',
									);
									$cover_img_url	 = bp_attachments_get_attachment( 'url', $args );
									if ( empty( $cover_img_url ) ) {
										global $wbtm_reign_settings;
										$cover_img_url	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
										if( empty( $cover_img_url ) ) {
											$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
										}
									}
									echo '<div class="wbtm-group-cover-img"><img src="' . $cover_img_url . '" /></div>';
								}
								?>

								<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
									<div class="item-avatar">
										<?php
										if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
											echo '<figure class="img-dynamic aspect-ratio avatar">';
										}
										?>
										<a class="item-avatar-group <?php echo $img_class; ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a><?php
										if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
											echo '</figure>';
										}
										?>
									</div>
								<?php endif; ?>

								<div class="group-content-wrap">
									<div class="item">

										<div class="item-block">

											<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

											<?php if ( bp_nouveau_group_has_meta() ) : ?>

												<p class="item-meta group-details"><?php bp_nouveau_group_meta(); ?></p>

											<?php endif; ?>

											<p class="last-activity item-meta">
												<?php
												printf(
												/* translators: %s = last activity timestamp (e.g. "active 1 hour ago") */
												__( 'active %s', 'buddypress' ), bp_get_group_last_active()
												);
												?>
											</p>

										</div>

										<div class="group-desc"><p><?php bp_nouveau_group_description_excerpt(); ?></p></div>

										<?php bp_nouveau_groups_loop_item(); ?>

										<?php //bp_nouveau_groups_loop_buttons();  ?>

									</div>

									<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

									<div class="group-admins-wrap">
										<?php reign_bp_group_list_admins(); ?>
									</div>

									<!-- Added action buttons here -->
									<?php
									if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
										echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
									}
									bp_nouveau_groups_loop_buttons();
									if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
										echo '</div>';
									}
									?>
								</div>
							</div>
						</li>

						<?php endwhile; ?>

					</ul>
					<?php else : ?>

						<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

					<?php endif; ?>
			</div>
		<?php }
		echo '</div>';
	}
	/**
	 * This is outputted while rending the page.
	 */
	protected function content_template() {
		?>
		<div class="reign-wp-menu-content-area">
		</div>
		<?php
	}

}
