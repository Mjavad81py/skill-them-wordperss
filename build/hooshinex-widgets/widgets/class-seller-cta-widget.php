<?php
/**
 * Seller CTA widget.
 *
 * The "become a seller" panel: headline, description, two buttons and a list of
 * selling points.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Seller CTA.
 */
class Seller_Cta_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-seller-cta';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Seller CTA', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-call-to-action';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'cta', 'seller', 'vendor', 'call to action', 'فروشنده' );
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

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'kicker',
			array(
				'label'   => esc_html__( 'Kicker', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'همکاری با ما', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'فروشنده', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title_accent',
			array(
				'label'   => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'شوید', 'hooshinex-widgets' ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h2' );

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'محصول خود را منتشر کنید و از فروش آن درآمد کسب کنید. ثبت‌نام رایگان است.', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'primary_text',
			array(
				'label'     => esc_html__( 'Primary Button', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'شروع همکاری', 'hooshinex-widgets' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'primary_link',
			array(
				'label' => esc_html__( 'Primary Link', 'hooshinex-widgets' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'secondary_text',
			array(
				'label'   => esc_html__( 'Secondary Button', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'مشاوره رایگان', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'secondary_link',
			array(
				'label' => esc_html__( 'Secondary Link', 'hooshinex-widgets' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_perks',
			array(
				'label' => esc_html__( 'Selling Points', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'درآمد پایدار', 'hooshinex-widgets' ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'تسویه حساب دوره‌ای و شفاف برای همه فروش‌ها', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'perks',
			array(
				'label'       => esc_html__( 'Points', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => esc_html__( 'درآمد پایدار', 'hooshinex-widgets' ) ),
					array( 'title' => esc_html__( 'مخاطب آماده', 'hooshinex-widgets' ) ),
					array( 'title' => esc_html__( 'پشتیبانی فنی', 'hooshinex-widgets' ) ),
				),
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'panel_background',
				'selector' => '{{WRAPPER}} .hx-seller',
			)
		);

		$this->add_responsive_control(
			'panel_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hx-seller' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-seller-title' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hx-seller-title',
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
		$tag      = $this->safe_tag( $settings['title_tag'], 'h2' );

		if ( ! empty( $settings['primary_link']['url'] ) ) {
			$this->add_link_attributes( 'primary', $settings['primary_link'] );
			$this->add_render_attribute( 'primary', 'class', 'hx-btn-primary' );
		}

		if ( ! empty( $settings['secondary_link']['url'] ) ) {
			$this->add_link_attributes( 'secondary', $settings['secondary_link'] );
			$this->add_render_attribute( 'secondary', 'class', 'hx-btn-secondary' );
		}
		?>
		<div class="hx-seller">

			<span class="hx-seller-glow" aria-hidden="true"></span>

			<div class="hx-seller-content">

				<?php if ( $settings['kicker'] ) : ?>
					<span class="hx-seller-kicker">
						<?php $this->theme_icon( 'shop', array( 'stroke' => '2' ) ); ?>
						<?php echo esc_html( $settings['kicker'] ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $settings['title'] || $settings['title_accent'] ) : ?>
					<<?php echo esc_html( $tag ); ?> class="hx-seller-title">
						<?php echo esc_html( $settings['title'] ); ?>
						<?php if ( $settings['title_accent'] ) : ?>
							<span class="gold"><?php echo esc_html( $settings['title_accent'] ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				<?php endif; ?>

				<?php if ( $settings['description'] ) : ?>
					<p class="hx-seller-desc"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>

				<div class="hx-seller-actions">
					<?php if ( ! empty( $settings['primary_link']['url'] ) && $settings['primary_text'] ) : ?>
						<a <?php $this->print_render_attribute_string( 'primary' ); ?>>
							<?php echo esc_html( $settings['primary_text'] ); ?>
							<?php $this->theme_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
						</a>
					<?php endif; ?>

					<?php if ( ! empty( $settings['secondary_link']['url'] ) && $settings['secondary_text'] ) : ?>
						<a <?php $this->print_render_attribute_string( 'secondary' ); ?>>
							<?php echo esc_html( $settings['secondary_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

			<?php if ( ! empty( $settings['perks'] ) ) : ?>
				<ul class="hx-seller-perks">
					<?php foreach ( $settings['perks'] as $perk ) : ?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $perk['_id'] ); ?>">
							<?php if ( ! empty( $perk['icon']['value'] ) ) : ?>
								<span class="hx-seller-perk-icon" aria-hidden="true">
									<?php Icons_Manager::render_icon( $perk['icon'], array( 'aria-hidden' => 'true' ) ); ?>
								</span>
							<?php endif; ?>
							<span>
								<strong><?php echo esc_html( $perk['title'] ); ?></strong>
								<span><?php echo esc_html( $perk['description'] ); ?></span>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
		<?php
	}
}
