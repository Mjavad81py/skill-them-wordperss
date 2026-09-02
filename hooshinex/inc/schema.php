<?php
/**
 * JSON-LD structured data.
 *
 * Emits a single connected @graph (Google's recommended JSON-LD format) only when no
 * SEO plugin is already producing one. Two Organization nodes on a page is worse than
 * none: it fragments the entity graph and produces Search Console warnings.
 *
 * When WooCommerce is active, Product schema is deliberately NOT emitted here —
 * WooCommerce outputs its own structured data, and duplicating it causes conflicting
 * offers/price nodes.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the JSON-LD graph.
 *
 * @return void
 */
function hooshinex_json_ld() {

	if ( ! hooshinex_should_output_seo() ) {
		return;
	}

	$graph = array();

	$site_url      = home_url( '/' );
	$org_id        = $site_url . '#organization';
	$website_id    = $site_url . '#website';

	/* --------------------------------------------------------- Organization */

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $org_id,
		'name'  => get_bloginfo( 'name' ),
		'url'   => $site_url,
	);

	if ( has_custom_logo() ) {
		$logo_id  = (int) get_theme_mod( 'custom_logo' );
		$logo_src = wp_get_attachment_image_src( $logo_id, 'full' );

		if ( $logo_src ) {
			$organization['logo'] = array(
				'@type'  => 'ImageObject',
				'@id'    => $site_url . '#logo',
				'url'    => $logo_src[0],
				'width'  => $logo_src[1],
				'height' => $logo_src[2],
			);
			$organization['image'] = array( '@id' => $site_url . '#logo' );
		}
	}

	/**
	 * Filter the Organization node — add sameAs profiles, contactPoint, address.
	 *
	 * @param array $organization Organization node.
	 */
	$graph[] = apply_filters( 'hooshinex_schema_organization', $organization );

	/* -------------------------------------------------------------- WebSite */

	$website = array(
		'@type'     => 'WebSite',
		'@id'       => $website_id,
		'url'       => $site_url,
		'name'      => get_bloginfo( 'name' ),
		'publisher' => array( '@id' => $org_id ),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	// Sitelinks search box.
	$website['potentialAction'] = array(
		'@type'       => 'SearchAction',
		'target'      => array(
			'@type'       => 'EntryPoint',
			'urlTemplate' => $site_url . '?s={search_term_string}',
		),
		'query-input' => 'required name=search_term_string',
	);

	$graph[] = apply_filters( 'hooshinex_schema_website', $website );

	/* -------------------------------------------------------------- WebPage */

	$canonical = hooshinex_get_canonical_url();

	if ( $canonical ) {
		$webpage = array(
			'@type'      => 'WebPage',
			'@id'        => $canonical . '#webpage',
			'url'        => $canonical,
			'name'       => wp_get_document_title(),
			'isPartOf'   => array( '@id' => $website_id ),
			'inLanguage' => get_bloginfo( 'language' ),
		);

		$description = hooshinex_get_meta_description();
		if ( $description ) {
			$webpage['description'] = $description;
		}

		if ( is_singular() ) {
			$webpage['datePublished'] = get_the_date( DATE_W3C );
			$webpage['dateModified']  = get_the_modified_date( DATE_W3C );
		}

		$graph[] = apply_filters( 'hooshinex_schema_webpage', $webpage );
	}

	/* ------------------------------------------------------- BreadcrumbList */

	$breadcrumbs = hooshinex_get_breadcrumbs();

	if ( count( $breadcrumbs ) > 1 ) {

		$items = array();

		foreach ( $breadcrumbs as $position => $crumb ) {
			$item = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => $crumb['label'],
			);

			if ( ! empty( $crumb['url'] ) ) {
				$item['item'] = $crumb['url'];
			}

			$items[] = $item;
		}

		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => ( $canonical ? $canonical : $site_url ) . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	/* -------------------------------------------------------------- Article */

	if ( is_singular( 'post' ) ) {

		$author_id = (int) get_post_field( 'post_author', get_the_ID() );

		$article = array(
			'@type'            => 'BlogPosting',
			'@id'              => $canonical . '#article',
			'headline'         => wp_strip_all_tags( get_the_title() ),
			'datePublished'    => get_the_date( DATE_W3C ),
			'dateModified'     => get_the_modified_date( DATE_W3C ),
			'mainEntityOfPage' => array( '@id' => $canonical . '#webpage' ),
			'publisher'        => array( '@id' => $org_id ),
			'author'           => array(
				'@type' => 'Person',
				'@id'   => get_author_posts_url( $author_id ) . '#author',
				'name'  => get_the_author_meta( 'display_name', $author_id ),
				'url'   => get_author_posts_url( $author_id ),
			),
		);

		$description = hooshinex_get_meta_description();
		if ( $description ) {
			$article['description'] = $description;
		}

		if ( has_post_thumbnail() ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			if ( $img ) {
				$article['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $img[0],
					'width'  => $img[1],
					'height' => $img[2],
				);
			}
		}

		$terms = get_the_terms( get_the_ID(), 'category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$article['articleSection'] = wp_list_pluck( $terms, 'name' );
		}

		$graph[] = apply_filters( 'hooshinex_schema_article', $article );
	}

	/**
	 * Filter the entire JSON-LD graph before output.
	 *
	 * @param array $graph Schema graph nodes.
	 */
	$graph = apply_filters( 'hooshinex_schema_graph', $graph );

	if ( empty( $graph ) ) {
		return;
	}

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( $graph ),
	);

	printf(
		"<script type=\"application/ld+json\">%s</script>\n",
		wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'hooshinex_json_ld', 10 );

/**
 * Build the breadcrumb trail for the current view.
 *
 * Shared by the JSON-LD graph and the visible breadcrumb template tag, so the
 * markup and the structured data can never drift apart.
 *
 * @return array List of array{label:string,url:string} items.
 */
function hooshinex_get_breadcrumbs() {

	$crumbs = array(
		array(
			'label' => esc_html__( 'Home', 'hooshinex' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_front_page() ) {
		return $crumbs;
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {

		$shop_id = wc_get_page_id( 'shop' );
		if ( $shop_id > 0 ) {
			$crumbs[] = array(
				'label' => get_the_title( $shop_id ),
				'url'   => get_permalink( $shop_id ),
			);
		}
		return $crumbs;
	}

	if ( is_singular() ) {

		$post_id   = get_queried_object_id();
		$post_type = get_post_type( $post_id );

		// WooCommerce products sit under the shop page.
		if ( 'product' === $post_type && function_exists( 'wc_get_page_id' ) ) {
			$shop_id = wc_get_page_id( 'shop' );
			if ( $shop_id > 0 ) {
				$crumbs[] = array(
					'label' => get_the_title( $shop_id ),
					'url'   => get_permalink( $shop_id ),
				);
			}
		} elseif ( 'post' === $post_type ) {
			$terms = get_the_terms( $post_id, 'category' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$term     = array_shift( $terms );
				$crumbs[] = array(
					'label' => $term->name,
					'url'   => get_term_link( $term ),
				);
			}
		}

		// Page hierarchy.
		foreach ( array_reverse( (array) get_post_ancestors( $post_id ) ) as $ancestor_id ) {
			$crumbs[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => get_permalink( $ancestor_id ),
			);
		}

		$crumbs[] = array(
			'label' => get_the_title( $post_id ),
			'url'   => '',
		);

	} elseif ( is_category() || is_tag() || is_tax() ) {

		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) ) as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, $term->taxonomy );
				if ( $ancestor && ! is_wp_error( $ancestor ) ) {
					$crumbs[] = array(
						'label' => $ancestor->name,
						'url'   => get_term_link( $ancestor ),
					);
				}
			}

			$crumbs[] = array(
				'label' => $term->name,
				'url'   => '',
			);
		}
	} elseif ( is_search() ) {
		$crumbs[] = array(
			'label' => sprintf(
				/* translators: %s: search query */
				esc_html__( 'Search: %s', 'hooshinex' ),
				get_search_query()
			),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$crumbs[] = array(
			'label' => esc_html__( 'Not Found', 'hooshinex' ),
			'url'   => '',
		);
	} elseif ( is_archive() ) {
		$crumbs[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	}

	/**
	 * Filter the breadcrumb trail.
	 *
	 * @param array $crumbs Breadcrumb items.
	 */
	return apply_filters( 'hooshinex_breadcrumbs', $crumbs );
}

/**
 * Print a visible breadcrumb navigation.
 *
 * The JSON-LD BreadcrumbList is emitted separately from the same data source, so no
 * microdata attributes are needed here.
 *
 * @return void
 */
function hooshinex_breadcrumbs() {

	$crumbs = hooshinex_get_breadcrumbs();

	if ( count( $crumbs ) < 2 ) {
		return;
	}

	printf(
		'<nav class="breadcrumbs" aria-label="%s"><ol class="breadcrumbs__list">',
		esc_attr__( 'Breadcrumb', 'hooshinex' )
	);

	$last = count( $crumbs ) - 1;

	foreach ( $crumbs as $index => $crumb ) {

		echo '<li class="breadcrumbs__item">';

		if ( ! empty( $crumb['url'] ) && $index !== $last ) {
			printf(
				'<a href="%s">%s</a>',
				esc_url( $crumb['url'] ),
				esc_html( $crumb['label'] )
			);
		} else {
			printf(
				'<span aria-current="page">%s</span>',
				esc_html( $crumb['label'] )
			);
		}

		echo '</li>';
	}

	echo '</ol></nav>';
}
