<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="wpDataTables metabox-holder">
    <div id="wdtPreloadLayer" class="overlayed">
    </div>
    
    <div class="wrap">
	    <div id="poststuff">
		    <div id="post-body" class="metabox-holder">
		    	<div id="postbox-container-1" class="postbox-container">
				<img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/wpdatatables-logo.png" class="wpdatatables_logo" />
				<p><i><?php _e('Please refer to','wpdatatables');?> <a href="http://wpdatatables.com/documentation/general/configuration/"><?php _e('wpDataTables documentation on this feature','wpdatatables');?></a> <?php _e('if you have some questions or problems with the plugin.','wpdatatables'); ?></i></p>
				<h2><?php _e('wpDataTables settings','wpdatatables'); ?></h2>
				<form method="post" action="<?php echo WDT_ROOT_URL ?>" id="wpDataTablesSettings">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all settings">
						<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all settings">
							<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active settings"><a href="#tabs-1" class="ui-tabs-anchor settings"><div class="dashicons dashicons-admin-tools"></div> <?php _e('Main settings','wpdatatables'); ?></a></li>
							<li class="ui-state-default ui-corner-top settings"><a href="#tabs-2" class="ui-tabs-anchor settings"><div class="dashicons dashicons-art"></div> <?php _e('Color and font settings','wpdatatables'); ?></a></li>
							<li class="ui-state-default ui-corner-top settings"><a href="#tabs-3" class="ui-tabs-anchor settings"><div class="dashicons dashicons-exerpt-view"></div> <?php _e('Custom JS and CSS','wpdatatables'); ?></a></li>
						</ul>
						<div id="tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom settings">
							<div class="postbox">
						<div class="handlediv" title="<?php _e('Click to toggle','wpdatatables'); ?>"><br/></div>
					    <h3 class="hndle">
					    	<span><div class="dashicons dashicons-admin-tools"></div> <?php _e('Main settings','wpdatatables'); ?></span>
					    </h3>
					    <div class="inside">
						    <table class="form-table wpDataTables">
							<tbody>
							<input type="hidden" id="wdtSettingsNonce" value="<?php echo wp_create_nonce( 'wdt_settings_nonce_' . get_current_user_id() ); ?>" />
							<?php echo '<tr valign="top"><th scope="row"><label for="wdtSiteLink">'; _e("Show plugin credentials below tables","wpdatatables"); echo '</label></th><td><input type="checkbox" name="wdtSiteLink" id="wdtSiteLink"'; if($wdtSiteLink) { echo 'checked="checked"'; }; echo ' /><span class="description">'; _e("If you want to support our project, please, keep this checkbox as checked","wpdatatables"); echo'.</span></td></tr>' ?>
							    <tr valign="top">
								<th scope="row">
								    <label for="wpUseSeparateCon"><?php _e('Use separate MySQL connection','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="checkbox" <?php echo ' class="full_version_option" ' ?> id="wpUseSeparateCon" name="wpUseSeparateCon" <?php if ($wpUseSeparateCon) { ?>checked="checked"<?php } ?> />
								    <span class="description"><?php _e('If this checkbox is checked, wpDataTables will use its own connection to MySQL bases. In other case it will use the main WordPress MySQL connection.','wpdatatables'); ?></span>
								</td>
							    </tr>
							<?php  ?>
							    <tr>
								<th scope="row">
								    <label for="wpInterfaceLanguage"><?php _e('Interface language','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wpInterfaceLanguage" id="wpInterfaceLanguage">
									<option value="" <?php if ($wpInterfaceLanguage == '') { ?>selected="selected"<?php } ?> >English (default)</option>
									<?php foreach ($languages as $language) { ?>
					    				<option value="<?php echo $language['file'] ?>" <?php if ($wpInterfaceLanguage == $language['file']) { ?>selected="selected"<?php } ?> >
										<?php echo $language['name']; ?>
					    				</option>
									<?php } ?>
								    </select>
								    <span class="description"><?php _e('Pick the language which will be used in tables interface','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<th scope="row">
								    <label for="wdtTablesPerPage"><?php _e('Tables per admin page','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wdtTablesPerPage" id="wdtTablesPerPage">
								    <?php for($i=10;$i<=50;$i+=10){ ?>
								    <option value="<?php echo $i ?>" <?php if($wdtTablesPerPage == $i){ ?>selected="selected"<?php } ?>><?php echo $i ?></option>
								    <?php } ?>
								    </select>
								    <span class="description"><?php _e('How many tables to show in the browse page','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<th scope="row">
								    <label for="wdtBaseSkin"><?php _e('Base skin','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wdtBaseSkin" id="wdtBaseSkin">
									<option value="skin1" <?php if ($wdtBaseSkin == 'skin1') { ?>selected="selected"<?php } ?> ><?php _e('Skin','wpdatatables'); ?> 1</option>
									<option value="skin2" <?php if ($wdtBaseSkin == 'skin2') { ?>selected="selected"<?php } ?> ><?php _e('Skin','wpdatatables'); ?> 2</option>
								    </select>
								    <span class="description"><?php _e('Choose the base skin for the plugin','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<th scope="row">
								    <label for="wpRenderFilter"><?php _e('Render advanced filter','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wpRenderFilter" id="wpRenderFilter">
									<option value="header" <?php if ($wpRenderFilter == 'header') { ?>selected="selected"<?php } ?> ><?php _e('In the header','wpdatatables'); ?></option>
									<option value="footer" <?php if ($wpRenderFilter == 'footer') { ?>selected="selected"<?php } ?> ><?php _e('In the footer','wpdatatables'); ?></option>
								    </select>
								    <span class="description"><?php _e('Choose where you would like to render the advanced filter for tables where enabled','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<th scope="row">
								    <label for="wpDateFormat"><?php _e('Date format','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wpDateFormat" id="wpDateFormat">
									<option value="d/m/Y" <?php if ($wpDateFormat == 'd/m/Y') { ?>selected="selected"<?php } ?> >15/07/2005 (d/m/Y)</option>
									<option value="m/d/Y" <?php if ($wpDateFormat == 'm/d/Y') { ?>selected="selected"<?php } ?> >07/15/2005 (m/d/Y)</option>
									<option value="d.m.Y" <?php if ($wpDateFormat == 'd.m.Y') { ?>selected="selected"<?php } ?> >15.07.2005 (d.m.Y)</option>
									<option value="m.d.Y" <?php if ($wpDateFormat == 'm.d.Y') { ?>selected="selected"<?php } ?> >07.15.2005 (m.d.Y)</option>
									<option value="d-m-Y" <?php if ($wpDateFormat == 'd-m-Y') { ?>selected="selected"<?php } ?> >15-07-2005 (d-m-Y)</option>
									<option value="m-d-Y" <?php if ($wpDateFormat == 'm-d-Y') { ?>selected="selected"<?php } ?> >07-15-2005 (m-d-Y)</option>
									<option value="d.m.y" <?php if ($wpDateFormat == 'd.m.y') { ?>selected="selected"<?php } ?> >15.07.05 (d.m.y)</option>
									<option value="m.d.y" <?php if ($wpDateFormat == 'm.d.y') { ?>selected="selected"<?php } ?> >07.15.05 (m.d.y)</option>
									<option value="d-m-y" <?php if ($wpDateFormat == 'd-m-y') { ?>selected="selected"<?php } ?> >15-07-05 (d-m-y)</option>
									<option value="m-d-y" <?php if ($wpDateFormat == 'm-d-y') { ?>selected="selected"<?php } ?> >07-15-05 (m-d-y)</option>
									<option value="d M Y" <?php if ($wpDateFormat == 'd M Y') { ?>selected="selected"<?php } ?> >15 Jun 2005 (d Mon Y)</option>
								    </select>
								    <span class="description"><?php _e('Pick the date format to use in date column type','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							<tr>
								<th scope="row">
									<label for="wdtTimeFormat"><?php _e('Time format','wpdatatables'); ?></label>
								</th>
								<td>
									<select name="wdtTimeFormat" id="wdtTimeFormat">
										<option value="h:i A" <?php if ($wdtTimeFormat == 'h:i A') { ?>selected="selected"<?php } ?> >1:25 PM (12h)</option>
										<option value="H:i" <?php if ($wdtTimeFormat == 'H:i') { ?>selected="selected"<?php } ?> >13:25 (24h)</option>
									</select>
									<span class="description"><?php _e('Pick the time format to use in datetime and time column type','wpdatatables'); ?>.</span>
								</td>
							</tr>
							    <tr>
								<th scope="row">
								    <label for="wdtNumberFormat"><?php _e('Number format','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wdtNumberFormat" id="wdtNumberFormat">
										<option value="1" <?php if ($wdtNumberFormat == '1') { ?>selected="selected"<?php } ?> >15.000,00</option>
										<option value="2" <?php if ($wdtNumberFormat == '2') { ?>selected="selected"<?php } ?> >15,000.00</option>
								    </select>
								    <span class="description"><?php _e('Pick the number format (thousands and decimals separator)','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<th scope="row">
								    <label for="wdtDecimalPlaces"><?php _e('Decimal places','wpdatatables'); ?></label>
								</th>
								<td>
									<input type="number" name="wdtDecimalPlaces" id="wdtDecimalPlaces" value="<?php echo $wdtDecimalPlaces ?>" />
								    <span class="description"><?php _e('Define the amount of decimal places for the float numbers','wpdatatables'); ?>.</span>
								</td>
							    </tr>
								<tr>
									<th scope="row">
										<label for="wdtTimepickerRange"><?php _e('Timepicker step (min)','wpdatatables'); ?></label>
									</th>
									<td>
										<input type="number" name="wdtTimepickerRange" id="wdtTimepickerRange" value="<?php echo $wdtTimepickerRange ?>" />
										<span class="description"><?php _e('Define the minutes step for the timepicker based filters and editors.','wpdatatables'); ?>.</span>
									</td>
								</tr>
							    <tr>
								<th scope="row">
								    <label for="wdtNumbersAlign"><?php _e('Align numbers to the right','wpdatatables'); ?></label>
								</th>
								<td>
									<input type="checkbox" name="wdtNumbersAlign" id="wdtNumbersAlign" <?php if($wdtNumbersAlign) { ?>checked="checked"<?php } ?> />
								    <span class="description"><?php _e('If this checkbox is checked all numerical values will be aligned to the right of the cell','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    
							    <tr>
								<th scope="row">
								    <label for="wdtTabletWidth"><?php _e('Tablet width','wpdatatables'); ?></label>
								</th>
								<td>
									<input type="number" name="wdtTabletWidth" id="wdtTabletWidth" value="<?php echo $wdtTabletWidth ?>" />
								    <span class="description"><?php _e('Here you can specify width of the screen (in pixels) that will be treated as a tablet. You can set it wider if you want responsive effect on desktops.','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    
							    <tr>
								<th scope="row">
								    <label for="wdtMobileWidth"><?php _e('Mobile width','wpdatatables'); ?></label>
								</th>
								<td>
									<input type="number" name="wdtMobileWidth" id="wdtMobileWidth" value="<?php echo $wdtMobileWidth ?>" />
								    <span class="description"><?php _e('Here you can specify width (in pixels) will be treated as a mobile.','wpdatatables'); ?>.</span>
								</td>
							    </tr>
                                <?php  ?>

							    <tr>
								<td colspan="2">
								    <input type="submit" name="submit" class="button-primary" value="<?php _e('Save options','wpdatatables'); ?>">
								</td> 
							    </tr>                    
							</tbody>
						    </table>
					    </div>
					</div>
						</div>
						<div id="tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom settings">
							<div class="postbox">
						<div class="handlediv" title="<?php _e('Click to toggle','wpdatatables'); ?>"><br/></div>
					    <h3 class="hndle">
				    		<div class="dashicons dashicons-art"></div> <?php _e('Color and font settings','wpdatatables'); ?>
				    	</h3>
					    <div class="inside">
						    <p><?php _e('Leave the setting blank to use default value','wpdatatables'); ?>.</p>
						    <table class="form-table colorFontSettings">
							<tbody>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtTableFontColor"><?php _e('Table font color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtTableFontColor" id="wdtTableFontColor" value="<?php echo (!empty($wdtFontColorSettings['wdtTableFontColor']) ? $wdtFontColorSettings['wdtTableFontColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for the main font in table cells','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtHeaderBaseColor"><?php _e('Header background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtHeaderBaseColor" id="wdtHeaderBaseColor" value="<?php echo (!empty($wdtFontColorSettings['wdtHeaderBaseColor']) ? $wdtFontColorSettings['wdtHeaderBaseColor'] : '') ?>" />
								    <span class="description"><?php _e('The color is used for background of the table header','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtHeaderBorderColor"><?php _e('Header border color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtHeaderBorderColor" id="wdtHeaderBorderColor" value="<?php echo (!empty($wdtFontColorSettings['wdtHeaderBorderColor']) ? $wdtFontColorSettings['wdtHeaderBorderColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for the border in the table header','wpdatatables'); ?>.</span>
								</td>
							    </tr>     
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtHeaderFontColor"><?php _e('Header font color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtHeaderFontColor" id="wdtHeaderFontColor" value="<?php echo (!empty($wdtFontColorSettings['wdtHeaderFontColor']) ? $wdtFontColorSettings['wdtHeaderFontColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for the font in the table header','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtHeaderHoverColor"><?php _e('Header active and hover color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtHeaderActiveColor" id="wdtHeaderActiveColor" value="<?php echo (!empty($wdtFontColorSettings['wdtHeaderActiveColor']) ? $wdtFontColorSettings['wdtHeaderActiveColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used when you hover the mouse above the table header, or when you choose a column','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtTableInnerBorderColor"><?php _e('Table inner border color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtTableInnerBorderColor" id="wdtTableInnerBorderColor" value="<?php echo (!empty($wdtFontColorSettings['wdtTableInnerBorderColor']) ? $wdtFontColorSettings['wdtTableOuterBorderColor'] : '') ?>"  />
								    <span class="description"><?php _e('This color is used for the inner border in the table between cells','wpdatatables'); ?>.</span>
								</td>
							    </tr>           
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtTableOuterBorderColor"><?php _e('Table outer border color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtTableOuterBorderColor" id="wdtTableOuterBorderColor" value="<?php echo (!empty($wdtFontColorSettings['wdtTableOuterBorderColor']) ? $wdtFontColorSettings['wdtTableOuterBorderColor'] : '') ?>"  />
								    <span class="description"><?php _e('This color is used for the outer border of the whole table body','wpdatatables'); ?>.</span>
								</td>
							    </tr>           
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtEvenRowColor"><?php _e('Even row background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtEvenRowColor" id="wdtEvenRowColor" value="<?php echo (!empty($wdtFontColorSettings['wdtEvenRowColor']) ? $wdtFontColorSettings['wdtEvenRowColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for for background in even rows','wpdatatables'); ?>.</span>
								</td>
							    </tr>           
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtOddRowColor"><?php _e('Odd row background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtOddRowColor" id="wdtOddRowColor" value="<?php echo (!empty($wdtFontColorSettings['wdtOddRowColor']) ? $wdtFontColorSettings['wdtOddRowColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for for background in odd rows','wpdatatables'); ?>.</span>
								</td>
							    </tr>           
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtHoverRowColor"><?php _e('Hover row color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtHoverRowColor" id="wdtHoverRowColor" value="<?php echo (!empty($wdtFontColorSettings['wdtHoverRowColor']) ? $wdtFontColorSettings['wdtHoverRowColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for to highlight the row when you hover your mouse above it','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtActiveEvenCellColor"><?php _e('Cell color in active (sorted) columns for even rows','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtActiveEvenCellColor" id="wdtActiveEvenCellColor" value="<?php echo (!empty($wdtFontColorSettings['wdtActiveEvenCellColor']) ? $wdtFontColorSettings['wdtActiveEvenCellColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for background in cells which are in the active columns (columns used for sorting) in even rows','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtActiveOddCellColor"><?php _e('Cell color in active (sorted) columns for odd rows','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtActiveOddCellColor" id="wdtActiveOddCellColor" value="<?php echo (!empty($wdtFontColorSettings['wdtActiveOddCellColor']) ? $wdtFontColorSettings['wdtActiveOddCellColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for background in cells which are in the active columns (columns used for sorting) in odd rows','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtSelectedRowColor"><?php _e('Backround color for selected rows','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtSelectedRowColor" id="wdtSelectedRowColor" value="<?php echo (!empty($wdtFontColorSettings['wdtSelectedRowColor']) ? $wdtFontColorSettings['wdtSelectedRowColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for background in selected rows','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonColor"><?php _e('Buttons background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonColor" id="wdtButtonColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonColor']) ? $wdtFontColorSettings['wdtButtonColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for background in buttons','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonBorderColor"><?php _e('Buttons border color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonBorderColor" id="wdtButtonBorderColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonBorderColor']) ? $wdtFontColorSettings['wdtButtonBorderColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for border in buttons','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonColor"><?php _e('Buttons font color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonFontColor" id="wdtButtonFontColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonFontColor']) ? $wdtFontColorSettings['wdtButtonFontColor'] : '') ?>" />
								    <span class="description"><?php _e('This color is used for font in buttons','wpdatatables'); ?>.</span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonBackgroundHoverColor"><?php _e('Buttons background hover color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonBackgroundHoverColor" id="wdtButtonBackgroundHoverColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonBackgroundHoverColor']) ? $wdtFontColorSettings['wdtButtonBackgroundHoverColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for button backgrounds when you hover above them','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonFontHoverColor"><?php _e('Buttons hover font color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonFontHoverColor" id="wdtButtonFontHoverColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonFontHoverColor']) ? $wdtFontColorSettings['wdtButtonFontHoverColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for buttons font when you hover above them','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtModalFontColor"><?php _e('Modals font color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtModalFontColor" id="wdtModalFontColor" value="<?php echo (!empty($wdtFontColorSettings['wdtModalFontColor']) ? $wdtFontColorSettings['wdtModalFontColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for wpDataTable popup (filter, datepicker) fonts','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtModalBackgroundColor"><?php _e('Modals background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtModalBackgroundColor" id="wdtModalBackgroundColor" value="<?php echo (!empty($wdtFontColorSettings['wdtModalBackgroundColor']) ? $wdtFontColorSettings['wdtModalBackgroundColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for wpDataTable popup (filter, datepicker) background','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtOverlayColor"><?php _e('Overlay background color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtOverlayColor" id="wdtOverlayColor" value="<?php echo (!empty($wdtFontColorSettings['wdtOverlayColor']) ? $wdtFontColorSettings['wdtOverlayColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for overlay which appears below the plugin popups','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtButtonBorderHoverColor"><?php _e('Buttons hover border color','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtButtonBorderHoverColor" id="wdtButtonBorderHoverColor" value="<?php echo (!empty($wdtFontColorSettings['wdtButtonBorderHoverColor']) ? $wdtFontColorSettings['wdtButtonBorderHoverColor'] : '') ?>" />
								    <span class="description"><?php _e('This color will be used for button borders when you hover above them','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtBorderRadius"><?php _e('Buttons and inputs border radius (in px)','wpdatatables'); ?><?php echo '<br /><small><a href="http://wpdatatables.com" target="_blank">FULL version</a></small>' ?></label>
								</th>
								<td>
								    <input type="text" <?php echo ' class="full_version_option" ' ?> name="wdtBorderRadius" id="wdtBorderRadius" value="<?php echo (!empty($wdtFontColorSettings['wdtBorderRadius']) ? $wdtFontColorSettings['wdtBorderRadius'] : '') ?>" />
								    <span class="description"><?php _e('This is a border radius for inputs in buttons. Default is 3px.','wpdatatables'); ?></span>
								</td>
							    </tr>         
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtTableFont"><?php _e('Table font','wpdatatables'); ?></label>
								</th>
								<td>
								    <select name="wdtTableFont" id="wdtTableFont" style="width: 200px;">
									<option value="" <?php echo (empty($wdtFontColorSettings['wdtTableFont']) ? 'selected="selected"' : '') ?> ></option>
									<?php foreach ($wdtFonts as $font) { ?>
					    				<option value="<?php echo $font ?>" <?php echo ((!empty($wdtFontColorSettings['wdtTableFont']) && ($wdtFontColorSettings['wdtTableFont'] == $font)) ? 'selected="selected"' : '') ?>><?php echo $font ?></option>
									<?php } ?>
								    </select>
								    <span class="description"><?php _e('This font will be used in rendered tables. Leave blank not to override default theme settings','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr>
								<td colspan="2">
								    <input type="submit" name="submit" class="button-primary" value="<?php _e('Save options','wpdatatables'); ?>">
								    <button class="resetColorSettings button-primary"><?php _e('Reset colors and fonts to default','wpdatatables'); ?></button>
								</td> 
							    </tr> 
							</tbody>      
						    </table>    
					    </div>
			    	</div>
						</div>
						<div id="tabs-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom settings">
							<div class="postbox">
						<div class="handlediv" title="<?php _e('Click to toggle','wpdatatables'); ?>"><br/></div>
					    <h3 class="hndle">
				    		<div class="dashicons dashicons-exerpt-view"></div> <?php _e('Custom JS and CSS','wpdatatables'); ?>
				    	</h3>
					    <div class="inside">
						    <p><?php _e('Leave the setting blank to use default value','wpdatatables'); ?>.</p>
						    <table class="form-table colorFontSettings">
							<tbody>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtCustomJs"><?php _e('Custom wpDataTables JS','wpdatatables'); ?></label>
								</th>
								<td>
								    <textarea name="wdtCustomJs" id="wdtCustomJs" style="width: 430px; height: 200px;"><?php echo (!empty($wdtCustomJs) ? $wdtCustomJs : '') ?></textarea><br/>
								    <span class="description"><?php _e('This JS will be inserted as an inline script block on every page that has a wpDataTable','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtCustomCss"><?php _e('Custom wpDataTables CSS','wpdatatables'); ?></label>
								</th>
								<td>
								    <textarea name="wdtCustomCss" id="wdtCustomCss" style="width: 430px; height: 200px;"><?php echo (!empty($wdtCustomCss) ? stripslashes($wdtCustomCss) : '') ?></textarea><br/>
								    <span class="description"><?php _e('This CSS will be inserted as an inline style block on every page that has a wpDataTable','wpdatatables'); ?>.</span>
								</td>
							    </tr>
							    <tr valign="top">
								<th scope="row">
								    <label for="wdtMinifiedJs"><?php _e('Use minified wpDataTables Javascript','wpdatatables'); ?></label>
								</th>
								<td>
								    <input type="checkbox" id="wdtMinifiedJs" name="wdtMinifiedJs" <?php if(!empty($wdtMinifiedJs)) { ?>checked="checked"<?php } ?> /><br/>
								    <span class="description"><?php _e('Uncheck if you would like to make some changes to the main wpDataTables JS file (assets/js/wpdatatables/wpdatatables.js). Minified is inserted by default (better performance)','wpdatatables'); ?>.</span>
								</td>
							    </tr>							    
							    <tr>
								<td colspan="2">
								    <input type="submit" name="submit" class="button-primary" value="<?php _e('Save options','wpdatatables'); ?>">
								</td> 
							    </tr> 
							 </tbody>
							 </table>
						</div>
					</div>
						</div>
								    	
			    	
			    </div>
				</form>
				</div>
		    </div>
	    </div>
    </div>
</div>
<style>

</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
		<?php  ?>
	
	jQuery('.resetColorSettings').click(function(e){
	    e.preventDefault();
	    jQuery('table.colorFontSettings input[type="text"]').val('').change();
	    jQuery('table.colorFontSettings select option[value=""]').attr('selected','selected');
	    jQuery('#wpDataTablesSettings').submit();
	});
	
	jQuery('#wpUseSeparateCon').change(function(){
		if(jQuery('#wpUseSeparateCon').is(':checked')){
		    jQuery('tr.wpMySQLParam').show();
		}else{
		    jQuery('tr.wpMySQLParam').hide();
		}
	});
	jQuery('#wpUseSeparateCon').change();

		<?php echo 'var full_version_title = "'; _e('Full version only!','wpdatatables'); echo '"; var full_version_text = "'; _e('Sorry, this function is available only in FULL version of wpDataTables along with many others! Please go to our <a href=\"http://wpdatatables.com/\">website</a> to see the full list and to purchase!'); echo '";
var full_version_message = function() {
	wdtAlertDialog(full_version_text, full_version_title);
}
jQuery(document).on("focus", ".full_version_option", full_version_message);' ?>
        
        /**
         * Test MySQL settings
         */
        jQuery('#wpMySqlTest').click(function(e){
            e.preventDefault();
            var mysql_settings = {
                host: jQuery('#wpMySqlHost').val(),
                db: jQuery('#wpMySqlDB').val(),
                user: jQuery('#wpMySqlUser').val(),
                password: jQuery('#wpMySqlPwd').val(),
                port: jQuery('#wpMySqlPort').val()
            };
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'wpdatatables_test_mysql_settings',
                    mysql_settings: mysql_settings
                },
                success: function(data) {
                    if( data.errors.length > 0 ){
                        var errorMessage = '';
                        for( var i in data.errors ){
                            errorMessage += data.errors[i]+'<br/>';
                        }
                        wdtAlertDialog( errorMessage, '<?php _e('Error!','wpdatatables'); ?>');
                    }else if( data.success.length > 0 ){
                        var successMessage = '';
                        for( var i in data.success ){
                            successMessage += data.success[i]+'<br/>';
                        }
                        wdtAlertDialog( successMessage, '<?php _e('Success!','wpdatatables'); ?>');
                    }
                }
            })
        });
    
	jQuery('#wpDataTablesSettings').submit(function(e){
	    e.preventDefault();
	    e.stopImmediatePropagation();
	    var data = {
			action: 'wdt_save_settings',
			<?php echo 'wdtSiteLink: jQuery("#wdtSiteLink").is(":checked") ? 1 : 0,' ?>
			wpUseSeparateCon: (jQuery('#wpUseSeparateCon').attr('checked') == 'checked'),
			wpMySqlHost: jQuery('#wpMySqlHost').val(),
			wpMySqlDB: jQuery('#wpMySqlDB').val(),
			wpMySqlUser: jQuery('#wpMySqlUser').val(),
			wpMySqlPwd: jQuery('#wpMySqlPwd').val(),
			wpMySqlPort: jQuery('#wpMySqlPort').val(),
			wdtSettingsNonce: jQuery('#wdtSettingsNonce').val(),
			wpRenderFilter: jQuery('#wpRenderFilter').val(),
			wpInterfaceLanguage: jQuery('#wpInterfaceLanguage').val(),
			wpDateFormat: jQuery('#wpDateFormat').val(),
			wdtTimeFormat: jQuery('#wdtTimeFormat').val(),
			wpTopOffset: '',
			wpLeftOffset: '',
			wdtTablesPerPage: jQuery('#wdtTablesPerPage').val(),
			wdtNumberFormat: jQuery('#wdtNumberFormat').val(),
			wdtDecimalPlaces: jQuery('#wdtDecimalPlaces').val(),
			wdtTimepickerRange: jQuery('#wdtTimepickerRange').val(),
			wdtNumbersAlign: jQuery('#wdtNumbersAlign').is(':checked') ? 1 : 0,
			wdtCustomJs: jQuery('#wdtCustomJs').val(),
			wdtCustomCss: jQuery('#wdtCustomCss').val(),
                        wdtMinifiedJs: jQuery('#wdtMinifiedJs').is(':checked') ? 1 : 0,
			wdtBaseSkin: jQuery('#wdtBaseSkin').val(),
			wdtTabletWidth: jQuery('#wdtTabletWidth').val(),
			wdtMobileWidth: jQuery('#wdtMobileWidth').val(),
                        wdtPurchaseCode: jQuery('#wdtPurchaseCode').val(),
			wdtHeaderBaseColor: jQuery('#wdtHeaderBaseColor').val(),
			wdtHeaderActiveColor: jQuery('#wdtHeaderActiveColor').val(),
			wdtHeaderFontColor: jQuery('#wdtHeaderFontColor').val(),
			wdtHeaderBorderColor: jQuery('#wdtHeaderBorderColor').val(),
			wdtTableOuterBorderColor: jQuery('#wdtTableOuterBorderColor').val(),
			wdtTableInnerBorderColor: jQuery('#wdtTableInnerBorderColor').val(),
			wdtTableFontColor: jQuery('#wdtTableFontColor').val(),
			wdtTableFont: jQuery('#wdtTableFont').val(),
			wdtHoverRowColor: jQuery('#wdtHoverRowColor').val(),
			wdtOddRowColor: jQuery('#wdtOddRowColor').val(),
			wdtEvenRowColor: jQuery('#wdtEvenRowColor').val(),
			wdtActiveOddCellColor: jQuery('#wdtActiveOddCellColor').val(),
			wdtActiveEvenCellColor: jQuery('#wdtActiveEvenCellColor').val(),
			wdtSelectedRowColor: jQuery('#wdtSelectedRowColor').val(),
			wdtButtonColor: jQuery('#wdtButtonColor').val(),
			wdtButtonBorderColor: jQuery('#wdtButtonBorderColor').val(),
			wdtButtonFontColor: jQuery('#wdtButtonFontColor').val(),
			wdtButtonBackgroundHoverColor: jQuery('#wdtButtonBackgroundHoverColor').val(),
			wdtButtonBorderHoverColor: jQuery('#wdtButtonBorderHoverColor').val(),
			wdtButtonFontHoverColor: jQuery('#wdtButtonFontHoverColor').val(),
			wdtModalFontColor: jQuery('#wdtModalFontColor').val(),
			wdtModalBackgroundColor: jQuery('#wdtModalBackgroundColor').val(),
			wdtOverlayColor: jQuery('#wdtOverlayColor').val(),
			wdtBorderRadius: jQuery('#wdtBorderRadius').val()
	    };
	    jQuery('#wdtPreloadLayer').show();
	    jQuery.post(ajaxurl, data, function(response) {
		jQuery('#wdtPreloadLayer').hide();
			if(response.trim()=='success'){
		    wdtAlertDialog('<?php _e('Settings saved successfully','wpdatatables'); ?>','<?php _e('Success!','wpdatatables'); ?>');
		}else{
		    wdtAlertDialog('<?php _e('There was a problem saving your settings','wpdatatables'); ?>','<?php _e('Error!','wpdatatables'); ?>');
		}
	    });
	});
    
	applySelecter();

	jQuery( "#tabs" ).tabs();
    });

</script>
