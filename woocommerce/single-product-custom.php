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
?>

<!-- Start WooCommerce specific opening wrapper -->
<?php do_action( 'woocommerce_before_main_content' ); ?>

<div class="custom-product">

    <!-- LEFT COLUMN: Image & Gallery -->
    <div class="custom-product__gallery">
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
        <div class="breadcrumbs" style="font-size: 12px; color: #666; margin-bottom: 15px;">
            <?php woocommerce_breadcrumb(); ?>
        </div>
        
        <?php if($subtitle): ?>
            <div class="custom-product__subtitle"><?php echo esc_html($subtitle); ?></div>
        <?php endif; ?>

        <h1 class="custom-product__title"><?php the_title(); ?></h1>
        
        <?php if($custom_reviews): ?>
            <div class="custom-product__rating" style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                <div style="display: flex; gap: 4px;">
                    <?php for($i=0; $i<5; $i++): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#f26522"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <?php endfor; ?>
                </div>
                <?php 
                    $review_text = trim($custom_reviews);
                    if (stripos($review_text, 'review') === false) {
                        $review_text .= ' reviews';
                    }
                ?>
                <span style="font-size: 16px; color: #111; font-weight: 500;"><?php echo esc_html($review_text); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="custom-product__price-block">
            <?php 
            $regular_price = (float) $product->get_regular_price();
            $sale_price = (float) $product->get_sale_price();

            if ( $product->is_on_sale() && $regular_price && $sale_price ) : 
                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
            ?>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 5px;">
                    <span style="font-size: 28px; font-weight: 800; color: #1e4d30;">$<?php echo number_format($sale_price, 2); ?></span>
                    <span style="font-size: 20px; font-weight: 500; color: #8a968f; text-decoration: line-through;">$<?php echo number_format($regular_price, 2); ?></span>
                    <span style="background: #ea580c; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px;">-<?php echo esc_html($percentage); ?>% OFF</span>
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
        ?>

        <?php if(!empty($linked_prods)): ?>
        <div class="custom-product-swatches" style="margin-bottom: 12px;">
            <?php if($swatch_prefix || $swatch_name): ?>
                <div style="font-size: 16px; margin-bottom: 12px; color: #1e4d30;">
                    <strong style="font-weight: 800;"><?php echo esc_html($swatch_prefix); ?></strong> 
                    <span style="color: #444; font-weight: 500;"><?php echo esc_html($swatch_name); ?></span>
                </div>
            <?php endif; ?>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <?php foreach($linked_prods as $linked_item): 
                    $linked_id = is_object($linked_item) ? $linked_item->ID : intval($linked_item);
                    $linked_url = get_permalink($linked_id);
                    $linked_img = get_the_post_thumbnail_url($linked_id, 'woocommerce_thumbnail') ?: get_the_post_thumbnail_url($linked_id, 'thumbnail') ?: get_the_post_thumbnail_url($linked_id, 'full'); 
                    $is_active = ((int)$linked_id === (int)$product->get_id());
                ?>
                    <a href="<?php echo esc_url($linked_url); ?>" style="display: block; width: 65px; height: 65px; border-radius: 8px; border: 2.5px solid <?php echo $is_active ? '#ea580c' : 'transparent'; ?>; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: 0.2s;">
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
                    <div style="position: relative; margin-bottom: 8px; width: 100%;">
                        <div style="font-size: 16px; font-weight: 800; color: #1e4d30; padding-bottom: 4px;">
                            Pack Size: <span id="dynamic-pack-label" style="font-weight: 500; color: #444; margin-left: 4px;"><?php echo esc_html($default_title); ?></span>
                        </div>
                        <img src="http://unclejimbkp.local/wp-content/uploads/2026/03/product-type-poster-2.png" alt="Worms" style="position: absolute; right: 0; bottom: 5px; width: 64px; height: 64px; object-fit: contain; z-index: 10;" />
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
                    <a href="<?php echo esc_url($wholesale); ?>" class="custom-product__wholesale-link" style="display:inline-block; margin: 5px 0 15px 0; color:#356e49; font-weight:bold; text-decoration:underline;">
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
            <ul class="custom-product__features-list" style="list-style: none; padding: 0; margin-top:25px;">
                <?php foreach($lines as $line): ?>
                    <?php 
                        $line = trim($line); 
                        if(empty($line)) continue; 
                    ?>
                    <li class="custom-product__feature-item" style="margin-bottom:14px; display:flex; gap:12px; align-items:flex-start; font-size:16px; font-weight:500; color:#111; letter-spacing:-0.2px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#2d6a4f" style="flex-shrink:0; margin-top: -1px;"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        <span style="line-height: 1.4;"><?php echo esc_html($line); ?></span>
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