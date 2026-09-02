<?php
/**
 * On-page SEO layer.
 *
 * DESIGN RULE: this theme never competes with an SEO plugin. If Yoast, Rank Math,
 * AIOSEO, SEOPress or Slim SEO is active, that plugin owns titles, meta descriptions,
 * canonicals, Open Graph and the schema graph — we output nothing and stay silent.
 * Only when no SEO plugin is present do we emit our own baseline, so a site is never
 * left with an empty head.
 *
 * Duplicate meta tags and duplicate Organization nodes actively harm a site. Detection
 * and deference is the whole point of this file.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Detect whether a dedicated SEO plugin is handling output.
 *
 * Result is cached per request because it is called from several hooks.
 *
 * @return bool True when an SEO plugin owns head output.
 */
function hooshinex_seo_plugin_active() {

	static $active = null;

	if ( null !== $active ) {
		return $active;
	}

	$active = (
		defined( 'WPSEO_VERSION' )            // Yoast SEO.
		|| defined( 'RANK_MATH_VERSION' )     // Rank Math.
		|| defined( 'AIOSEO_VERSION' )        // All in One SEO.
		|| defined( 'SEOPRESS_VERSION' )      // SEOPress.
		|| defined( 'SLIM_SEO_VERSION' )      // Slim SEO.
		|| class_exists( 'The_SEO_Framework\\Load' )
	);

	/**
	 * Filter SEO plugin detection.
	 *
	 * Return true to silence the theme's own SEO output, false to force it on.
	 *
	 * @param bool $active Whether an SEO plugin was detected.
	 */
	$active = (bool) apply_filters( 'hooshinex_seo_plugin_active', $active );

	return $active;
}

/**
 * Should the theme emit its own SEO meta tags?
 *
 * @return bool
 */
function hooshinex_should_output_seo() {
	return ! hooshinex_seo_plugin_active();
}

/**
 * Output the meta description, canonical, robots and social tags.
 *
 * Runs early in wp_head so the tags sit near the top of the document.
 *
 * @return void
 */
function hooshinex_meta_tags() {

	if ( ! hooshinex_should_output_seo() ) {
		return;
	}

	$description = hooshinex_get_meta_description();
	$canonical   = hooshinex_get_canonical_url();
	$title       = wp_get_document_title();
	$image       = hooshinex_get_social_image();

	echo "\n<!-- Hooshinex SEO -->\n";

	if ( $description ) {
		printf( "<meta name=\"description\" content=\"%s\">\n", esc_attr( $description ) );
	}

	if ( $canonical ) {
		printf( "<link rel=\"canonical\" href=\"%s\">\n", esc_url( $canonical ) );
	}

	// Keep thin/duplicate archives out of the index.
	if ( is_search() || is_404() || is_paged() && is_home() ) {
		echo "<meta name=\"robots\" content=\"noindex, follow\">\n";
	}

	/* ------------------------------------------------------------ Open Graph */

	printf( "<meta property=\"og:locale\" content=\"%s\">\n", esc_attr( get_locale() ) );
	printf( "<meta property=\"og:type\" content=\"%s\">\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
	printf( "<meta property=\"og:site_name\" content=\"%s\">\n", esc_attr( get_bloginfo( 'name' ) ) );

	if ( $description ) {
		printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( $description ) );
	}

	if ( $canonical ) {
		printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( $canonical ) );
	}

	if ( $image ) {
		printf( "<meta property=\"og:image\" content=\"%s\">\n", esc_url( $image['url'] ) );

		if ( ! empty( $image['width'] ) && ! empty( $image['height'] ) ) {
			printf( "<meta property=\"og:image:width\" content=\"%d\">\n", absint( $image['width'] ) );
			printf( "<meta property=\"og:image:height\" content=\"%d\">\n", absint( $image['height'] ) );
		}

		if ( ! empty( $image['alt'] ) ) {
			printf( "<meta property=\"og:image:alt\" content=\"%s\">\n", esc_attr( $image['alt'] ) );
		}
	}

	if ( is_singular( 'post' ) ) {
		printf(
			"<meta property=\"article:published_time\" content=\"%s\">\n",
			esc_attr( get_the_date( DATE_W3C ) )
		);
		printf(
			"<meta property=\"article:modified_time\" content=\"%s\">\n",
			esc_attr( get_the_modified_date( DATE_W3C ) )
		);
	}

	/* --------------------------------------------------------- Twitter cards */

	printf(
		"<meta name=\"twitter:card\" content=\"%s\">\n",
		$image ? 'summary_large_image' : 'summary'
	);
	printf( "<meta name=\"twitter:title\" content=\"%s\">\n", esc_attr( $title ) );

	if ( $description ) {
		printf( "<meta name=\"twitter:description\" content=\"%s\">\n", esc_attr( $description ) );
	}

	if ( $image ) {
		printf( "<meta name=\"twitter:image\" content=\"%s\">\n", esc_url( $image['url'] ) );
	}

	echo "<!-- /Hooshinex SEO -->\n\n";
}
add_action( 'wp_head', 'hooshinex_meta_tags', 2 );

/**
 * Build a meta description for the current view.
 *
 * @return string
 */
function hooshinex_get_meta_description() {

	$description = '';

	if ( is_singular() ) {

		$post = get_queried_object();

		if ( $post instanceof WP_Post ) {
			$description = has_excerpt( $post->ID )
				? get_the_excerpt( $post )
				: wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description', get_queried_object_id() );
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description' );
	}

	$description = wp_strip_all_tags( (string) $description );
	$description = preg_replace( '/\s+/', ' ', $description );
	$description = trim( $description );

	if ( '' === $description ) {
		return '';
	}

	// ~155 characters is the practical SERP limit; trim on a word boundary.
	if ( mb_strlen( $description ) > 155 ) {
		$description = rtrim( mb_substr( $description, 0, 155 ), " \t\n\r\0\x0B.,;:" ) . '…';
	}

	/**
	 * Filter the generated meta description.
	 *
	 * @param string $description Meta description.
	 */
	return apply_filters( 'hooshinex_meta_description', $description );
}

/**
 * Resolve the canonical URL for the current view.
 *
 * @return string
 */
function hooshinex_get_canonical_url() {

	$canonical = '';

	if ( is_singular() ) {
		$canonical = get_permalink( get_queried_object_id() );
	} elseif ( is_front_page() ) {
		$canonical = home_url( '/' );
	} elseif ( is_home() ) {
		$canonical = get_permalink( (int) get_option( 'page_for_posts' ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$canonical = get_term_link( $term );
		}
	} elseif ( is_post_type_archive() ) {
		$canonical = get_post_type_archive_link( get_post_type() );
	} elseif ( is_author() ) {
		$canonical = get_author_posts_url( get_queried_object_id() );
	}

	if ( is_wp_error( $canonical ) || ! $canonical ) {
		return '';
	}

	// Paged archives are their own canonical, not page 1.
	$page = (int) get_query_var( 'paged' );
	if ( $page > 1 ) {
		$canonical = trailingslashit( $canonical ) . 'page/' . $page . '/';
	}

	/**
	 * Filter the canonical URL.
	 *
	 * @param string $canonical Canonical URL.
	 */
	return apply_filters( 'hooshinex_canonical_url', $canonical );
}

/**
 * Resolve the social sharing image for the current view.
 *
 * @return array|false Array with url/width/height/alt, or false.
 */
function hooshinex_get_social_image() {

	$attachment_id = 0;

	if ( is_singular() && has_post_thumbnail() ) {
		$attachment_id = get_post_thumbnail_id();
	} elseif ( has_custom_logo() ) {
		$attachment_id = (int) get_theme_mod( 'custom_logo' );
	}

	/**
	 * Filter the social image attachment ID.
	 *
	 * @param int $attachment_id Attachment ID.
	 */
	$attachment_id = (int) apply_filters( 'hooshinex_social_image_id', $attachment_id );

	if ( ! $attachment_id ) {
		return false;
	}

	$src = wp_get_attachment_image_src( $attachment_id, 'large' );

	if ( ! $src ) {
		return false;
	}

	return array(
		'url'    => $src[0],
		'width'  => $src[1],
		'height' => $src[2],
		'alt'    => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
	);
}

/**
 * Improve the document title separator and tagline handling.
 *
 * @param array $parts Title parts.
 * @return array
 */
function hooshinex_document_title_parts( $parts ) {

	if ( ! hooshinex_should_output_seo() ) {
		return $parts;
	}

	// The tagline only adds value on the front page.
	if ( ! is_front_page() && isset( $parts['tagline'] ) ) {
		unset( $parts['tagline'] );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'hooshinex_document_title_parts' );

/**
 * Emit a single H1 guard notice for administrators in debug mode.
 *
 * Multiple H1 elements are the most common on-page SEO regression when a client
 * builds pages in Elementor. This surfaces the problem during development only.
 *
 * @return void
 */
function hooshinex_heading_debug_notice() {

	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! current_user_can( 'edit_posts' ) ) {
		return;
	}

	if ( is_admin() || ( function_exists( 'elementor_theme_do_location' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) ) {
		return;
	}

	?>
	<script>
	( function () {
		var h1s = document.querySelectorAll( 'h1' );
		if ( 1 !== h1s.length ) {
			console.warn(
				'[Hooshinex SEO] This page has ' + h1s.length +
				' H1 elements. Exactly one is expected.', h1s
			);
		}
		var imgs = document.querySelectorAll( 'img:not([alt])' );
		if ( imgs.length ) {
			console.warn( '[Hooshinex SEO] ' + imgs.length + ' image(s) missing alt attributes.', imgs );
		}
	} )();
	</script>
	<?php
}
add_action( 'wp_footer', 'hooshinex_heading_debug_notice', 999 );
