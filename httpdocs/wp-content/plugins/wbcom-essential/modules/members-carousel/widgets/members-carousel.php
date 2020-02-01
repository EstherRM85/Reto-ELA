<?php

namespace WbcomElementorAddons\Modules\MembersCarousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class MembersCarousel extends Widget_Base {

	protected $nav_menu_index = 1;

	public function get_name() {
		return 'wbcom-members-carousel';
	}

	public function get_title() {
		return __( 'Members Carousel', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'wbcom-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
		'section_reign_notification_area', [
			'label' => __( 'Settings', 'elementor' ),
		]
		);

		$this->add_control(
			'full-width',
			[
				'label'       => __( 'Carousel Style', 'wbcom-essential' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Boxed', 'wbcom-essential' ),
				'label_on' => esc_html__( 'Full-Width', 'wbcom-essential' ),
				'default' => '',
				'return_value' => '1',
				'description' => esc_html__( 'Enable Full width Carousel', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Sort', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => [
					'newest'  => esc_html__( 'Newest', 'wbcom-essential' ),
					'active'  => esc_html__( 'Most Active', 'wbcom-essential' ),
					'popular' => esc_html__( 'Most Popular', 'wbcom-essential' ),
				]
			]
		);

		$this->add_control(
			'total',
			[
				'label'       => __( 'Total members', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '12',
				'placeholder' => __( 'Total members', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'scroll',
			[
				'label'       => __( 'Members to scroll', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 2,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'visible-lg',
			[
				'label'       => __( 'Visible members - Large Desktop', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 5,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'visible-dd',
			[
				'label'       => __( 'Visible members - Desktop', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 4,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'visible-md',
			[
				'label'       => __( 'Visible members - Tablet', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 3,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'visible-sm',
			[
				'label'       => __( 'Visible members - Mobile', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 2,
				'placeholder' => '',
			]
		);

		$this->add_control(
			'visible-xs',
			[
				'label'       => __( 'Visible members - Small Mobile', 'wbcom-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 2,
				'placeholder' => '',
			]
		);

		$this->end_controls_section();

		do_action( 'reign_wp_menu_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		if ( ! function_exists( 'bp_is_active' ) ) {
			esc_html_e( 'You need BuddyPress plugin to be active!', 'wbcom-essential' );
			return;
		}

		wp_enqueue_script( 'slick' );

		$settings = $this->get_settings();
		$rand = mt_rand(99, 999);

		$this->add_render_attribute( 'wrapper', [
			'class' => 'wbcom-sh-carousel',
		] );

		if ($settings['full-width'] == 1) {
			$this->add_render_attribute( 'wrapper', [
				'class' => 'wbcom-sh-carousel-full',
			] );
		}
		?>
		<div>

			<div class="row mb-3">

				<div class="col-12 d-flex justify-content-between">
					<a href="#" class="wbcom-slick-prev btn btn-xs" data-carousel="slick-<?php echo esc_attr( $rand ); ?>-0">
						<i class="icon icon-arrow-left"></i>
					</a>
					<a href="#" class="wbcom-slick-next btn btn-xs" data-carousel="slick-<?php echo esc_attr( $rand ); ?>-0">
						<i class="icon icon-arrow-right"></i>
					</a>

				</div>
			</div>


					<section class="item-list wbcom-slick row" data-carousel="slick-<?php echo esc_attr( $rand ); ?>"
					         data-arrows="false" data-infinite="true"
					         data-dd-show-slides="<?php echo esc_attr( $settings['visible-dd'] );?>"
					         data-lg-show-slides="<?php echo esc_attr( $settings['visible-lg'] );?>"
					         data-md-show-slides="<?php echo esc_attr( $settings['visible-md'] );?>"
					         data-sm-show-slides="<?php echo esc_attr( $settings['visible-sm'] );?>"
					         data-xs-show-slides="<?php echo esc_attr( $settings['visible-xs'] );?>"
					         data-scroll-slides="<?php echo esc_attr( $settings['scroll'] );?>">

						<?php
						$query_string = '&type=' . $settings['type'] . '&per_page=' . $settings['total'] . '&max=' . $settings['total'];
						if ( isset( $tab['field_id'] ) && isset( $tab['field_value'] ) && ! empty( $tab['field_id'] ) &&! empty( $tab['field_value'] ) ) {
							$query_string .= wbcom_bp_custom_ids( $tab['field_id'], $tab['field_value'] );
						}

						if ( bp_has_members( bp_ajax_querystring( 'members' ) . $query_string ) ) :

							while ( bp_members() ) : bp_the_member(); ?>
							<div class="col-lg-3">
								<div <?php bp_member_class(); ?>>
									<div class="item-container">
										<div class="item-avatar">
											<figure class="img-dynamic aspect-ratio avatar">

												<a class="img-card" href="<?php bp_member_permalink(); ?>">
													<?php bp_member_avatar(); ?>
												</a>

											</figure>
										</div>

										<div class="item-card">
											<div class="item">

												<div class="item-meta">
													<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>">
														<?php bp_member_last_active(); ?>
													</span>
												</div>

												<h4 class="item-title h5">
													<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
												</h4>

												<?php

												/**
												 * Fires inside the display of a directory member item.
												 *
												 * @since 1.1.0
												 */
												do_action( 'bp_directory_members_item' ); ?>

												<?php
												/***
												 * If you want to show specific profile fields here you can,
												 * but it'll add an extra query for each member in the loop
												 * (only one regardless of the number of fields you show):
												 *
												 * bp_member_profile_data( 'field=the field name' );
												 */
												?>
											</div>

											<div class="action"><?php

												/**
												 * Fires inside the members action HTML markup to display actions.
												 *
												 * @since 1.1.0
												 */
												do_action( 'bp_directory_members_actions' ); ?></div>
										</div>

									</div>
								</div>
							</div>

								<?php
							endwhile;
						else :
							esc_html_e( 'No members found by the criteria.', 'wbcom-essential' );
						endif;
						?>
					</section>
		</div>

		<?php

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
