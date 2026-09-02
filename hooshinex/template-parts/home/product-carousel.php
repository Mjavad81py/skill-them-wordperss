<?php
/**
 * Home: a draggable product carousel.
 *
 * Expects $args: title, accent, source (featured|recent|sale|best), limit,
 * section_class, link, link_label, autoplay.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
	return;
}

$args = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : array(),
	array(
		'title'         => '',
		'accent'        => '',
		'source'        => 'recent',
		'limit'         => 10,
		'section_class' => '',
		'link'          => hooshinex_shop_url(),
		'link_label'    => __( 'مشاهده همه', 'hooshinex' ),
		'autoplay'      => 3000,
		'id'            => '',
	)
);

$hooshinex_query_args = array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => (int) $args['limit'],
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'tax_query'           => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
);

if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
	$hooshinex_visibility = wc_get_product_visibility_term_ids();

	$hooshinex_query_args['tax_query'][] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'term_taxonomy_id',
		'terms'    => $hooshinex_visibility['exclude-from-catalog'],
		'operator' => 'NOT IN',
	);

	if ( 'featured' === $args['source'] ) {
		$hooshinex_query_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => $hooshinex_visibility['featured'],
		);
	}
}

if ( 'sale' === $args['source'] && function_exists( 'wc_get_product_ids_on_sale' ) ) {
	$hooshinex_on_sale = wc_get_product_ids_on_sale();

	if ( ! $hooshinex_on_sale ) {
		return;
	}

	$hooshinex_query_args['post__in'] = $hooshinex_on_sale;
}

if ( 'best' === $args['source'] ) {
	$hooshinex_query_args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	$hooshinex_query_args['orderby']  = 'meta_value_num';
	$hooshinex_query_args['order']    = 'DESC';
}

$hooshinex_products = new WP_Query( $hooshinex_query_args );

if ( ! $hooshinex_products->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="hx-section <?php echo esc_attr( $args['section_class'] ); ?>"<?php echo $args['id'] ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>>
	<div class="hx-container">

		<?php
		hooshinex_section_header(
			array(
				'title'      => $args['title'],
				'accent'     => $args['accent'],
				'link'       => $args['link'],
				'link_label' => $args['link_label'],
			)
		);
		?>

		<div class="hx-carousel" data-hx-carousel data-hx-autoplay="<?php echo esc_attr( (int) $args['autoplay'] ); ?>">

			<button type="button" class="hx-carousel-nav prev" data-hx-prev aria-label="<?php esc_attr_e( 'قبلی', 'hooshinex' ); ?>">
				<?php hooshinex_icon( 'prev', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-carousel-viewport">
				<div class="hx-carousel-track" data-hx-track>
					<?php
					while ( $hooshinex_products->have_posts() ) {
						$hooshinex_products->the_post();
						hooshinex_render_product_card( wc_get_product( get_the_ID() ) );
					}
					?>
				</div>
			</div>

			<button type="button" class="hx-carousel-nav next" data-hx-next aria-label="<?php esc_attr_e( 'بعدی', 'hooshinex' ); ?>">
				<?php hooshinex_icon( 'next', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-dots" data-hx-dots></div>
		</div>

	</div>
</section>
<?php
wp_reset_postdata();
