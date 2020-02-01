<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'RTM_Mega_Menu_Customization' ) ) :

/**
 * @class RTM_Mega_Menu_Customization
 */
class RTM_Mega_Menu_Customization {
	
	/**
	 * The single instance of the class.
	 *
	 * @var RTM_Mega_Menu_Customization
	 */
	protected static $_instance = null;
	
	/**
	 * Main RTM_Mega_Menu_Customization Instance.
	 *
	 * Ensures only one instance of RTM_Mega_Menu_Customization is loaded or can be loaded.
	 *
	 * @return RTM_Mega_Menu_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * RTM_Mega_Menu_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {

		add_filter("megamenu_output_public_toggle_block_reign_logo", array( $this, 'reign_output_public_toggle_block_reign_logo' ), 10, 2 );

		add_filter("megamenu_output_public_toggle_block_reign_icons", array( $this, 'reign_output_public_toggle_block_reign_icons' ), 10, 2 );
	
		add_filter( "megamenu_registered_toggle_blocks", array( $this, "reign_megamenu_registered_toggle_blocks" ), 10, 1 );

		add_action( 'wp_ajax_mm_get_toggle_block_reign_logo', array( $this, 'output_reign_logo_block_html' ) );
		add_action( 'megamenu_output_admin_toggle_block_reign_logo', array( $this, 'output_reign_logo_block_html' ), 10, 2 );

		add_action( 'wp_ajax_mm_get_toggle_block_reign_icons', array( $this, 'output_reign_icons_block_html' ) );
		add_action( 'megamenu_output_admin_toggle_block_reign_icons', array( $this, 'output_reign_icons_block_html' ), 10, 2 );

		add_filter( 'body_class', array( $this, 'manage_body_class' ), 10, 1 );
		//add_filter( 'reign_theme_header_choices', array( $this, 'reign_theme_header_choices' ), 10, 1 );

		add_action( 'wp_head', array( $this, 'manage_header_v4_css' ), 999 );
	
	}

	public function manage_header_v4_css() {
		$default_value_set = reign_get_customizer_default_value_set();
		$reign_header_main_menu_font = get_theme_mod( 'reign_header_main_menu_font', $default_value_set['reign_header_main_menu_font'] );
		$reign_header_mobile_menu_font = get_theme_mod( 'reign_header_mobile_menu_font', $default_value_set['reign_header_mobile_menu_font'] );
		$reign_header_mobile_menu_bg_color = get_theme_mod( 'reign_header_mobile_menu_bg_color', $default_value_set['reign_header_mobile_menu_bg_color'] );
		
		$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
		if( !$reign_header_header_type ) {
			$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
			if( 'v4' === $header_version ) {
				?>
				<style type="text/css">
					body.reign-header-v4 header#masthead .reign-fallback-header.version-four .mega-menu-wrap,
					body.reign-header-v4 header#masthead .reign-fallback-header.version-four .mega-menu-wrap ul.mega-menu.max-mega-menu.mega-menu-horizontal li.mega-menu-item > a.mega-menu-link {
						background: transparent !important;
					}

					body.reign-header-v4 header#masthead .reign-fallback-header.version-four .mega-menu-wrap ul.mega-menu.max-mega-menu.mega-menu-horizontal > li.mega-menu-item > a.mega-menu-link {
						font-family: <?php echo $reign_header_main_menu_font['font-family']; ?> !important;
						font-weight: <?php echo $reign_header_main_menu_font['variant']; ?> !important;
						font-size: <?php echo $reign_header_main_menu_font['font-size']; ?> !important;
						letter-spacing: <?php echo $reign_header_main_menu_font['letter-spacing']; ?> !important;
						color: <?php echo $reign_header_main_menu_font['color']; ?> !important;
						text-transform: <?php echo $reign_header_main_menu_font['text-transform']; ?> !important;
						text-align: <?php echo $reign_header_main_menu_font['text-align']; ?> !important;
						font-weight: <?php echo $reign_header_main_menu_font['font-weight']; ?> !important;
						font-style: <?php echo $reign_header_main_menu_font['font-style']; ?> !important;
					}
					@media (max-width: 767px) {
						body.reign-header-v4 header#masthead .reign-fallback-header.version-four .mega-menu-wrap ul.mega-menu.max-mega-menu.mega-menu-horizontal {
							background-color: <?php echo $reign_header_mobile_menu_bg_color; ?> !important;
						}
						body.reign-header-v4 header#masthead .reign-fallback-header.version-four .mega-menu-wrap ul.mega-menu.max-mega-menu.mega-menu-horizontal > li.mega-menu-item > a.mega-menu-link {
							font-family: <?php echo $reign_header_mobile_menu_font['font-family']; ?> !important;
							font-weight: <?php echo $reign_header_mobile_menu_font['variant']; ?> !important;
							font-size: <?php echo $reign_header_mobile_menu_font['font-size']; ?> !important;
							letter-spacing: <?php echo $reign_header_mobile_menu_font['letter-spacing']; ?> !important;
							color: <?php echo $reign_header_mobile_menu_font['color']; ?> !important;
							text-transform: <?php echo $reign_header_mobile_menu_font['text-transform']; ?> !important;
							text-align: <?php echo $reign_header_mobile_menu_font['text-align']; ?> !important;
							font-weight: <?php echo $reign_header_mobile_menu_font['font-weight']; ?> !important;
							font-style: <?php echo $reign_header_mobile_menu_font['font-style']; ?> !important;
						}
					}
				</style>
				<?php
			}
		}
	}

	public function reign_theme_header_choices( $choices ) {
		$choices['v4'] = REIGN_THEME_URI . '/lib/images/header-v4.png';
		return $choices;
	}

	public function manage_body_class( $classes ) {
		$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
		if( !$reign_header_header_type ) {
			$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
			if( 'v4' === $header_version ) {
				$classes[] = 'reign-header-v4';
			}
		}
		return $classes;
	}

	public function reign_output_public_toggle_block_reign_logo( $block_html, $block ) {
		$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
		if( !$reign_header_header_type ) {
			$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
			if( 'v4' !== $header_version ) {
				return;
			}
		}
		ob_start();
		?>
		<div class="site-branding">
			<div class="logo">
				<?php
				if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
					$mobile_menu_logo_enable = get_theme_mod( 'reign_header_mobile_menu_logo_enable', false );
					if( $mobile_menu_logo_enable ) {
						$reign_header_mobile_menu_logo = get_theme_mod( 'reign_header_mobile_menu_logo', '' );
						if( !empty( $reign_header_mobile_menu_logo ) ) {
							echo '<img class="reign-mobile-menu" src="'.$reign_header_mobile_menu_logo.'" />';
						}
						else {
							the_custom_logo();
						}
					}
					else {
						the_custom_logo();
					}
				} else {
					if ( is_front_page() && is_home() ) :
						?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;
				}
				?>
			</div>
		</div>
		<?php
		$block_html  = ob_get_clean();
		return $block_html;
	}

	public function reign_output_public_toggle_block_reign_icons( $block_html, $block ) {
		$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
		if( !$reign_header_header_type ) {
			$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
			if( 'v4' !== $header_version ) {
				return;
			}
		}
		ob_start();
		echo '<div class="header-right no-gutter wb-grid-flex wb-grid-center">';
		$reign_header_default_icons_set = reign_header_default_icons_set();
		$reign_header_icons_set = get_theme_mod( 'reign_header_icons_set', $reign_header_default_icons_set );
		foreach ( $reign_header_icons_set as $header_icon ) {
			get_template_part( 'template-parts/header-icons/'.$header_icon, '' );
		}
		echo '</div>';
		$block_html  = ob_get_clean();
		return $block_html;
	}

	public function reign_megamenu_registered_toggle_blocks( $block_types ) {
		$block_types['reign_logo'] = __("Reign: Site Logo", "reign");
		$block_types['reign_icons'] = __("Reign: Menu Icons", "reign");
		return $block_types;
	}

	public function output_reign_logo_block_html( $block_id, $settings = array() ) {

	    if ( empty( $settings ) ) {
	        $block_id = "0";
	    }

	    $defaults = array(
	        'align' => 'right',
	    );

	    $settings = array_merge( $defaults, $settings );

	    ?>

	    <div class='block'>
	        <div class='block-title'><?php esc_html_e("Reign: Site Logo", "megamenu"); ?> <span title='<?php esc_html_e("Reign: Site Logo", "megamenu"); ?>' class="dashicons dashicons-arrow-down"></span></div>
	        <div class='block-settings'>
	            <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='reign_logo' />
	            <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
	            <a class='mega-delete'><?php esc_html_e("Delete", "megamenu"); ?></a>
	        </div>
	    </div>

	    <?php
	}

	public function output_reign_icons_block_html( $block_id, $settings = array() ) {

	    if ( empty( $settings ) ) {
	        $block_id = "0";
	    }

	    $defaults = array(
	        'align' => 'center',
	    );

	    $settings = array_merge( $defaults, $settings );

	    ?>

	    <div class='block'>
	        <div class='block-title'><?php esc_html_e("Reign: Menu Icons", "megamenu"); ?> <span title='<?php esc_html_e("Reign: Menu Icons", "megamenu"); ?>' class="dashicons dashicons-arrow-down"></span></div>
	        <div class='block-settings'>
	            <input type='hidden' class='type' name='toggle_blocks[<?php echo $block_id; ?>][type]' value='reign_icons' />
	            <input type='hidden' class='align' name='toggle_blocks[<?php echo $block_id; ?>][align]' value='<?php echo $settings['align'] ?>'>
	            <a class='mega-delete'><?php esc_html_e("Delete", "megamenu"); ?></a>
	        </div>
	    </div>

	    <?php
	}
		
}

endif;

/**
 * Main instance of RTM_Mega_Menu_Customization.
 * @return RTM_Mega_Menu_Customization
 */
RTM_Mega_Menu_Customization::instance();