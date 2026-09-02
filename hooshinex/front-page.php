<?php
/**
 * Front page template.
 *
 * Order of precedence, highest first:
 *   1. An Elementor Theme Builder template assigned to the `single` location.
 *   2. The page's own content (Elementor canvas / block editor).
 *   3. The theme's fallback storefront sections, so a fresh install already looks
 *      like the design instead of a blank page.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

hooshinex_do_location(
	'single',
	function () {

		$has_content = false;

		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				if ( trim( get_the_content() ) ) {
					$has_content = true;
					echo '<div class="entry-content hx-container">';
					the_content();
					echo '</div>';
				}
			}
			rewind_posts();
		}

		if ( $has_content ) {
			return;
		}

		get_template_part( 'template-parts/home/hero' );
		get_template_part( 'template-parts/home/categories' );
		get_template_part( 'template-parts/home/featured-products' );
		get_template_part( 'template-parts/home/twin-banners' );
		get_template_part( 'template-parts/home/latest-products' );
		get_template_part( 'template-parts/home/offer' );
		get_template_part( 'template-parts/home/amazing' );
		get_template_part( 'template-parts/home/blog' );
		get_template_part( 'template-parts/home/reviews' );
		get_template_part( 'template-parts/home/seller' );
	}
);

get_footer();
