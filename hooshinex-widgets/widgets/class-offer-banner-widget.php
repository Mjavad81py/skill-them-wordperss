<?php
/**
 * Offer Banner widget.
 *
 * A promotional strip with an optional live countdown. The deadline can be typed in
 * or pulled from the soonest WooCommerce sale end date, so the banner keeps itself
 * honest without anyone editing the page.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Offer banner.
 */
class Offer_Banner_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-offer-banner';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Offer Banner', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-countdown';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'offer', 'sale', 'countdown', 'discount', 'تخفیف' );
	}

	/**
	 * The countdown reads the clock at render time.
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
				'label' => esc_html__( 'Content', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'badge',
			array(
				'label'   => esc_html__( 'Badge', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'پیشنهاد شگفت‌انگیز', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'تا ۲۰٪ تخفیف روی محصولات منتخب', 'hooshinex-widgets' ),
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);

		$this->add_tag_control( 'title_tag', 'h3' );

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'فرصت محدود است؛ محصول مورد نظرت را با بهترین قیمت بردار.', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'cta_text',
			array(
				'label'   => esc_html__( 'Button Text', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'مشاهده تخفیف‌ها', 'hooshinex-widgets' ),
			)
		);

		$this->add_control(
			'cta_link',
			array(
				'label' => esc_html__( 'Button Link', 'hooshinex-widgets' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_countdown',
			array(
				'label' => esc_html__( 'Countdown', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'countdown_source',
			array(
				'label'   => esc_html__( 'Deadline', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => esc_html__( 'No countdown', 'hooshinex-widgets' ),
					'custom' => esc_html__( 'Fixed date', 'hooshinex-widgets' ),
					'sale'   => esc_html__( 'Next WooCommerce sale end', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'deadline',
			array(
				'label'          => esc_html__( 'Date', 'hooshinex-widgets' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => array( 'enableTime' => true ),
				'condition'      => array( 'countdown_source' => 'custom' ),
			)
		);

		$this->add_control(
			'labels_heading',
			array(
				'label'     => esc_html__( 'Labels', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'countdown_source!' => 'none' ),
			)
		);

		foreach ( array(
			'days'    => esc_html__( 'روز', 'hooshinex-widgets' ),
			'hours'   => esc_html__( 'ساعت', 'hooshinex-widgets' ),
			'minutes' => esc_html__( 'دقیقه', 'hooshinex-widgets' ),
			'seconds' => esc_html__( 'ثانیه', 'hooshinex-widgets' ),
		) as $unit => $label ) {
			$this->add_control(
				'label_' . $unit,
				array(
					'label'     => ucfirst( $unit ),
					'type'      => Controls_Manager::TEXT,
					'default'   => $label,
					'condition' => array( 'countdown_source!' => 'none' ),
				)
			);
		}

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
				'name'     => 'banner_background',
				'selector' => '{{WRAPPER}} .hx-offer-banner',
			)
		);

		$this->add_responsive_control(
			'banner_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hx-offer-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'banner_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hx-offer-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-offer-title' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hx-offer-title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve the countdown deadline as a unix timestamp.
	 *
	 * @param array $settings Widget settings.
	 * @return int
	 */
	private function resolve_deadline( array $settings ): int {

		if ( 'custom' === $settings['countdown_source'] && ! empty( $settings['deadline'] ) ) {
			$timestamp = strtotime( get_gmt_from_date( $settings['deadline'] ) . ' UTC' );

			return $timestamp ? (int) $timestamp : 0;
		}

		if ( 'sale' !== $settings['countdown_source'] || ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
			return 0;
		}

		$deadline = 0;

		foreach ( array_slice( wc_get_product_ids_on_sale(), 0, 40 ) as $product_id ) {
			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			$date = $product->get_date_on_sale_to();

			if ( ! $date ) {
				continue;
			}

			$timestamp = $date->getTimestamp();

			if ( $timestamp > time() && ( ! $deadline || $timestamp < $deadline ) ) {
				$deadline = $timestamp;
			}
		}

		return $deadline;
	}

	/**
	 * Frontend output.
	 *
	 * @return void
	 */
	protected function render(): void {

		$settings = $this->get_settings_for_display();
		$tag      = $this->safe_tag( $settings['title_tag'] );
		$deadline = $this->resolve_deadline( $settings );

		if ( ! empty( $settings['cta_link']['url'] ) ) {
			$this->add_link_attributes( 'cta', $settings['cta_link'] );
			$this->add_render_attribute( 'cta', 'class', array( 'hx-btn-primary', 'hx-offer-cta' ) );
		}
		?>
		<div class="hx-offer-banner">

			<span class="hx-offer-glow" aria-hidden="true"></span>

			<div class="hx-offer-main">
				<?php if ( $settings['badge'] ) : ?>
					<span class="hx-offer-badge">
						<?php $this->theme_icon( 'tag', array( 'stroke' => '2' ) ); ?>
						<?php echo esc_html( $settings['badge'] ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $settings['title'] ) : ?>
					<<?php echo esc_html( $tag ); ?> class="hx-offer-title"><?php echo esc_html( $settings['title'] ); ?></<?php echo esc_html( $tag ); ?>>
				<?php endif; ?>

				<?php if ( $settings['description'] ) : ?>
					<p class="hx-offer-desc"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $deadline ) : ?>
				<div class="hx-countdown" data-hx-countdown="<?php echo esc_attr( $deadline ); ?>">
					<?php
					$units = array(
						'days'    => 'data-hx-cd-days',
						'hours'   => 'data-hx-cd-hours',
						'minutes' => 'data-hx-cd-minutes',
						'seconds' => 'data-hx-cd-seconds',
					);

					$first = true;

					foreach ( $units as $unit => $attribute ) :
						if ( ! $first ) :
							?>
							<span class="hx-countdown-sep">:</span>
							<?php
						endif;

						$first = false;
						?>
						<div class="hx-countdown-unit">
							<span class="num" <?php echo esc_attr( $attribute ); ?>><?php echo esc_html( $this->digits( '00' ) ); ?></span>
							<span class="label"><?php echo esc_html( $settings[ 'label_' . $unit ] ); ?></span>
						</div>
						<?php
					endforeach;
					?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $settings['cta_link']['url'] ) && $settings['cta_text'] ) : ?>
				<a <?php $this->print_render_attribute_string( 'cta' ); ?>>
					<?php echo esc_html( $settings['cta_text'] ); ?>
					<?php $this->theme_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
				</a>
			<?php endif; ?>

		</div>
		<?php
	}
}
