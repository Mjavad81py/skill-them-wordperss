<?php
/**
 * Hero widget.
 *
 * The storefront's opening block: two-tone headline, description, a search field
 * that posts to the site search, and a row of stats.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Control_Media;

defined( 'ABSPATH' ) || exit;

/**
 * Hero.
 */
class Hero_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-hero';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Hero', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-banner';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'hero', 'banner', 'header', 'intro', 'هیرو' );
	}

	/**
	 * No script needed.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
		return array();
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_stats_controls();
		$this->register_style_controls();
	}

	/**
	 * Content tab.
	 *
	 * @return void
	 */
	private function register_content_controls(): void {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'محصولات هوشمند', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'title_accent',
			array(
				'label'   => esc_html__( 'Accent Line', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'وردپرس و ووکامرس', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h1' );

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'افزونه، قالب، اسکریپت و ابزارهای مبتنی بر هوش مصنوعی؛ همه در یک جا.', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'show_search',
			array(
				'label'        => esc_html__( 'Search Field', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'search_placeholder',
			array(
				'label'     => esc_html__( 'Search Placeholder', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'جستجوی محصولات، افزونه‌ها، مقالات…', 'hooshinex-widgets' ),
				'condition' => array( 'show_search' => 'yes' ),
			)
		);

		$this->add_control(
			'search_products_only',
			array(
				'label'        => esc_html__( 'Search Products Only', 'hooshinex-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_search' => 'yes' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Illustration', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Stats repeater.
	 *
	 * @return void
	 */
	private function register_stats_controls(): void {

		$this->start_controls_section(
			'section_stats',
			array(
				'label' => esc_html__( 'Stats', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Number', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '۴+',
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'محصول اختصاصی', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => esc_html__( 'Stats', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ number }}} — {{{ label }}}',
				'default'     => array(
					array(
						'number' => '۴+',
						'label'  => esc_html__( 'محصول اختصاصی', 'hooshinex-widgets' ),
					),
					array(
						'number' => '۸',
						'label'  => esc_html__( 'عضو تیم', 'hooshinex-widgets' ),
					),
					array(
						'number' => '۲۴/۷',
						'label'  => esc_html__( 'پشتیبانی', 'hooshinex-widgets' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style tab.
	 *
	 * @return void
	 */
	private function register_style_controls(): void {

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-hero-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => esc_html__( 'Accent Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-hero-title .gold' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hx-hero-title',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-hero-desc' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'content_align',
			array(
				'label'     => esc_html__( 'Alignment', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'hooshinex-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'hooshinex-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
				),
				'default'   => 'start',
				'selectors' => array(
					'{{WRAPPER}} .hx-hero-content' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
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
		$tag      = $this->safe_tag( $settings['title_tag'], 'h1' );
		$image    = $settings['image']['url'] ?? '';
		?>
		<section class="hx-hero hooshinex-hero">
			<div class="hx-hero-inner">

				<div class="hx-hero-content">

					<?php if ( $settings['title'] || $settings['title_accent'] ) : ?>
						<<?php echo esc_html( $tag ); ?> class="hx-hero-title">
							<?php echo esc_html( $settings['title'] ); ?>
							<?php if ( $settings['title_accent'] ) : ?>
								<span class="gold"><?php echo esc_html( $settings['title_accent'] ); ?></span>
							<?php endif; ?>
						</<?php echo esc_html( $tag ); ?>>
					<?php endif; ?>

					<?php if ( $settings['description'] ) : ?>
						<p class="hx-hero-desc"><?php echo esc_html( $settings['description'] ); ?></p>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_search'] ) : ?>
						<form class="hx-hero-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<label class="screen-reader-text" for="hooshinex-hero-<?php echo esc_attr( $this->get_id() ); ?>">
								<?php esc_html_e( 'Search', 'hooshinex-widgets' ); ?>
							</label>
							<input type="search" id="hooshinex-hero-<?php echo esc_attr( $this->get_id() ); ?>" name="s"
								placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>">
							<?php if ( 'yes' === $settings['search_products_only'] && $this->has_woocommerce() ) : ?>
								<input type="hidden" name="post_type" value="product">
							<?php endif; ?>
							<button type="submit" aria-label="<?php esc_attr_e( 'Search', 'hooshinex-widgets' ); ?>">
								<?php $this->theme_icon( 'search', array( 'stroke' => '1.8' ) ); ?>
							</button>
						</form>
					<?php endif; ?>

					<?php if ( ! empty( $settings['stats'] ) ) : ?>
						<div class="hx-hero-stats">
							<?php foreach ( $settings['stats'] as $index => $stat ) : ?>
								<?php if ( $index > 0 ) : ?>
									<span class="hx-hero-stat-divider" aria-hidden="true"></span>
								<?php endif; ?>
								<div class="hx-hero-stat elementor-repeater-item-<?php echo esc_attr( $stat['_id'] ); ?>">
									<div class="num"><?php echo esc_html( $this->digits( $stat['number'] ) ); ?></div>
									<div class="label"><?php echo esc_html( $stat['label'] ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

				</div>

				<?php if ( $image ) : ?>
					<div class="hx-hero-visual">
						<img src="<?php echo esc_url( $image ); ?>"
							alt="<?php echo esc_attr( Control_Media::get_image_alt( $settings['image'] ) ); ?>"
							fetchpriority="high" decoding="async">
					</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
