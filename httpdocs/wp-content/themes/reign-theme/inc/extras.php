<?php

if ( !function_exists( 'reign_body_classes' ) ) {

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function reign_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		// Adds a class of hfeed to non-singular pages.
		if ( !is_singular() ) {
			$classes[] = 'hfeed';
		}

		$diable_typo = get_option( 'elementor_disable_typography_schemes' );
		if ( isset( $diable_typo ) && ( $diable_typo == 'yes' ) ) {
			$classes[] = 'reign-typo';
		}

		// Boxed layout
		if ( true ) {
			//$classes[] = 'rg-boxed-layout';
		}

		$reign_mobile_default_icons_set = reign_mobile_header_default_icons_set();
		$reign_mobile_header_icons_set  = get_theme_mod( 'shiftnav_config_togglebar_mobile_header_icons_setting', $reign_mobile_default_icons_set );
		if ( ! empty( $reign_mobile_header_icons_set ) ) {
			$classes[] = 'reign-header-icons-enable';
		}	

		return $classes;
	}

	add_filter( 'body_class', 'reign_body_classes' );
}

if ( !function_exists( 'reign_pingback_header' ) ) {

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	function reign_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '" />';
		}
	}

	add_action( 'wp_head', 'reign_pingback_header' );
}

if ( !function_exists( 'reign_viewport_meta' ) ) {

	/**
	 * Add a viewport meta
	 */
	function reign_viewport_meta() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
	}

	add_action( 'wp_head', 'reign_viewport_meta' );
}

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Reign
 */
if ( !function_exists( 'reign_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function reign_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'reign' ), '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'reign' ), '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
	}

endif;

/**
 * Prints HTML with meta information.
 */
if ( !function_exists( 'reign_entry_list_footer' ) ) {

	function reign_entry_list_footer() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
		}

		$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'reign' ), '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		/** Co-author support added * */
		if ( class_exists( 'CoAuthors_Plus' ) ) {
			$coauthors = get_coauthors();
			foreach ( $coauthors as $coauthor ) :
				$author_name = get_the_author_meta( 'first_name', $coauthor->ID ) . ' ' . get_the_author_meta( 'last_name', $coauthor->ID );
				$byline		 = sprintf(
				esc_html_x( '%s', 'post author', 'reign' ), '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $coauthor->ID ) ) . '">' . esc_html( $author_name ) . '</a></span>'
				);

				echo '<span class="byline"><i class="fa fa-user-circle"></i> ' . $byline . '<span class="posted-on">' . $posted_on . '</span></span>'; // WPCS: XSS OK
			endforeach;
		}
		else {

			$avatar = '<i class="fa fa-user-circle"></i>';
			if ( function_exists( 'get_avatar' ) ) {
				$avatar = sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_avatar( get_the_author_meta( 'email' ), 55 )
				);
			}

			$byline = sprintf(
			esc_html_x( '%s', 'post author', 'reign' ), '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);

			echo '<span class="byline">' . $avatar . $byline . '<span class="posted-on">' . $posted_on . '</span></span>'; // WPCS: XSS OK
		}

		// echo '<span class="posted-on"><!--<i class="rg-calendar"></i>-->'. $posted_on . '</span>';


		/* list of categories assigned to post */
		$output			 = '';
		$categories_list = get_the_category_list( __( ' ', 'reign' ) );
		if ( $categories_list ) {
			$categories	 = sprintf(
			esc_html( '%1$s' ), $categories_list
			);
			$output		 .= '<span class="cat-links"><!--<i class="rg-category"></i>-->' . $categories . '</span>';
		}
		echo apply_filters( 'reign_post_categories', $output );

		/* list of tags assigned to post */
		// $tags_list	 = get_the_term_list( get_the_ID(), 'post_tag', $before		 = '', $sep		 = ', ', $after		 = '' );
		// if ( $tags_list ) {
		// 	$tags_list = '<span class="tag-links"><!-- <i class="rg-tag"></i> -->' . $tags_list . '</span>';
		// }
		// echo apply_filters( 'reign_post_tags', $tags_list );

		do_action( 'reign_render_additional_post_meta' );
	}

}

if ( !function_exists( 'reign_entry_footer' ) ) :

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function reign_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'reign' ) );
			if ( $categories_list && reign_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'reign' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'reign' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'reign' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( !is_single() && !post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			/* translators: %s: post title */
			comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'reign' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
			echo '</span>';
		}

		edit_post_link(
		sprintf(
		/* translators: %s: Name of current post */
		esc_html__( 'Edit %s', 'reign' ), the_title( '<span class="screen-reader-text">"', '"</span>', false )
		), '<span class="edit-link">', '</span>'
		);
	}

endif;

if ( !function_exists( 'reign_categorized_blog' ) ) {

	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function reign_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'reign_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'	 => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'	 => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'reign_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so reign_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so reign_categorized_blog should return false.
			return false;
		}
	}

}

if ( !function_exists( 'reign_category_transient_flusher' ) ) {

	/**
	 * Flush out the transients used in reign_categorized_blog.
	 */
	function reign_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Like, beat it. Dig?
		delete_transient( 'reign_categories' );
	}

	add_action( 'edit_category', 'reign_category_transient_flusher' );
	add_action( 'save_post', 'reign_category_transient_flusher' );
}


if ( !function_exists( 'rg_page_loader' ) ) {

	/**
	 * Page Loader
	 */
	function rg_page_loader() {
		// global $wbtm_reign_settings;
		// $active_loader_layout	 = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'active_loader_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'active_loader_layout' ] : 'no';
		$active_loader_layout		 = get_theme_mod( 'reign_enable_preloading', false );
		$reign_preloading_icon		 = get_theme_mod( 'reign_preloading_icon', '' );
		$reign_preloading_bg_color	 = get_theme_mod( 'reign_preloading_bg_color', '' );
		if ( $active_loader_layout ) {
			echo '<div class="rg-page-loader"></div>';
		}
	}

	add_action( 'wbcom_before_page', 'rg_page_loader' );
}


if ( !function_exists( 'rg_content_wrapper_start' ) ) {

	function rg_content_wrapper_start() {
		/*		 * * Add PeepSo support ** */
		global $wbtm_reign_settings;
		$header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
		$header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );
		$wrapper		 = '<div class="container"><div class="wb-grid">';
		if ( class_exists( 'PeepSo' ) ) {
			$peepso_url_segments = PeepSoUrlSegments::get_instance();
			if ( 'peepso_profile' === $peepso_url_segments->_shortcode || ( ( 'peepso_groups' === $peepso_url_segments->_shortcode ) && ( sizeof( $peepso_url_segments->_segments ) > 1 ) ) ) {
				if ( 'inside' !== $header_position ) {
					$wrapper = '<div class="container"><div class="reign-peepso-page">';
				}
			}
		}
		echo $wrapper;
	}

	add_action( 'wbcom_content_top', 'rg_content_wrapper_start' );
}


if ( !function_exists( 'rg_content_wrapper_end' ) ) {

	function rg_content_wrapper_end() {
		echo '</div></div>';
	}

	add_action( 'wbcom_content_bottom', 'rg_content_wrapper_end' );
}

if ( !function_exists( 'rg_woocommerce_theme_wrapper_start' ) ) {

	/**
	 * WooCommerce start wrapper
	 */
	function rg_woocommerce_theme_wrapper_start() {
		echo '<div class="content-wrapper">';
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	add_action( 'woocommerce_before_main_content', 'rg_woocommerce_theme_wrapper_start', 10 );
}

if ( !function_exists( 'rg_woocommerce_theme_wrapper_end' ) ) {

	/**
	 * WooCommerce end wrapper
	 */
	function rg_woocommerce_theme_wrapper_end() {
		echo '</div>';
	}

	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_after_main_content', 'rg_woocommerce_theme_wrapper_end', 10 );
}

/**
 * WooCommerce Change number or products per row to 3
 */



/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */
// function woo_related_products_limit() {
// 	global $product;

// 	$args[ 'posts_per_page' ] = 6;
// 	return $args;
// }

// add_filter( 'woocommerce_output_related_products_args', 'rg_related_products_args' );

// function rg_related_products_args( $args ) {
// 	$args[ 'posts_per_page' ]	 = 6; // 4 related products
// 	$args[ 'columns' ]			 = 5; // arranged in 2 columns
// 	return $args;
// }

/**
 * Mobile user menu
 */
// if ( !function_exists( 'rg_mobile_user_menu' ) ) {

// 	function rg_mobile_user_menu() {
// 		get_template_part( 'template-parts/mobile-user-menu' );
// 	}

// 	add_action( 'wbcom_begin_masthead', 'rg_mobile_user_menu' );
// }
