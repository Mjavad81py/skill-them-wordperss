<?php
/**
 * WooCommerce shell template.
 *
 * WordPress routes every WooCommerce view (shop, product, cart, checkout, account)
 * through this file when the theme provides it. Elementor Pro's WooCommerce Builder
 * templates take precedence via their registered locations; this is the fallback.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

$location = ( function_exists( 'is_product' ) && is_product() ) ? 'single' : 'archive';

hooshinex_do_location(
	$location,
	function () {
		// woocommerce_content() renders the correct shop view for the current query.
		woocommerce_content();
	}
);

get_footer();
