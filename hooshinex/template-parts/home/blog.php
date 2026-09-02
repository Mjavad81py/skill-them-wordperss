<?php
/**
 * Home: latest blog posts.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $hooshinex_posts->have_posts() ) {
	wp_reset_postdata();
	return;
}

$hooshinex_blog_url = get_permalink( (int) get_option( 'page_for_posts' ) );
?>
<section class="hx-section" id="blog">
	<div class="hx-container">

		<?php
		hooshinex_section_header(
			array(
				'accent'     => __( 'آخرین', 'hooshinex' ),
				'title'      => __( 'مقالات', 'hooshinex' ),
				'link'       => $hooshinex_blog_url ? $hooshinex_blog_url : home_url( '/' ),
				'link_label' => __( 'همه مقالات', 'hooshinex' ),
			)
		);
		?>

		<div class="hx-blog-grid">
			<?php
			while ( $hooshinex_posts->have_posts() ) :
				$hooshinex_posts->the_post();
				?>
				<article <?php post_class( 'hx-blog-card' ); ?>>

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
						<?php
						$hooshinex_cats = get_the_category();

						if ( $hooshinex_cats ) :
							?>
							<a class="hx-blog-cat" href="<?php echo esc_url( get_category_link( $hooshinex_cats[0] ) ); ?>">
								<?php echo esc_html( $hooshinex_cats[0]->name ); ?>
							</a>
						<?php endif; ?>

						<h3 class="hx-blog-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<p class="hx-blog-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>

						<div class="hx-blog-meta">
							<span>
								<?php hooshinex_icon( 'clock', array( 'stroke' => '2' ) ); ?>
								<?php echo esc_html( hooshinex_fa_digits( get_the_date() ) ); ?>
							</span>
							<span>
								<?php hooshinex_icon( 'eye', array( 'stroke' => '2' ) ); ?>
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
				<?php
			endwhile;
			?>
		</div>

	</div>
</section>
<?php
wp_reset_postdata();
