<?php
/**
 * Home: the two promo banners.
 *
 * Each banner points at a product category; the first two categories are used when
 * nothing is configured.
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
		'number'     => 2,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

if ( is_wp_error( $hooshinex_terms ) || count( $hooshinex_terms ) < 2 ) {
	return;
}

$hooshinex_icons = array( 'plug', 'code' );
$hooshinex_mods  = array( '', 'hx-twin-banner--gold' );
?>
<section class="hx-section hx-section--flush-top">
	<div class="hx-container">
		<div class="hx-twin-banners">

			<?php foreach ( array_values( $hooshinex_terms ) as $hooshinex_i => $hooshinex_term ) : ?>
				<a class="hx-twin-banner <?php echo esc_attr( $hooshinex_mods[ $hooshinex_i ] ); ?>"
					href="<?php echo esc_url( get_term_link( $hooshinex_term ) ); ?>">

					<span class="hx-twin-icon">
						<?php hooshinex_icon( $hooshinex_icons[ $hooshinex_i ] ); ?>
					</span>

					<span class="hx-twin-text">
						<span class="hx-twin-kicker"><?php esc_html_e( 'دسته‌بندی ویژه', 'hooshinex' ); ?></span>
						<span class="hx-twin-title"><?php echo esc_html( $hooshinex_term->name ); ?></span>
						<span class="hx-twin-desc">
							<?php
							echo esc_html(
								$hooshinex_term->description
									? wp_trim_words( $hooshinex_term->description, 12 )
									: sprintf(
										/* translators: %s: product count */
										__( '%s محصول آماده دانلود با پشتیبانی و بروزرسانی دائمی', 'hooshinex' ),
										hooshinex_fa_digits( $hooshinex_term->count )
									)
							);
							?>
						</span>
					</span>

					<span class="hx-twin-cta">
						<?php esc_html_e( 'مشاهده همه', 'hooshinex' ); ?>
						<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
					</span>
				</a>
			<?php endforeach; ?>

		</div>
	</div>
</section>
