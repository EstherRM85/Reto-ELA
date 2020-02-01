<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<select name="action<?php echo $two ?>" class="form-group fg-line wpdt-bulk-select selectpicker" tabindex="-98"
        id="bulk-action-selector-<?php echo esc_attr($which) ?>">
    <option value="-1"><?php echo __('Bulk Actions', 'wpdatatables') ?></option>
    <?php
    foreach ($this->_actions as $name => $title) {
        $class = 'edit' === $name ? ' class="hide-if-no-js"' : '';

        echo '<option value="' . $name . '"' . $class . '>' . $title . '</option>';
    } ?>
</select>

<button id="doaction<?php echo $two ?>" class="wpdt-control-buttons bulk-action-button">
    <?php echo __('APPLY', 'wpdatatables') ?>
</button>
