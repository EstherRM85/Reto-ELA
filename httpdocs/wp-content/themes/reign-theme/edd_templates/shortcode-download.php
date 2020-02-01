<?php
$download_list_layout = 'rtm-download-item-article';
$download_id = get_the_ID();
$download = edd_get_download( $download_id );
$variable_pricing = $download->has_variable_prices();
$type             = $download->is_single_price_mode() ? 'data-price-mode=multi' : 'data-price-mode=single';
$data_price = '';
$options = array();
if ( edd_item_in_cart( $download_id, $options ) && ( ! $variable_pricing || ! $download->is_single_price_mode() ) ) {
    $button_text = __( 'Checkout', 'reign' );
    $href = esc_url( edd_get_checkout_uri() );
    $class_to_manage = '';
    
    $button_display   = 'style="display:none;"';
    $checkout_display = '';
} else {
    $button_text = __( 'Buy Now', 'reign' );
    $href = 'javascript:void(0);';
    $class_to_manage = 'edd_buy_now';

    $button_display   = '';
    $checkout_display = 'style="display:none;"';
}
if ( ! $variable_pricing ) {
    $data_price_value = $download->price;
    //if ( $show_price ) {
        $price = $download->price;
        $data_price  = 'data-price="' . $data_price_value . '"';
    //}
}
$button_html = '<a href="'.$href.'" class="button button-overlay-white edd-add-to-cart ' . esc_attr( $class_to_manage ) . '" data-nonce="' .  wp_create_nonce( 'edd-add-to-cart-' . $download_id ) . '" data-action="edd_add_to_cart" data-download-id="' . esc_attr( $download_id ) . '"' . $type . ' ' . $data_price . ' ' . $button_display . '><span class="edd-add-to-cart-label">' . $button_text . '</span><span class="edd-loading" aria-label="' . esc_attr__( 'Loading', 'easy-digital-downloads' ) . '"></span></a>';
$RTM_PMPRO_Customization_OBJ = RTM_PMPRO_Customization::instance();
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $download_list_layout ); ?> >
    <div class="rtm-download-item">
        <div class="rtm-download-item-inner">
            <div class="rtm-download-item-top">
                <?php edd_get_template_part( 'shortcode', 'content-image' ); ?>
                <div class="rtm-download-overlay">
                    <div class="rtm-download-action">
                        <?php 
                            if ( ! $variable_pricing ) {
                                echo $button_html;
                            }
                        ?>
                        <?php echo '<a href="' . esc_url( edd_get_checkout_uri() ) . '" class="button button-overlay-white edd_go_to_checkout ' . esc_attr( $class_to_manage ) . '" ' . $checkout_display . '>Checkout</a>'; ?>
                        <a href="<?php echo get_the_permalink(); ?>" class="button button-overlay-white">
                            <?php _e( 'Details', 'reign' ); ?>
                        </a>
                        <?php $RTM_PMPRO_Customization_OBJ->rtm_get_edd_download_price_html(); ?>
                    </div>
                </div>
            </div>
            <div class="rtm-download-item-bottom">
                <?php  edd_get_template_part( 'shortcode', 'content-title' ); ?>
                <?php 
                    if( class_exists( 'EDD_Front_End_Submissions' ) ){
                         $vendor_store_url = EDD_FES()->vendors->get_vendor_store_url( get_the_author_meta( 'ID' ) );
                    }else{
                        $vendor_store_url = '';
                    }
                   
                    printf(
                        __( '<span class="byline"> by %1$s</span>', 'easy-digital-downloads' ),
                        sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
                             $vendor_store_url,
                            esc_attr( sprintf( __( 'View all posts by %s', 'easy-digital-downloads' ), get_the_author_meta( 'display_name' ) ) ),
                            esc_html( get_the_author_meta( 'display_name' ) ),
                            get_avatar( get_the_author_meta( 'ID' ), 50 )
                        )
                    ); 
                ?>
                <?php do_action( 'edd_download_after_title' ); ?>
            </div>
            <div class="rtm-download-checkout-popup" style="display: none;">
                <div class="rtm-download-popup-inners">
                    <span class="close_edd_popup"><i class="fa fa-close"></i></span>
                    <h3 class="section-title"><span>Buying Options</span></h3>
                    <?php echo do_shortcode( '[purchase_link id="' . $download_id . '" text="Purchase"]' ); ?>
                </div>
            </div>
        </div>
    </div>
</div><!-- #post-## -->
<?php
return;
/**
 * A single download inside of the [downloads] shortcode.
 *
 * @since 2.8.0
 *
 * @package EDD
 * @category Template
 * @author Easy Digital Downloads
 * @version 1.0.0
 */

global $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i;
?>

<?php $schema = edd_add_schema_microdata() ? 'itemscope itemtype="http://schema.org/Product" ' : ''; ?>

<?php
$download_id = get_the_ID();
$download = edd_get_download( $download_id );
$variable_pricing = $download->has_variable_prices();
$options = array();
if ( edd_item_in_cart( $download_id, $options ) && ( ! $variable_pricing || ! $download->is_single_price_mode() ) ) {
    $button_text = __( 'Checkout', 'reign' );
    $href = esc_url( edd_get_checkout_uri() );
    $class_to_manage = '';
} else {
    $button_text = __( 'Buy Now', 'reign' );
    $href = 'javascript:void(0);';
    $class_to_manage = 'edd_buy_now';
}
$RTM_PMPRO_Customization_OBJ = RTM_PMPRO_Customization::instance();
?>

<div <?php echo $schema; ?>class="<?php echo esc_attr( apply_filters( 'edd_download_class', 'edd_download', get_the_ID(), $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i ) ); ?>" id="edd_download_<?php the_ID(); ?>">

    <div class="rtm-download-item-inner <?php echo esc_attr( apply_filters( 'edd_download_inner_class', 'edd_download_inner', get_the_ID(), $edd_download_shortcode_item_atts, $edd_download_shortcode_item_i ) ); ?>">

        <div class="rtm-download-item-top">
        <?php
            do_action( 'edd_download_before' );

            if ( 'false' !== $edd_download_shortcode_item_atts['thumbnails'] ) :
                edd_get_template_part( 'shortcode', 'content-image' );
                do_action( 'edd_download_after_thumbnail' );
            endif;

            ?>
            <div class="rtm-download-overlay">
                <div class="rtm-download-action">
                    <a href="<?php echo $href; ?>" class="button button-overlay-white <?php echo $class_to_manage; ?>">
                        <?php echo $button_text; ?>
                    </a>
                    <a href="<?php echo get_the_permalink(); ?>" class="button button-overlay-white">
                        <?php _e( 'Details', 'reign' ); ?>
                    </a>
                    <?php $RTM_PMPRO_Customization_OBJ->rtm_get_edd_download_price_html(); ?>
                </div>
            </div>
        </div>

        <div class="rtm-download-item-bottom">   
            <?php

            edd_get_template_part( 'shortcode', 'content-title' );

            do_action( 'edd_download_after_title' );

            if ( 'yes' === $edd_download_shortcode_item_atts['excerpt'] && 'yes' !== $edd_download_shortcode_item_atts['full_content'] ) :
                edd_get_template_part( 'shortcode', 'content-excerpt' );
                do_action( 'edd_download_after_content' );
            elseif ( 'yes' === $edd_download_shortcode_item_atts['full_content'] ) :
                edd_get_template_part( 'shortcode', 'content-full' );
                do_action( 'edd_download_after_content' );
            endif;

            if ( 'yes' === $edd_download_shortcode_item_atts['price'] ) :
                edd_get_template_part( 'shortcode', 'content-price' );
                do_action( 'edd_download_after_price' );
            endif;

            ?>
        </div>

            <div class="rtm-download-checkout-popup" style="display: none;">
                <div class="rtm-download-popup-inners">
                    <span class="close_edd_popup"><i class="fa fa-close"></i></span>
                    <h3 class="section-title"><span>Buying Options</span></h3>
                    <?php //echo do_shortcode( '[purchase_link id="' . $download_id . '" text="Purchase"]' ); ?>
                    <?php edd_get_template_part( 'shortcode', 'content-cart-button' ); ?>
                </div>
            </div>
            <?php


            if ( 'yes' === $edd_download_shortcode_item_atts['buy_button'] ) :
                // edd_get_template_part( 'shortcode', 'content-cart-button' );
            endif;

            do_action( 'edd_download_after' );
        ?>

    </div>

</div>