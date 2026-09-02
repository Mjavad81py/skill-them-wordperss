<?php
/**
 * Home: "become a seller" call to action.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_pages   = get_option( 'hooshinex_pages', array() );
$hooshinex_seller  = ! empty( $hooshinex_pages['sellers'] ) ? get_permalink( (int) $hooshinex_pages['sellers'] ) : '';
$hooshinex_contact = ! empty( $hooshinex_pages['contact'] ) ? get_permalink( (int) $hooshinex_pages['contact'] ) : '';

$hooshinex_perks = array(
	array(
		'icon'  => 'coin',
		'title' => __( 'درآمد پایدار', 'hooshinex' ),
		'text'  => __( 'تسویه حساب دوره‌ای و شفاف برای همه فروش‌ها', 'hooshinex' ),
	),
	array(
		'icon'  => 'user-plus',
		'title' => __( 'مخاطب آماده', 'hooshinex' ),
		'text'  => __( 'دسترسی به جامعه‌ای از توسعه‌دهندگان و صاحبان کسب‌وکار', 'hooshinex' ),
	),
	array(
		'icon'  => 'check-circle',
		'title' => __( 'پشتیبانی فنی', 'hooshinex' ),
		'text'  => __( 'تیم ما در انتشار، بروزرسانی و پاسخ به کاربران کنارتان است', 'hooshinex' ),
	),
);
?>
<section class="hx-section" id="seller">
	<div class="hx-container">
		<div class="hx-seller">

			<span class="hx-seller-glow" aria-hidden="true"></span>

			<div class="hx-seller-content">
				<span class="hx-seller-kicker">
					<?php hooshinex_icon( 'shop', array( 'stroke' => '2' ) ); ?>
					<?php esc_html_e( 'همکاری با ما', 'hooshinex' ); ?>
				</span>

				<h2 class="hx-seller-title">
					<?php esc_html_e( 'فروشنده', 'hooshinex' ); ?>
					<span class="gold"><?php esc_html_e( 'شوید', 'hooshinex' ); ?></span>
				</h2>

				<p class="hx-seller-desc">
					<?php esc_html_e( 'محصول خود را در هوشینکس منتشر کنید و از فروش آن درآمد کسب کنید. ثبت‌نام رایگان است.', 'hooshinex' ); ?>
				</p>

				<div class="hx-seller-actions">
					<a class="hx-btn-primary" href="<?php echo esc_url( $hooshinex_seller ? $hooshinex_seller : hooshinex_account_url() ); ?>">
						<?php esc_html_e( 'شروع همکاری', 'hooshinex' ); ?>
						<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
					</a>

					<?php if ( $hooshinex_contact ) : ?>
						<a class="hx-btn-secondary" href="<?php echo esc_url( $hooshinex_contact ); ?>">
							<?php esc_html_e( 'مشاوره رایگان', 'hooshinex' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<ul class="hx-seller-perks">
				<?php foreach ( $hooshinex_perks as $hooshinex_perk ) : ?>
					<li>
						<span class="hx-seller-perk-icon"><?php hooshinex_icon( $hooshinex_perk['icon'], array( 'stroke' => '1.8' ) ); ?></span>
						<span>
							<strong><?php echo esc_html( $hooshinex_perk['title'] ); ?></strong>
							<span><?php echo esc_html( $hooshinex_perk['text'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
	</div>
</section>
