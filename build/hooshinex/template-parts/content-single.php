<?php
/**
 * Fallback single-post content.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry-single' ); ?>>

	<?php
	hooshinex_breadcrumbs();
	hooshinex_page_header();
	hooshinex_entry_meta();
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

	<?php if ( has_tag() ) : ?>
		<footer class="entry-footer">
			<?php the_tags( '<span class="tag-links">', '', '</span>' ); ?>
		</footer>
	<?php endif; ?>

</article>
