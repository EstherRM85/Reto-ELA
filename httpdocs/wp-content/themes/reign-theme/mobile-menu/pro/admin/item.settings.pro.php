<?php

function shiftnav_pro_menu_item_settings( $settings ){
	
	

	$settings['general'][30] = array(
		'id' 		=> 'icon',
		'title'		=> __( 'Icon', 'reign' ),
		'type'		=> 'icon',
		'default' 	=> '',
		'desc'		=> '',
		'ops'		=> shiftnav_get_icon_ops()
	);
	$settings['general'][31] = array(
		'id' 		=> 'icon_custom_class',
		'title'		=> __( 'Custom Icon Class', 'reign' ),
		'type'		=> 'text',
		'default' 	=> '',
		'desc'		=> 'Add a custom class to the &lt;i&gt; tag. If an icon is set above, this class will be appended. If no icon is set above, an icon will appear with this class.',
	);

	$settings['general'][35] = array(
		'id' 		=> 'disable_text',
		'title'		=> 'Disable Text',
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'desc'		=> 'Disable the text of this menu item.  Useful for displaying only an icon',
	);

	$settings['general'][50] = array(
		'id' 		=> 'custom_url',
		'title'		=> __( 'Custom URL' , 'reign' ),
		'type'		=> 'text',
		'default' 	=> '',
		'desc'		=> __( 'Customize your link URL - you can use shortcodes here.  Your setting will be escaped with the esc_url() function', 'reign' ),
	);

	$settings['submenu'][20] = array(
		'id' 		=> 'submenu_type',
		'title'		=> __( 'Submenu Type', 'reign' ),
		'type'		=> 'select',
		'default'	=> 'default',
		'desc'		=>  __( 'Overrides the default submenu type, which can be set in the Control Panel for each menu. ' , 'reign' ),
		'ops'		=> array(
						'default'	=>  __( 'Menu Default', 'reign' ),
						'always'	=>	__( 'Always visible', 'reign' ),
						'accordion'	=>	__( 'Accordion', 'reign' ),
						'shift'		=>	__( 'Shift', 'reign' ),
					),
	);

	return $settings;
}
add_filter( 'shiftnav_menu_item_settings' , 'shiftnav_pro_menu_item_settings' );