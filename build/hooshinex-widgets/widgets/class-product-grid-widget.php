<?php
/**
 * Product Grid widget.
 *
 * A WooCommerce-aware, SEO-conscious product loop. Only registers when WooCommerce
 * is active. Deliberately does NOT emit Product JSON-LD — WooCommerce already outputs
 * structured data for products, and a second Product node with a different @id creates
 * conflicting offers in Search Console.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Product Grid.
 */
class Product_Grid_Widget extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-product-grid';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Product Grid', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-woocommerce';
	}

	/**
	 * Panel category.
	 *
	 * @return array
	 */
	public function get_categories(): array {
		return array( 'hooshinex' );
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'product', 'products', 'shop', 'woocommerce', 'store', 'grid' );
	}

	/**
	 * Conditionally loaded styles.
	 *
	 * @return array
	 */
	public function get_style_depends(): array {
		return array( 'hooshinex-widgets' );
	}

	/**
	 * Live product data must not be cached.
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {

		/* --------------------------------------------------------------- Query */

		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Products', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => array(
					'recent'   => esc_html__( 'Recent Products', 'hooshinex-widgets' ),
					'featured' => esc_html__( 'Featured Products', 'hooshinex-widgets' ),
					'sale'     => esc_html__( 'On Sale', 'hooshinex-widgets' ),
					'best'     => esc_html__( 'Best Selling', 'hooshinex-widgets' ),
					'category' => esc_html__( 'By Category', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'product_category',
			array(
				'label'       => esc_html__( 'Category', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_product_categories(),
				'label_block' => true,
				'condition'   => array( 'source' => 'category' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Products', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'hide_out_of_stock',
			array(
				'label'        => esc_html__( 'Hide Out of Stock', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* -------------------------------------------------------------- Layout */

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'hooshinex-widgets' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .hooshinex-products' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-products' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'woocommerce_thumbnail',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Show Rating', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Show Price', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => esc_html__( 'Show Add to Cart', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'       => esc_html__( 'Title HTML Tag', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'div'  => 'div',
				),
				'description' => esc_html__( 'Keep product titles below the page H1 in the heading hierarchy.', 'hooshinex-widgets' ),
			)
		);

		$this->end_controls_section();

		/* --------------------------------------------------------------- Style */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Card Background', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-product' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Card Padding', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-product__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-product__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hooshinex-product__title',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-product__price' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Build the product category options list.
	 *
	 * @return array
	 */
	private function get_product_categories(): array {

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	/**
	 * Build WP_Query arguments from the widget settings.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	private function build_query_args( array $settings ): array {

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => absint( $settings['limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(), // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_query'          => array(), // phpcs:ignore WordPress.DB.SlowDBQuery
		);

		// Exclude hidden products from the catalog.
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'exclude-from-catalog',
			'operator' => 'NOT IN',
		);

		switch ( $settings['source'] ) {

			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
				break;

			case 'sale':
				$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
				break;

			case 'best':
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'category':
				if ( ! empty( $settings['product_category'] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => array_map( 'sanitize_title', (array) $settings['product_category'] ),
					);
				}
				break;
		}

		if ( 'yes' === $settings['hide_out_of_stock'] ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			);
		}

		return $args;
	}

	/**
	 * Frontend output.
	 *
	 * @return void
	 */
	protected function render(): void {

		if ( ! class_exists( 'WooCommerce' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				printf(
					'<div class="hooshinex-notice">%s</div>',
					esc_html__( 'WooCommerce is not active. Activate it to use this widget.', 'hooshinex-widgets' )
				);
			}
			return;
		}

		$settings = $this->get_settings_for_display();
		$query    = new \WP_Query( $this->build_query_args( $settings ) );

		if ( ! $query->have_posts() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				printf(
					'<div class="hooshinex-notice">%s</div>',
					esc_html__( 'No products match this query.', 'hooshinex-widgets' )
				);
			}
			wp_reset_postdata();
			return;
		}

		$allowed   = array( 'h2', 'h3', 'h4', 'h5', 'div' );
		$title_tag = in_array( $settings['title_tag'], $allowed, true ) ? $settings['title_tag'] : 'h3';

		echo '<div class="hooshinex-products">';

		while ( $query->have_posts() ) {
			$query->the_post();

			$product = wc_get_product( get_the_ID() );

			if ( ! $product instanceof \WC_Product ) {
				continue;
			}
			?>
			<div <?php wc_product_class( 'hooshinex-product', $product ); ?>>

				<a class="hooshinex-product__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php
					if ( has_post_thumbnail() ) {
						echo wp_kses_post(
							get_the_post_thumbnail(
								get_the_ID(),
								$settings['image_size'] ?? 'woocommerce_thumbnail',
								array( 'alt' => the_title_attribute( array( 'echo' => false ) ) )
							)
						);
					} else {
						echo wp_kses_post( wc_placeholder_img( $settings['image_size'] ?? 'woocommerce_thumbnail' ) );
					}

					if ( $product->is_on_sale() ) {
						printf(
							'<span class="hooshinex-product__badge">%s</span>',
							esc_html__( 'Sale', 'hooshinex-widgets' )
						);
					}
					?>
				</a>

				<div class="hooshinex-product__body">

					<<?php echo esc_html( $title_tag ); ?> class="hooshinex-product__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</<?php echo esc_html( $title_tag ); ?>>

					<?php if ( 'yes' === $settings['show_rating'] && wc_review_ratings_enabled() && $product->get_average_rating() ) : ?>
						<div class="hooshinex-product__rating">
							<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_price'] && $product->get_price_html() ) : ?>
						<div class="hooshinex-product__price">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
						<div class="hooshinex-product__cart">
							<?php woocommerce_template_loop_add_to_cart( array( 'product' => $product ) ); ?>
						</div>
					<?php endif; ?>

				</div>

			</div>
			<?php
		}

		echo '</div>';

		wp_reset_postdata();
	}
}
