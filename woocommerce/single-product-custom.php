<?php
/**
 * Custom Single Product Template
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

while ( have_posts() ) :
	the_post();
	
    // Securely initialize Product object to prevent fatal errors
	global $product;
    if ( empty( $product ) || ! is_object( $product ) ) {
        $product = wc_get_product( get_the_ID() );
    }

// Get ACF Fields safely
$subtitle      = function_exists('get_field') ? get_field('product_subtitle') : '';
$badge_text    = function_exists('get_field') ? get_field('image_badge_text') : '';
$wholesale     = function_exists('get_field') ? get_field('wholesale_link') : '';
$feature_text  = function_exists('get_field') ? get_field('feature_item_text') : ''; 
$custom_reviews= function_exists('get_field') ? get_field('custom_product_reviews') : ''; 
$pack_label_image = function_exists('get_field') ? get_field('pack_label_image') : '';
?>

<!-- Start WooCommerce specific opening wrapper -->
<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="custom-product">
    
    <!-- LEFT COLUMN: Image & Gallery -->
    <div class="custom-product__gallery">
          <div class="custom-product-gallery-header-breadcumb breadcrumbs">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        <?php if($badge_text): ?>
            <div class="custom-product__main-badge"><?php echo esc_html($badge_text); ?></div>
        <?php endif; ?>
        <?php 
            // Load native WooCommerce images
            do_action( 'woocommerce_before_single_product_summary' ); 
        ?>
    </div>

    <!-- RIGHT COLUMN: Details -->
    <div class="custom-product__details">
        <div class="breadcrumbs">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        
        <?php if($subtitle): ?>
            <div class="custom-product__subtitle"><?php echo esc_html($subtitle); ?></div>
        <?php endif; ?>

        <h1 class="custom-product__title"><?php the_title(); ?></h1>
        
        <?php if($custom_reviews): ?>
            <div class="custom-product__rating">
                <div style="display: flex;">
                    <?php for($i=0; $i<5; $i++): ?>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M7.19206 2.14046C7.49095 1.42183 8.50897 1.42183 8.80787 2.14046L10.1959 5.4776L13.7986 5.76643C14.5744 5.82863 14.889 6.79682 14.2979 7.30316L11.553 9.65445L12.3916 13.1701C12.5722 13.9272 11.7486 14.5256 11.0844 14.1199L7.99996 12.2359L4.91553 14.1199C4.25131 14.5255 3.42772 13.9272 3.60831 13.1701L4.44692 9.65445L1.70202 7.30316C1.11093 6.79682 1.42551 5.82863 2.20133 5.76643L5.80406 5.4776L7.19206 2.14046Z" fill="#EA580C"/>
</svg>

                    <?php endfor; ?>
                </div>
                <?php 
                    $review_text = trim($custom_reviews);
                    if (stripos($review_text, 'review') === false) {
                        $review_text .= ' reviews';
                    }
                ?>
                <span><?php echo esc_html($review_text); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="custom-product__price-block">
            <?php 
            $regular_price = (float) $product->get_regular_price();
            $sale_price = (float) $product->get_sale_price();

            if ( $product->is_on_sale() && $regular_price && $sale_price ) : 
                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
            ?>
                <div>
                    <span>$<?php echo number_format($sale_price, 2); ?></span>
                    <span>$<?php echo number_format($regular_price, 2); ?></span>
                    <span >-<?php echo esc_html($percentage); ?>% OFF</span>
                </div>
            <?php else : ?>
                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                    <span style="font-size: 28px; font-weight: 800; color: #1e4d30;">$<?php echo number_format((float)$product->get_price(), 2); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php
        $swatch_prefix = function_exists('get_field') ? get_field('swatch_prefix') : '';
        $swatch_name   = function_exists('get_field') ? get_field('swatch_name') : '';
        $linked_prods_raw = function_exists('get_field') ? get_field('linked_worm_products') : false;
        
        $linked_prods = array();
        if ($linked_prods_raw) {
            if (is_array($linked_prods_raw)) {
                $linked_prods = $linked_prods_raw;
            } else {
                $linked_prods = array($linked_prods_raw);
            }
        }

        // Always include the current product as a visible swatch.
        $swatch_product_ids = array( (int) $product->get_id() );
        foreach ( $linked_prods as $linked_item ) {
            $linked_id = is_object( $linked_item ) ? (int) $linked_item->ID : (int) $linked_item;
            if ( $linked_id > 0 ) {
                $swatch_product_ids[] = $linked_id;
            }
        }
        $swatch_product_ids = array_values( array_unique( $swatch_product_ids ) );
        ?>

        <?php if ( ! empty( $swatch_product_ids ) ) : ?>
        <div class="custom-product-swatches">
            <?php if($swatch_prefix || $swatch_name): ?>
                <div class="custom-product-swatch-heading">
                    <strong class="custom-product-swatch-prefix"><?php echo esc_html($swatch_prefix); ?></strong> 
                    <span class="custom-product-swatch-name"><?php echo esc_html($swatch_name); ?></span>
                </div>
            <?php endif; ?>
            <div class="custom-product-swatch-container">
                <?php foreach ( $swatch_product_ids as $linked_id ) :
                    $linked_url = get_permalink($linked_id);
                    $linked_img = get_the_post_thumbnail_url($linked_id, 'woocommerce_thumbnail') ?: get_the_post_thumbnail_url($linked_id, 'thumbnail') ?: get_the_post_thumbnail_url($linked_id, 'full'); 
                    $is_active = ((int)$linked_id === (int)$product->get_id());
                ?>
                    <a href="<?php echo esc_url($linked_url); ?>" class="custom-product-swatch<?php echo $is_active ? ' is-active' : ''; ?>" >
                        <?php if($linked_img): ?>
                            <img src="<?php echo esc_url($linked_img); ?>" alt="Product Swatch" style="width: 100%; height: 100%; object-fit: cover; opacity: <?php echo $is_active ? '1' : '0.8'; ?>;">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background:#eee;"></div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="custom-product__add-to-cart" style="width: 100%; position: relative;">
            <form class="cart" method="post" enctype='multipart/form-data' style="width: 100%;">
                <?php $pack_sizes = function_exists('get_field') ? get_field('pack_sizes') : false; ?>
                
                <?php if($pack_sizes): ?>
                    <?php $default_title = isset($pack_sizes[0]['title']) ? $pack_sizes[0]['title'] : ''; ?>
                    <div >
                        <div class="custom-pack-label" >
                            Pack Size: <span id="dynamic-pack-label" ><?php echo esc_html($default_title); ?></span>
                        </div>
                        <?php $pack_label_image_src = $pack_label_image ? $pack_label_image : 'http://unclejimbkp.local/wp-content/uploads/2026/03/product-type-poster-2.png'; ?>
                        <img src="<?php echo esc_url($pack_label_image_src); ?>" alt="Worms" style="position: absolute; right: 0; bottom: 5px; width: 64px; height: 64px; object-fit: contain; z-index: 10;" />
                    </div>
                    <div class="custom-pack-cards-container">
                        <?php foreach($pack_sizes as $index => $pack): ?>
                            <label class="custom-pack-card <?php echo $index === 0 ? 'custom-pack-card--selected' : ''; ?>">
                                <input type="radio" name="custom_pack_size_index" value="<?php echo esc_attr($index); ?>" style="display:none;" <?php checked($index, 0); ?>>
                                
                                <div class="custom-pack-card__left">
                                    <span class="custom-pack-card__radio"></span>
                                    <div class="custom-pack-card__content">
                                        <p class="custom-pack-card__title"><?php echo esc_html($pack['title']); ?></p>
                                        <?php if($pack['subtitle']): ?>
                                            <p class="custom-pack-card__subtitle"><?php echo esc_html($pack['subtitle']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                                $clean_price = floatval(preg_replace('/[^0-9.]/', '', $pack['price']));
                                $clean_old = floatval(preg_replace('/[^0-9.]/', '', $pack['old_price']));
                                
                                $dynamic_discount = '';
                                if ($clean_old > 0 && $clean_price > 0 && $clean_old > $clean_price) {
                                    $dynamic_discount = '-' . round((($clean_old - $clean_price) / $clean_old) * 100) . '%';
                                }
                                ?>
                                <div class="custom-pack-card__badges">
                                    <?php if($pack['badge']): ?>
                                        <span class="custom-pack-card__badge"><?php echo esc_html($pack['badge']); ?></span>
                                    <?php endif; ?>
                                    <?php if($dynamic_discount): ?>
                                        <span class="custom-pack-card__discount"><?php echo esc_html($dynamic_discount); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="custom-pack-card__right">
                                    <?php if($clean_price > 0): ?>
                                        <p class="custom-pack-card__price">$<?php echo number_format($clean_price, 2); ?></p>
                                    <?php endif; ?>
                                    <?php if($clean_old > 0 && $clean_old > $clean_price): ?>
                                        <p class="custom-pack-card__reg-price">$<?php echo number_format($clean_old, 2); ?></p>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="color:red; font-size:12px;">Please fill out the "Pack Options" repeater in the product edit screen to show the radio cards.</p>
                <?php endif; ?>
                
                <?php if($wholesale): ?>
                    <a href="<?php echo esc_url($wholesale); ?>" class="custom-product__wholesale-link" >
                        Contact us for wholesale orders
                    </a>
                <?php endif; ?>

                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" />
                <button type="submit" class="single_add_to_cart_button button alt wp-element-button">ADD TO CART</button>
            </form>
        </div>

        <?php if($feature_text): ?>
            <?php 
                // Explode the textarea content by new lines
                $lines = explode("\n", $feature_text); 
            ?>
            <ul class="custom-product__features-list">
                <?php foreach($lines as $line): ?>
                    <?php 
                        $line = trim($line); 
                        if(empty($line)) continue; 
                    ?>
                    <li class="custom-product__feature-item">
                       <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0 8.125C0 3.63769 3.63769 0 8.125 0C12.6123 0 16.25 3.63769 16.25 8.125C16.25 12.6123 12.6123 16.25 8.125 16.25C3.63769 16.25 0 12.6123 0 8.125ZM11.1336 6.61327C11.3342 6.33239 11.2692 5.94205 10.9883 5.74142C10.7074 5.54079 10.317 5.60584 10.1164 5.88673L7.42025 9.66136L6.06694 8.30806C5.82286 8.06398 5.42714 8.06398 5.18306 8.30806C4.93898 8.55214 4.93898 8.94786 5.18306 9.19194L7.05806 11.0669C7.18797 11.1969 7.36846 11.263 7.55155 11.2479C7.73464 11.2327 7.9018 11.1378 8.00858 10.9883L11.1336 6.61327Z" fill="#2C6A4C"/>
</svg>

                        <span><?php echo esc_html($line); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<!-- Close WooCommerce wrapper -->
<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer( 'shop' ); ?>


