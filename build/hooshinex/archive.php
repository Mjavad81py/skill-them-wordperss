<?php
/**
 * Archive template. Delegates to the Elementor `archive` location when one is assigned.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

hooshinex_do_location(
	'archive',
	function () {
		get_template_part( 'template-parts/archive-loop' );
	}
);

get_footer();
