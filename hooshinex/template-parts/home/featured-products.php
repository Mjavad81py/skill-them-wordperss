<?php
/**
 * Home: featured products carousel.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_template_part(
	'template-parts/home/product-carousel',
	null,
	array(
		'title'         => __( 'محصولات', 'hooshinex' ),
		'accent'        => __( 'ویژه', 'hooshinex' ),
		'source'        => 'featured',
		'limit'         => 10,
		'section_class' => 'hx-section--muted',
		'link_label'    => __( 'فروشگاه', 'hooshinex' ),
		'id'            => 'products',
	)
);
