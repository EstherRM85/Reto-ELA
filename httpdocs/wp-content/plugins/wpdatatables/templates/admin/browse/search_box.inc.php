<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="form-group wpdt-search-box search-box">
    <div class="fg-line">
        <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>"><?php echo $text; ?>:</label>
        <input type="search" class="form-control input-sm pull-left" id="<?php echo esc_attr($input_id); ?>" name="s"
               value="<?php _admin_search_query(); ?>"/>
    </div>
    <button id="search-submit" class="wpdt-control-buttons">
        <i class="zmdi zmdi-search"></i>
    </button>
</div>