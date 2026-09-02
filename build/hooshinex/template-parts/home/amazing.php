<?php
/**
 * Home: the "amazing product" spotlight, with a thumbnail rail underneath.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
	return;
}

$hooshinex_args = array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => 5,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'meta_key'            => 'total_sales', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	'orderby'             => 'meta_value_num',
	'order'               => 'DESC',
);

if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
	$hooshinex_visibility = wc_get_product_visibility_term_ids();

	$hooshinex_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => $hooshinex_visibility['exclude-from-catalog'],
			'operator' => 'NOT IN',
		),
	);
}

$hooshinex_spotlight = new WP_Query( $hooshinex_args );

if ( ! $hooshinex_spotlight->have_posts() ) {
	wp_reset_postdata();
	return;
}

$hooshinex_slides = array();

while ( $hooshinex_spotlight->have_posts() ) {
	$hooshinex_spotlight->the_post();

	$hooshinex_product = wc_get_product( get_the_ID() );

	if ( ! $hooshinex_product ) {
		continue;
	}

	$hooshinex_slides[] = $hooshinex_product;
}

wp_reset_postdata();

if ( ! $hooshinex_slides ) {
	return;
}
?>
<section class="hx-section hx-section--muted" id="amazing">
	<div class="hx-container">

		<?php
		hooshinex_section_header(
			array(
				'accent'     => __( 'محصول', 'hooshinex' ),
				'title'      => __( 'شگفت‌انگیز', 'hooshinex' ),
				'link'       => hooshinex_shop_url(),
				'link_label' => __( 'فروشگاه', 'hooshinex' ),
			)
		);
		?>

		<div class="hx-spotlight" data-hx-spotlight>

			<div class="hx-spotlight-stage">
				<?php foreach ( $hooshinex_slides as $hooshinex_index => $hooshinex_item ) : ?>
					<?php
					$hooshinex_id     = $hooshinex_item->get_id();
					$hooshinex_rating = (float) $hooshinex_item->get_average_rating();
					?>
					<article class="hx-spotlight-slide<?php echo 0 === $hooshinex_index ? ' is-active' : ''; ?>"
						data-hx-slide="<?php echo esc_attr( $hooshinex_index ); ?>"
						<?php echo 0 === $hooshinex_index ? '' : 'aria-hidden="true"'; ?>>

						<div class="hx-spotlight-media">
							<a href="<?php echo esc_url( get_permalink( $hooshinex_id ) ); ?>">
								<?php
								if ( has_post_thumbnail( $hooshinex_id ) ) {
									echo get_the_post_thumbnail(
										$hooshinex_id,
										'hooshinex-wide',
										array(
											'loading'  => 'lazy',
											'decoding' => 'async',
											'alt'      => esc_attr( $hooshinex_item->get_name() ),
										)
									);
								} else {
									echo '<span class="hx-thumb-placeholder">';
									hooshinex_icon( 'shop', array( 'stroke' => '1.4' ) );
									echo '</span>';
								}
								?>
							</a>

							<?php if ( $hooshinex_item->is_on_sale() ) : ?>
								<span class="hx-badge hx-badge--sale"><?php esc_html_e( 'تخفیف', 'hooshinex' ); ?></span>
							<?php endif; ?>
						</div>

						<div class="hx-spotlight-body">
							<span class="hx-spotlight-kicker">
								<?php hooshinex_icon( 'star', array( 'fill' => 'currentColor' ) ); ?>
								<?php esc_html_e( 'پرفروش‌ترین‌ها', 'hooshinex' ); ?>
							</span>

							<h3 class="hx-spotlight-title">
								<a href="<?php echo esc_url( get_permalink( $hooshinex_id ) ); ?>">
									<?php echo esc_html( $hooshinex_item->get_name() ); ?>
								</a>
							</h3>

							<div class="hx-spotlight-excerpt">
								<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hooshinex_item->get_short_description() ? $hooshinex_item->get_short_description() : $hooshinex_item->get_description() ), 28 ) ); ?>
							</div>

							<div class="hx-spotlight-meta">
								<?php if ( $hooshinex_rating > 0 ) : ?>
									<span class="hx-rating">
										<?php hooshinex_icon( 'star', array( 'fill' => 'currentColor' ) ); ?>
										<?php echo esc_html( hooshinex_fa_digits( number_format_i18n( $hooshinex_rating, 1 ) ) ); ?>
									</span>
								<?php endif; ?>

								<span class="hx-spotlight-sales">
									<?php hooshinex_icon( 'download', array( 'stroke' => '2' ) ); ?>
									<?php
									printf(
										/* translators: %s: sales count */
										esc_html__( '%s فروش', 'hooshinex' ),
										esc_html( hooshinex_fa_digits( (int) get_post_meta( $hooshinex_id, 'total_sales', true ) ) )
									);
									?>
								</span>
							</div>

							<div class="hx-spotlight-footer">
								<div class="hx-price"><?php echo wp_kses_post( $hooshinex_item->get_price_html() ); ?></div>

								<a class="hx-btn-primary" href="<?php echo esc_url( get_permalink( $hooshinex_id ) ); ?>">
									<?php esc_html_e( 'مشاهده و خرید', 'hooshinex' ); ?>
									<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="hx-spotlight-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'انتخاب محصول', 'hooshinex' ); ?>">
				<?php foreach ( $hooshinex_slides as $hooshinex_index => $hooshinex_item ) : ?>
					<button type="button" role="tab"
						class="hx-spotlight-thumb<?php echo 0 === $hooshinex_index ? ' is-active' : ''; ?>"
						data-hx-slide-to="<?php echo esc_attr( $hooshinex_index ); ?>"
						aria-selected="<?php echo 0 === $hooshinex_index ? 'true' : 'false'; ?>">
						<?php
						if ( has_post_thumbnail( $hooshinex_item->get_id() ) ) {
							echo get_the_post_thumbnail(
								$hooshinex_item->get_id(),
								'hooshinex-thumb',
								array(
									'loading' => 'lazy',
									'alt'     => esc_attr( $hooshinex_item->get_name() ),
								)
							);
						} else {
							hooshinex_icon( 'shop', array( 'stroke' => '1.4' ) );
						}
						?>
						<span class="hx-spotlight-thumb-title"><?php echo esc_html( wp_trim_words( $hooshinex_item->get_name(), 4 ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

		</div>

	</div>
</section>
