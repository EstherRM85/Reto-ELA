<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wbcom_Render_Postmeta_Fields' ) ) :

/**
 * @class Wbcom_Render_Postmeta_Fields
 */
class Wbcom_Render_Postmeta_Fields {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Wbcom_Render_Postmeta_Fields
	 */
	protected static $_instance = null;
	protected static $_theme_slug = 'reign';
	
	/**
	 * Main Wbcom_Render_Postmeta_Fields Instance.
	 *
	 * Ensures only one instance of Wbcom_Render_Postmeta_Fields is loaded or can be loaded.
	 *
	 * @return Wbcom_Render_Postmeta_Fields - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wbcom_Render_Postmeta_Fields Constructor.
	 */
	public function __construct() {
	}

	/**
	 * function to render dropdown.
	 * @since 1.0.4
	 */
	public function render_dropdown_option( $args = array() ) {
		$defaults = array(
			'label'	=>	'',
			'desc'	=>	'',
			'section_name'	=>	'',
			'field_name'	=>	'',
			'options_array'	=>	array(),
		);
		$args = wp_parse_args( $args, $defaults );
		
		global $post;
		$wbcom_metabox_data = get_post_meta( $post->ID, self::$_theme_slug . '_wbcom_metabox_data', true );
		$args['value']	= isset( $wbcom_metabox_data[$args['section_name']][$args['field_name']] ) ? $wbcom_metabox_data[$args['section_name']][$args['field_name']] : '';
		$field_name_to_use = $args['section_name']."[".$args['field_name']."]";
		?>
		<div class="wbcom-metabox-control wbcom-metabox-control-dropdown">
			<div class="wbcom-metabox-desc">
				<span class="wbcom-metabox-label"><?php echo $args['label']; ?></span>
				<div class="rtm-tooltip">?
					<span class="rtm-tooltiptext"><?php echo $args['desc']; ?></span>
				</div>
			</div>
			<div class="wbcom-metabox-field">
				<select name="<?php echo $field_name_to_use; ?>" class="wbcom-metabox-select2">
					<?php
					if( !empty( $args['options_array'] ) && is_array( $args['options_array'] ) ) {
						foreach ( $args['options_array'] as $key => $value ) {
							echo '<option value="' . $key . '" ' . selected( $args['value'], $key, false ) . '>' . $value . '</option>';
						}
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * function to render radio buttons.
	 * @since 1.0.4
	 */
	public function render_radio_option( $args = array() ) {
		$defaults = array(
			'label'	=>	'',
			'desc'	=>	'',
			'section_name'	=>	'',
			'field_name'	=>	'',
			'options_array'	=>	array(),
		);
		$args = wp_parse_args( $args, $defaults );
		
		global $post;
		$wbcom_metabox_data = get_post_meta( $post->ID, self::$_theme_slug . '_wbcom_metabox_data', true );
		$args['value']	= isset( $wbcom_metabox_data[$args['section_name']][$args['field_name']] ) ? $wbcom_metabox_data[$args['section_name']][$args['field_name']] : '';
		$field_name_to_use = $args['section_name']."[".$args['field_name']."]";
		?>
		<div class="wbcom-metabox-control wbcom-metabox-control-radio">
			<div class="wbcom-metabox-desc">
				<span class="wbcom-metabox-label"><?php echo $args['label']; ?></span>
				<div class="rtm-tooltip">?
					<span class="rtm-tooltiptext"><?php echo $args['desc']; ?></span>
				</div>
			</div>
			<div class="wbcom-metabox-field">
				<?php
				if( !empty( $args['options_array'] ) && is_array( $args['options_array'] ) ) {
					echo '<ul class="wbcom-metabox-radio-wrapper">';
						foreach ( $args['options_array'] as $key => $value ) {
							echo '<li>';
								echo '<input type="radio" id="wbcom-metabox-radio-' . $key . '" name="'. $field_name_to_use .'" value="'. $key . '" />';
								echo '<label for="wbcom-metabox-radio-' . $key . '">' . $value . '</label>';
							echo '</li>';
						}
					echo '</ul>';
				}
				?>
			</div>
		</div>
		<?php
	}

	public function render_checkbox_option( $args = array() ) {
		$defaults = array(
			'label'	=>	'',
			'desc'	=>	'',
			'section_name'	=>	'',
			'field_name'	=>	'',
			'option'	=>	array(),
		);
		$args = wp_parse_args( $args, $defaults );
		
		global $post;
		$wbcom_metabox_data = get_post_meta( $post->ID, self::$_theme_slug . '_wbcom_metabox_data', true );
		$args['value']	= isset( $wbcom_metabox_data[$args['section_name']][$args['field_name']] ) ? $wbcom_metabox_data[$args['section_name']][$args['field_name']] : '';
		$field_name_to_use = $args['section_name']."[".$args['field_name']."]";
		?>
		<div class="wbcom-metabox-control wbcom-metabox-control-radio">
			<div class="wbcom-metabox-desc">
				<span class="wbcom-metabox-label"><?php echo $args['label']; ?></span>
				<div class="rtm-tooltip">?
					<span class="rtm-tooltiptext"><?php echo $args['desc']; ?></span>
				</div>
			</div>
			<div class="wbcom-metabox-field">
				<?php
				echo '<input type="checkbox" name="'.$field_name_to_use.'" value="on" '.checked('on', $args['value'], false).'>';
				?>
			</div>
		</div>
		<?php
	}
	
}

endif;

/**
 * Main instance of Wbcom_Render_Postmeta_Fields.
 * @return Wbcom_Render_Postmeta_Fields
 */
$GLOBALS['wbcom_render_postmeta_fields'] = Wbcom_Render_Postmeta_Fields::instance();
?>