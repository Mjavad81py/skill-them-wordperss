<?php
/**
 * Search results template.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

hooshinex_do_location(
	'archive',
	function () {

		printf(
			'<header class="page-header hx-container"><h1 class="entry-title">%s</h1><p class="archive-description">%s</p></header>',
			sprintf(
				/* translators: %s: search query */
				esc_html__( 'نتایج جستجو برای: %s', 'hooshinex' ),
				'<span class="accent">' . esc_html( get_search_query() ) . '</span>'
			),
			esc_html(
				sprintf(
					/* translators: %s: results count */
					_n( '%s نتیجه پیدا شد', '%s نتیجه پیدا شد', (int) $GLOBALS['wp_query']->found_posts, 'hooshinex' ),
					hooshinex_fa_digits( (int) $GLOBALS['wp_query']->found_posts )
				)
			)
		);

		get_template_part(
			'template-parts/archive-loop',
			null,
			array(
				'header' => false,
				'part'   => 'search',
			)
		);
	}
);

get_footer();
