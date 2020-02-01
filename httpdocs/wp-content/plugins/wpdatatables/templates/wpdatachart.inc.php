<?php defined('ABSPATH') or die('Access denied.'); ?>

<script type="text/javascript">if (typeof (wpDataCharts) == 'undefined') {
        wpDataCharts = {};
    }
        wpDataCharts[<?php echo $chart_id; ?>] = {
        render_data: <?php echo $json_chart_render_data; ?>,
        engine: "google",
        type: "<?php echo $this->_type; ?>",
        title: "<?php echo $this->_title; ?>",
        container: "wpDataChart_<?php echo $chart_id?>",
        wpdatatable_id: <?php echo $this->_wpdatatable_id ?>}</script>

<div id="wpDataChart_<?php echo $chart_id ?>" class="<?php echo $this->_type ?>" style="width: 100%"></div>
