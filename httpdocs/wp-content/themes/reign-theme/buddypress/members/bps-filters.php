<?php
/*
 * BP Profile Search - filters template 'bps-filters'
 *
 * See http://dontdream.it/bp-profile-search/form-templates/ if you wish to modify this template or develop a new one.
 * A new or modified template should be moved to the 'buddypress/members' directory in your theme's root, otherwise it
 * will be overwritten during the next plugin update.
 *
 */

$F = bps_escaped_filters_data ();
if (empty ($F->fields))  return false;
?>
<div class='bps_filters'>
	<?php
	foreach ($F->fields as $f)
	{
		$filter = bps_print_filter ($f);
		$filter = apply_filters ('bps_print_filter', $filter, $f);

		/* manage */
		$filter = explode( ': ', $filter );
		$filter = $filter[1];
		/* manage */
		
		?>
		<div class="wbtm-bps_filter"><strong><?php echo $f->label; ?></strong><span>:<?php echo $filter; ?></span></div>
		<?php
	}
	if (!empty ($F->action))
	{
		?>
		<a href='<?php echo $F->action; ?>'><?php _e('Clear', 'buddypress'); ?></a>
		<?php
	}
	?>
</div>
<?php

// BP Profile Search - end of template
