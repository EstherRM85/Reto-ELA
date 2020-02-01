<?php
// global $wbtm_reign_settings;
// $membership_levels_per_row = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_pmpro_per_row' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_pmpro_per_row' ] : '3-col-layout';

$membership_levels_per_row = get_theme_mod( 'reign_pmpro_per_row', '3-col-layout' );
if( '3-col-layout' === $membership_levels_per_row ) {
	$membership_levels_per_row = 'price_table_column_3';	
}
else if( '4-col-layout' === $membership_levels_per_row ) {
	$membership_levels_per_row = 'price_table_column_4';	
}

// $membership_levels_layout = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_pmpro_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_pmpro_layout' ] : 'default';
$membership_levels_layout = get_theme_mod( 'reign_pmpro_layout', 'default' );
if( 'default' === $membership_levels_layout ) {
	$membership_levels_layout = 'rtm_pmpro_levels_plan';	
}
else if( 'multicolor' === $membership_levels_layout ) {
	$membership_levels_layout = 'rtm_pmpro_levels_table';	
}

global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$pmpro_levels = pmpro_getAllLevels(false, true);
$pmpro_level_order = pmpro_getOption('level_order');

if(!empty($pmpro_level_order))
{
	$order = explode(',',$pmpro_level_order);

	//reorder array
	$reordered_levels = array();
	foreach($order as $level_id) {
		foreach($pmpro_levels as $key=>$level) {
			if($level_id == $level->id)
				$reordered_levels[] = $pmpro_levels[$key];
		}
	}

	$pmpro_levels = $reordered_levels;
}

$pmpro_levels = apply_filters("pmpro_levels_array", $pmpro_levels);

if($pmpro_msg)
{
?>
<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
}
?>
<div id="pmpro_levels_table" class="pmpro_checkout">
	<div class="<?php echo $membership_levels_layout; ?>">
		<?php	
		$count = 0;
		foreach($pmpro_levels as $level)
		{
			if(isset($current_user->membership_level->ID))
				$current_level = ($current_user->membership_level->ID == $level->id);
			else
				$current_level = false;


			$level_id = $level->id;
			$rtm_pmpro_customization = get_option( 'rtm_pmpro_customization', array() );
			$bg_color = isset( $rtm_pmpro_customization[$level_id]['bg_color'] ) ? $rtm_pmpro_customization[$level_id]['bg_color'] : '';
			$is_featured = isset( $rtm_pmpro_customization[$level_id]['is_featured'] ) ? 'rtm_pmpro_featured' : '';

			$inline_style = '';
			if( !empty( $bg_color ) ) {
				$inline_style = 'background:' . $bg_color . ';';
			}

		?>
		<div class="rtm_pmpro_levels_section items-levels <?php echo $membership_levels_per_row; ?> <?php echo $is_featured; ?>">
			<div class="rtm_pmpro_price" style="<?php echo $inline_style; ?>" >
			<div class="rtm_pmpro_price_top">		

			<h2><?php echo $current_level ? "<strong>{$level->name}</strong>" : $level->name?></h2>
			<div class="rtm_levels_table_price">
				<?php 
					if(pmpro_isLevelFree($level))
						$cost_text = "<strong>" . __("Free", 'paid-memberships-pro' ) . "</strong>";
					else
						$cost_text = pmpro_getLevelCost($level, true, true);
					$expiration_text = pmpro_getLevelExpiration($level);
					if(!empty($cost_text) && !empty($expiration_text))
						echo $cost_text . "" . $expiration_text;
					elseif(!empty($cost_text))
						echo $cost_text;
					elseif(!empty($expiration_text))
						echo $expiration_text;
				?>
			</div>
			</div>
			<div class="rtm_levels_table_des">
				<?php
					/**
					 * All devs to filter the level description at checkout.
					 * We also have a function in includes/filters.php that applies the the_content filters to this description.
					 * @param string $description The level description.
					 * @param object $pmpro_level The PMPro Level object.
					 */
					$level_description = apply_filters('rtm_pmpro_level_description', $level->description, $level);
					if(!empty($level_description))
						echo $level_description;
				?>
			</div>
			<div class="rtm_levels_table_button">
			<?php if(empty($current_user->membership_level->ID)) { ?>
				<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'paid-memberships-pro' );?></a>
			<?php } elseif ( !$current_level ) { ?>                	
				<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Select', 'paid-memberships-pro' );?></a>
			<?php } elseif($current_level) { ?>      
				
				<?php
					//if it's a one-time-payment level, offer a link to renew				
					if( pmpro_isLevelExpiringSoon( $current_user->membership_level) && $current_user->membership_level->allow_signups ) {
						?>
							<a class="pmpro_btn pmpro_btn-select" href="<?php echo pmpro_url("checkout", "?level=" . $level->id, "https")?>"><?php _e('Renew', 'paid-memberships-pro' );?></a>
						<?php
					} else {
						?>
							<a class="pmpro_btn disabled" href="<?php echo pmpro_url("account")?>"><?php _e('Your&nbsp;Level', 'paid-memberships-pro' );?></a>
						<?php
					}
				?>
				
			<?php } ?>
			</div>
			</div>
		</div>
		<?php
		}
		?>

	</div>
</div>
<nav id="nav-below" class="navigation" role="navigation">
	<div class="nav-previous alignleft">
		<?php if(!empty($current_user->membership_level->ID)) { ?>
			<a href="<?php echo pmpro_url("account")?>" id="pmpro_levels-return-account"><?php _e('&larr; Return to Your Account', 'paid-memberships-pro' );?></a>
		<?php } else { ?>
			<a href="<?php echo home_url()?>" id="pmpro_levels-return-home"><?php _e('&larr; Return to Home', 'paid-memberships-pro' );?></a>
		<?php } ?>
	</div>
</nav>