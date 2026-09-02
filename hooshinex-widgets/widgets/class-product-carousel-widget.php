<?php
/**
 * Product Carousel widget.
 *
 * A draggable rail of product cards, filtered by source (latest, featured, on sale,
 * best selling) and optionally by category.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Product carousel.
 */
class Product_Carousel_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-product-carousel';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Product Carousel', 'hooshinex-widgets' );
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
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'product', 'woocommerce', 'carousel', 'slider', 'shop', 'محصولات' );
	}

	/**
	 * Query-driven output must not be cached as static markup.
	 *
	 * @return bool
	 */
	public function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {

		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Products', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'   => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'جدیدترین', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'محصولات', 'hooshinex-widgets' ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h2' );

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => array(
					'recent'   => esc_html__( 'Latest', 'hooshinex-widgets' ),
					'featured' => esc_html__( 'Featured', 'hooshinex-widgets' ),
					'sale'     => esc_html__( 'On Sale', 'hooshinex-widgets' ),
					'best'     => esc_html__( 'Best Selling', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => esc_html__( 'Category', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_category_options(),
				'label_block' => true,
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Products', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 24,
				'default' => 10,
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Arrows', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => esc_html__( 'Dots', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'       => esc_html__( 'Autoplay Delay (ms)', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 15000,
				'step'        => 500,
				'default'     => 0,
				'description' => esc_html__( 'Zero disables autoplay. Autoplay always pauses on hover and is skipped for visitors who prefer reduced motion.', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label' => esc_html__( 'Header Link', 'hooshinex-widgets' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'     => esc_html__( 'Header Link Label', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'مشاهده همه', 'hooshinex-widgets' ),
				'condition' => array( 'link[url]!' => '' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Cards', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_width',
			array(
				'label'      => esc_html__( 'Card Width', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 180,
						'max' => 520,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hx-carousel-track > *' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 64,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hx-carousel-track' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product categories for the SELECT2 control.
	 *
	 * @return array
	 */
	private function get_category_options(): array {

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) || ! $terms ) {
			return array();
		}

		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->slug ] = $term->name;
		}

		return $options;
	}

	/**
	 * Build the product query.
	 *
	 * @param array $settings Widget settings.
	 * @return \WP_Query
	 */
	private function build_query( array $settings ): \WP_Query {

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 2, (int) $settings['limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$visibility = wc_get_product_visibility_term_ids();

			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $visibility['exclude-from-catalog'],
				'operator' => 'NOT IN',
			);

			if ( 'featured' === $settings['source'] ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $visibility['featured'],
				);
			}
		}

		if ( ! empty( $settings['category'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => (array) $settings['category'],
			);
		}

		if ( 'sale' === $settings['source'] && function_exists( 'wc_get_product_ids_on_sale' ) ) {
			$on_sale = wc_get_product_ids_on_sale();

			$args['post__in'] = $on_sale ? $on_sale : array( 0 );
		}

		if ( 'best' === $settings['source'] ) {
			$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		}

		return new \WP_Query( $args );
	}

	/**
	 * Frontend output.
	 *
	 * @return void
	 */
	protected function render(): void {

		if ( ! $this->has_woocommerce() ) {
			$this->editor_notice( esc_html__( 'WooCommerce is not active, so there are no products to show.', 'hooshinex-widgets' ) );
			return;
		}

		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings );

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			$this->editor_notice( esc_html__( 'No products matched this query.', 'hooshinex-widgets' ) );
			return;
		}

		$this->section_header(
			array(
				'title'      => $settings['title'],
				'accent'     => $settings['accent'],
				'tag'        => $this->safe_tag( $settings['title_tag'], 'h2' ),
				'link'       => $settings['link']['url'] ?? '',
				'link_label' => $settings['link_label'] ?? '',
			)
		);
		?>
		<div class="hx-carousel" data-hx-carousel data-hx-autoplay="<?php echo esc_attr( (int) $settings['autoplay'] ); ?>">

			<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<button type="button" class="hx-carousel-nav prev" data-hx-prev aria-label="<?php esc_attr_e( 'Previous', 'hooshinex-widgets' ); ?>">
					<?php $this->theme_icon( 'prev', array( 'stroke' => '2' ) ); ?>
				</button>
			<?php endif; ?>

			<div class="hx-carousel-viewport">
				<div class="hx-carousel-track" data-hx-track>
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();

						$product = wc_get_product( get_the_ID() );

						if ( ! $product ) {
							continue;
						}

						if ( function_exists( 'hooshinex_render_product_card' ) ) {
							hooshinex_render_product_card( $product );
							continue;
						}

						$this->render_fallback_card( $product );
					}
					?>
				</div>
			</div>

			<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<button type="button" class="hx-carousel-nav next" data-hx-next aria-label="<?php esc_attr_e( 'Next', 'hooshinex-widgets' ); ?>">
					<?php $this->theme_icon( 'next', array( 'stroke' => '2' ) ); ?>
				</button>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_dots'] ) : ?>
				<div class="hx-dots" data-hx-dots></div>
			<?php endif; ?>

		</div>
		<?php
		wp_reset_postdata();
	}

	/**
	 * Minimal card for when the Hooshinex theme is not the active theme.
	 *
	 * @param \WC_Product $product Product.
	 * @return void
	 */
	private function render_fallback_card( $product ): void {
		?>
		<article class="hx-product-card hooshinex-product-card">
			<a class="hx-product-card__media" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
				<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
			</a>
			<div class="hx-product-card__body">
				<h3 class="hx-product-card__title">
					<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
						<?php echo esc_html( $product->get_name() ); ?>
					</a>
				</h3>
			</div>
			<div class="hx-product-card__footer">
				<div class="hx-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			</div>
		</article>
		<?php
	}
}
