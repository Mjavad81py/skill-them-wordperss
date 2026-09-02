<?php
/**
 * Uninstall routine. Leave the database exactly as we found it.
 *
 * @package HooshinexWidgets
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Remove plugin options.
delete_option( 'hooshinex_widgets_settings' );
delete_site_option( 'hooshinex_widgets_settings' );

// Remove cached remote data.
delete_transient( 'hooshinex_widgets_cache' );

/*
 * Deliberately NOT removed: Elementor page content containing this plugin's widgets.
 * Users who reactivate expect their layouts to still be there.
 */
