<?php
/**
 * Template tags used by the fallback markup and the theme's front-end parts.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon library.
 *
 * Icons are printed inline (no icon font, no extra request) and inherit currentColor.
 *
 * @param string $name  Icon key.
 * @param array  $args  Optional. 'class', 'width', 'height', 'stroke'.
 * @return string Escaped SVG markup, or an empty string for unknown icons.
 */
function hooshinex_get_icon( $name, $args = array() ) {

	$paths = array(
		'search'    => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'arrow'     => '<path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>',
		'chevron'   => '<path d="M6 9l6 6 6-6"/>',
		'prev'      => '<path d="M9 18l6-6-6-6"/>',
		'next'      => '<path d="M15 18l-6-6 6-6"/>',
		'close'     => '<path d="M18 6L6 18M6 6l12 12"/>',
		'menu'      => '<path d="M3 7h18M3 12h18M3 17h18"/>',
		'up'        => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'cart'      => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
		'eye'       => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
		'layers'    => '<path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>',
		'clock'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'star'      => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
		'tag'       => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><path d="M7 7h.01"/>',
		'shop'      => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'plug'      => '<path d="M12 2v6M8 8h8l-1 6a4 4 0 0 1-3 3v5"/><path d="M9 17a4 4 0 0 0 3-3"/>',
		'code'      => '<path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>',
		'ai'        => '<path d="M12 2a4 4 0 0 1 4 4v2a4 4 0 0 1-8 0V6a4 4 0 0 1 4-4z"/><path d="M6 10a6 6 0 0 0 12 0"/><path d="M12 16v6M8 20h8"/>',
		'user'      => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'user-plus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>',
		'chat'      => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
		'check'     => '<polyline points="20 6 9 17 4 12"/>',
		'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
		'lock'      => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'globe'     => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/>',
		'map'       => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
		'coin'      => '<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
		'plus'      => '<path d="M12 5v14M5 12h14"/>',
		'book'      => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
		'download'  => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
		'gift'      => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
		'telegram'  => '<path d="M21.9 4.3 2.9 11.6c-1 .4-1 1.8 0 2.2l4.6 1.5 1.8 5.4c.3.9 1.4 1.1 2 .4l2.5-2.6 4.6 3.4c.8.6 1.9.1 2.1-.8l3-14.6c.2-1-.8-1.8-1.6-1.2z"/>',
		'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>',
		'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
		'github'    => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.9a3.4 3.4 0 0 0-1-2.6c3-.3 6.2-1.5 6.2-6.7A5.2 5.2 0 0 0 19.8 5a4.9 4.9 0 0 0-.1-3.6s-1.1-.3-3.7 1.4a12.7 12.7 0 0 0-6.6 0C6.8 1.1 5.7 1.4 5.7 1.4A4.9 4.9 0 0 0 5.6 5 5.2 5.2 0 0 0 4.2 8.8c0 5.2 3.2 6.4 6.2 6.7a3.4 3.4 0 0 0-1 2.6V22"/>',
		'youtube'   => '<path d="M22.5 6.4a2.8 2.8 0 0 0-2-2C18.8 4 12 4 12 4s-6.8 0-8.5.4a2.8 2.8 0 0 0-2 2A29 29 0 0 0 1.1 12a29 29 0 0 0 .4 5.6 2.8 2.8 0 0 0 2 2C5.2 20 12 20 12 20s6.8 0 8.5-.4a2.8 2.8 0 0 0 2-2A29 29 0 0 0 22.9 12a29 29 0 0 0-.4-5.6z"/><polygon points="9.8 15.3 15.5 12 9.8 8.7"/>',
		'twitter'   => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>',
		'whatsapp'  => '<path d="M21 11.5a8.5 8.5 0 0 1-12.6 7.4L3 21l2.2-5.2A8.5 8.5 0 1 1 21 11.5z"/>',
		'aparat'    => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'class'  => '',
			'width'  => '',
			'height' => '',
			'fill'   => 'none',
			'stroke' => '1.7',
		)
	);

	$attrs = sprintf(
		'viewBox="0 0 24 24" fill="%1$s" stroke="%2$s" stroke-width="%3$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"',
		esc_attr( $args['fill'] ),
		'none' === $args['fill'] ? 'currentColor' : 'none',
		esc_attr( $args['stroke'] )
	);

	if ( $args['class'] ) {
		$attrs .= ' class="' . esc_attr( $args['class'] ) . '"';
	}

	if ( $args['width'] ) {
		$attrs .= ' width="' . esc_attr( $args['width'] ) . '"';
	}

	if ( $args['height'] ) {
		$attrs .= ' height="' . esc_attr( $args['height'] ) . '"';
	}

	return '<svg ' . $attrs . '>' . $paths[ $name ] . '</svg>';
}

/**
 * Echo an inline SVG icon.
 *
 * @param string $name Icon key.
 * @param array  $args Icon args.
 * @return void
 */
function hooshinex_icon( $name, $args = array() ) {
	echo wp_kses( hooshinex_get_icon( $name, $args ), hooshinex_svg_allowed_html() );
}

/**
 * Allowed SVG tags/attributes for wp_kses().
 *
 * @return array
 */
function hooshinex_svg_allowed_html() {
	return array(
		'svg'      => array(
			'class'            => true,
			'viewbox'          => true,
			'width'            => true,
			'height'           => true,
			'fill'             => true,
			'stroke'           => true,
			'stroke-width'     => true,
			'stroke-linecap'   => true,
			'stroke-linejoin'  => true,
			'aria-hidden'      => true,
			'focusable'        => true,
			'xmlns'            => true,
			'role'             => true,
		),
		'path'     => array( 'd' => true, 'fill' => true, 'opacity' => true, 'stroke' => true ),
		'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true, 'opacity' => true ),
		'ellipse'  => array( 'cx' => true, 'cy' => true, 'rx' => true, 'ry' => true, 'fill' => true ),
		'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true, 'fill' => true ),
		'line'     => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ),
		'polyline' => array( 'points' => true ),
		'polygon'  => array( 'points' => true, 'fill' => true ),
		'g'        => array( 'fill' => true, 'opacity' => true ),
	);
}

/**
 * Print the site branding: logo when set, otherwise the site title.
 *
 * @return void
 */
function hooshinex_site_branding() {

	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	$tag = ( is_front_page() && is_home() ) ? 'h1' : 'p';

	printf(
		'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
		esc_html( $tag ),
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Print post meta for the fallback loop.
 *
 * @return void
 */
function hooshinex_entry_meta() {

	if ( 'post' !== get_post_type() ) {
		return;
	}

	printf(
		'<div class="entry-meta"><span class="hx-blog-card__author"><span class="hx-avatar">%1$s</span><span>%2$s</span></span><span class="hx-blog-card__time">%3$s<time datetime="%4$s">%5$s</time></span></div>',
		get_avatar( get_the_author_meta( 'ID' ), 28 ),
		esc_html( get_the_author() ),
		wp_kses( hooshinex_get_icon( 'clock' ), hooshinex_svg_allowed_html() ),
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Print the post thumbnail with a sensible default size.
 *
 * @param string $size Image size.
 * @return void
 */
function hooshinex_post_thumbnail( $size = 'large' ) {

	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	echo '<figure class="post-thumbnail">';

	if ( is_singular() ) {
		the_post_thumbnail( $size );
	} else {
		printf(
			'<a href="%s" aria-hidden="true" tabindex="-1">%s</a>',
			esc_url( get_permalink() ),
			get_the_post_thumbnail( null, $size )
		);
	}

	echo '</figure>';
}

/**
 * Print the archive/singular page header when Elementor is not handling it.
 *
 * @return void
 */
function hooshinex_page_header() {

	echo '<header class="page-header hx-container">';

	if ( is_singular() ) {
		the_title( '<h1 class="entry-title">', '</h1>' );
	} else {
		the_archive_title( '<h1 class="entry-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
	}

	echo '</header>';
}

/**
 * Section header: title (with a highlighted word), divider and a "view all" link.
 *
 * @param array $args Title, accent, link URL and link label.
 * @return void
 */
function hooshinex_section_header( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'title'      => '',
			'accent'     => '',
			'link'       => '',
			'link_label' => esc_html__( 'مشاهده همه', 'hooshinex' ),
			'tag'        => 'h2',
		)
	);

	$tag = in_array( $args['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $args['tag'] : 'h2';

	echo '<div class="hx-section-header">';

	printf(
		'<%1$s class="hx-section-title">%2$s%3$s</%1$s>',
		esc_html( $tag ),
		$args['title'] ? '<span>' . esc_html( $args['title'] ) . '</span> ' : '',
		$args['accent'] ? '<span class="accent">' . esc_html( $args['accent'] ) . '</span>' : ''
	);

	echo '<span class="hx-section-divider" aria-hidden="true"></span>';

	if ( $args['link'] ) {
		printf(
			'<a class="hx-section-link" href="%1$s">%2$s%3$s</a>',
			esc_url( $args['link'] ),
			esc_html( $args['link_label'] ),
			wp_kses( hooshinex_get_icon( 'arrow' ), hooshinex_svg_allowed_html() )
		);
	}

	echo '</div>';
}

/**
 * Currency symbol used by the fallback markup.
 *
 * @return string
 */
function hooshinex_currency_symbol() {

	if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
		return wp_strip_all_tags( get_woocommerce_currency_symbol() );
	}

	return esc_html__( 'تومان', 'hooshinex' );
}

/**
 * The list of trending search terms shown in the empty search panel.
 *
 * @return array
 */
function hooshinex_trending_searches() {

	$raw = get_theme_mod( 'hooshinex_trending_searches', 'هوش مصنوعی, ووکامرس, درگاه بانکی, صفحه ساز, سئو' );
	$out = array();

	foreach ( explode( ',', (string) $raw ) as $term ) {
		$term = trim( $term );

		if ( '' !== $term ) {
			$out[] = $term;
		}
	}

	return array_slice( $out, 0, 8 );
}

/**
 * Convert ASCII digits to Persian digits for display.
 *
 * @param string $value Any string.
 * @return string
 */
function hooshinex_fa_digits( $value ) {

	if ( ! apply_filters( 'hooshinex_persian_digits', 'fa_IR' === get_locale() ) ) {
		return (string) $value;
	}

	return str_replace(
		array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ),
		array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ),
		(string) $value
	);
}

/**
 * The URL of the shop landing page (WooCommerce shop, or the blog as a fallback).
 *
 * @return string
 */
function hooshinex_shop_url() {

	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'shop' );

		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/' );
}

/**
 * The URL of the account page (WooCommerce account, or wp-login).
 *
 * @return string
 */
function hooshinex_account_url() {

	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'myaccount' );

		if ( $url ) {
			return $url;
		}
	}

	return wp_login_url();
}

/**
 * Read the theme's registered social profiles.
 *
 * @return array List of [ 'icon', 'label', 'url' ].
 */
function hooshinex_social_links() {

	$networks = array(
		'telegram'  => esc_html__( 'تلگرام', 'hooshinex' ),
		'instagram' => esc_html__( 'اینستاگرام', 'hooshinex' ),
		'linkedin'  => esc_html__( 'لینکدین', 'hooshinex' ),
		'github'    => esc_html__( 'گیت‌هاب', 'hooshinex' ),
		'youtube'   => esc_html__( 'یوتیوب', 'hooshinex' ),
		'twitter'   => esc_html__( 'ایکس', 'hooshinex' ),
		'whatsapp'  => esc_html__( 'واتس‌اپ', 'hooshinex' ),
	);

	$links = array();

	foreach ( $networks as $key => $label ) {
		$url = get_theme_mod( 'hooshinex_social_' . $key, '' );

		if ( ! $url ) {
			continue;
		}

		$links[] = array(
			'icon'  => $key,
			'label' => $label,
			'url'   => $url,
		);
	}

	return $links;
}

/**
 * Render one product card in the Hooshinex design.
 *
 * Works with any WC_Product. Used by the fallback home page and by the widgets
 * plugin through the `hooshinex_render_product_card` function check.
 *
 * @param \WC_Product $product Product object.
 * @return void
 */
function hooshinex_render_product_card( $product ) {

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$id        = $product->get_id();
	$permalink = get_permalink( $id );
	$sales     = (int) get_post_meta( $id, 'total_sales', true );
	$rating    = (float) $product->get_average_rating();
	$percent   = 0;

	if ( $product->is_on_sale() ) {
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_sale_price();

		if ( $regular > 0 && $sale > 0 ) {
			$percent = (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
		}
	}

	$terms    = get_the_terms( $id, 'product_cat' );
	$cat_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	$image    = get_the_post_thumbnail(
		$id,
		'hooshinex-card',
		array(
			'loading' => 'lazy',
			'alt'     => the_title_attribute( array( 'echo' => false, 'post' => $id ) ),
		)
	);
	?>
	<article <?php wc_product_class( 'hx-product-card', $product ); ?>>

		<a class="hx-product-card__media" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<?php
			echo wp_kses_post( $image );

			if ( $percent > 0 ) {
				printf(
					'<span class="hx-badge-discount">%s</span>',
					sprintf(
						/* translators: %s: discount percentage */
						esc_html__( '%s٪ تخفیف', 'hooshinex' ),
						esc_html( hooshinex_fa_digits( $percent ) )
					)
				);
			}
			?>
		</a>

		<div class="hx-product-card__body">
			<h3 class="hx-product-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
			</h3>

			<?php if ( $cat_name ) : ?>
				<div class="hx-product-card__cat">
					<?php hooshinex_icon( 'layers' ); ?>
					<?php echo esc_html( $cat_name ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="hx-product-card__footer">
			<div class="hx-product-card__stats">
				<div class="hx-stat">
					<span class="hx-stat__num"><?php echo esc_html( hooshinex_fa_digits( $sales ) ); ?></span>
					<span class="hx-stat__label"><?php esc_html_e( 'فروش', 'hooshinex' ); ?></span>
				</div>
				<div class="hx-stat">
					<span class="hx-stat__num"><?php echo esc_html( hooshinex_fa_digits( number_format_i18n( $rating, 1 ) ) ); ?></span>
					<span class="hx-stat__label"><?php esc_html_e( 'امتیاز', 'hooshinex' ); ?></span>
				</div>
				<div class="hx-stat">
					<span class="hx-stat__num">A+</span>
					<span class="hx-stat__label"><?php esc_html_e( 'پشتیبانی', 'hooshinex' ); ?></span>
				</div>
			</div>

			<div class="hx-product-card__price">
				<?php
				if ( $percent > 0 ) {
					printf( '<span class="hx-price-off">%s٪</span>', esc_html( hooshinex_fa_digits( $percent ) ) );
				}

				echo wp_kses_post( $product->get_price_html() );
				?>
			</div>
		</div>

		<div class="hx-product-card__actions">
			<?php
			woocommerce_template_loop_add_to_cart(
				array(
					'class' => 'hx-buy-btn hx-add-to-cart',
				)
			);
			?>
			<a class="hx-preview-btn" href="<?php echo esc_url( $permalink ); ?>" data-tip="<?php esc_attr_e( 'مشاهده محصول', 'hooshinex' ); ?>" aria-label="<?php esc_attr_e( 'مشاهده محصول', 'hooshinex' ); ?>">
				<?php hooshinex_icon( 'eye' ); ?>
			</a>
		</div>

	</article>
	<?php
}

/**
 * Split rendered page content into question/answer pairs.
 *
 * Editors write FAQs the normal way — a heading followed by its answer — and this
 * turns that into an accordion, so no bespoke meta box or shortcode is needed.
 *
 * @param string $content Rendered page content.
 * @return array<int, array{question:string, answer:string}>
 */
function hooshinex_parse_faq( $content ) {

	$items = array();

	if ( ! $content || ! class_exists( 'DOMDocument' ) ) {
		return $items;
	}

	$document = new DOMDocument();

	libxml_use_internal_errors( true );
	$document->loadHTML( '<?xml encoding="utf-8" ?><div>' . $content . '</div>' );
	libxml_clear_errors();

	$body = $document->getElementsByTagName( 'div' )->item( 0 );

	if ( ! $body ) {
		return $items;
	}

	$current = null;

	foreach ( $body->childNodes as $node ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( XML_ELEMENT_NODE !== $node->nodeType ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			continue;
		}

		$tag = strtolower( $node->nodeName ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

		if ( in_array( $tag, array( 'h2', 'h3', 'h4' ), true ) ) {
			if ( $current ) {
				$items[] = $current;
			}

			$current = array(
				'question' => trim( $node->textContent ), // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				'answer'   => '',
			);

			continue;
		}

		if ( $current ) {
			$current['answer'] .= $document->saveHTML( $node );
		}
	}

	if ( $current ) {
		$items[] = $current;
	}

	$items = array_filter(
		$items,
		static function ( $item ) {
			return '' !== trim( $item['question'] ) && '' !== trim( wp_strip_all_tags( $item['answer'] ) );
		}
	);

	return array_values( $items );
}

/**
 * The FAQ shown before an editor writes their own.
 *
 * @return array<int, array{question:string, answer:string}>
 */
function hooshinex_default_faq() {

	$faq = array(
		array(
			'question' => __( 'بعد از خرید، محصول را چگونه دریافت می‌کنم؟', 'hooshinex' ),
			'answer'   => '<p>' . esc_html__( 'بلافاصله پس از پرداخت موفق، لینک دانلود در حساب کاربری شما و همچنین در ایمیل ثبت‌شده قرار می‌گیرد.', 'hooshinex' ) . '</p>',
		),
		array(
			'question' => __( 'بروزرسانی محصولات رایگان است؟', 'hooshinex' ),
			'answer'   => '<p>' . esc_html__( 'بله، تمام نسخه‌های بعدی محصولی که خریده‌اید از طریق همان حساب کاربری و بدون هزینه اضافه در دسترس شماست.', 'hooshinex' ) . '</p>',
		),
		array(
			'question' => __( 'پشتیبانی محصولات چطور انجام می‌شود؟', 'hooshinex' ),
			'answer'   => '<p>' . esc_html__( 'از طریق تیکت در حساب کاربری و کانال پشتیبانی، پاسخگوی سوالات فنی شما درباره نصب و پیکربندی محصول هستیم.', 'hooshinex' ) . '</p>',
		),
		array(
			'question' => __( 'امکان بازگشت وجه وجود دارد؟', 'hooshinex' ),
			'answer'   => '<p>' . esc_html__( 'برای محصولات دیجیتال، اگر محصول مطابق توضیحات نباشد و مشکل از سمت ما قابل رفع نباشد، مبلغ پرداختی بازگردانده می‌شود.', 'hooshinex' ) . '</p>',
		),
		array(
			'question' => __( 'می‌توانم محصولم را در سایت شما بفروشم؟', 'hooshinex' ),
			'answer'   => '<p>' . esc_html__( 'بله، از بخش همکاری در فروش درخواست خود را ثبت کنید تا کارشناسان ما محصول شما را بررسی کنند.', 'hooshinex' ) . '</p>',
		),
	);

	/**
	 * Filters the default FAQ entries.
	 *
	 * @param array $faq Question/answer pairs.
	 */
	return apply_filters( 'hooshinex_default_faq', $faq );
}
