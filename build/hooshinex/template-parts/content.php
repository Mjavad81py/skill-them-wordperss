<?php
/**
 * Fallback loop item, styled as a blog card so archives match the storefront.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_cats = get_the_category();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hx-blog-card' ); ?>>

	<a class="hx-blog-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'hooshinex-card',
				array(
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => '',
				)
			);
		} else {
			echo '<span class="hx-thumb-placeholder">';
			hooshinex_icon( 'book', array( 'stroke' => '1.4' ) );
			echo '</span>';
		}
		?>
	</a>

	<div class="hx-blog-body">

		<?php if ( $hooshinex_cats ) : ?>
			<a class="hx-blog-cat" href="<?php echo esc_url( get_category_link( $hooshinex_cats[0] ) ); ?>">
				<?php echo esc_html( $hooshinex_cats[0]->name ); ?>
			</a>
		<?php endif; ?>

		<h2 class="hx-blog-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>

		<p class="hx-blog-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>

		<div class="hx-blog-meta">
			<span>
				<?php hooshinex_icon( 'clock', array( 'stroke' => '2' ) ); ?>
				<?php echo esc_html( hooshinex_fa_digits( get_the_date() ) ); ?>
			</span>
			<span>
				<?php hooshinex_icon( 'chat', array( 'stroke' => '2' ) ); ?>
				<?php
				printf(
					/* translators: %s: comment count */
					esc_html__( '%s دیدگاه', 'hooshinex' ),
					esc_html( hooshinex_fa_digits( get_comments_number() ) )
				);
				?>
			</span>
		</div>

	</div>

</article>
