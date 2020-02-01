<?php
$current_active_tab = 0;
if ( isset( $_POST[ 'render_theme_setting_current_tab' ] ) ) {
	$current_active_tab = intval( $_POST[ 'render_theme_setting_current_tab' ] );
}
?>
<input type="hidden" id="render_theme_setting_current_tab" name="render_theme_setting_current_tab" value="<?php echo $current_active_tab; ?>" />

<?php
$vertical_tabs;
if ( isset( $vertical_tabs ) && is_array( $vertical_tabs ) ) {
	echo '<div class="reign-tab">';
	$counter = 0;
	foreach ( $vertical_tabs as $key => $value ) {
		$active = ( $current_active_tab == $counter ) ? ' active' : '';
		echo '<button class="reign-tablinks ' . $key . ' ' . $active . '" onclick="openSettingsTab(event, \'' . $key . '\')">' . $value . '</button>';
		$counter++;
	}
	echo '</div>';
}

if ( isset( $vertical_tabs ) && is_array( $vertical_tabs ) ) {
	foreach ( $vertical_tabs as $key => $value ) {
		echo '<div id="' . $key . '" class="reign-tabcontent">';
		do_action( 'render_theme_options_for_' . $key );
		?>
		<p class="submit" style="clear: both;">
			<input id="reign-theme-options-submit" type="submit" name="Submit"  class="button-primary" value="<?php _e( 'Update Settings', 'reign' ); ?>" />
			<input type="hidden" name="reign-settings-submit" value="Y" />
		</p>
		<?php
		echo '</div>';
	}
}
?>