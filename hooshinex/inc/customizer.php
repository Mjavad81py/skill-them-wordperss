<?php
/**
 * Customizer options.
 *
 * Everything an editor might want to change on the fallback header/footer and the
 * home page sections lives here, so no string is hardcoded in a template.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register panels, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function hooshinex_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'hooshinex_panel',
		array(
			'title'    => esc_html__( 'تنظیمات قالب هوشینکس', 'hooshinex' ),
			'priority' => 20,
		)
	);

	/* ------------------------------------------------------------- Header */

	$wp_customize->add_section(
		'hooshinex_header',
		array(
			'title' => esc_html__( 'هدر', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_header_search',
		array(
			'default' => true,
			'type'    => 'checkbox',
			'label'   => esc_html__( 'نمایش جستجوی هوشمند در هدر', 'hooshinex' ),
			'section' => 'hooshinex_header',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_header_cart',
		array(
			'default' => true,
			'type'    => 'checkbox',
			'label'   => esc_html__( 'نمایش سبد خرید در هدر', 'hooshinex' ),
			'section' => 'hooshinex_header',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_trending_searches',
		array(
			'default'     => 'هوش مصنوعی, ووکامرس, درگاه بانکی, صفحه ساز, سئو',
			'type'        => 'text',
			'label'       => esc_html__( 'جستجوهای پرطرفدار (با کاما جدا کنید)', 'hooshinex' ),
			'section'     => 'hooshinex_header',
			'sanitize'    => 'sanitize_text_field',
		)
	);

	/* --------------------------------------------------------------- Hero */

	$wp_customize->add_section(
		'hooshinex_hero',
		array(
			'title' => esc_html__( 'بخش هیرو (صفحه اصلی)', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_hero_title',
		array(
			'default'  => 'محصولات هوشمند',
			'type'     => 'text',
			'label'    => esc_html__( 'عنوان', 'hooshinex' ),
			'section'  => 'hooshinex_hero',
			'sanitize' => 'sanitize_text_field',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_hero_title_accent',
		array(
			'default'  => 'وردپرس و ووکامرس',
			'type'     => 'text',
			'label'    => esc_html__( 'بخش طلایی عنوان', 'hooshinex' ),
			'section'  => 'hooshinex_hero',
			'sanitize' => 'sanitize_text_field',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_hero_desc',
		array(
			'default'  => 'هوشینکس با توسعه افزونه‌ها، قالب‌ها و محصولات مبتنی بر هوش مصنوعی، به شما کمک می‌کند کسب‌وکار آنلاین خود را سریع‌تر، ساده‌تر و حرفه‌ای‌تر توسعه دهید.',
			'type'     => 'textarea',
			'label'    => esc_html__( 'توضیح', 'hooshinex' ),
			'section'  => 'hooshinex_hero',
			'sanitize' => 'wp_kses_post',
		)
	);

	$wp_customize->add_setting(
		'hooshinex_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'hooshinex_hero_image',
			array(
				'label'     => esc_html__( 'تصویر هیرو', 'hooshinex' ),
				'section'   => 'hooshinex_hero',
				'mime_type' => 'image',
			)
		)
	);

	foreach ( array( 1, 2, 3 ) as $i ) {
		hooshinex_add_setting(
			$wp_customize,
			'hooshinex_hero_stat_' . $i . '_num',
			array(
				'default'  => array( 1 => '4+', 2 => '8', 3 => '24/7' )[ $i ],
				'type'     => 'text',
				/* translators: %d: stat index */
				'label'    => sprintf( esc_html__( 'آمار %d — عدد', 'hooshinex' ), $i ),
				'section'  => 'hooshinex_hero',
				'sanitize' => 'sanitize_text_field',
			)
		);

		hooshinex_add_setting(
			$wp_customize,
			'hooshinex_hero_stat_' . $i . '_label',
			array(
				'default'  => array( 1 => 'محصول اختصاصی', 2 => 'عضو تیم', 3 => 'پشتیبانی' )[ $i ],
				'type'     => 'text',
				/* translators: %d: stat index */
				'label'    => sprintf( esc_html__( 'آمار %d — برچسب', 'hooshinex' ), $i ),
				'section'  => 'hooshinex_hero',
				'sanitize' => 'sanitize_text_field',
			)
		);
	}

	/* ------------------------------------------------------------- Footer */

	$wp_customize->add_section(
		'hooshinex_footer',
		array(
			'title' => esc_html__( 'فوتر', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_footer_tagline',
		array(
			'default'  => 'نوآوری در وردپرس با هوش مصنوعی',
			'type'     => 'text',
			'label'    => esc_html__( 'شعار', 'hooshinex' ),
			'section'  => 'hooshinex_footer',
			'sanitize' => 'sanitize_text_field',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_footer_about',
		array(
			'default'  => 'هوشینکس یک شرکت توسعه نرم‌افزار است که بر طراحی و توسعه افزونه‌های وردپرس، قالب‌های وردپرس، اسکریپت‌های اختصاصی، سیستم‌های CRM و محصولات مبتنی بر هوش مصنوعی تمرکز دارد.',
			'type'     => 'textarea',
			'label'    => esc_html__( 'درباره ما', 'hooshinex' ),
			'section'  => 'hooshinex_footer',
			'sanitize' => 'wp_kses_post',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_footer_copyright',
		array(
			'default'  => '',
			'type'     => 'text',
			'label'    => esc_html__( 'متن کپی‌رایت (خالی = پیش‌فرض)', 'hooshinex' ),
			'section'  => 'hooshinex_footer',
			'sanitize' => 'sanitize_text_field',
		)
	);

	/* ------------------------------------------------------------ Contact */

	$wp_customize->add_section(
		'hooshinex_contact',
		array(
			'title' => esc_html__( 'اطلاعات تماس', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	$contact_fields = array(
		'phone'    => esc_html__( 'تلفن', 'hooshinex' ),
		'email'    => esc_html__( 'ایمیل', 'hooshinex' ),
		'telegram' => esc_html__( 'آیدی تلگرام پشتیبانی', 'hooshinex' ),
		'address'  => esc_html__( 'نشانی', 'hooshinex' ),
		'website'  => esc_html__( 'وب‌سایت', 'hooshinex' ),
	);

	foreach ( $contact_fields as $key => $label ) {
		hooshinex_add_setting(
			$wp_customize,
			'hooshinex_contact_' . $key,
			array(
				'default'  => '',
				'type'     => 'text',
				'label'    => $label,
				'section'  => 'hooshinex_contact',
				'sanitize' => 'sanitize_text_field',
			)
		);
	}

	/* ------------------------------------------------------------- Social */

	$wp_customize->add_section(
		'hooshinex_social',
		array(
			'title' => esc_html__( 'شبکه‌های اجتماعی', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	$networks = array(
		'telegram'  => esc_html__( 'تلگرام', 'hooshinex' ),
		'instagram' => esc_html__( 'اینستاگرام', 'hooshinex' ),
		'linkedin'  => esc_html__( 'لینکدین', 'hooshinex' ),
		'github'    => esc_html__( 'گیت‌هاب', 'hooshinex' ),
		'youtube'   => esc_html__( 'یوتیوب', 'hooshinex' ),
		'twitter'   => esc_html__( 'ایکس (توییتر)', 'hooshinex' ),
		'whatsapp'  => esc_html__( 'واتس‌اپ', 'hooshinex' ),
	);

	foreach ( $networks as $key => $label ) {
		hooshinex_add_setting(
			$wp_customize,
			'hooshinex_social_' . $key,
			array(
				'default'  => '',
				'type'     => 'url',
				'label'    => $label,
				'section'  => 'hooshinex_social',
				'sanitize' => 'esc_url_raw',
			)
		);
	}

	/* --------------------------------------------------------------- Shop */

	$wp_customize->add_section(
		'hooshinex_shop',
		array(
			'title' => esc_html__( 'فروشگاه', 'hooshinex' ),
			'panel' => 'hooshinex_panel',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_products_per_row',
		array(
			'default'  => 3,
			'type'     => 'number',
			'label'    => esc_html__( 'تعداد محصول در هر ردیف', 'hooshinex' ),
			'section'  => 'hooshinex_shop',
			'sanitize' => 'absint',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_products_per_page',
		array(
			'default'  => 12,
			'type'     => 'number',
			'label'    => esc_html__( 'تعداد محصول در هر صفحه', 'hooshinex' ),
			'section'  => 'hooshinex_shop',
			'sanitize' => 'absint',
		)
	);

	hooshinex_add_setting(
		$wp_customize,
		'hooshinex_shop_mode',
		array(
			'default' => true,
			'type'    => 'checkbox',
			'label'   => esc_html__( 'این سایت یک فروشگاه است (هشدار نبود ووکامرس نمایش داده شود)', 'hooshinex' ),
			'section' => 'hooshinex_shop',
		)
	);
}
add_action( 'customize_register', 'hooshinex_customize_register' );

/**
 * Small helper that pairs a setting with its control.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @param string               $id           Setting id.
 * @param array                $args         label, section, type, default, sanitize.
 * @return void
 */
function hooshinex_add_setting( $wp_customize, $id, $args ) {

	$args = wp_parse_args(
		$args,
		array(
			'default'  => '',
			'type'     => 'text',
			'label'    => '',
			'section'  => '',
			'sanitize' => 'sanitize_text_field',
		)
	);

	if ( 'checkbox' === $args['type'] ) {
		$args['sanitize'] = 'hooshinex_sanitize_checkbox';
	}

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize'],
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'label'   => $args['label'],
			'section' => $args['section'],
			'type'    => $args['type'],
		)
	);
}

/**
 * Sanitize a checkbox value.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function hooshinex_sanitize_checkbox( $value ) {
	return (bool) $value;
}
