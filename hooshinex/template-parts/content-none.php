<?php
/**
 * Empty state for loops with no results.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="hx-empty-state no-results not-found">

	<span class="hx-empty-icon" aria-hidden="true">
		<?php hooshinex_icon( 'search', array( 'stroke' => '1.4' ) ); ?>
	</span>

	<h2 class="hx-empty-title">
		<?php
		if ( is_search() ) {
			esc_html_e( 'چیزی پیدا نشد', 'hooshinex' );
		} else {
			esc_html_e( 'هنوز مطلبی منتشر نشده', 'hooshinex' );
		}
		?>
	</h2>

	<p class="hx-empty-text">
		<?php
		if ( is_search() ) {
			esc_html_e( 'عبارت دیگری را امتحان کنید یا از میان دسته‌بندی‌ها جستجو کنید.', 'hooshinex' );
		} else {
			esc_html_e( 'به‌زودی محتوای این بخش منتشر می‌شود.', 'hooshinex' );
		}
		?>
	</p>

	<?php if ( is_search() ) : ?>
		<div class="hx-empty-search">
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>

	<div class="hx-empty-actions">
		<a class="hx-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'بازگشت به صفحه اصلی', 'hooshinex' ); ?>
		</a>

		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<a class="hx-btn-secondary" href="<?php echo esc_url( hooshinex_shop_url() ); ?>">
				<?php esc_html_e( 'فروشگاه', 'hooshinex' ); ?>
			</a>
		<?php endif; ?>
	</div>

</section>
