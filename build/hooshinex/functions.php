<?php
/**
 * Hooshinex functions and definitions.
 *
 * This theme is a thin shell: it registers Elementor locations and hands rendering
 * to Theme Builder templates, falling back to plain WordPress markup when Elementor
 * Pro is unavailable. It deliberately contains no Elementor widgets — those live in
 * the companion plugin (Hooshinex Widgets) so that switching themes never destroys
 * client content.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

define( 'HOOSHINEX_VERSION', '1.0.0' );
define( 'HOOSHINEX_DIR', get_template_directory() );
define( 'HOOSHINEX_URL', get_template_directory_uri() );

require_once HOOSHINEX_DIR . '/inc/setup.php';
require_once HOOSHINEX_DIR . '/inc/template-tags.php';
require_once HOOSHINEX_DIR . '/inc/enqueue.php';
require_once HOOSHINEX_DIR . '/inc/elementor.php';
require_once HOOSHINEX_DIR . '/inc/customizer.php';
require_once HOOSHINEX_DIR . '/inc/search.php';
require_once HOOSHINEX_DIR . '/inc/pages.php';

// Technical + on-page SEO. Defers entirely to an active SEO plugin.
require_once HOOSHINEX_DIR . '/inc/seo.php';
require_once HOOSHINEX_DIR . '/inc/schema.php';
require_once HOOSHINEX_DIR . '/inc/performance.php';

// WooCommerce. Self-gating: harmless when the store plugin is absent.
require_once HOOSHINEX_DIR . '/inc/woocommerce.php';
