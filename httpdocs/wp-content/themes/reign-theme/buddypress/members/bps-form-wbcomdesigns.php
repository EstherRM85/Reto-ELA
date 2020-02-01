<?php

/*
 * BP Profile Search - form template 'bps-form-horizontal'
 *
 * See http://dontdream.it/bp-profile-search/form-templates/ if you wish to modify this template or develop a new one.
 * A new or modified template should be moved to the 'buddypress/members' directory in your theme's root, otherwise it
 * will be overwritten during the next plugin update.
 *
 */

	$F = bps_escaped_form_data ();

	/* custom code to manage things properly */
	$wbtm_bps_form_layout = get_post_meta( $F->id, 'wbtm_bps_form_layout', true );
    $wbtm_bps_form_label = get_post_meta( $F->id, 'wbtm_bps_form_label', true );
    $wbtm_bps_form_placeholder = get_post_meta( $F->id, 'wbtm_bps_form_placeholder', true );
    $inline_style = "";
    if( $wbtm_bps_form_layout == 'horizontal' ) {
    	$inline_style = "display: flex;";
    }
    /* custom code to manage things properly */
    
	$toggle_id = 'bps_toggle'. $F->id;
	$form_id = 'bps_'. $F->location. $F->id;

	if ($F->location != 'directory')
	{
		echo "<div id='buddypress'>";
		?>
		<div class="wbtm-bps-form-wrapper wbtm-bps-form-wrapper-<?php echo $F->id; ?> wbtm-bps-form-wrapper-<?php echo esc_attr($wbtm_bps_form_layout); ?>">
			<div class="item-list-tabs bps_header" style="clear: both;">
				<ul>
					<li><?php echo $F->header; ?></li>
				</ul>
			</div>	
		<?php
	}
	else
	{
?>
	<div class="wbtm-bps-form-wrapper wbtm-bps-form-wrapper-<?php echo $F->id; ?> wbtm-bps-form-wrapper-<?php echo $wbtm_bps_form_layout; ?>">
	<div class="item-list-tabs bps_header" style="clear: both;">
	  <ul>
		<li><?php echo $F->header; ?></li>
<?php
		if ($F->toggle)
		{
?>
		<li class="last">
		  <span id="<?php echo esc_attr($toggle_id); ?>" type="submit" value="<?php //echo $F->toggle_text; ?>" class="wbtm-bps-form-toggle"><i class="fa fa-angle-down"></i></span>
		</li>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#<?php echo $form_id; ?>').hide();
				$('#<?php echo $toggle_id; ?>').click(function(){
					$( this ).toggleClass('wbtm-bps-form-open');
					$('#<?php echo $form_id; ?>').toggle();
				});
			});
		</script>
<?php
		}
?>
	  </ul>
	</div>
<?php
	}
	$wbtm_bps_form_bg_color = get_post_meta( $F->id, 'wbtm_bps_form_bg_color', true );
    $wbtm_bps_form_txt_color = get_post_meta( $F->id, 'wbtm_bps_form_txt_color', true );
	?>
	<style type="text/css">
		<?php if( !empty( $wbtm_bps_form_bg_color ) ) : ?>
			.wbtm-bps-form-wrapper-<?php echo $F->id; ?>{
				background-color: <?php echo $wbtm_bps_form_bg_color; ?>;
			}
		<?php endif; ?>
		<?php if( !empty( $wbtm_bps_form_txt_color ) ) : ?>
			.wbtm-bps-form-wrapper-<?php echo $F->id; ?> label,
			.wbtm-bps-form-wrapper-<?php echo $F->id; ?> .description {
				color: <?php echo $wbtm_bps_form_txt_color; ?> !important;
			}
		<?php endif; ?>
	</style>
	<?php

	echo "<form action='$F->action' method='$F->method' id='$form_id' class='standard-form bps_form wbtm-bps-form wbtm-bps-form-" . $wbtm_bps_form_layout . "' style='". $inline_style . "'>\n";

	$j = 0;
	foreach ($F->fields as $f)
	{
		if ($f->display == 'hidden')
		{
			echo "<input type='hidden' name='$f->code' value='$f->value'>\n";
			continue;
		}

		$name = sanitize_title ($f->name);
		$alt = ($j++ % 2)? 'alt': '';
		$class = "editfield $f->code field_$name $alt";

		echo "<div class='$class'>\n";

		switch ($f->display)
		{
		case 'range':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<input style='width: 10%; display: inline;' type='text' name='{$f->code}_min' id='$f->code' value='$f->min'>";
			echo '&nbsp;-&nbsp;';
			echo "<input style='width: 10%; display: inline;' type='text' name='{$f->code}_max' value='$f->max'>\n";
			break;

		case 'textbox':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<input type='text' name='$f->code' id='$f->code' value='$f->value' placeholder='$_placehoder'>\n";
			break;

		case 'number':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<input type='number' name='$f->code' id='$f->code' value='$f->value'>\n";
			break;

		case 'url':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<input type='text' inputmode='url' name='$f->code' id='$f->code' value='$f->value'>\n";
			break;

		case 'textarea':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<textarea rows='5' cols='40' name='$f->code' id='$f->code'>$f->value</textarea>\n";
			break;

		case 'distance':
			$within = __('Within', 'reign');
			$of = __('of', 'reign');
			$km = __('km', 'reign');
			$miles = __('miles', 'reign');
?>
			<label for="<?php echo $f->unique_id; ?>"><?php echo $f->label; ?></label>
			<span><?php echo $within; ?></span>
			<input style="width: 4em;" type="number" min="1"
				name="<?php echo $f->code. '[distance]'; ?>"
				value="<?php echo $f->value['distance']; ?>">
			<select name="<?php echo $f->code. '[units]'; ?>">
				<option value="km" <?php selected ($f->value['units'], "km"); ?>><?php echo $km; ?></option>
				<option value="miles" <?php selected ($f->value['units'], "miles"); ?>><?php echo $miles; ?></option>
			</select>
			<span><?php echo $of; ?></span>
			<input style="width: 80%;" type="text" id="<?php echo $f->unique_id; ?>"
				name="<?php echo $f->code. '[location]'; ?>"
				value="<?php echo $f->value['location']; ?>"
				placeholder="<?php _e('Start typing, then select a location', 'reign'); ?>">
			<img id="Btn_<?php echo $f->unique_id; ?>" style="cursor: pointer;" src="<?php echo plugins_url ('bp-profile-search/templates/members/locator.png'); ?>" title="<?php _e('get current location', 'reign'); ?>">
<?php
			bps_autocomplete_script ($f);
			break;

		case 'selectbox':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<select name='$f->code' id='$f->code'>\n";

			$no_selection = apply_filters ('bps_field_selectbox_no_selection', '', $f);
			if (is_string ($no_selection))
				echo "<option  value=''>$no_selection</option>\n";

			foreach ($f->options as $key => $label)
			{
				$selected = in_array ($key, $f->values)? "selected='selected'": "";
				echo "<option $selected value='$key'>$label</option>\n";
			}
			echo "</select>\n";
			break;

		case 'multiselectbox':
			if( $wbtm_bps_form_label == 'show' ) {
				echo "<label for='$f->code'>$f->label</label>\n";
			}
			$_placehoder = '';
			if( $wbtm_bps_form_placeholder == 'show' ) {
				$_placehoder = $f->label;
			}
			echo "<select name='{$f->code}[]' id='$f->code' multiple='multiple'>\n";

			foreach ($f->options as $key => $label)
			{
				$selected = in_array ($key, $f->values)? "selected='selected'": "";
				echo "<option $selected value='$key'>$label</option>\n";
			}
			echo "</select>\n";
			break;

		case 'radio':
			echo "<div class='radio'>\n";
			echo "<span class='label'>$f->label</span>\n";
			echo "<div id='$f->code'>\n";

			foreach ($f->options as $key => $label)
			{
				$checked = in_array ($key, $f->values)? "checked='checked'": "";
				echo "<label><input $checked type='radio' name='$f->code' value='$key'>$label</label>\n";
			}
			echo "</div>\n";
			echo "<a style='display: inline;' class='clear-value' href='javascript:clear(\"$f->code\");'>". __('Clear', 'buddypress'). "</a>\n";
			echo "</div>\n";
			break;

		case 'checkbox':
			echo "<div class='checkbox'>\n";
			echo "<span class='label'>$f->label</span>\n";

			foreach ($f->options as $key => $label)
			{
				$checked = in_array ($key, $f->values)? "checked='checked'": "";
				echo "<label><input $checked type='checkbox' name='{$f->code}[]' value='$key'>$label</label>\n";
			}
			echo "</div>\n";
			break;

		default:
			echo "<p>BP Profile Search: unknown display <em>$f->display</em> for field <em>$f->name</em>.</p>\n";
			break;
		}

		if (!empty ($f->description) && $f->description != '-')
			echo "<p class='description'>$f->description</p>\n";

		echo "</div>\n";
	}

	echo "<div class='submit'>\n";
	echo "<input type='submit' value='". __('Search', 'buddypress'). "'>\n";
	echo "</div>\n";
	echo "</form>\n";
	echo "</div>\n";

	if ($F->location != 'directory')  echo "</div>\n";

// BP Profile Search - end of template
