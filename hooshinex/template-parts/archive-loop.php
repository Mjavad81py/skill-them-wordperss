<?php
/**
 * The archive loop: header, card grid, pagination and the optional sidebar.
 *
 * Shared by index.php, archive.php and search.php so the three stay identical by
 * construction rather than by copy-paste.
 *
 * Expects $args: header (bool), part (string, the content template slug).
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : array(),
	array(
		'header' => true,
		'part'   => '',
	)
);

$hooshinex_has_sidebar = is_active_sidebar( 'hooshinex-sidebar' );
?>

<?php if ( $args['header'] ) : ?>
	<?php hooshinex_page_header(); ?>
<?php endif; ?>

<div class="hx-container">
	<div class="hx-archive<?php echo $hooshinex_has_sidebar ? ' has-sidebar' : ''; ?>">

		<div class="hx-archive-main">

			<?php if ( have_posts() ) : ?>

				<div class="hx-blog-grid">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', $args['part'] ? $args['part'] : get_post_type() );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => esc_html__( 'قبلی', 'hooshinex' ),
						'next_text'          => esc_html__( 'بعدی', 'hooshinex' ),
						'screen_reader_text' => esc_html__( 'صفحه‌بندی نوشته‌ها', 'hooshinex' ),
					)
				);
				?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>

		</div>

		<?php if ( $hooshinex_has_sidebar ) : ?>
			<aside class="hx-sidebar widget-area" aria-label="<?php esc_attr_e( 'نوار کناری', 'hooshinex' ); ?>">
				<?php dynamic_sidebar( 'hooshinex-sidebar' ); ?>
			</aside>
		<?php endif; ?>

	</div>
</div>
