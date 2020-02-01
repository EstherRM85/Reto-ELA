
<div class="reign-header-top">
	<div class="container">

		<div class="header-top-aside header-top-left">
			<?php
			$info_links = get_theme_mod( 'reign_header_topbar_info_links', reign_header_topbar_default_info_links() );
			if( !empty( $info_links ) && is_array( $info_links ) ) {
				foreach ( $info_links as $info_link ) {
					$text = $info_link['link_text'];
					if( !empty( $info_link['link_url'] ) ) {
						$text ='<a href="' . $info_link['link_url'] . '">' . $text . '</a>'; 
					}
					echo '<span>' . $info_link['link_icon'] . $text . '</span>';
				}
			}
			?>
		</div>

		<div class="header-top-aside header-top-right">
			<?php
			$social_links = get_theme_mod( 'reign_header_topbar_social_links', reign_header_topbar_default_social_links() );
			if( !empty( $social_links ) && is_array( $social_links ) ) {
				foreach ( $social_links as $social_link ) {
					echo '<a href="' . $social_link['link_url'] . '" title="' . $social_link['link_text'] . '">' . $social_link['link_icon'] . '</a>';
				}
			}
			?>
		</div>

	</div>
</div>