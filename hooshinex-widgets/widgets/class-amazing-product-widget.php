<?php
/**
 * Amazing Product widget.
 *
 * A spotlight for a handful of products: one large slide at a time, with a
 * thumbnail rail to jump between them.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Amazing product spotlight.
 */
class Amazing_Product_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-amazing-product';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Amazing Product', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-slides';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'product', 'spotlight', 'slider', 'featured', 'شگفت‌انگیز' );
	}

	/**
	 * Query-driven output.
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
			'section_content',
			array(
				'label' => esc_html__( 'Spotlight', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'   => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'محصول', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'شگفت‌انگیز', 'hooshinex-widgets' ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h2' );

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'best',
				'options' => array(
					'best'     => esc_html__( 'Best Selling', 'hooshinex-widgets' ),
					'featured' => esc_html__( 'Featured', 'hooshinex-widgets' ),
					'sale'     => esc_html__( 'On Sale', 'hooshinex-widgets' ),
					'manual'   => esc_html__( 'Specific products', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'products',
			array(
				'label'       => esc_html__( 'Products', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_product_options(),
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'     => esc_html__( 'Slides', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 2,
				'max'       => 10,
				'default'   => 5,
				'condition' => array( 'source!' => 'manual' ),
			)
		);

		$this->add_control(
			'kicker',
			array(
				'label'   => esc_html__( 'Slide Kicker', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'پرفروش‌ترین‌ها', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'cta_text',
			array(
				'label'   => esc_html__( 'Button Text', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'مشاهده و خرید', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'excerpt_words',
			array(
				'label'   => esc_html__( 'Excerpt Words', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 80,
				'default' => 28,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'slide_title_color',
			array(
				'label'     => esc_html__( 'Product Title Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-spotlight-title, {{WRAPPER}} .hx-spotlight-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slide_title_typography',
				'selector' => '{{WRAPPER}} .hx-spotlight-title',
			)
		);

		$this->add_responsive_control(
			'media_ratio',
			array(
				'label'     => esc_html__( 'Image Ratio', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '16 / 9',
				'options'   => array(
					'16 / 9' => '16:9',
					'4 / 3'  => '4:3',
					'3 / 2'  => '3:2',
					'1 / 1'  => '1:1',
				),
				'selectors' => array(
					'{{WRAPPER}} .hx-spotlight-media' => 'aspect-ratio: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * A modest list of products for the manual picker.
	 *
	 * @return array
	 */
	private function get_product_options(): array {

		if ( ! $this->has_woocommerce() ) {
			return array();
		}

		$products = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			)
		);

		$options = array();

		foreach ( $products as $product_id ) {
			$options[ $product_id ] = get_the_title( $product_id );
		}

		return $options;
	}

	/**
	 * Collect the products to display.
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	private function get_products( array $settings ): array {

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 2, (int) $settings['limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		);

		if ( 'manual' === $settings['source'] ) {
			if ( empty( $settings['products'] ) ) {
				return array();
			}

			$args['post__in']       = array_map( 'absint', (array) $settings['products'] );
			$args['orderby']        = 'post__in';
			$args['posts_per_page'] = count( $args['post__in'] );
		}

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

		if ( 'sale' === $settings['source'] && function_exists( 'wc_get_product_ids_on_sale' ) ) {
			$on_sale = wc_get_product_ids_on_sale();

			$args['post__in'] = $on_sale ? $on_sale : array( 0 );
		}

		if ( 'best' === $settings['source'] ) {
			$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		}

		$query    = new \WP_Query( $args );
		$products = array();

		foreach ( $query->posts as $post ) {
			$product = wc_get_product( $post->ID );

			if ( $product ) {
				$products[] = $product;
			}
		}

		wp_reset_postdata();

		return $products;
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
		$products = $this->get_products( $settings );

		if ( ! $products ) {
			$this->editor_notice( esc_html__( 'No products matched this query.', 'hooshinex-widgets' ) );
			return;
		}

		$this->section_header(
			array(
				'title'  => $settings['title'],
				'accent' => $settings['accent'],
				'tag'    => $this->safe_tag( $settings['title_tag'], 'h2' ),
			)
		);
		?>
		<div class="hx-spotlight" data-hx-spotlight>

			<div class="hx-spotlight-stage">
				<?php foreach ( $products as $index => $product ) : ?>
					<?php
					$permalink = get_permalink( $product->get_id() );
					$rating    = (float) $product->get_average_rating();
					$excerpt   = wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() );
					?>
					<article class="hx-spotlight-slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
						data-hx-slide="<?php echo esc_attr( $index ); ?>"
						<?php echo 0 === $index ? '' : 'aria-hidden="true"'; ?>>

						<div class="hx-spotlight-media">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<?php echo wp_kses_post( $product->get_image( 'large' ) ); ?>
							</a>

							<?php if ( $product->is_on_sale() ) : ?>
								<span class="hx-badge hx-badge--sale"><?php esc_html_e( 'تخفیف', 'hooshinex-widgets' ); ?></span>
							<?php endif; ?>
						</div>

						<div class="hx-spotlight-body">

							<?php if ( $settings['kicker'] ) : ?>
								<span class="hx-spotlight-kicker">
									<?php $this->theme_icon( 'star', array( 'fill' => 'currentColor' ) ); ?>
									<?php echo esc_html( $settings['kicker'] ); ?>
								</span>
							<?php endif; ?>

							<h3 class="hx-spotlight-title">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
							</h3>

							<?php if ( (int) $settings['excerpt_words'] > 0 && $excerpt ) : ?>
								<div class="hx-spotlight-excerpt">
									<?php echo esc_html( wp_trim_words( $excerpt, (int) $settings['excerpt_words'] ) ); ?>
								</div>
							<?php endif; ?>

							<div class="hx-spotlight-meta">
								<?php if ( $rating > 0 ) : ?>
									<span class="hx-rating">
										<?php $this->theme_icon( 'star', array( 'fill' => 'currentColor' ) ); ?>
										<?php echo esc_html( $this->digits( number_format_i18n( $rating, 1 ) ) ); ?>
									</span>
								<?php endif; ?>

								<span class="hx-spotlight-sales">
									<?php $this->theme_icon( 'download', array( 'stroke' => '2' ) ); ?>
									<?php
									printf(
										/* translators: %s: sales count */
										esc_html__( '%s sales', 'hooshinex-widgets' ),
										esc_html( $this->digits( (int) get_post_meta( $product->get_id(), 'total_sales', true ) ) )
									);
									?>
								</span>
							</div>

							<div class="hx-spotlight-footer">
								<div class="hx-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

								<?php if ( $settings['cta_text'] ) : ?>
									<a class="hx-btn-primary" href="<?php echo esc_url( $permalink ); ?>">
										<?php echo esc_html( $settings['cta_text'] ); ?>
										<?php $this->theme_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
									</a>
								<?php endif; ?>
							</div>

						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="hx-spotlight-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Choose a product', 'hooshinex-widgets' ); ?>">
				<?php foreach ( $products as $index => $product ) : ?>
					<button type="button" role="tab"
						class="hx-spotlight-thumb<?php echo 0 === $index ? ' is-active' : ''; ?>"
						data-hx-slide-to="<?php echo esc_attr( $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?>
						<span class="hx-spotlight-thumb-title"><?php echo esc_html( wp_trim_words( $product->get_name(), 4 ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

		</div>
		<?php
	}
}
