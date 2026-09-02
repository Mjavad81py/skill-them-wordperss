<?php
/**
 * Asset loading.
 *
 * Keep this minimal. Widget assets are registered by the companion plugin and loaded
 * conditionally through get_style_depends() / get_script_depends().
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue frontend assets.
 *
 * The stylesheet is written with CSS logical properties, so a single file serves
 * both RTL and LTR. That is why no `rtl => replace` variant is registered: a second
 * mirrored file would only be one more thing to keep in sync.
 *
 * @return void
 */
function hooshinex_enqueue_assets() {

	wp_enqueue_style(
		'hooshinex',
		HOOSHINEX_URL . '/style.css',
		array(),
		HOOSHINEX_VERSION
	);

	wp_enqueue_script(
		'hooshinex-navigation',
		HOOSHINEX_URL . '/assets/js/navigation.js',
		array(),
		HOOSHINEX_VERSION,
		true
	);

	wp_enqueue_script(
		'hooshinex-app',
		HOOSHINEX_URL . '/assets/js/app.js',
		array(),
		HOOSHINEX_VERSION,
		true
	);

	wp_localize_script(
		'hooshinex-app',
		'hooshinexData',
		array(
			'ajaxUrl'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'restSearch'   => esc_url_raw( rest_url( 'hooshinex/v1/search' ) ),
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'homeUrl'      => esc_url_raw( home_url( '/' ) ),
			'searchUrl'    => esc_url_raw( home_url( '/' ) ),
			'isRtl'        => is_rtl(),
			'persianDigits' => (bool) apply_filters( 'hooshinex_persian_digits', 'fa_IR' === get_locale() ),
			'trending'     => hooshinex_trending_searches(),
			'i18n'         => array(
				'trending'   => esc_html__( 'جستجوهای پرطرفدار', 'hooshinex' ),
				'results'    => esc_html__( 'نتیجه', 'hooshinex' ),
				'noResults'  => esc_html__( 'نتیجه‌ای پیدا نشد', 'hooshinex' ),
				'tryAgain'   => esc_html__( 'املای عبارت را بررسی کنید یا کلمه دیگری را امتحان کنید', 'hooshinex' ),
				'searching'  => esc_html__( 'در حال جستجو…', 'hooshinex' ),
				'addedToCart' => esc_html__( 'به سبد خرید اضافه شد', 'hooshinex' ),
				'cartError'  => esc_html__( 'افزودن به سبد خرید انجام نشد. دوباره تلاش کنید.', 'hooshinex' ),
				'currency'   => hooshinex_currency_symbol(),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hooshinex_enqueue_assets' );

/**
 * Preload the Estedad webfont files when they are actually present in the theme.
 *
 * Preloading a missing file wastes a request and logs a console warning, so the
 * existence check matters.
 *
 * @return void
 */
function hooshinex_preload_fonts() {

	$fonts = array( 'Estedad-Regular.woff2', 'Estedad-Bold.woff2' );

	foreach ( $fonts as $font ) {
		$path = HOOSHINEX_DIR . '/assets/fonts/' . $font;

		if ( ! file_exists( $path ) ) {
			continue;
		}

		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( HOOSHINEX_URL . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'hooshinex_preload_fonts', 2 );

/**
 * Add a preconnect hint only when Elementor is actually loading Google Fonts.
 *
 * @param array  $urls          URLs to print.
 * @param string $relation_type Relation type.
 * @return array
 */
function hooshinex_resource_hints( $urls, $relation_type ) {

	if ( 'preconnect' === $relation_type && wp_style_is( 'google-fonts-1', 'queue' ) ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'hooshinex_resource_hints', 10, 2 );

/**
 * Editor styles so the block editor matches the frontend typography.
 *
 * @return void
 */
function hooshinex_editor_styles() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'hooshinex_editor_styles' );
