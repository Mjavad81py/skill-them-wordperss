<?php
/**
 * Plugin bootstrap: compatibility checks and Elementor registration.
 *
 * @package HooshinexWidgets
 */

namespace Hooshinex\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
final class Plugin {

	const MINIMUM_ELEMENTOR_VERSION = '3.5.0';
	const MINIMUM_PHP_VERSION       = '7.4';

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );

		if ( ! $this->is_compatible() ) {
			return;
		}

		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'hooshinex-widgets', false, dirname( plugin_basename( HOOSHINEX_WIDGETS_FILE ) ) . '/languages' );
	}

	/**
	 * Verify the environment before touching any Elementor class.
	 *
	 * @return bool
	 */
	public function is_compatible() {

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_missing_elementor' ) );
			return false;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_minimum_elementor_version' ) );
			return false;
		}

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_minimum_php_version' ) );
			return false;
		}

		return true;
	}

	/**
	 * Hook into Elementor once it is safely loaded.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_dynamic_tags' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Add a dedicated panel category so the project's widgets are findable.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 * @return void
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'hooshinex',
			array(
				'title' => esc_html__( 'Hooshinex', 'hooshinex-widgets' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register every widget shipped by this plugin.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {

		// Every storefront widget extends this, so it has to be loaded first.
		require_once HOOSHINEX_WIDGETS_DIR . 'widgets/class-base-widget.php';

		$widgets = array(
			'class-feature-cards-widget.php'   => __NAMESPACE__ . '\\Widgets\\Feature_Cards_Widget',
			'class-post-loop-widget.php'       => __NAMESPACE__ . '\\Widgets\\Post_Loop_Widget',
			'class-section-heading-widget.php' => __NAMESPACE__ . '\\Widgets\\Section_Heading_Widget',
			'class-hero-widget.php'            => __NAMESPACE__ . '\\Widgets\\Hero_Widget',
			'class-category-grid-widget.php'   => __NAMESPACE__ . '\\Widgets\\Category_Grid_Widget',
			'class-promo-banners-widget.php'   => __NAMESPACE__ . '\\Widgets\\Promo_Banners_Widget',
			'class-offer-banner-widget.php'    => __NAMESPACE__ . '\\Widgets\\Offer_Banner_Widget',
			'class-questions-widget.php'       => __NAMESPACE__ . '\\Widgets\\Questions_Widget',
			'class-seller-cta-widget.php'      => __NAMESPACE__ . '\\Widgets\\Seller_Cta_Widget',
		);

		// Shop widgets only exist when there is a shop.
		if ( class_exists( 'WooCommerce' ) ) {
			$widgets['class-product-grid-widget.php']     = __NAMESPACE__ . '\\Widgets\\Product_Grid_Widget';
			$widgets['class-product-carousel-widget.php'] = __NAMESPACE__ . '\\Widgets\\Product_Carousel_Widget';
			$widgets['class-amazing-product-widget.php']  = __NAMESPACE__ . '\\Widgets\\Amazing_Product_Widget';
		}

		foreach ( $widgets as $file => $class ) {
			$path = HOOSHINEX_WIDGETS_DIR . 'widgets/' . $file;

			if ( ! file_exists( $path ) ) {
				continue;
			}

			require_once $path;

			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}

	/**
	 * Register the dynamic tag group and its tags.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Dynamic tags manager.
	 * @return void
	 */
	public function register_dynamic_tags( $dynamic_tags_manager ) {

		$dynamic_tags_manager->register_group(
			'hooshinex',
			array(
				'title' => esc_html__( 'Hooshinex', 'hooshinex-widgets' ),
			)
		);

		$tags = array(
			'class-reading-time-tag.php' => __NAMESPACE__ . '\\DynamicTags\\Reading_Time_Tag',
		);

		foreach ( $tags as $file => $class ) {
			$path = HOOSHINEX_WIDGETS_DIR . 'dynamic-tags/' . $file;

			if ( ! file_exists( $path ) ) {
				continue;
			}

			require_once $path;

			if ( class_exists( $class ) ) {
				$dynamic_tags_manager->register( new $class() );
			}
		}
	}

	/**
	 * Register (not enqueue) styles so widgets can request them conditionally.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'hooshinex-widgets',
			HOOSHINEX_WIDGETS_URL . 'assets/css/widgets.css',
			array(),
			HOOSHINEX_WIDGETS_VERSION
		);
	}

	/**
	 * Register (not enqueue) scripts so widgets can request them conditionally.
	 *
	 * @return void
	 */
	public function register_scripts() {
		wp_register_script(
			'hooshinex-widgets',
			HOOSHINEX_WIDGETS_URL . 'assets/js/widgets.js',
			array( 'jquery' ),
			HOOSHINEX_WIDGETS_VERSION,
			true
		);
	}

	/**
	 * Print an admin notice.
	 *
	 * @param string $message Notice text.
	 * @return void
	 */
	private function print_notice( $message ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', esc_html( $message ) );
	}

	/**
	 * Notice: Elementor not installed.
	 *
	 * @return void
	 */
	public function notice_missing_elementor() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: required plugin */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'hooshinex-widgets' ),
				esc_html__( 'Hooshinex Widgets', 'hooshinex-widgets' ),
				esc_html__( 'Elementor', 'hooshinex-widgets' )
			)
		);
	}

	/**
	 * Notice: Elementor too old.
	 *
	 * @return void
	 */
	public function notice_minimum_elementor_version() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: required plugin, 3: version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'hooshinex-widgets' ),
				esc_html__( 'Hooshinex Widgets', 'hooshinex-widgets' ),
				esc_html__( 'Elementor', 'hooshinex-widgets' ),
				self::MINIMUM_ELEMENTOR_VERSION
			)
		);
	}

	/**
	 * Notice: PHP too old.
	 *
	 * @return void
	 */
	public function notice_minimum_php_version() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: version */
				esc_html__( '"%1$s" requires PHP version %2$s or greater.', 'hooshinex-widgets' ),
				esc_html__( 'Hooshinex Widgets', 'hooshinex-widgets' ),
				self::MINIMUM_PHP_VERSION
			)
		);
	}
}
