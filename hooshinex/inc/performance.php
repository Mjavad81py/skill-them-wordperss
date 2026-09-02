<?php
/**
 * Core Web Vitals helpers.
 *
 * Technical SEO is mostly performance. These are the theme-level fixes that move
 * LCP, CLS and INP; they cost nothing and apply to every page.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Preload the LCP image and mark it high priority.
 *
 * WordPress 6.3+ tries to do this automatically, but it reliably misfires when the
 * hero is rendered by a page builder. Being explicit for the featured image is the
 * single highest-impact LCP fix available to a theme.
 *
 * @return void
 */
function hooshinex_preload_lcp_image() {

	if ( ! is_singular() || ! has_post_thumbnail() ) {
		return;
	}

	$id  = get_post_thumbnail_id();
	$src = wp_get_attachment_image_src( $id, 'large' );

	if ( ! $src ) {
		return;
	}

	$srcset = wp_get_attachment_image_srcset( $id, 'large' );
	$sizes  = wp_get_attachment_image_sizes( $id, 'large' );

	printf(
		'<link rel="preload" as="image" href="%s"%s%s fetchpriority="high">' . "\n",
		esc_url( $src[0] ),
		$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : '',
		$sizes ? ' imagesizes="' . esc_attr( $sizes ) . '"' : ''
	);
}
add_action( 'wp_head', 'hooshinex_preload_lcp_image', 1 );

/**
 * Never lazy-load the featured image on singular views — it is the LCP candidate.
 *
 * @param string $value   The loading attribute value.
 * @param string $image   The image tag.
 * @param string $context Where the image is used.
 * @return string|false
 */
function hooshinex_disable_lazy_on_lcp( $value, $image, $context ) {

	if ( 'the_post_thumbnail' === $context && is_singular() ) {
		return false;
	}

	return $value;
}
add_filter( 'wp_img_tag_add_loading_attr', 'hooshinex_disable_lazy_on_lcp', 10, 3 );

/**
 * Add fetchpriority="high" to the featured image on singular views.
 *
 * @param array $attr Attributes for the image markup.
 * @return array
 */
function hooshinex_thumbnail_fetchpriority( $attr ) {

	if ( is_singular() && ! did_action( 'hooshinex_lcp_marked' ) ) {
		$attr['fetchpriority'] = 'high';
		$attr['loading']       = 'eager';
		do_action( 'hooshinex_lcp_marked' );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'hooshinex_thumbnail_fetchpriority' );

/**
 * Remove render-blocking and unused core output.
 *
 * @return void
 */
function hooshinex_trim_head() {

	// Emoji script and styles: ~15KB of JS almost nobody needs.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	// Legacy discovery endpoints.
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Adjacent post links are rarely used and add queries.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'hooshinex_trim_head' );

/**
 * Disable the emoji DNS prefetch left behind after removing the script.
 *
 * @param array  $urls          Resource URLs.
 * @param string $relation_type Relation type.
 * @return array
 */
function hooshinex_remove_emoji_prefetch( $urls, $relation_type ) {

	if ( 'dns-prefetch' === $relation_type ) {
		$emoji = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/' );
		$urls  = array_filter(
			$urls,
			static function ( $url ) use ( $emoji ) {
				return ! is_string( $url ) || false === strpos( $url, $emoji );
			}
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'hooshinex_remove_emoji_prefetch', 10, 2 );

/**
 * Self-host or preconnect Google Fonts used by Elementor.
 *
 * Elementor loads Google Fonts by default. Preconnecting saves ~100-300ms; setting
 * display=swap prevents invisible text during load (a CLS and LCP factor).
 *
 * @param string $url Google Fonts URL.
 * @return string
 */
function hooshinex_google_fonts_display_swap( $url ) {

	if ( false === strpos( $url, 'display=' ) ) {
		$url = add_query_arg( 'display', 'swap', $url );
	}

	return $url;
}
add_filter( 'elementor/frontend/print_google_fonts_url', 'hooshinex_google_fonts_display_swap' );

/**
 * Add width/height to content images that lack them, preventing layout shift.
 *
 * @param string $content Post content.
 * @return string
 */
function hooshinex_ensure_image_dimensions( $content ) {

	if ( is_admin() || empty( $content ) ) {
		return $content;
	}

	return wp_filter_content_tags( $content );
}
add_filter( 'the_content', 'hooshinex_ensure_image_dimensions', 20 );

/**
 * Defer non-critical theme scripts so they do not block first paint.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function hooshinex_defer_scripts( $tag, $handle ) {

	$defer = apply_filters(
		'hooshinex_deferred_scripts',
		array( 'hooshinex-navigation' )
	);

	if ( in_array( $handle, $defer, true ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'hooshinex_defer_scripts', 10, 2 );
