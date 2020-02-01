<?php
/* setting up the title */
$title = get_the_title();

$kirki_post_types_support_class	 = new Reign_Kirki_Post_Types_Support();
$supported_post_types			 = $kirki_post_types_support_class->get_post_types_to_support();

if ( is_tag() || is_tax() ) {
	$title = single_term_title( '', false );
} elseif ( is_post_type_archive() ) {
	$post_type = get_query_var( 'post_type' );
	if ( is_array( $post_type ) ) {
		$post_type = reset( $post_type );
	}
	$post_type_obj = get_post_type_object( $post_type );
	if( isset( $post_type_obj->labels->name ) ){
		$title = $post_type_obj->labels->name;
	}
} elseif ( is_category() ) {
	$title = single_cat_title( '', false );
} elseif ( is_author() ) {
	$author_id = get_query_var( 'author' );
	if ( $author_id ) {
		$author = get_user_by( 'id', $author_id );
		if ( !empty( get_user_meta( $author_id, 'first_name', true ) ) ) {
			$author_name = get_user_meta( $author_id, 'first_name', true ) . ' ' . get_user_meta( $author_id, 'last_name', true );
		} else {
			$author_info = get_userdata( $author_id );
			$author_name = $author_info->data->user_login;
		}
		$title = $author_name;
	}
}

if( !$title && is_single() ){
	$title = get_the_title( get_queried_object_id() );
}

if ( !is_front_page() && is_home() ) {
	$title = 'Blog';
}
if ( is_search() ) {
	$title = __( 'Search results for: ', 'reign' ) . get_search_query();
}

$title = apply_filters( 'reign_page_header_section_title', $title );

$post_type = get_post_type();
if ( is_singular() ) {
	$post_type = get_post_type();
	if ( !in_array( $post_type, array_column( $supported_post_types, 'slug' ) ) ) {
		$banner_header = get_theme_mod( 'reign_cpt_default_single_enable_header_image', true );
	} else {
		$banner_header = get_theme_mod( 'reign_' . $post_type . '_single_enable_header_image', true );
	}
	// $banner_header = get_theme_mod( 'reign_' . $post_type . '_single_enable_header_image', true );
} else {
	$banner_header = get_theme_mod( 'reign_' . $post_type . '_archive_enable_header_image', true );
}

$breadcrumb = get_theme_mod( 'reign_site_enable_breadcrumb', true );
if ( !$banner_header ) :
	?>
	<div class="lm-site-header-section without-img-header">
		<div class="lm-header-banner">
			<div class="rg-sub-header-inner-section">
				<div class="container">
					<?php
					if( $title ){
						echo '<h3 class="lm-header-title">' . $title . '</h3>';
					}
					if ( $breadcrumb ) {
						?>
						<div class="lm-breadcrumbs-wrapper">
							<div class="container"><?php reign_breadcrumbs(); ?></div>
						</div>
						<?php
					}
					?>
				</div>
				<?php do_action( 'reign_page_header_extra' ); ?>
			</div>
		</div>
	</div>
	<?php
else:
	$post_type = get_post_type();
	// $kirki_post_types_support_class = new Reign_Kirki_Post_Types_Support();
	// $supported_post_types = $kirki_post_types_support_class->get_post_types_to_support();
	if ( is_singular() ) {
		if ( !in_array( $post_type, array_column( $supported_post_types, 'slug' ) ) ) {
			$header_banner_image_url = get_theme_mod( 'reign_cpt_default_sub_header_image', '' );
		} else {
			$header_banner_image_url = get_theme_mod( 'reign_' . $post_type . '_single_header_image', '' );
		}

		if ( empty( $header_banner_image_url ) ) {
			$header_banner_image_url = reign_get_default_page_header_image();
		}

		if ( 'post' === $post_type ) {
			$switch_header_image = get_theme_mod( 'reign_single_post_switch_header_image', false );
			if ( $switch_header_image && has_post_thumbnail() ) {
				$header_banner_image_url = get_the_post_thumbnail_url();
			}
		} elseif ( !in_array( $post_type, array_column( $supported_post_types, 'slug' ) ) ) {
			$switch_header_image = get_theme_mod( 'reign_cpt_default_switch_header_image', false );
			if ( $switch_header_image && has_post_thumbnail() ) {
				$header_banner_image_url = get_the_post_thumbnail_url();
			}
		}

		$header_banner_image_url = apply_filters( 'reign_' . $post_type . '_single_header_image', $header_banner_image_url, $post_type );
	} else {
		if ( !in_array( $post_type, array_column( $supported_post_types, 'slug' ) ) ) {
			$header_banner_image_url = get_theme_mod( 'reign_cpt_default_sub_header_image', '' );
		} else {
			$header_banner_image_url = get_theme_mod( 'reign_' . $post_type . '_archive_header_image', '' );
		}
		if ( empty( $header_banner_image_url ) ) {
			$header_banner_image_url = reign_get_default_page_header_image();
		}
		$header_banner_image_url = apply_filters( 'reign_' . $post_type . '_archive_header_image', $header_banner_image_url, $post_type );
	}
	?>
	<div class="lm-site-header-section">
		<div class="lm-header-banner">
			<div class="lm-header-banner-overlay" style="background-image:url(<?php echo $header_banner_image_url; ?>);">
			</div>
			<div class="rg-sub-header-inner-section">
				<div class="lm-header-title-wrapper container">
					<?php
						if( $title ){
							echo '<h3 class="lm-header-title">' . $title . '</h3>';
						}
					?>
				</div>
				<?php
				if ( $breadcrumb ) {
					?>
					<div class="lm-breadcrumbs-wrapper">
						<div class="container"><?php reign_breadcrumbs(); ?></div>
					</div>
					<?php
				}
				?>
				<?php do_action( 'reign_page_header_extra' ); ?>
			</div>
		</div>
	</div>
				    <?php
endif;
