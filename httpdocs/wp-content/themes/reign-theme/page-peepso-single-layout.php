<?php
/*
  Template Name: PeepSo Layout
*/

get_header();
if ( class_exists('PeepSo') ) {
  global $wbtm_reign_settings;
  $header_position = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'header_position' ] : 'inside';
  $header_position = apply_filters( 'wbtm_rth_manage_header_position', $header_position );

  $peepso_url_segments = PeepSoUrlSegments::get_instance();
  if ( 'peepso_profile' === $peepso_url_segments->_shortcode  || ( ( 'peepso_groups' === $peepso_url_segments->_shortcode ) && ( sizeof( $peepso_url_segments->_segments ) > 1 )  ) ) {
    if ( 'inside' === $header_position ) {
      do_action( 'wbcom_before_content_section' );
    }
  } else {
    do_action( 'wbcom_before_content_section' );
  }
?>

<div class="content-wrapper">
  <?php
  while ( have_posts() ) : the_post();

    get_template_part( 'template-parts/content', 'page' );

  endwhile; // End of the loop.
  ?>
</div>

<?php
  $peepso_url_segments = PeepSoUrlSegments::get_instance();
  if ( 'peepso_profile' === $peepso_url_segments->_shortcode  || ( ( 'peepso_groups' === $peepso_url_segments->_shortcode ) && ( sizeof( $peepso_url_segments->_segments ) > 1 )  ) ) {
    if ( 'inside' === $header_position ) {
      do_action( 'wbcom_after_content_section' );
    }
  } else {
    do_action( 'wbcom_after_content_section' );
  }   
}
get_footer();