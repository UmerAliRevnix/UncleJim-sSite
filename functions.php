<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'ASTRA_THEME_VERSION', '4.12.6' );
define( 'ASTRA_THEME_SETTINGS', 'astra-settings' );
define( 'ASTRA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'ASTRA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'ASTRA_THEME_ORG_VERSION', file_exists( ASTRA_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the Astra Pro addon.
 * This constant will be used to display the notice asking user to update the Astra addon to the version defined below.
 */
define( 'ASTRA_EXT_MIN_VER', '4.12.0' );

/**
 * Load in-house compatibility.
 */
if ( ASTRA_THEME_ORG_VERSION ) {
	require_once ASTRA_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of Astra.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-theme-options.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once ASTRA_THEME_DIR . 'inc/core/common-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-icons.php';

define( 'ASTRA_WEBSITE_BASE_URL', 'https://wpastra.com' );

/**
 * Update theme
 */
require_once ASTRA_THEME_DIR . 'inc/theme-update/astra-update-functions.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-font-families.php';
if ( is_admin() ) {
	require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts-data.php';
}

require_once ASTRA_THEME_DIR . 'inc/lib/webfont/class-astra-webfont-loader.php';
require_once ASTRA_THEME_DIR . 'inc/lib/docs/class-astra-docs-loader.php';
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts.php';

require_once ASTRA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/astra-icons.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-walker-page.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-enqueue-scripts.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-wp-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-command-palette.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-dynamic-css.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'ASTRA_SITES_VER' ) || version_compare( ASTRA_SITES_VER, '4.3.7', '<' ) || version_compare( ASTRA_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-notice.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-attr.php';
require_once ASTRA_THEME_DIR . 'inc/template-tags.php';

require_once ASTRA_THEME_DIR . 'inc/widgets.php';
require_once ASTRA_THEME_DIR . 'inc/core/theme-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/admin-functions.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-memory-limit-notice.php';
require_once ASTRA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once ASTRA_THEME_DIR . 'inc/markup-extras.php';
require_once ASTRA_THEME_DIR . 'inc/extras.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog-config.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog.php';
require_once ASTRA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once ASTRA_THEME_DIR . 'inc/template-parts.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-loop.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once ASTRA_THEME_DIR . 'inc/class-astra-after-setup-theme.php';

// Required files.
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-helper.php';

require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-schema.php';

/* Setup API */
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-learn.php';
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-settings.php';
	require_once ASTRA_THEME_DIR . 'admin/class-astra-admin-loader.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/astra-notices/class-astra-notices.php';
}

/**
 * Metabox additions.
 */
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-boxes.php';
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-box-operations.php';
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-elementor-editor-settings.php';

/**
 * Customizer additions.
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer.php';

/**
 * Astra Modules.
 */
require_once ASTRA_THEME_DIR . 'inc/modules/posts-structures/class-astra-post-structures.php';
require_once ASTRA_THEME_DIR . 'inc/modules/related-posts/class-astra-related-posts.php';

/**
 * Compatibility
 */
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gutenberg.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-jetpack.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/class-astra-woocommerce.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/edd/class-astra-edd.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/lifterlms/class-astra-lifterlms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/learndash/class-astra-learndash.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bb-ultimate-addon.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-contact-form-7.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-visual-composer.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-site-origin.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gravity-forms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bne-flyout.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-ubermeu.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-divi-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-amp.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-yoast-seo.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/surecart/class-astra-surecart.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-starter-content.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-buddypress.php';
require_once ASTRA_THEME_DIR . 'inc/addons/transparent-header/class-astra-ext-transparent-header.php';
require_once ASTRA_THEME_DIR . 'inc/addons/breadcrumbs/class-astra-breadcrumbs.php';
require_once ASTRA_THEME_DIR . 'inc/addons/scroll-to-top/class-astra-scroll-to-top.php';
require_once ASTRA_THEME_DIR . 'inc/addons/heading-colors/class-astra-heading-colors.php';
require_once ASTRA_THEME_DIR . 'inc/builder/class-astra-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor-pro.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-themer.php';
}

require_once ASTRA_THEME_DIR . 'inc/core/markup/class-astra-markup.php';

/**
 * Abilities API integration.
 */
require_once ASTRA_THEME_DIR . 'inc/abilities/bootstrap.php';

/**
 * Load deprecated functions
 */
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';

add_action('woocommerce_product_options_general_product_data', function() {

    woocommerce_wp_checkbox([
        'id' => '_use_custom_template',
        'label' => 'Use Custom Product Template',
        'description' => 'Enable custom Figma-based product layout for this product'
    ]);

});

add_action('woocommerce_process_product_meta', function($post_id) {

    $value = !empty($_POST['_use_custom_template']) ? 'yes' : 'no';
    update_post_meta($post_id, '_use_custom_template', $value);

});

add_filter('woocommerce_locate_template', function($template, $template_name, $template_path) {

    if (!is_product()) {
        return $template;
    }

    global $post;

    if (!$post) return $template;

    $use_custom = get_post_meta($post->ID, '_use_custom_template', true);

    if ($use_custom === 'yes' && $template_name === 'single-product.php') {

        $custom = get_stylesheet_directory() . '/woocommerce/single-product-custom.php';

        if (file_exists($custom)) {
            return $custom;
        }
    }

    return $template;

}, 10, 3);

add_action('wp', function() {
    if (is_product()) {
        error_log('Product page loaded');
    }
});

function enqueue_custom_product_assets() {
    if ( is_product() ) {
        // Enqueue perfectly generated styles
        wp_enqueue_style( 
            'custom-product-css', 
            get_stylesheet_directory_uri() . '/single-product-custom.css', 
            array(), 
            filemtime(get_stylesheet_directory() . '/single-product-custom.css') 
        );
        // Enqueue precisely extracted JS
        wp_enqueue_script( 
            'custom-product-js', 
            get_stylesheet_directory_uri() . '/single-product-custom.js', 
            array(), 
            filemtime(get_stylesheet_directory() . '/single-product-custom.js'), 
            true 
        );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_product_assets' );

// -------------------------------------------------------------
// ACF RADIO CARDS AUTO-GENERATOR
// -------------------------------------------------------------
if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_custom_pack_options',
        'title' => 'Custom Pack Radio Options (Figma)',
        'fields' => array(
            array(
                'key' => 'field_product_swatch_label_prefix',
                'label' => 'Product Type Label Prefix',
                'name' => 'swatch_prefix',
                'type' => 'text',
                'placeholder' => 'e.g. Composting Mix:',
            ),
            array(
                'key' => 'field_product_swatch_name',
                'label' => 'Product Type Name',
                'name' => 'swatch_name',
                'type' => 'text',
                'placeholder' => 'e.g. Red Worms',
            ),
            array(
                'key' => 'field_linked_worm_products',
                'label' => 'Linked Products (Image Swatch)',
                'name' => 'linked_worm_products',
                'type' => 'relationship',
                'post_type' => array('product'),
                'return_format' => 'id',
                'instructions' => 'Select the products (including THIS ONE) that should appear side-by-side as thumbnail links.',
            ),
            array(
                'key' => 'field_pack_options_repeater',
                'label' => 'Pack Options',
                'name' => 'pack_sizes',
                'type' => 'repeater',
                'instructions' => 'Add the options for the radio cards here.',
                'button_label' => 'Add Option Card',
                'sub_fields' => array(
                    array(
                        'key' => 'field_pack_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'placeholder' => 'e.g. 500 Worms',
                    ),
                    array(
                        'key' => 'field_pack_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'placeholder' => 'e.g. Great For Beginners',
                    ),
                    array(
                        'key' => 'field_pack_badge',
                        'label' => 'Green Badge',
                        'name' => 'badge',
                        'type' => 'text',
                        'placeholder' => 'e.g. Bestseller',
                    ),
                    array(
                        'key' => 'field_pack_price',
                        'label' => 'Current Price (Number only)',
                        'name' => 'price',
                        'type' => 'text',
                        'placeholder' => '39.95',
                    ),
                    array(
                        'key' => 'field_pack_old_price',
                        'label' => 'Old/Regular Price',
                        'name' => 'old_price',
                        'type' => 'text',
                        'placeholder' => '59.95',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
        'position' => 'acf_after_title',
    ));
endif;
    
// -------------------------------------------------------------
// DYNAMIC CART PRICE OVERRIDE
// -------------------------------------------------------------

// 1. Store custom selected option data in Cart Item
add_filter( 'woocommerce_add_cart_item_data', 'add_custom_pack_size_data', 10, 3 );
function add_custom_pack_size_data( $cart_item_data, $product_id, $variation_id ) {
    if ( isset( $_POST['custom_pack_size_index'] ) && $_POST['custom_pack_size_index'] !== '' ) {
        $index = intval( $_POST['custom_pack_size_index'] );
        $repeater = get_field('pack_sizes', $product_id);
        
        if($repeater && isset($repeater[$index])) {
             $price = floatval($repeater[$index]['price']);
             $title = $repeater[$index]['title'];
             $cart_item_data['custom_pack_price'] = $price;
             $cart_item_data['custom_pack_title'] = $title;
        }
    }
    return $cart_item_data;
}
    
// 2. Display custom option title in cart details row
add_filter( 'woocommerce_get_item_data', 'display_custom_pack_size_in_cart', 10, 2 );
function display_custom_pack_size_in_cart( $item_data, $cart_item ) {
    if ( isset( $cart_item['custom_pack_title'] ) ) {
        $item_data[] = array(
            'key'     => 'Pack Size',
            'value'   => wc_clean( $cart_item['custom_pack_title'] ),
            'display' => '',
        );
    }
    return $item_data;
}
    
// 3. Change the actual item price to match the newly selected Radio Card price
add_action( 'woocommerce_before_calculate_totals', 'calculate_custom_pack_price', 10, 1 );
function calculate_custom_pack_price( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        if ( isset( $cart_item['custom_pack_price'] ) ) {
            $cart_item['data']->set_price( $cart_item['custom_pack_price'] );
        }
    }
}