<?php
/**
 * Home: product categories carousel.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

if ( ! taxonomy_exists( 'product_cat' ) ) {
	return;
}

$hooshinex_terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

if ( is_wp_error( $hooshinex_terms ) || ! $hooshinex_terms ) {
	return;
}
?>
<section class="hx-section" id="categories">
	<div class="hx-container">

		<?php
		hooshinex_section_header(
			array(
				'accent'     => __( 'دسته‌بندی', 'hooshinex' ),
				'title'      => __( 'محصولات', 'hooshinex' ),
				'link'       => hooshinex_shop_url(),
				'link_label' => __( 'همه محصولات', 'hooshinex' ),
			)
		);
		?>

		<div class="hx-carousel" data-hx-carousel data-hx-autoplay="0">
			<div class="hx-carousel-viewport">
				<div class="hx-carousel-track hx-category-track" data-hx-track>
					<?php foreach ( $hooshinex_terms as $hooshinex_term ) : ?>
						<?php
						$hooshinex_thumb_id = (int) get_term_meta( $hooshinex_term->term_id, 'thumbnail_id', true );
						?>
						<a class="hx-category-card" href="<?php echo esc_url( get_term_link( $hooshinex_term ) ); ?>">
							<span class="hx-category-icon">
								<?php
								if ( $hooshinex_thumb_id ) {
									echo wp_get_attachment_image( $hooshinex_thumb_id, 'hooshinex-thumb', false, array( 'alt' => esc_attr( $hooshinex_term->name ) ) );
								} else {
									hooshinex_icon( 'layers', array( 'stroke' => '1.6' ) );
								}
								?>
							</span>
							<span><?php echo esc_html( $hooshinex_term->name ); ?></span>
							<span class="hx-category-count">
								<?php
								printf(
									/* translators: %s: product count */
									esc_html__( '%s محصول', 'hooshinex' ),
									esc_html( hooshinex_fa_digits( $hooshinex_term->count ) )
								);
								?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="hx-dots" data-hx-dots></div>
		</div>

	</div>
</section>
