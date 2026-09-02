<?php
/**
 * Template Name: Elementor Full Width
 *
 * Theme header and footer, content edge-to-edge with no sidebar or title.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="elementor-template-full-width">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</div>

<?php
get_footer();
