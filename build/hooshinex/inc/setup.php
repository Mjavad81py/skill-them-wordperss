<?php
/**
 * Theme setup.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports, menus, and the text domain.
 *
 * @return void
 */
function hooshinex_setup() {

	load_theme_textdomain( 'hooshinex', HOOSHINEX_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 212,
			'width'       => 640,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' )
	);

	// Semantic, SEO-relevant image sizes. The 2100x1040 ratio matches the product art.
	add_image_size( 'hooshinex-card', 700, 347, true );
	add_image_size( 'hooshinex-wide', 1400, 693, true );
	add_image_size( 'hooshinex-hero', 1600, 900, true );
	add_image_size( 'hooshinex-thumb', 160, 160, true );

	// Declare Elementor compatibility.
	add_theme_support( 'elementor' );
	add_theme_support( 'elementor-pro' );
	add_theme_support( 'header-footer-elementor' );

	register_nav_menus(
		array(
			'primary'         => esc_html__( 'منوی اصلی (هدر)', 'hooshinex' ),
			'footer-products' => esc_html__( 'فوتر — محصولات', 'hooshinex' ),
			'footer-quick'    => esc_html__( 'فوتر — دسترسی سریع', 'hooshinex' ),
			'footer-legal'    => esc_html__( 'فوتر — قوانین', 'hooshinex' ),
		)
	);
}
add_action( 'after_setup_theme', 'hooshinex_setup' );

/**
 * Register the image sizes in the media picker so editors can choose them.
 *
 * @param array $sizes Existing sizes.
 * @return array
 */
function hooshinex_custom_image_sizes( $sizes ) {

	return array_merge(
		$sizes,
		array(
			'hooshinex-card' => esc_html__( 'کارت محصول هوشینکس', 'hooshinex' ),
			'hooshinex-wide' => esc_html__( 'بنر عریض هوشینکس', 'hooshinex' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'hooshinex_custom_image_sizes' );

/**
 * Set the default content width used by Elementor and embeds.
 *
 * @return void
 */
function hooshinex_content_width() {
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = apply_filters( 'hooshinex_content_width', 1400 );
	}
}
add_action( 'after_setup_theme', 'hooshinex_content_width', 0 );

/**
 * Register widget areas for installs that still rely on classic sidebars.
 *
 * @return void
 */
function hooshinex_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'ساید‌بار', 'hooshinex' ),
			'id'            => 'hooshinex-sidebar',
			'description'   => esc_html__( 'در نوشته‌ها و برگه‌هایی که از قالب پیش‌فرض استفاده می‌کنند نمایش داده می‌شود.', 'hooshinex' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'ساید‌بار فروشگاه', 'hooshinex' ),
			'id'            => 'hooshinex-shop-sidebar',
			'description'   => esc_html__( 'فیلترها و ویجت‌های صفحه فروشگاه و آرشیو محصولات.', 'hooshinex' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hooshinex_widgets_init' );

/**
 * Expose the Elementor-oriented page templates.
 *
 * @param array $templates Existing page templates.
 * @return array
 */
function hooshinex_page_templates( $templates ) {

	$templates['templates/full-width.php'] = esc_html__( 'المنتور تمام عرض', 'hooshinex' );
	$templates['templates/canvas.php']     = esc_html__( 'المنتور بوم خالی', 'hooshinex' );
	$templates['templates/contact.php']    = esc_html__( 'صفحه تماس با ما', 'hooshinex' );
	$templates['templates/faq.php']        = esc_html__( 'صفحه سوالات متداول', 'hooshinex' );

	return $templates;
}
add_filter( 'theme_page_templates', 'hooshinex_page_templates' );

/**
 * Give the body a direction-aware helper class.
 *
 * @param array $classes Body classes.
 * @return array
 */
function hooshinex_body_classes( $classes ) {

	$classes[] = 'hooshinex';

	if ( is_rtl() ) {
		$classes[] = 'hooshinex-rtl';
	}

	return $classes;
}
add_filter( 'body_class', 'hooshinex_body_classes' );

/**
 * Excerpt length tuned for the card layout.
 *
 * @return int
 */
function hooshinex_excerpt_length() {
	return (int) apply_filters( 'hooshinex_excerpt_words', 24 );
}
add_filter( 'excerpt_length', 'hooshinex_excerpt_length', 999 );

/**
 * Excerpt ellipsis.
 *
 * @return string
 */
function hooshinex_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'hooshinex_excerpt_more' );
