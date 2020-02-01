<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div id="wdtPreloadLayer" class="overlayed">
</div>

<div class="wrap">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/wpdatatables-logo.png" class="wpdatatables_logo" style="margin: 10px" />
	<p><i><?php _e('Please refer to','wpdatatables');?> <a href="http://wpdatatables.com/documentation/"><?php _e('wpDataTables documentation','wpdatatables');?></a> <?php _e('if you have some questions or problems with the plugin.','wpdatatables'); ?></i></p>    <h2>wpDataTables <a href="admin.php?page=wpdatatables-addnew" class="add-new-h2"><?php _e('Add new','wpdatatables');?></a></h2>

	<form method="post" action="<?php echo admin_url('admin.php?page=wpdatatables-administration'); ?>" id="wpDataTablesBrowseForm">
		<?php echo $tableHTML; ?>
        <?php wp_nonce_field( 'wdtDeleteTableNonce', 'wdtNonce' ); ?>
	</form>
	
</div>

<div id="newTableName" style="display: none;">
    <div id="wdtDuplicateTableName">
        <label><?php _e('New table title','wpdatatables');?></label>
        <input type="hidden" id="wdtDuplicateNonce" value="<?php echo wp_create_nonce( 'wdt_duplicate_nonce_' . get_current_user_id() ); ?>" />
        <input type="text" value="" class="wdtDuplicateTableName" />
    </div>
    <div class="manual_duplicate" style="display: none" >
        <input type="checkbox" class="manual_check" name="manual_check"  value="duplicate"> Duplicate database table <span class="dashicons dashicons-info"></span><br>
        <div class="duplicate_explain" style="display: none">
            <p><strong>Unckecked -</strong>  will create exact copy of this table which means that all changes made in one table will be reflected in all copies.</p>
            <p><strong>Checked -</strong>  will create separate database table so changing one table won't affect other copies.</p>
        </div>
    </div>
</div>

<script type="text/javascript">
var duplicate_table_id = '';

jQuery(document).ready(function(){
	jQuery('a.submitdelete').click(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            if(confirm("<?php _e('Are you sure','wpdatatables'); ?>?")){
                    window.location = jQuery(this).attr('href');
            }
	})
	
	jQuery('button.wpDataTablesDuplicateTable').click(function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            duplicate_table_id = jQuery(this).data('table_id');
            if (jQuery(this).data('table_type') === 'manual') {
                jQuery('.manual_duplicate').show();
                jQuery('body').off('click').on('click','.dashicons-info',function(){
                    jQuery('.duplicate_explain').slideToggle( "slow");
                });
            } else {
                jQuery('.manual_duplicate').hide();
            }
            wdtAlertDialog(jQuery('#newTableName').html(),'<?php _e('Duplicate table','wpdatatables') ?>');
            jQuery('input.wdtDuplicateTableName').val(jQuery(this).data('table_name')+'_<?php _e('copy','wpdatatables'); ?>');
	});
        
    jQuery('button.wpDataTablesManualEdit').click(function(e){
        e.preventDefault();
        var url = '<?php echo admin_url('admin.php?page=wpdatatables-editor');?>&table_id='+jQuery(this).data('table_id');
        window.location = url;
    });

    jQuery('button.wpDataTablesManualExcelEdit').click(function(e){
        e.preventDefault();
        var url = '<?php echo admin_url('admin.php?page=wpdatatables-editor&table_type=excel');?>&table_id='+jQuery(this).data('table_id');
        window.location = url;
    });
	
	jQuery(document).on('click','button.remodal-confirm',function(e){
            jQuery('#wdtPreloadLayer').show();
            var new_table_name = jQuery(this).parent().find('input.wdtDuplicateTableName').val();
            var manual_duplicate_input = (jQuery('input[name=manual_check]').is(':checked')) ? 1 : 0;
            jQuery.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                            action: 'wpdatatables_duplicate_table',
                            table_id: duplicate_table_id,
                            new_table_name: new_table_name,
                            manual_duplicate_input: manual_duplicate_input,
                            wdtDuplicateNonce: jQuery('#wdtDuplicateNonce').val()
                    },
                    success: function(){
                            window.location.reload();
                    }
            });

            jQuery('.wdtRemodal').remodal().close();
        
	});

	jQuery('#doaction, #doaction2').click(function(e){
            e.preventDefault();

            if(jQuery('#bulk-action-selector-top').val() == ''){ return; }
            if(jQuery('#wpDataTablesBrowseForm table.widefat input[type="checkbox"]:checked').length == 0){ return; }

            if(confirm("<?php _e('Are you sure','wpdatatables'); ?>?")){
                    jQuery('#wpDataTablesBrowseForm').submit();
            }
	});
	
});
</script>
