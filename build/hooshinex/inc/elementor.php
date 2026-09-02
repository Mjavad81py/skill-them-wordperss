<?php
/**
 * Elementor integration.
 *
 * Registers Theme Builder locations and small compatibility tweaks. No widgets here —
 * widgets belong in the companion plugin.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Elementor Theme Builder locations.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager Locations manager.
 * @return void
 */
function hooshinex_register_elementor_locations( $elementor_theme_manager ) {

	// header, footer, single, archive.
	$elementor_theme_manager->register_all_core_location();

	// A custom location so designers can build the sidebar in Elementor too.
	$elementor_theme_manager->register_location(
		'sidebar',
		array(
			'label'           => esc_html__( 'Sidebar', 'hooshinex' ),
			'multiple'        => true,
			'edit_in_content' => false,
		)
	);
}
add_action( 'elementor/theme/register_locations', 'hooshinex_register_elementor_locations' );

/**
 * Render an Elementor location, or a theme fallback when none is assigned.
 *
 * @param string   $location Location slug.
 * @param callable $fallback Optional callable printing fallback markup.
 * @return bool True when an Elementor template rendered.
 */
function hooshinex_do_location( $location, $fallback = null ) {

	$rendered = function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location );

	if ( ! $rendered && is_callable( $fallback ) ) {
		call_user_func( $fallback );
	}

	return $rendered;
}

/**
 * Tell Elementor this theme handles its own page-title output.
 *
 * @return void
 */
function hooshinex_elementor_page_title_support() {
	add_theme_support( 'elementor-page-title-selector', '.page-header .entry-title' );
}
add_action( 'after_setup_theme', 'hooshinex_elementor_page_title_support' );

/**
 * Warn the admin when Elementor is missing.
 *
 * @return void
 */
function hooshinex_elementor_missing_notice() {

	if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__(
			'This theme is designed for the Elementor page builder. Install and activate Elementor to use its layout features.',
			'hooshinex'
		)
	);
}
add_action( 'admin_notices', 'hooshinex_elementor_missing_notice' );
