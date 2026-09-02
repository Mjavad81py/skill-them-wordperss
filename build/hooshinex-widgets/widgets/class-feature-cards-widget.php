<?php
/**
 * Feature Cards widget.
 *
 * Reference implementation. Demonstrates: repeater controls, dynamic tags, responsive
 * controls, group controls, {{WRAPPER}} selectors, normal/hover tabs, conditional
 * controls, per-repeater-item styling, render attributes, inline editing, and a
 * matching Underscore.js editor template.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Feature Cards.
 */
class Feature_Cards_Widget extends Widget_Base {

	/**
	 * Widget slug. Permanent — changing it orphans existing instances.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-feature-cards';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Feature Cards', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-info-box';
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
		return array( 'feature', 'cards', 'services', 'icon', 'box', 'grid' );
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
	 * Leaner DOM.
	 *
	 * @return bool
	 */
	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls(): void {
		$this->register_content_controls();
		$this->register_layout_controls();
		$this->register_card_style_controls();
		$this->register_text_style_controls();
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
				'label' => esc_html__( 'Cards', 'hooshinex-widgets' ),
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
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Feature title', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'Describe the feature in a sentence or two.', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array( 'active' => true ),
			)
		);

		// Per-item accent, scoped by the repeater item id.
		$repeater->add_control(
			'accent_color',
			array(
				'label'     => esc_html__( 'Accent Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .hooshinex-card__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cards',
			array(
				'label'       => esc_html__( 'Cards', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => esc_html__( 'Fast', 'hooshinex-widgets' ) ),
					array( 'title' => esc_html__( 'Flexible', 'hooshinex-widgets' ) ),
					array( 'title' => esc_html__( 'Reliable', 'hooshinex-widgets' ) ),
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Layout controls.
	 *
	 * @return void
	 */
	private function register_layout_controls(): void {

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
					'{{WRAPPER}} .hooshinex-cards' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', 'em' ),
				'range'      => array(
					'px'  => array(
						'min' => 0,
						'max' => 100,
					),
					'rem' => array(
						'min' => 0,
						'max' => 8,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-cards' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'text_align',
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
					'end'    => array(
						'title' => esc_html__( 'End', 'hooshinex-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'start',
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card' => 'text-align: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Card style controls, with normal/hover states.
	 *
	 * @return void
	 */
	private function register_card_style_controls(): void {

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => esc_html__( 'Card', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'card_state_tabs' );

		$this->start_controls_tab(
			'card_state_normal',
			array( 'label' => esc_html__( 'Normal', 'hooshinex-widgets' ) )
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Background', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .hooshinex-card',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .hooshinex-card',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_state_hover',
			array( 'label' => esc_html__( 'Hover', 'hooshinex-widgets' ) )
		);

		$this->add_control(
			'card_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow_hover',
				'selector' => '{{WRAPPER}} .hooshinex-card:hover',
			)
		);

		$this->add_control(
			'card_lift',
			array(
				'label'      => esc_html__( 'Lift on Hover', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-card'       => 'transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;',
					'{{WRAPPER}} .hooshinex-card:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Icon and text style controls.
	 *
	 * @return void
	 */
	private function register_text_style_controls(): void {

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Icon & Text', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'hooshinex-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 32,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hooshinex-card__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .hooshinex-card__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card__icon'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .hooshinex-card__icon svg path' => 'fill: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hooshinex-card__title',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hooshinex-card__desc' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .hooshinex-card__desc',
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

		if ( empty( $settings['cards'] ) ) {
			return;
		}

		$allowed_tags = array( 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span' );
		$title_tag    = in_array( $settings['title_tag'], $allowed_tags, true ) ? $settings['title_tag'] : 'h3';

		$this->add_render_attribute( 'wrapper', 'class', 'hooshinex-cards' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			foreach ( $settings['cards'] as $index => $item ) :

				$card_key = 'card_' . $index;

				$this->add_render_attribute(
					$card_key,
					'class',
					array(
						'hooshinex-card',
						'elementor-repeater-item-' . $item['_id'],
					)
				);

				$has_link = ! empty( $item['link']['url'] );

				if ( $has_link ) {
					$link_key = 'link_' . $index;
					$this->add_link_attributes( $link_key, $item['link'] );
					$this->add_render_attribute( $link_key, 'class', 'hooshinex-card__link' );
				}
				?>
				<div <?php $this->print_render_attribute_string( $card_key ); ?>>

					<?php if ( ! empty( $item['icon']['value'] ) ) : ?>
						<span class="hooshinex-card__icon" aria-hidden="true">
							<?php \Elementor\Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php endif; ?>

					<?php if ( ! empty( $item['title'] ) ) : ?>
						<<?php echo esc_html( $title_tag ); ?> class="hooshinex-card__title">
							<?php if ( $has_link ) : ?>
								<a <?php $this->print_render_attribute_string( 'link_' . $index ); ?>>
									<?php echo esc_html( $item['title'] ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( $item['title'] ); ?>
							<?php endif; ?>
						</<?php echo esc_html( $title_tag ); ?>>
					<?php endif; ?>

					<?php if ( ! empty( $item['description'] ) ) : ?>
						<p class="hooshinex-card__desc"><?php echo esc_html( $item['description'] ); ?></p>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Editor live preview. Mirrors render().
	 *
	 * @return void
	 */
	protected function content_template(): void {
		?>
		<# var titleTag = settings.title_tag || 'h3'; #>
		<div class="hooshinex-cards">
			<# _.each( settings.cards, function( item ) {

				var iconHtml = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true }, 'i', 'object' );
			#>
				<div class="hooshinex-card elementor-repeater-item-{{ item._id }}">

					<# if ( item.icon && item.icon.value ) { #>
						<span class="hooshinex-card__icon" aria-hidden="true">{{{ iconHtml.value }}}</span>
					<# } #>

					<# if ( item.title ) { #>
						<{{{ titleTag }}} class="hooshinex-card__title">
							<# if ( item.link && item.link.url ) { #>
								<a class="hooshinex-card__link" href="{{ item.link.url }}">{{{ item.title }}}</a>
							<# } else { #>
								{{{ item.title }}}
							<# } #>
						</{{{ titleTag }}}>
					<# } #>

					<# if ( item.description ) { #>
						<p class="hooshinex-card__desc">{{{ item.description }}}</p>
					<# } #>

				</div>
			<# } ); #>
		</div>
		<?php
	}
}
