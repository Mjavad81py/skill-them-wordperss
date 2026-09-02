<?php
/**
 * Home: the discount countdown banner.
 *
 * The headline percentage and the deadline are derived from real WooCommerce sale
 * data, so the banner disappears by itself once the campaign is over.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
	return;
}

$hooshinex_ids = wc_get_product_ids_on_sale();

if ( ! $hooshinex_ids ) {
	return;
}

$hooshinex_best     = 0;
$hooshinex_deadline = 0;

foreach ( array_slice( $hooshinex_ids, 0, 40 ) as $hooshinex_id ) {
	$hooshinex_product = wc_get_product( $hooshinex_id );

	if ( ! $hooshinex_product ) {
		continue;
	}

	$hooshinex_regular = (float) $hooshinex_product->get_regular_price();
	$hooshinex_sale    = (float) $hooshinex_product->get_sale_price();

	if ( $hooshinex_regular > 0 && $hooshinex_sale > 0 && $hooshinex_sale < $hooshinex_regular ) {
		$hooshinex_best = max( $hooshinex_best, (int) round( ( 1 - $hooshinex_sale / $hooshinex_regular ) * 100 ) );
	}

	$hooshinex_to = $hooshinex_product->get_date_on_sale_to();

	if ( $hooshinex_to ) {
		$hooshinex_ts = $hooshinex_to->getTimestamp();

		if ( $hooshinex_ts > time() && ( ! $hooshinex_deadline || $hooshinex_ts < $hooshinex_deadline ) ) {
			$hooshinex_deadline = $hooshinex_ts;
		}
	}
}

if ( ! $hooshinex_best ) {
	return;
}

$hooshinex_sale_url = add_query_arg( 'on_sale', '1', hooshinex_shop_url() );
?>
<section class="hx-section hx-section--flush-top">
	<div class="hx-container">
		<div class="hx-offer-banner">

			<span class="hx-offer-glow" aria-hidden="true"></span>

			<div class="hx-offer-main">
				<span class="hx-offer-badge">
					<?php hooshinex_icon( 'tag', array( 'stroke' => '2' ) ); ?>
					<?php esc_html_e( 'پیشنهاد شگفت‌انگیز', 'hooshinex' ); ?>
				</span>

				<h3 class="hx-offer-title">
					<?php
					printf(
						/* translators: %s: discount percentage */
						esc_html__( 'تا %s تخفیف روی محصولات منتخب', 'hooshinex' ),
						'<span class="gold">' . esc_html( hooshinex_fa_digits( $hooshinex_best ) ) . '٪</span>'
					);
					?>
				</h3>

				<p class="hx-offer-desc">
					<?php esc_html_e( 'فرصت محدود است؛ محصول مورد نظرت را با بهترین قیمت بردار.', 'hooshinex' ); ?>
				</p>
			</div>

			<?php if ( $hooshinex_deadline ) : ?>
				<div class="hx-countdown" data-hx-countdown="<?php echo esc_attr( $hooshinex_deadline ); ?>">
					<div class="hx-countdown-unit">
						<span class="num" data-hx-cd-days>۰۰</span>
						<span class="label"><?php esc_html_e( 'روز', 'hooshinex' ); ?></span>
					</div>
					<span class="hx-countdown-sep">:</span>
					<div class="hx-countdown-unit">
						<span class="num" data-hx-cd-hours>۰۰</span>
						<span class="label"><?php esc_html_e( 'ساعت', 'hooshinex' ); ?></span>
					</div>
					<span class="hx-countdown-sep">:</span>
					<div class="hx-countdown-unit">
						<span class="num" data-hx-cd-minutes>۰۰</span>
						<span class="label"><?php esc_html_e( 'دقیقه', 'hooshinex' ); ?></span>
					</div>
					<span class="hx-countdown-sep">:</span>
					<div class="hx-countdown-unit">
						<span class="num" data-hx-cd-seconds>۰۰</span>
						<span class="label"><?php esc_html_e( 'ثانیه', 'hooshinex' ); ?></span>
					</div>
				</div>
			<?php endif; ?>

			<a class="hx-btn-primary hx-offer-cta" href="<?php echo esc_url( $hooshinex_sale_url ); ?>">
				<?php esc_html_e( 'مشاهده تخفیف‌ها', 'hooshinex' ); ?>
				<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
			</a>

		</div>
	</div>
</section>
