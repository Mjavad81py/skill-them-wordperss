<?php
/**
 * Section Heading widget.
 *
 * The two-tone "accent + title" heading with an optional link on the far side,
 * used above every list on the storefront.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Section heading.
 */
class Section_Heading_Widget extends Base_Widget {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-section-heading';
	}

	/**
	 * Panel label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Section Heading', 'hooshinex-widgets' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'eicon-heading';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords(): array {
		return array( 'heading', 'title', 'section', 'عنوان' );
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
				'label' => esc_html__( 'Heading', 'hooshinex-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'       => esc_html__( 'Accent Word', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'جدیدترین', 'hooshinex-widgets' ),
				'description' => esc_html__( 'Shown in the gold accent colour before the title.', 'hooshinex-widgets' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'محصولات', 'hooshinex-widgets' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_tag_control( 'title_tag', 'h2' );

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'hooshinex-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'     => esc_html__( 'Link Label', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'مشاهده همه', 'hooshinex-widgets' ),
				'condition' => array( 'link[url]!' => '' ),
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
			'accent_color',
			array(
				'label'     => esc_html__( 'Accent Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-section-title .accent' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Colour', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hx-section-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hx-section-title',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'hooshinex-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'hooshinex-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'        => array(
						'title' => esc_html__( 'Center', 'hooshinex-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'space-between' => array(
						'title' => esc_html__( 'Space Between', 'hooshinex-widgets' ),
						'icon'  => 'eicon-justify-space-between-h',
					),
				),
				'default'   => 'space-between',
				'selectors' => array(
					'{{WRAPPER}} .hx-section-header' => 'justify-content: {{VALUE}};',
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

		if ( empty( $settings['title'] ) && empty( $settings['accent'] ) ) {
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
	}
}
