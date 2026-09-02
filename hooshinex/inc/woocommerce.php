<?php
/**
 * WooCommerce integration.
 *
 * Entirely self-gating: every function checks that WooCommerce is active before it
 * runs, so this file is harmless on a non-shop site and the theme never fatals if
 * the store plugin is deactivated.
 *
 * Elementor Pro's WooCommerce Builder takes precedence where a template exists —
 * these hooks provide the wrapper markup and behavior it expects underneath.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is WooCommerce active?
 *
 * @return bool
 */
function hooshinex_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Declare WooCommerce theme support and gallery features.
 *
 * Without add_theme_support( 'woocommerce' ) WooCommerce assumes the theme is not
 * shop-aware and falls back to shortcode rendering, which ignores template overrides.
 *
 * @return void
 */
function hooshinex_woocommerce_setup() {

	if ( ! hooshinex_is_woocommerce_active() ) {
		return;
	}

	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);

	// Gallery features are opt-in; without these the product gallery is static.
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'hooshinex_woocommerce_setup' );

/**
 * Declare High-Performance Order Storage (HPOS) compatibility.
 *
 * Without this, WooCommerce flags the theme as incompatible and blocks the store
 * owner from enabling HPOS.
 *
 * @return void
 */
function hooshinex_declare_hpos_compatibility() {

	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			HOOSHINEX_DIR . '/functions.php',
			true
		);
	}
}
add_action( 'before_woocommerce_init', 'hooshinex_declare_hpos_compatibility' );

/**
 * Replace WooCommerce's default content wrappers with the theme's own.
 *
 * @return void
 */
function hooshinex_woocommerce_wrappers() {

	if ( ! hooshinex_is_woocommerce_active() ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	add_action( 'woocommerce_before_main_content', 'hooshinex_woocommerce_wrapper_start', 10 );
	add_action( 'woocommerce_after_main_content', 'hooshinex_woocommerce_wrapper_end', 10 );

	// WooCommerce breadcrumbs duplicate the theme's; keep one source of truth.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}
add_action( 'init', 'hooshinex_woocommerce_wrappers' );

/**
 * Open the shop content wrapper.
 *
 * @return void
 */
function hooshinex_woocommerce_wrapper_start() {
	echo '<div class="site-container woocommerce-wrapper">';
	hooshinex_breadcrumbs();
}

/**
 * Close the shop content wrapper.
 *
 * @return void
 */
function hooshinex_woocommerce_wrapper_end() {
	echo '</div>';
}

/**
 * Register Elementor Theme Builder locations for WooCommerce views.
 *
 * Elementor Pro's WooCommerce Builder registers product/archive locations itself when
 * the store is detected. This adds the theme's own extra slots on top.
 *
 * @param object $elementor_theme_manager Locations manager.
 * @return void
 */
function hooshinex_register_woocommerce_locations( $elementor_theme_manager ) {

	if ( ! hooshinex_is_woocommerce_active() ) {
		return;
	}

	$elementor_theme_manager->register_location(
		'shop-sidebar',
		array(
			'label'           => esc_html__( 'Shop Sidebar', 'hooshinex' ),
			'multiple'        => true,
			'edit_in_content' => false,
		)
	);
}
add_action( 'elementor/theme/register_locations', 'hooshinex_register_woocommerce_locations', 20 );

/**
 * Load shop assets only on shop views.
 *
 * WooCommerce enqueues its stylesheets site-wide by default, which costs every
 * non-shop page unnecessary CSS. This is a measurable Core Web Vitals win.
 *
 * @return void
 */
function hooshinex_dequeue_woocommerce_assets() {

	if ( ! hooshinex_is_woocommerce_active() ) {
		return;
	}

	// The front page renders product carousels, so it needs the shop scripts too.
	$is_shop_view = is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_front_page();

	/**
	 * Filter whether the current request counts as a shop view.
	 *
	 * Set true on pages that embed Elementor product widgets outside shop templates.
	 *
	 * @param bool $is_shop_view Whether shop assets are needed.
	 */
	$is_shop_view = (bool) apply_filters( 'hooshinex_needs_woocommerce_assets', $is_shop_view );

	if ( $is_shop_view ) {
		return;
	}

	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'wc-blocks-style' );

	wp_dequeue_script( 'wc-cart-fragments' );
	wp_dequeue_script( 'woocommerce' );
	wp_dequeue_script( 'wc-add-to-cart' );
}
add_action( 'wp_enqueue_scripts', 'hooshinex_dequeue_woocommerce_assets', 99 );

/**
 * Set the number of products shown per row.
 *
 * @return int
 */
function hooshinex_loop_columns() {
	return (int) apply_filters( 'hooshinex_products_per_row', get_theme_mod( 'hooshinex_products_per_row', 3 ) );
}
add_filter( 'loop_shop_columns', 'hooshinex_loop_columns', 20 );

/**
 * Set the number of products shown per page.
 *
 * @return int
 */
function hooshinex_products_per_page() {
	return (int) apply_filters( 'hooshinex_products_per_page', get_theme_mod( 'hooshinex_products_per_page', 12 ) );
}
add_filter( 'loop_shop_per_page', 'hooshinex_products_per_page', 20 );

/**
 * Improve product image accessibility and SEO.
 *
 * WooCommerce loop thumbnails frequently ship with empty alt attributes; falling back
 * to the product title is better for image search and screen readers.
 *
 * @param array $attr Image attributes.
 * @param WP_Post $attachment Attachment post.
 * @return array
 */
function hooshinex_product_image_alt( $attr, $attachment ) {

	if ( ! hooshinex_is_woocommerce_active() || ! empty( $attr['alt'] ) ) {
		return $attr;
	}

	$parent_id = (int) $attachment->post_parent;

	if ( $parent_id && 'product' === get_post_type( $parent_id ) ) {
		$attr['alt'] = get_the_title( $parent_id );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'hooshinex_product_image_alt', 10, 2 );

/**
 * Keep cart, checkout and account pages out of search results.
 *
 * These are thin, session-specific pages that dilute crawl budget.
 *
 * @return void
 */
function hooshinex_noindex_shop_utility_pages() {

	if ( ! hooshinex_is_woocommerce_active() ) {
		return;
	}

	// Respect an SEO plugin already handling robots directives.
	if ( hooshinex_seo_plugin_active() ) {
		return;
	}

	if ( is_cart() || is_checkout() || is_account_page() ) {
		echo "<meta name=\"robots\" content=\"noindex, nofollow\">\n";
	}
}
add_action( 'wp_head', 'hooshinex_noindex_shop_utility_pages', 1 );

/**
 * Warn the admin if the theme was set up for a shop but WooCommerce is missing.
 *
 * @return void
 */
function hooshinex_woocommerce_missing_notice() {

	if ( hooshinex_is_woocommerce_active() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ! get_theme_mod( 'hooshinex_shop_mode', false ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__(
			'This theme was configured for an online store. Install and activate WooCommerce to enable shop features.',
			'hooshinex'
		)
	);
}
add_action( 'admin_notices', 'hooshinex_woocommerce_missing_notice' );

/**
 * Keep the header cart counter in sync after an AJAX add-to-cart.
 *
 * WooCommerce swaps every node whose selector appears in this array, so the counter
 * updates without a page reload and without any custom JavaScript.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function hooshinex_cart_count_fragment( $fragments ) {

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	printf(
		'<span class="hx-cart-count%1$s" data-hx-cart-count>%2$s</span>',
		WC()->cart->get_cart_contents_count() ? '' : ' is-empty',
		esc_html( hooshinex_fa_digits( WC()->cart->get_cart_contents_count() ) )
	);

	$fragments['span.hx-cart-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'hooshinex_cart_count_fragment' );
