<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="wpDataTables metabox-holder">
    <div id="wdtPreloadLayer" class="overlayed">
    </div>

	<input type="hidden" id="wdtConstructorNonce" value="<?php echo wp_create_nonce( 'wdt_constructor_nonce_'.get_current_user_id() ); ?>" />
    
    <div class="wrap">
	    <div id="poststuff">
		    <div id="post-body" class="metabox-holder">
				<div id="postbox-container-1" class="postbox-container">
					<img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/wpdatatables-logo.png" class="wpdatatables_logo" />
					<p><i><?php _e('Please refer to','wpdatatables');?> <a href="http://wpdatatables.com/documentation/creating-new-wpdatatables-with-table-constructor/"><?php _e('wpDataTables documentation on Table Consturctor','wpdatatables');?></a> <?php _e('if you have some questions or problems.','wpdatatables'); ?></i></p>
					<h2><?php _e('wpDataTable constructor','wpdatatables'); ?></h2>
					<form method="post" action="<?php echo WDT_ROOT_URL ?>" id="wpDataTablesSettings">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox">
							<div class="handlediv" title="<?php _e('Click to toggle','wpdatatables'); ?>"><br/></div>
						    <h3 class="hndle">
						    	<span><div class="dashicons dashicons-edit"></div> <?php _e('Table Creation Wizard','wpdatatables'); ?></span>
						    </h3>
						    <div class="inside">

								<div class="steps">
									
									<!-- Selection of data source type -->
									<div class="constructorStep step1" data-step="1">
										<h3><?php _e('Choose what kind of table would you like to construct','wpdatatables'); ?></h3>
										<fieldset style="margin: 10px;">
											<?php  ?>
											<?php  echo'<label for="manual_input"><input class="full_version_option" id="manual_input" type="radio" name="wpdatatables_type_input" value="manual" /> <span>I would like to prepare structure and input all the data manually</span></label><br/><label for="file_input"><input class="full_version_option" id="file_input" type="radio" name="wpdatatables_type_input" value="file" /> <span>I would like to read the initial table data from an input file</span></label><br/><label for="wp_posts_input"><input class="full_version_option" id="wp_posts_input" type="radio" name="wpdatatables_type_input" value="wp" /> <span>I want to create a table based on my WordPress data (posts or pages, and post meta or taxonomy values</span></label><br/><label for="mysql_construct_input"><input class="full_version_option" id="mysql_construct_input" type="radio" name="wpdatatables_type_input" value="mysql" /> <span>I want to construct a table based on data from existing MySQL DB tables</span></label><br/><input type="hidden" value="" id="wdt_date_format" />' ?>
											
										</fieldset>
									</div>
									<?php  ?>
								</div>

								<?php  ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php  ?>