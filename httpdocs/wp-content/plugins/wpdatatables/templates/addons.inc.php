<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div id="wdtPreloadLayer" class="overlayed">
</div>

<div class="wrap">
    <img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/wpdatatables-logo.png" class="wpdatatables_logo" style="margin: 10px" />
	<p><i><?php _e('Please refer to','wpdatatables');?> <a href="http://wpdatatables.com/documentation/"><?php _e('wpDataTables documentation','wpdatatables');?></a> <?php _e('if you have some questions or problems with the plugin.','wpdatatables'); ?></i></p>
    <h2>
        <?php echo __('wpDataTables Addons'); ?>
    </h2>
    <p><?php _e('While wpDataTables itself provides quite a large amount of features and unlimited customisation flexibility, you can achieve even more with our premium addons. Each addon brings you some unique extension to the core functionality. There will be more addons developed over time by wpDataTables creators and 3rd party developers, so stay tuned.','wpdatatables');?></p>

    <div class="addons_container">
        <div class="wdt_addon">
            <div class="wdt_addon_thumb">
                <a href="http://wpreportbuilder.com?utm_source=wpdt" target="_blank"><img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/reportbuilder_addon.png" /></a>
                <div class="wdt_addon_title">
                    <h3><a href="http://wpreportbuilder.com?utm_source=wpdt" target="_blank"><?php _e('Report Builder','wpdatatables');?></a></h3>
                </div>
            </div>
            <div class="wdt_description">
                <p><?php _e('A unique tool that allows you to generate almost any Word DOCX and Excel XLSX documents filled in with actual data from your database.','wpdatatables'); ?></p>
                <a href="http://wpreportbuilder.com?utm_source=wpdt" target="_blank" class="button button-primary wdt_addon_read_more"><?php _e('Find out more','wpdatatables'); ?> &gt;</a>
            </div>
        </div>
    </div>

</div>
