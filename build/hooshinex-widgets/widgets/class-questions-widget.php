<?php
/**
 * Latest Questions widget.
 *
 * Turns the newest approved comments — product questions and blog replies — into a
 * carousel of cards, so the storefront always shows live activity.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Latest questions.
 */
class Questions_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-questions';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Latest Questions', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-comments';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'comments', 'questions', 'reviews', 'testimonial', 'پرسش' );
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
				'label' => esc_html__( 'Questions', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'   => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'آخرین', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'پرسش‌ها', 'hooshinex-widgets' ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h2' );

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Comments From', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'any',
				'options' => array(
					'any'     => esc_html__( 'Anywhere', 'hooshinex-widgets' ),
					'product' => esc_html__( 'Products only', 'hooshinex-widgets' ),
					'post'    => esc_html__( 'Blog posts only', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => esc_html__( 'Items', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 20,
				'default' => 8,
			)
		);

		$this->add_control(
			'words',
			array(
				'label'   => esc_html__( 'Excerpt Words', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 6,
				'max'     => 60,
				'default' => 22,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'   => esc_html__( 'Autoplay Delay (ms)', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 15000,
				'step'    => 500,
				'default' => 5000,
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
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 220,
						'max' => 600,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hx-question-card' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-question-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output.
	 *
	 * @return void
	 */
	protected function render(): void {

		$settings = $this->get_settings_for_display();

		$args = array(
			'status'      => 'approve',
			'number'      => max( 2, (int) $settings['limit'] ),
			'type'        => 'comment',
			'parent'      => 0,
			'post_status' => 'publish',
		);

		if ( 'any' !== $settings['post_type'] ) {
			$args['post_type'] = $settings['post_type'];
		}

		$comments = get_comments( $args );

		if ( ! $comments ) {
			$this->editor_notice( esc_html__( 'No approved comments yet.', 'hooshinex-widgets' ) );
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
		<div class="hx-carousel" data-hx-carousel data-hx-autoplay="<?php echo esc_attr( (int) $settings['autoplay'] ); ?>">

			<button type="button" class="hx-carousel-nav prev" data-hx-prev aria-label="<?php esc_attr_e( 'Previous', 'hooshinex-widgets' ); ?>">
				<?php $this->theme_icon( 'prev', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-carousel-viewport">
				<div class="hx-carousel-track hx-question-track" data-hx-track>
					<?php foreach ( $comments as $comment ) : ?>
						<article class="hx-question-card">

							<div class="hx-question-head">
								<span class="hx-question-avatar">
									<?php echo get_avatar( $comment, 44, '', '', array( 'class' => 'hx-avatar' ) ); ?>
								</span>
								<span class="hx-question-author">
									<strong><?php echo esc_html( $comment->comment_author ); ?></strong>
									<time datetime="<?php echo esc_attr( get_comment_date( 'c', $comment ) ); ?>">
										<?php
										printf(
											/* translators: %s: human readable time difference */
											esc_html__( '%s ago', 'hooshinex-widgets' ),
											esc_html( $this->digits( human_time_diff( get_comment_time( 'U', false, false, $comment ) ) ) )
										);
										?>
									</time>
								</span>
								<span class="hx-question-icon"><?php $this->theme_icon( 'chat', array( 'stroke' => '1.8' ) ); ?></span>
							</div>

							<p class="hx-question-text">
								<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $comment->comment_content ), (int) $settings['words'] ) ); ?>
							</p>

							<a class="hx-question-link" href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
								<?php echo esc_html( get_the_title( $comment->comment_post_ID ) ); ?>
								<?php $this->theme_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
							</a>

						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<button type="button" class="hx-carousel-nav next" data-hx-next aria-label="<?php esc_attr_e( 'Next', 'hooshinex-widgets' ); ?>">
				<?php $this->theme_icon( 'next', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-dots" data-hx-dots></div>
		</div>
		<?php
	}
}
