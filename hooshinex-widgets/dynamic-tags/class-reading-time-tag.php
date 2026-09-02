<?php
/**
 * Reading Time dynamic tag.
 *
 * Reference implementation: a tag that can fill any text control anywhere in the
 * design, including inside Theme Builder templates.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets\DynamicTags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

/**
 * Estimated reading time for the current post.
 */
class Reading_Time_Tag extends Tag {

	/**
	 * Tag slug.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'hooshinex-reading-time';
	}

	/**
	 * Tag label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return esc_html__( 'Reading Time', 'hooshinex-widgets' );
	}

	/**
	 * Tag group.
	 *
	 * @return array
	 */
	public function get_group(): array {
		return array( 'hooshinex' );
	}

	/**
	 * Which control types this tag can fill.
	 *
	 * @return array
	 */
	public function get_categories(): array {
		return array( TagsModule::TEXT_CATEGORY );
	}

	/**
	 * Tag settings.
	 *
	 * @return void
	 */
	protected function register_controls(): void {

		$this->add_control(
			'wpm',
			array(
				'label'   => esc_html__( 'Words Per Minute', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 200,
				'min'     => 50,
				'max'     => 600,
			)
		);

		$this->add_control(
			'format',
			array(
				'label'   => esc_html__( 'Format', 'hooshinex-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'long',
				'options' => array(
					'long'   => esc_html__( '5 min read', 'hooshinex-widgets' ),
					'short'  => esc_html__( '5 min', 'hooshinex-widgets' ),
					'number' => esc_html__( '5', 'hooshinex-widgets' ),
				),
			)
		);
	}

	/**
	 * Print the tag value.
	 *
	 * @return void
	 */
	public function render(): void {

		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return;
		}

		$wpm     = max( 1, (int) $this->get_settings( 'wpm' ) );
		$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
		$words   = str_word_count( $content );
		$minutes = max( 1, (int) ceil( $words / $wpm ) );

		switch ( $this->get_settings( 'format' ) ) {

			case 'number':
				echo esc_html( (string) $minutes );
				break;

			case 'short':
				printf(
					/* translators: %d: number of minutes */
					esc_html( _n( '%d min', '%d min', $minutes, 'hooshinex-widgets' ) ),
					absint( $minutes )
				);
				break;

			default:
				printf(
					/* translators: %d: number of minutes */
					esc_html( _n( '%d min read', '%d min read', $minutes, 'hooshinex-widgets' ) ),
					absint( $minutes )
				);
				break;
		}
	}
}
