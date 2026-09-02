<?php
/**
 * Home: newest products carousel.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_template_part(
	'template-parts/home/product-carousel',
	null,
	array(
		'title'      => __( 'جدیدترین', 'hooshinex' ),
		'accent'     => __( 'محصولات', 'hooshinex' ),
		'source'     => 'recent',
		'limit'      => 10,
		'link_label' => __( 'همه محصولات', 'hooshinex' ),
		'autoplay'   => 4000,
		'id'         => 'featured',
	)
);
