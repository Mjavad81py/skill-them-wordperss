<?php
/**
 * Single post template. Delegates to the Elementor `single` location when one is assigned.
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

			get_template_part( 'template-parts/content', 'single' );

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		}

		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'hooshinex' ) . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'hooshinex' ) . '</span> <span class="nav-title">%title</span>',
			)
		);
	}
);

get_footer();
