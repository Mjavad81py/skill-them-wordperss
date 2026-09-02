<?php
/**
 * One-time content bootstrap.
 *
 * Creates the pages an online store legally and practically needs, assigns the
 * static front page and the blog page, and builds a starter primary menu. Runs
 * once on activation and can be re-run from Appearance → Hooshinex Setup.
 *
 * Nothing is overwritten: an existing page with the same slug is reused.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * The pages this theme considers mandatory for a store.
 *
 * @return array
 */
function hooshinex_required_pages() {

	return array(
		'home'     => array(
			'title'    => esc_html__( 'صفحه اصلی', 'hooshinex' ),
			'slug'     => 'home',
			'template' => '',
			'content'  => '',
		),
		'blog'     => array(
			'title'    => esc_html__( 'وبلاگ', 'hooshinex' ),
			'slug'     => 'blog',
			'template' => '',
			'content'  => '',
		),
		'about'    => array(
			'title'    => esc_html__( 'درباره ما', 'hooshinex' ),
			'slug'     => 'about',
			'template' => '',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'ما یک تیم توسعه نرم‌افزار هستیم که روی افزونه، قالب، اسکریپت و محصولات مبتنی بر هوش مصنوعی برای وردپرس و ووکامرس تمرکز داریم. این متن را از پیشخوان ویرایش کنید.', 'hooshinex' ) . '</p><!-- /wp:paragraph -->',
		),
		'contact'  => array(
			'title'    => esc_html__( 'تماس با ما', 'hooshinex' ),
			'slug'     => 'contact',
			'template' => 'templates/contact.php',
			'content'  => '',
		),
		'faq'      => array(
			'title'    => esc_html__( 'سوالات متداول', 'hooshinex' ),
			'slug'     => 'faq',
			'template' => 'templates/faq.php',
			'content'  => '',
		),
		'terms'    => array(
			'title'    => esc_html__( 'قوانین و مقررات', 'hooshinex' ),
			'slug'     => 'terms',
			'template' => '',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'قوانین استفاده از سایت و شرایط خرید محصولات دیجیتال را اینجا بنویسید.', 'hooshinex' ) . '</p><!-- /wp:paragraph -->',
		),
		'privacy'  => array(
			'title'    => esc_html__( 'حریم خصوصی', 'hooshinex' ),
			'slug'     => 'privacy-policy',
			'template' => '',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'سیاست حفظ حریم خصوصی و نحوه نگهداری داده‌های کاربران را اینجا توضیح دهید.', 'hooshinex' ) . '</p><!-- /wp:paragraph -->',
		),
		'guide'    => array(
			'title'    => esc_html__( 'راهنمای خرید', 'hooshinex' ),
			'slug'     => 'buying-guide',
			'template' => '',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'مراحل ثبت سفارش، پرداخت و دانلود محصول را گام‌به‌گام توضیح دهید.', 'hooshinex' ) . '</p><!-- /wp:paragraph -->',
		),
		'sellers'  => array(
			'title'    => esc_html__( 'همکاری در فروش', 'hooshinex' ),
			'slug'     => 'become-a-seller',
			'template' => '',
			'content'  => '<!-- wp:paragraph --><p>' . esc_html__( 'شرایط فروشندگی و مزایای همکاری با فروشگاه را اینجا معرفی کنید.', 'hooshinex' ) . '</p><!-- /wp:paragraph -->',
		),
	);
}

/**
 * Create every missing page and remember its ID.
 *
 * @return array Map of key => page ID.
 */
function hooshinex_install_pages() {

	$created = get_option( 'hooshinex_pages', array() );

	foreach ( hooshinex_required_pages() as $key => $page ) {

		$existing = get_page_by_path( $page['slug'] );

		if ( $existing instanceof WP_Post ) {
			$created[ $key ] = $existing->ID;
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_name'    => $page['slug'],
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
			)
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			continue;
		}

		if ( $page['template'] ) {
			update_post_meta( $page_id, '_wp_page_template', $page['template'] );
		}

		$created[ $key ] = $page_id;
	}

	update_option( 'hooshinex_pages', $created );

	hooshinex_assign_front_page( $created );
	hooshinex_install_primary_menu( $created );

	return $created;
}

/**
 * Point WordPress at the static home page and the blog page.
 *
 * @param array $pages Map of key => page ID.
 * @return void
 */
function hooshinex_assign_front_page( $pages ) {

	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) ) {
		return;
	}

	if ( empty( $pages['home'] ) ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', (int) $pages['home'] );

	if ( ! empty( $pages['blog'] ) ) {
		update_option( 'page_for_posts', (int) $pages['blog'] );
	}
}

/**
 * Build a starter primary menu when the site has none.
 *
 * @param array $pages Map of key => page ID.
 * @return void
 */
function hooshinex_install_primary_menu( $pages ) {

	if ( has_nav_menu( 'primary' ) ) {
		return;
	}

	$menu_name = esc_html__( 'منوی اصلی', 'hooshinex' );
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );

	if ( ! $menu_id || is_wp_error( $menu_id ) ) {
		return;
	}

	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_id = wc_get_page_id( 'shop' );

		if ( $shop_id > 0 ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => esc_html__( 'فروشگاه', 'hooshinex' ),
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $shop_id,
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}
	}

	foreach ( array( 'blog', 'about', 'faq', 'contact' ) as $key ) {

		if ( empty( $pages[ $key ] ) ) {
			continue;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => get_the_title( $pages[ $key ] ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => (int) $pages[ $key ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations            = (array) get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Run the bootstrap once, the first time the theme is activated.
 *
 * @return void
 */
function hooshinex_after_switch_theme() {

	if ( get_option( 'hooshinex_installed' ) ) {
		return;
	}

	hooshinex_install_pages();
	update_option( 'hooshinex_installed', HOOSHINEX_VERSION );
}
add_action( 'after_switch_theme', 'hooshinex_after_switch_theme' );

/**
 * A tiny admin screen to re-run the bootstrap.
 *
 * @return void
 */
function hooshinex_setup_menu() {

	add_theme_page(
		esc_html__( 'راه‌اندازی هوشینکس', 'hooshinex' ),
		esc_html__( 'راه‌اندازی هوشینکس', 'hooshinex' ),
		'edit_theme_options',
		'hooshinex-setup',
		'hooshinex_setup_screen'
	);
}
add_action( 'admin_menu', 'hooshinex_setup_menu' );

/**
 * Render the setup screen.
 *
 * @return void
 */
function hooshinex_setup_screen() {

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$done = false;

	if (
		isset( $_POST['hooshinex_install'], $_POST['hooshinex_nonce'] )
		&& wp_verify_nonce( sanitize_key( wp_unslash( $_POST['hooshinex_nonce'] ) ), 'hooshinex_install' )
	) {
		hooshinex_install_pages();
		$done = true;
	}

	$pages = get_option( 'hooshinex_pages', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'راه‌اندازی قالب هوشینکس', 'hooshinex' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'صفحات واجب ساخته و بررسی شدند.', 'hooshinex' ); ?></p></div>
		<?php endif; ?>

		<p><?php esc_html_e( 'با زدن دکمه زیر، صفحات ضروری فروشگاه ساخته می‌شوند (اگر از قبل موجود باشند دست‌نخورده می‌مانند) و صفحه اصلی و وبلاگ تنظیم می‌شوند.', 'hooshinex' ); ?></p>

		<form method="post">
			<?php wp_nonce_field( 'hooshinex_install', 'hooshinex_nonce' ); ?>
			<p>
				<button type="submit" name="hooshinex_install" value="1" class="button button-primary">
					<?php esc_html_e( 'ساخت صفحات واجب', 'hooshinex' ); ?>
				</button>
			</p>
		</form>

		<?php if ( $pages ) : ?>
			<h2><?php esc_html_e( 'صفحات ساخته‌شده', 'hooshinex' ); ?></h2>
			<ul class="ul-disc">
				<?php foreach ( $pages as $page_id ) : ?>
					<?php if ( get_post( $page_id ) ) : ?>
						<li>
							<a href="<?php echo esc_url( get_edit_post_link( $page_id ) ); ?>"><?php echo esc_html( get_the_title( $page_id ) ); ?></a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
}
