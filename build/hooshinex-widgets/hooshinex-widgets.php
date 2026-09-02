<?php
/**
 * Plugin Name: Hooshinex Widgets
 * Description: Custom Elementor widgets and dynamic tags for the Hooshinex theme. Kept in a plugin so switching themes never destroys page content.
 * Version:     1.0.0
 * Author:      Hooshinex
 * Text Domain: hooshinex-widgets
 * Domain Path: /languages
 * License:     GPL-2.0-or-later
 * Requires PHP: 7.4
 *
 * Elementor tested up to: 3.28
 * Elementor Pro tested up to: 3.28
 *
 * @package HooshinexWidgets
 */

defined( 'ABSPATH' ) || exit;

define( 'HOOSHINEX_WIDGETS_VERSION', '1.0.0' );
define( 'HOOSHINEX_WIDGETS_FILE', __FILE__ );
define( 'HOOSHINEX_WIDGETS_DIR', plugin_dir_path( __FILE__ ) );
define( 'HOOSHINEX_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Declare WooCommerce HPOS (High-Performance Order Storage) compatibility.
 *
 * Must run on before_woocommerce_init from the main plugin file. Without it,
 * WooCommerce lists this plugin as incompatible and blocks the store owner from
 * enabling HPOS.
 *
 * @return void
 */
function hooshinex_widgets_declare_hpos() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			HOOSHINEX_WIDGETS_FILE,
			true
		);
	}
}
add_action( 'before_woocommerce_init', 'hooshinex_widgets_declare_hpos' );

require_once HOOSHINEX_WIDGETS_DIR . 'includes/class-plugin.php';

/**
 * Boot the plugin once all plugins are loaded, so Elementor detection is reliable.
 *
 * @return void
 */
function hooshinex_widgets_bootstrap() {
	\Hooshinex\Widgets\Plugin::instance();
}
add_action( 'plugins_loaded', 'hooshinex_widgets_bootstrap' );
