<?php
/**
 * Page template. Delegates to the Elementor `single` location when one is assigned.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

hooshinex_do_location(
	'single',
	function () {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		}
	}
);

get_footer();
