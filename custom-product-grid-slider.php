<?php
/**
 * Plugin Name: Custom Product Grid Slider
 * Description: A custom WordPress plugin to display WooCommerce products in a grid slider format for Elementor.
 * Version: 2.0.0
 * Author: Shohan Miah
 * Author URI: https://example.com
 * Text Domain: custom-product-grid-slider
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Custom Product Grid Slider Class
 */
final class Custom_Product_Grid_Slider {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const VERSION = '2.0.0';

	/**
	 * Instance
	 *
	 * @var Custom_Product_Grid_Slider
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Custom_Product_Grid_Slider An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Check for required plugins.
		if ( ! $this->is_compatible() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_dependencies' ) );
			return;
		}

		// Register Elementor widget.
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// AJAX handlers.
		add_action( 'wp_ajax_cpg_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_cpg_add_to_cart', array( $this, 'ajax_add_to_cart' ) );
		add_action( 'wp_ajax_cpg_toggle_wishlist', array( $this, 'ajax_toggle_wishlist' ) );
		add_action( 'wp_ajax_nopriv_cpg_toggle_wishlist', array( $this, 'ajax_toggle_wishlist' ) );
	}

	/**
	 * Check if required plugins are active
	 *
	 * @return bool
	 */
	private function is_compatible() {
		// Check if Elementor is active.
		if ( ! did_action( 'elementor/loaded' ) ) {
			return false;
		}

		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Admin notice for missing dependencies
	 */
	public function admin_notice_missing_dependencies() {
		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: WooCommerce */
			esc_html__( '"%1$s" requires "%2$s" and "%3$s" to be installed and activated.', 'custom-product-grid-slider' ),
			'<strong>' . esc_html__( 'Custom Product Grid Slider', 'custom-product-grid-slider' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'custom-product-grid-slider' ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'custom-product-grid-slider' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Register Elementor widgets
	 *
	 * @param object $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once plugin_dir_path( __FILE__ ) . 'widgets/product-grid-widget.php';
		$widgets_manager->register( new Product_Grid_Widget() );
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		// Enqueue CSS.
		wp_enqueue_style(
			'cpg-slider-style',
			plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
			array(),
			self::VERSION
		);

		// Enqueue JavaScript.
		wp_enqueue_script(
			'cpg-slider-script',
			plugin_dir_url( __FILE__ ) . 'assets/js/script.js',
			array( 'jquery' ),
			self::VERSION,
			true
		);

		// Localize script for AJAX.
		wp_localize_script(
			'cpg-slider-script',
			'cpgAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'cpg_nonce' ),
			)
		);
	}

	/**
	 * AJAX handler for add to cart
	 */
	public function ajax_add_to_cart() {
		check_ajax_referer( 'cpg_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$quantity   = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'custom-product-grid-slider' ) ) );
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'custom-product-grid-slider' ) ) );
		}

		// Add to cart.
		$added = WC()->cart->add_to_cart( $product_id, $quantity );

		if ( $added ) {
			wp_send_json_success(
				array(
					'message'    => esc_html__( 'Product added to cart', 'custom-product-grid-slider' ),
					'cart_count' => WC()->cart->get_cart_contents_count(),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Failed to add product to cart', 'custom-product-grid-slider' ) ) );
		}
	}

	/**
	 * AJAX handler for wishlist toggle
	 */
	public function ajax_toggle_wishlist() {
		check_ajax_referer( 'cpg_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$action     = isset( $_POST['wishlist_action'] ) ? sanitize_text_field( wp_unslash( $_POST['wishlist_action'] ) ) : '';

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'custom-product-grid-slider' ) ) );
		}

		if ( 'add' === $action ) {
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Product added to wishlist', 'custom-product-grid-slider' ),
					'action'  => 'added',
				)
			);
		} elseif ( 'remove' === $action ) {
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Product removed from wishlist', 'custom-product-grid-slider' ),
					'action'  => 'removed',
				)
			);
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid action', 'custom-product-grid-slider' ) ) );
		}
	}
}

// Initialize the plugin.
Custom_Product_Grid_Slider::instance();