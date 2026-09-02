<?php
/**
 * Promo Banners widget.
 *
 * Two (or more) linked promo tiles with an icon, kicker, title, description and CTA.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Promo banners.
 */
class Promo_Banners_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-promo-banners';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Promo Banners', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-image-box';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'banner', 'promo', 'cta', 'بنر' );
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
				'label' => esc_html__( 'Banners', 'hooshinex-widgets' ),
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
					'value'   => 'fas fa-plug',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'kicker',
			array(
				'label'   => esc_html__( 'Kicker', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'دسته‌بندی ویژه', 'hooshinex-widgets' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'افزونه وردپرس', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'محصولات آماده دانلود با پشتیبانی و بروزرسانی دائمی', 'hooshinex-widgets' ),
			)
		);

		$repeater->add_control(
			'cta',
			array(
				'label'   => esc_html__( 'CTA Text', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'مشاهده همه', 'hooshinex-widgets' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => esc_html__( 'Link', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'variant',
			array(
				'label'   => esc_html__( 'Variant', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                     => esc_html__( 'Navy', 'hooshinex-widgets' ),
					'hx-twin-banner--gold' => esc_html__( 'Gold', 'hooshinex-widgets' ),
				),
			)
		);

		$this->add_control(
			'banners',
			array(
				'label'       => esc_html__( 'Banners', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title'   => esc_html__( 'افزونه وردپرس', 'hooshinex-widgets' ),
						'variant' => '',
					),
					array(
						'title'   => esc_html__( 'اسکریپت PHP', 'hooshinex-widgets' ),
						'variant' => 'hx-twin-banner--gold',
					),
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

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '2',
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors' => array(
					'{{WRAPPER}} .hx-twin-banners' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
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
					'{{WRAPPER}} .hx-twin-banners' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hx-twin-title',
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

		if ( empty( $settings['banners'] ) ) {
			return;
		}
		?>
		<div class="hx-twin-banners">
			<?php
			foreach ( $settings['banners'] as $index => $item ) :

				$key = 'banner_' . $index;

				$this->add_render_attribute(
					$key,
					'class',
					array_filter(
						array(
							'hx-twin-banner',
							$item['variant'],
							'elementor-repeater-item-' . $item['_id'],
						)
					)
				);

				$is_link = ! empty( $item['link']['url'] );

				if ( $is_link ) {
					$this->add_link_attributes( $key, $item['link'] );
				}

				$element = $is_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $element ); ?> <?php $this->print_render_attribute_string( $key ); ?>>

					<?php if ( ! empty( $item['icon']['value'] ) ) : ?>
						<span class="hx-twin-icon" aria-hidden="true">
							<?php Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php endif; ?>

					<span class="hx-twin-text">
						<?php if ( $item['kicker'] ) : ?>
							<span class="hx-twin-kicker"><?php echo esc_html( $item['kicker'] ); ?></span>
						<?php endif; ?>

						<?php if ( $item['title'] ) : ?>
							<span class="hx-twin-title"><?php echo esc_html( $item['title'] ); ?></span>
						<?php endif; ?>

						<?php if ( $item['description'] ) : ?>
							<span class="hx-twin-desc"><?php echo esc_html( $item['description'] ); ?></span>
						<?php endif; ?>
					</span>

					<?php if ( $item['cta'] ) : ?>
						<span class="hx-twin-cta">
							<?php echo esc_html( $item['cta'] ); ?>
							<?php $this->theme_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
						</span>
					<?php endif; ?>

				</<?php echo esc_html( $element ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
