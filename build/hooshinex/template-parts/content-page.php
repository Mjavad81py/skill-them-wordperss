<?php
/**
 * Fallback page content.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-page' ); ?>>

	<?php
	if ( ! is_front_page() ) {
		hooshinex_page_header();
	}

	hooshinex_post_thumbnail( 'large' );
	?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'hooshinex' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

</article>
