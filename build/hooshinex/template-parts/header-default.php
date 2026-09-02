<?php
/**
 * Fallback header, used when no Elementor header template is assigned.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_show_search = (bool) get_theme_mod( 'hooshinex_header_search', true );
$hooshinex_show_cart   = (bool) get_theme_mod( 'hooshinex_header_cart', true ) && class_exists( 'WooCommerce' );
?>

<div class="hx-drawer-overlay" id="hxDrawerOverlay" hidden></div>

<div class="hx-drawer" id="hxDrawer" aria-hidden="true">

	<button type="button" class="hx-drawer-close" data-hx-drawer-close aria-label="<?php esc_attr_e( 'بستن منو', 'hooshinex' ); ?>">
		<?php hooshinex_icon( 'close' ); ?>
	</button>

	<?php if ( $hooshinex_show_search ) : ?>
		<div class="hx-search hx-search--mobile" data-hx-search>
			<form class="hx-search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php hooshinex_icon( 'search', array( 'class' => 'hx-search-lead' ) ); ?>
				<input type="search" class="hx-search-input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php esc_attr_e( 'جستجوی افزونه، قالب، اسکریپت…', 'hooshinex' ); ?>"
					autocomplete="off" aria-label="<?php esc_attr_e( 'جستجو', 'hooshinex' ); ?>">
				<button type="button" class="hx-search-clear" data-hx-search-clear aria-label="<?php esc_attr_e( 'پاک کردن', 'hooshinex' ); ?>">
					<?php hooshinex_icon( 'close', array( 'stroke' => '2.5' ) ); ?>
				</button>
				<button type="submit" class="hx-search-submit" aria-label="<?php esc_attr_e( 'جستجو', 'hooshinex' ); ?>">
					<?php hooshinex_icon( 'arrow', array( 'stroke' => '2.2' ) ); ?>
				</button>
			</form>
			<div class="hx-search-panel" data-hx-search-panel></div>
		</div>
	<?php endif; ?>

	<nav aria-label="<?php esc_attr_e( 'منوی موبایل', 'hooshinex' ); ?>">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'hx-drawer-menu',
					'container'      => false,
					'depth'          => 2,
				)
			);
		} else {
			wp_page_menu( array( 'menu_class' => 'hx-drawer-fallback-menu' ) );
		}
		?>
	</nav>

	<div class="hx-drawer-actions">
		<?php if ( is_user_logged_in() ) : ?>
			<a class="hx-btn-primary" href="<?php echo esc_url( hooshinex_account_url() ); ?>">
				<?php esc_html_e( 'حساب کاربری', 'hooshinex' ); ?>
			</a>
			<a class="hx-btn-secondary" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
				<?php esc_html_e( 'خروج', 'hooshinex' ); ?>
			</a>
		<?php else : ?>
			<a class="hx-btn-primary" href="<?php echo esc_url( hooshinex_account_url() ); ?>">
				<?php esc_html_e( 'ورود', 'hooshinex' ); ?>
			</a>
			<a class="hx-btn-secondary" href="<?php echo esc_url( wp_registration_url() ); ?>">
				<?php esc_html_e( 'ثبت‌نام', 'hooshinex' ); ?>
			</a>
		<?php endif; ?>
	</div>

</div>

<header id="masthead" class="site-header" data-hx-header>
	<div class="hx-container site-header-inner">

		<button type="button" class="menu-toggle" data-hx-drawer-open aria-controls="hxDrawer" aria-expanded="false">
			<?php hooshinex_icon( 'menu', array( 'stroke' => '1.5' ) ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'منو', 'hooshinex' ); ?></span>
		</button>

		<div class="site-branding">
			<?php hooshinex_site_branding(); ?>
		</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'منوی اصلی', 'hooshinex' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'depth'          => 2,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php if ( $hooshinex_show_search ) : ?>
			<div class="hx-search" data-hx-search>
				<form class="hx-search-box" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php hooshinex_icon( 'search', array( 'class' => 'hx-search-lead' ) ); ?>
					<input type="search" class="hx-search-input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
						placeholder="<?php esc_attr_e( 'جستجوی افزونه، قالب، اسکریپت…', 'hooshinex' ); ?>"
						autocomplete="off" aria-label="<?php esc_attr_e( 'جستجو', 'hooshinex' ); ?>">
					<kbd class="hx-kbd">Ctrl K</kbd>
					<button type="button" class="hx-search-clear" data-hx-search-clear aria-label="<?php esc_attr_e( 'پاک کردن', 'hooshinex' ); ?>">
						<?php hooshinex_icon( 'close', array( 'stroke' => '2.5' ) ); ?>
					</button>
					<button type="submit" class="hx-search-submit" aria-label="<?php esc_attr_e( 'جستجو', 'hooshinex' ); ?>">
						<?php hooshinex_icon( 'arrow', array( 'stroke' => '2.2' ) ); ?>
					</button>
				</form>
				<div class="hx-search-panel" data-hx-search-panel></div>
			</div>
		<?php endif; ?>

		<div class="site-header-actions">

			<?php if ( $hooshinex_show_cart ) : ?>
				<a class="hx-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" data-hx-cart
					aria-label="<?php esc_attr_e( 'سبد خرید', 'hooshinex' ); ?>">
					<?php hooshinex_icon( 'cart', array( 'stroke' => '1.6' ) ); ?>
					<span class="hx-cart-count" data-hx-cart-count><?php echo esc_html( hooshinex_fa_digits( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( is_user_logged_in() ) : ?>
				<a class="hx-btn-secondary hx-login-desktop" href="<?php echo esc_url( hooshinex_account_url() ); ?>">
					<?php esc_html_e( 'حساب کاربری', 'hooshinex' ); ?>
				</a>
			<?php else : ?>
				<a class="hx-btn-secondary hx-login-desktop" href="<?php echo esc_url( hooshinex_account_url() ); ?>">
					<?php esc_html_e( 'ورود', 'hooshinex' ); ?>
				</a>
				<a class="hx-btn-primary hx-login-desktop" href="<?php echo esc_url( wp_registration_url() ); ?>">
					<?php esc_html_e( 'ثبت‌نام', 'hooshinex' ); ?>
				</a>
			<?php endif; ?>

			<a class="hx-btn-secondary gold hx-login-mobile" href="<?php echo esc_url( hooshinex_account_url() ); ?>">
				<?php echo is_user_logged_in() ? esc_html__( 'حساب', 'hooshinex' ) : esc_html__( 'ورود', 'hooshinex' ); ?>
			</a>

		</div>

	</div>
</header>
