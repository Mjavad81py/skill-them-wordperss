<?php
/**
 * Post Loop widget.
 *
 * Reference implementation for query-driven, dynamic widgets: query controls,
 * WP_Query in render(), editor-aware empty states, and no content_template()
 * because the output depends on PHP-only data.
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
 * Post Loop.
 */
class Post_Loop_Widget extends Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-post-loop';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Post Loop', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-posts-grid';
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
		return array( 'posts', 'loop', 'blog', 'query', 'archive', 'grid' );
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
	 * Output depends on live data, so skip Elementor's element cache.
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
				'label' => esc_html__( 'Query', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Source', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_type_options(),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Items', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 50,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'          => esc_html__( 'Date', 'hooshinex-widgets' ),
					'title'         => esc_html__( 'Title', 'hooshinex-widgets' ),
					'menu_order'    => esc_html__( 'Menu Order', 'hooshinex-widgets' ),
					'comment_count' => esc_html__( 'Comment Count', 'hooshinex-widgets' ),
					'rand'          => esc_html__( 'Random', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => esc_html__( 'Descending', 'hooshinex-widgets' ),
					'ASC'  => esc_html__( 'Ascending', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'exclude_current',
			array(
				'label'        => esc_html__( 'Exclude Current Post', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
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
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .hooshinex-loop' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
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
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-loop' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => esc_html__( 'Show Image', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'medium_large',
				'condition' => array( 'show_thumbnail' => 'yes' ),
			)
		);

		$this->add_control(
			'show_meta',
			array(
				'label'        => esc_html__( 'Show Date', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => esc_html__( 'Show Excerpt', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => esc_html__( 'Excerpt Words', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 5,
				'max'       => 100,
				'condition' => array( 'show_excerpt' => 'yes' ),
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
			'item_bg',
			array(
				'label'     => esc_html__( 'Item Background', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-loop__item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-loop__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-loop__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hooshinex-loop__title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Build the list of selectable public post types.
	 *
	 * @return array
	 */
	private function get_post_type_options(): array {

		$options = array();

		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}

			$options[ $post_type->name ] = $post_type->label;
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

		$args = array(
			'post_type'           => sanitize_key( $settings['post_type'] ),
			'posts_per_page'      => absint( $settings['posts_per_page'] ),
			'orderby'             => sanitize_key( $settings['orderby'] ),
			'order'               => ( 'ASC' === $settings['order'] ) ? 'ASC' : 'DESC',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( 'yes' === $settings['exclude_current'] && is_singular() ) {
			$args['post__not_in'] = array( get_the_ID() );
		}

		/**
		 * Filter the Post Loop query arguments.
		 *
		 * @param array $args     WP_Query arguments.
		 * @param array $settings Widget settings.
		 */
		$args = apply_filters( 'hooshinex_post_loop_query_args', $args, $settings );

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {

			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				printf(
					'<div class="hooshinex-notice">%s</div>',
					esc_html__( 'No items match this query. Adjust the Query settings.', 'hooshinex-widgets' )
				);
			}

			wp_reset_postdata();
			return;
		}

		echo '<div class="hooshinex-loop">';

		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<article <?php post_class( 'hooshinex-loop__item' ); ?>>

				<?php if ( 'yes' === $settings['show_thumbnail'] && has_post_thumbnail() ) : ?>
					<a class="hooshinex-loop__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php echo wp_kses_post( $this->get_thumbnail_html( $settings ) ); ?>
					</a>
				<?php endif; ?>

				<div class="hooshinex-loop__body">

					<h3 class="hooshinex-loop__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>

					<?php if ( 'yes' === $settings['show_meta'] ) : ?>
						<time class="hooshinex-loop__meta" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_excerpt'] ) : ?>
						<p class="hooshinex-loop__excerpt">
							<?php
							echo esc_html(
								wp_trim_words(
									get_the_excerpt(),
									absint( $settings['excerpt_length'] ),
									'&hellip;'
								)
							);
							?>
						</p>
					<?php endif; ?>

				</div>

			</article>
			<?php
		}

		echo '</div>';

		wp_reset_postdata();
	}

	/**
	 * Render the post thumbnail at the size chosen by the image-size group control.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_thumbnail_html( array $settings ): string {

		$settings['thumbnail_size'] = isset( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'medium_large';

		$image = array(
			'id' => get_post_thumbnail_id(),
		);

		return Group_Control_Image_Size::get_attachment_image_html(
			array_merge( $settings, array( 'thumbnail' => $image ) ),
			'thumbnail',
			'thumbnail'
		);
	}
}
