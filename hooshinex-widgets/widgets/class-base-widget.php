<?php
/**
 * Shared base for every Hooshinex widget.
 *
 * Holds the panel wiring each widget repeats (category, conditional assets) plus a
 * few rendering helpers. The theme's markup and CSS are reused when the Hooshinex
 * theme is active; every helper degrades to plain markup otherwise, so a widget
 * dropped into a different theme still renders something sane.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Base widget.
 */
abstract class Base_Widget extends Widget_Base {

	/**
	 * Panel category.
	 *
	 * @return array
	 */
	public function get_categories(): array {
		return array( 'hooshinex' );
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
	 * Conditionally loaded scripts.
	 *
	 * The theme's app.js drives carousels and countdowns; when it is absent the
	 * plugin's own bundle takes over.
	 *
	 * @return array
	 */
	public function get_script_depends(): array {
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
	 * Output one of the theme's inline SVG icons, or nothing.
	 *
	 * @param string $name Icon key.
	 * @param array  $args Icon args.
	 * @return void
	 */
	protected function theme_icon( string $name, array $args = array() ): void {
		if ( function_exists( 'hooshinex_icon' ) ) {
			hooshinex_icon( $name, $args );
		}
	}

	/**
	 * A heading tag control, defaulting to h3 so widgets never fight the page's h1.
	 *
	 * @param string $id      Control id.
	 * @param string $default Default tag.
	 * @return void
	 */
	protected function add_tag_control( string $id = 'title_tag', string $default = 'h3' ): void {

		$this->add_control(
			$id,
			array(
				'label'   => esc_html__( 'HTML Tag', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => $default,
			)
		);
	}

	/**
	 * Validate a heading tag coming from settings.
	 *
	 * @param mixed  $tag      Raw setting.
	 * @param string $fallback Fallback tag.
	 * @return string
	 */
	protected function safe_tag( $tag, string $fallback = 'h3' ): string {

		$allowed = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );

		return in_array( $tag, $allowed, true ) ? $tag : $fallback;
	}

	/**
	 * Convert Latin digits to Persian when the theme offers that helper.
	 *
	 * @param string|int $value Value.
	 * @return string
	 */
	protected function digits( $value ): string {

		if ( function_exists( 'hooshinex_fa_digits' ) ) {
			return (string) hooshinex_fa_digits( $value );
		}

		return (string) $value;
	}

	/**
	 * Render the theme's section header, falling back to a plain heading.
	 *
	 * @param array $args Header args: title, accent, link, link_label, tag.
	 * @return void
	 */
	protected function section_header( array $args ): void {

		if ( function_exists( 'hooshinex_section_header' ) ) {
			hooshinex_section_header( $args );
			return;
		}

		if ( empty( $args['title'] ) && empty( $args['accent'] ) ) {
			return;
		}

		$tag = $this->safe_tag( $args['tag'] ?? 'h2', 'h2' );

		printf(
			'<div class="hx-section-header"><%1$s class="hx-section-title">%2$s%3$s</%1$s></div>',
			esc_html( $tag ),
			empty( $args['title'] ) ? '' : '<span>' . esc_html( $args['title'] ) . '</span> ',
			empty( $args['accent'] ) ? '' : '<span class="accent">' . esc_html( $args['accent'] ) . '</span>'
		);
	}

	/**
	 * A short "nothing to show" note, printed in the editor only.
	 *
	 * @param string $message Message.
	 * @return void
	 */
	protected function editor_notice( string $message ): void {

		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		printf(
			'<div class="hooshinex-widget-notice">%s</div>',
			esc_html( $message )
		);
	}

	/**
	 * Whether WooCommerce is available.
	 *
	 * @return bool
	 */
	protected function has_woocommerce(): bool {
		return class_exists( 'WooCommerce' ) && function_exists( 'wc_get_product' );
	}
}
