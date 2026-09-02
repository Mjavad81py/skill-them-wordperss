<?php
/**
 * Live search endpoint powering the header search panel.
 *
 * Exposes a tiny read-only REST route instead of admin-ajax so the response can be
 * cached by any HTTP layer and never touches the session.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the route.
 *
 * @return void
 */
function hooshinex_register_search_route() {

	register_rest_route(
		'hooshinex/v1',
		'/search',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'hooshinex_rest_search',
			'permission_callback' => '__return_true',
			'args'                => array(
				'q' => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'hooshinex_register_search_route' );

/**
 * Return up to eight matching products, posts and product categories.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function hooshinex_rest_search( $request ) {

	$term = trim( (string) $request->get_param( 'q' ) );

	if ( mb_strlen( $term ) < 2 ) {
		return rest_ensure_response( array( 'results' => array() ) );
	}

	$results    = array();
	$post_types = array( 'post' );

	if ( class_exists( 'WooCommerce' ) ) {
		array_unshift( $post_types, 'product' );
	}

	$query = new WP_Query(
		array(
			'post_type'              => $post_types,
			'post_status'            => 'publish',
			's'                      => $term,
			'posts_per_page'         => 6,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		)
	);

	foreach ( $query->posts as $post ) {

		$item = array(
			'title' => get_the_title( $post ),
			'url'   => get_permalink( $post ),
			'meta'  => '',
			'price' => '',
			'thumb' => get_the_post_thumbnail_url( $post, 'hooshinex-thumb' ),
			'kind'  => $post->post_type,
		);

		if ( 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $post->ID );

			if ( $product ) {
				$item['price'] = wp_strip_all_tags( $product->get_price_html() );
			}

			$terms = get_the_terms( $post->ID, 'product_cat' );

			if ( $terms && ! is_wp_error( $terms ) ) {
				$item['meta'] = $terms[0]->name;
			}
		} else {
			$item['meta'] = esc_html__( 'مقاله', 'hooshinex' );
		}

		$results[] = $item;
	}

	wp_reset_postdata();

	// Matching product categories round out the panel.
	if ( taxonomy_exists( 'product_cat' ) && count( $results ) < 8 ) {

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'name__like' => $term,
				'number'     => 8 - count( $results ),
				'hide_empty' => true,
			)
		);

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $tax_term ) {
				$results[] = array(
					'title' => $tax_term->name,
					'url'   => get_term_link( $tax_term ),
					'meta'  => esc_html__( 'دسته‌بندی', 'hooshinex' ),
					'price' => '',
					'thumb' => '',
					'kind'  => 'category',
				);
			}
		}
	}

	return rest_ensure_response( array( 'results' => array_slice( $results, 0, 8 ) ) );
}
