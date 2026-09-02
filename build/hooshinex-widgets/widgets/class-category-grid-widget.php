<?php
/**
 * Category Grid widget.
 *
 * Product categories as icon tiles, either in a wrapping grid or a draggable rail.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Category grid.
 */
class Category_Grid_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-category-grid';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Category Grid', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-gallery-grid';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'category', 'taxonomy', 'product', 'grid', 'دسته‌بندی' );
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
				'label' => esc_html__( 'Categories', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'   => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'دسته‌بندی', 'hooshinex-widgets' ),
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
			'taxonomy',
			array(
				'label'   => esc_html__( 'Taxonomy', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => taxonomy_exists( 'product_cat' ) ? 'product_cat' : 'category',
				'options' => $this->get_taxonomy_options(),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Items', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 30,
				'default' => 12,
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide Empty', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Count', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => array(
					'carousel' => esc_html__( 'Carousel', 'hooshinex-widgets' ),
					'grid'     => esc_html__( 'Grid', 'hooshinex-widgets' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Tiles', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '6',
				'options'   => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-category-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
				'condition' => array( 'layout' => 'grid' ),
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
						'max' => 48,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-category-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hx-carousel-track'       => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-category-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Label Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-category-card' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Public taxonomies that make sense as tiles.
	 *
	 * @return array
	 */
	private function get_taxonomy_options(): array {

		$options    = array();
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->labels->singular_name;
		}

		return $options;
	}

	/**
	 * Frontend output.
	 *
	 * @return void
	 */
	protected function render(): void {

		$settings = $this->get_settings_for_display();
		$taxonomy = $settings['taxonomy'] ? $settings['taxonomy'] : 'category';

		if ( ! taxonomy_exists( $taxonomy ) ) {
			$this->editor_notice( esc_html__( 'That taxonomy does not exist on this site.', 'hooshinex-widgets' ) );
			return;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => 'yes' === $settings['hide_empty'],
				'number'     => max( 2, (int) $settings['limit'] ),
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);

		if ( is_wp_error( $terms ) || ! $terms ) {
			$this->editor_notice( esc_html__( 'No terms found for this taxonomy.', 'hooshinex-widgets' ) );
			return;
		}

		$this->section_header(
			array(
				'title'  => $settings['title'],
				'accent' => $settings['accent'],
				'tag'    => $this->safe_tag( $settings['title_tag'], 'h2' ),
			)
		);

		$is_carousel = 'carousel' === $settings['layout'];

		if ( $is_carousel ) {
			echo '<div class="hx-carousel" data-hx-carousel data-hx-autoplay="0"><div class="hx-carousel-viewport"><div class="hx-carousel-track hx-category-track" data-hx-track>';
		} else {
			echo '<div class="hooshinex-category-grid">';
		}

		foreach ( $terms as $term ) {
			$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			?>
			<a class="hx-category-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
				<span class="hx-category-icon">
					<?php
					if ( $thumb_id ) {
						echo wp_get_attachment_image( $thumb_id, 'thumbnail', false, array( 'alt' => esc_attr( $term->name ) ) );
					} else {
						$this->theme_icon( 'layers', array( 'stroke' => '1.6' ) );
					}
					?>
				</span>
				<span><?php echo esc_html( $term->name ); ?></span>
				<?php if ( 'yes' === $settings['show_count'] ) : ?>
					<span class="hx-category-count">
						<?php
						printf(
							/* translators: %s: item count */
							esc_html__( '%s مورد', 'hooshinex-widgets' ),
							esc_html( $this->digits( $term->count ) )
						);
						?>
					</span>
				<?php endif; ?>
			</a>
			<?php
		}

		if ( $is_carousel ) {
			echo '</div></div><div class="hx-dots" data-hx-dots></div></div>';
		} else {
			echo '</div>';
		}
	}
}
