<?php
/**
 * Home: hero.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_title  = get_theme_mod( 'hooshinex_hero_title', 'محصولات هوشمند' );
$hooshinex_accent = get_theme_mod( 'hooshinex_hero_title_accent', 'وردپرس و ووکامرس' );
$hooshinex_desc   = get_theme_mod( 'hooshinex_hero_desc', '' );
$hooshinex_img_id = (int) get_theme_mod( 'hooshinex_hero_image', 0 );
?>
<section class="hx-hero">
	<div class="hx-container">
		<div class="hx-hero-inner">

			<div class="hx-hero-content">

				<h1 class="hx-hero-title">
					<?php echo esc_html( $hooshinex_title ); ?>
					<?php if ( $hooshinex_accent ) : ?>
						<span class="gold"><?php echo esc_html( $hooshinex_accent ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( $hooshinex_desc ) : ?>
					<p class="hx-hero-desc"><?php echo esc_html( $hooshinex_desc ); ?></p>
				<?php endif; ?>

				<form class="hx-hero-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="screen-reader-text" for="hx-hero-search-field"><?php esc_html_e( 'جستجو', 'hooshinex' ); ?></label>
					<input type="search" id="hx-hero-search-field" name="s"
						placeholder="<?php esc_attr_e( 'جستجوی محصولات، افزونه‌ها، مقالات…', 'hooshinex' ); ?>"
						value="<?php echo esc_attr( get_search_query() ); ?>">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<input type="hidden" name="post_type" value="product">
					<?php endif; ?>
					<button type="submit" aria-label="<?php esc_attr_e( 'جستجو', 'hooshinex' ); ?>">
						<?php hooshinex_icon( 'search', array( 'stroke' => '1.8' ) ); ?>
					</button>
				</form>

				<div class="hx-hero-stats">
					<?php foreach ( array( 1, 2, 3 ) as $hooshinex_i ) : ?>
						<?php
						$hooshinex_num   = get_theme_mod( 'hooshinex_hero_stat_' . $hooshinex_i . '_num', '' );
						$hooshinex_label = get_theme_mod( 'hooshinex_hero_stat_' . $hooshinex_i . '_label', '' );

						if ( ! $hooshinex_num ) {
							continue;
						}
						?>
						<?php if ( $hooshinex_i > 1 ) : ?>
							<span class="hx-hero-stat-divider" aria-hidden="true"></span>
						<?php endif; ?>
						<div class="hx-hero-stat">
							<div class="num"><?php echo esc_html( hooshinex_fa_digits( $hooshinex_num ) ); ?></div>
							<div class="label"><?php echo esc_html( $hooshinex_label ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>

			</div>

			<?php if ( $hooshinex_img_id ) : ?>
				<div class="hx-hero-visual">
					<?php
					echo wp_get_attachment_image(
						$hooshinex_img_id,
						'hooshinex-hero',
						false,
						array(
							'fetchpriority' => 'high',
							'decoding'      => 'async',
							'alt'           => esc_attr( get_bloginfo( 'name' ) ),
						)
					);
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
