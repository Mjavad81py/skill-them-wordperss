<?php
/**
 * Fallback footer, used when no Elementor footer template is assigned.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_tagline   = get_theme_mod( 'hooshinex_footer_tagline', 'نوآوری در وردپرس با هوش مصنوعی' );
$hooshinex_about     = get_theme_mod( 'hooshinex_footer_about', '' );
$hooshinex_socials   = hooshinex_social_links();
$hooshinex_phone     = get_theme_mod( 'hooshinex_contact_phone', '' );
$hooshinex_email     = get_theme_mod( 'hooshinex_contact_email', '' );
$hooshinex_telegram  = get_theme_mod( 'hooshinex_contact_telegram', '' );
$hooshinex_address   = get_theme_mod( 'hooshinex_contact_address', '' );
$hooshinex_website   = get_theme_mod( 'hooshinex_contact_website', '' );
$hooshinex_copyright = get_theme_mod( 'hooshinex_footer_copyright', '' );
?>
<footer id="colophon" class="site-footer">
	<div class="hx-container">
		<div class="site-footer-inner">

			<span class="hx-footer-deco" aria-hidden="true"></span>

			<div class="hx-footer-top">

				<div class="hx-footer-about">
					<h3>
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
						<span class="hx-logo-dot" aria-hidden="true"></span>
					</h3>

					<?php if ( $hooshinex_tagline ) : ?>
						<div class="hx-footer-tagline"><?php echo esc_html( $hooshinex_tagline ); ?></div>
					<?php endif; ?>

					<p><?php echo esc_html( $hooshinex_about ? $hooshinex_about : get_bloginfo( 'description' ) ); ?></p>

					<?php if ( $hooshinex_socials ) : ?>
						<div class="hx-footer-social">
							<?php foreach ( $hooshinex_socials as $hooshinex_social ) : ?>
								<a href="<?php echo esc_url( $hooshinex_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"
									title="<?php echo esc_attr( $hooshinex_social['label'] ); ?>">
									<?php hooshinex_icon( $hooshinex_social['icon'] ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $hooshinex_social['label'] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="hx-footer-col">
					<h4><?php esc_html_e( 'محصولات', 'hooshinex' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer-products' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer-products',
								'container'      => false,
								'depth'          => 1,
							)
						);
					} elseif ( taxonomy_exists( 'product_cat' ) ) {
						$hooshinex_cats = get_terms(
							array(
								'taxonomy'   => 'product_cat',
								'number'     => 5,
								'hide_empty' => true,
								'orderby'    => 'count',
								'order'      => 'DESC',
							)
						);

						if ( $hooshinex_cats && ! is_wp_error( $hooshinex_cats ) ) {
							echo '<ul>';
							foreach ( $hooshinex_cats as $hooshinex_cat ) {
								printf(
									'<li><a href="%1$s">%2$s</a></li>',
									esc_url( get_term_link( $hooshinex_cat ) ),
									esc_html( $hooshinex_cat->name )
								);
							}
							echo '</ul>';
						}
					}
					?>
				</div>

				<div class="hx-footer-col">
					<h4><?php esc_html_e( 'دسترسی سریع', 'hooshinex' ); ?></h4>
					<?php
					if ( has_nav_menu( 'footer-quick' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer-quick',
								'container'      => false,
								'depth'          => 1,
							)
						);
					} else {
						echo '<ul>';
						printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( hooshinex_shop_url() ), esc_html__( 'فروشگاه', 'hooshinex' ) );
						printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( hooshinex_account_url() ), esc_html__( 'حساب کاربری', 'hooshinex' ) );

						foreach ( array( 'about', 'faq', 'contact', 'terms' ) as $hooshinex_key ) {
							$hooshinex_pages = get_option( 'hooshinex_pages', array() );

							if ( empty( $hooshinex_pages[ $hooshinex_key ] ) ) {
								continue;
							}

							printf(
								'<li><a href="%1$s">%2$s</a></li>',
								esc_url( get_permalink( $hooshinex_pages[ $hooshinex_key ] ) ),
								esc_html( get_the_title( $hooshinex_pages[ $hooshinex_key ] ) )
							);
						}
						echo '</ul>';
					}
					?>
				</div>

				<div class="hx-footer-col">
					<h4><?php esc_html_e( 'تماس با ما', 'hooshinex' ); ?></h4>

					<?php if ( $hooshinex_phone ) : ?>
						<div class="hx-footer-contact-item">
							<span class="hx-ci-icon"><?php hooshinex_icon( 'phone', array( 'stroke' => '2' ) ); ?></span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $hooshinex_phone ) ); ?>" dir="ltr"><?php echo esc_html( $hooshinex_phone ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $hooshinex_email ) : ?>
						<div class="hx-footer-contact-item">
							<span class="hx-ci-icon"><?php hooshinex_icon( 'mail', array( 'stroke' => '2' ) ); ?></span>
							<a href="mailto:<?php echo esc_attr( sanitize_email( $hooshinex_email ) ); ?>" dir="ltr"><?php echo esc_html( $hooshinex_email ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $hooshinex_telegram ) : ?>
						<div class="hx-footer-contact-item">
							<span class="hx-ci-icon"><?php hooshinex_icon( 'telegram', array( 'fill' => 'currentColor' ) ); ?></span>
							<a href="https://t.me/<?php echo esc_attr( ltrim( $hooshinex_telegram, '@' ) ); ?>" target="_blank" rel="noopener noreferrer" dir="ltr">
								<?php echo esc_html( $hooshinex_telegram ); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( $hooshinex_website ) : ?>
						<div class="hx-footer-contact-item">
							<span class="hx-ci-icon"><?php hooshinex_icon( 'globe', array( 'stroke' => '2' ) ); ?></span>
							<a href="<?php echo esc_url( $hooshinex_website ); ?>" target="_blank" rel="noopener noreferrer" dir="ltr">
								<?php echo esc_html( wp_parse_url( $hooshinex_website, PHP_URL_HOST ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( $hooshinex_address ) : ?>
						<div class="hx-footer-contact-item">
							<span class="hx-ci-icon"><?php hooshinex_icon( 'map', array( 'stroke' => '2' ) ); ?></span>
							<span><?php echo esc_html( $hooshinex_address ); ?></span>
						</div>
					<?php endif; ?>
				</div>

			</div>

			<div class="hx-footer-bottom">
				<p class="site-info">
					<?php
					if ( $hooshinex_copyright ) {
						echo esc_html( $hooshinex_copyright );
					} else {
						printf(
							/* translators: 1: site name, 2: year */
							esc_html__( 'تمامی حقوق مادی و معنوی این سایت متعلق به %1$s است © %2$s', 'hooshinex' ),
							esc_html( get_bloginfo( 'name' ) ),
							esc_html( hooshinex_fa_digits( wp_date( 'Y' ) ) )
						);
					}
					?>
				</p>

				<div class="hx-footer-badges">
					<span class="hx-footer-badge">
						<?php hooshinex_icon( 'check-circle', array( 'stroke' => '2.5' ) ); ?>
						<?php esc_html_e( 'نماد اعتماد', 'hooshinex' ); ?>
					</span>
					<span class="hx-footer-badge">
						<?php hooshinex_icon( 'lock', array( 'stroke' => '2.5' ) ); ?>
						<?php esc_html_e( 'پرداخت امن', 'hooshinex' ); ?>
					</span>
				</div>
			</div>

		</div>
	</div>
</footer>

<button type="button" class="hx-scroll-top" data-hx-scroll-top aria-label="<?php esc_attr_e( 'بازگشت به بالا', 'hooshinex' ); ?>">
	<?php hooshinex_icon( 'up', array( 'stroke' => '1.8' ) ); ?>
</button>

<div class="hx-toasts" data-hx-toasts aria-live="polite"></div>
