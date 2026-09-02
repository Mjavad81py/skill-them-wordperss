<?php
/**
 * 404 template.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

hooshinex_do_location(
	'single',
	function () {
		?>
		<div class="hx-container">
			<section class="hx-empty-state error-404 not-found">

				<div class="hx-404-code" aria-hidden="true">
					<?php echo esc_html( hooshinex_fa_digits( '404' ) ); ?>
				</div>

				<h1 class="hx-empty-title"><?php esc_html_e( 'صفحه‌ای که دنبالش بودید پیدا نشد', 'hooshinex' ); ?></h1>

				<p class="hx-empty-text">
					<?php esc_html_e( 'ممکن است آدرس تغییر کرده باشد یا صفحه حذف شده باشد. از جستجو کمک بگیرید.', 'hooshinex' ); ?>
				</p>

				<div class="hx-empty-search">
					<?php get_search_form(); ?>
				</div>

				<div class="hx-empty-actions">
					<a class="hx-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'صفحه اصلی', 'hooshinex' ); ?>
					</a>

					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<a class="hx-btn-secondary" href="<?php echo esc_url( hooshinex_shop_url() ); ?>">
							<?php esc_html_e( 'فروشگاه', 'hooshinex' ); ?>
						</a>
					<?php endif; ?>
				</div>

			</section>
		</div>
		<?php
	}
);

get_footer();
