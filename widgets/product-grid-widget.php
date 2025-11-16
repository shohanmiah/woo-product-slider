<?php
/**
 * Product Grid Widget for Elementor
 *
 * @package Custom_Product_Grid_Slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Product Grid Widget Class
 */
class Product_Grid_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'custom_product_grid';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Grid Slider', 'custom-product-grid-slider' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		// Content Tab - Product Query Section.
		$this->start_controls_section(
			'product_query_section',
			array(
				'label' => esc_html__( 'Product Query', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_source',
			array(
				'label'   => esc_html__( 'Product Source', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => array(
					'recent'       => esc_html__( 'Recent Products', 'custom-product-grid-slider' ),
					'featured'     => esc_html__( 'Featured Products', 'custom-product-grid-slider' ),
					'sale'         => esc_html__( 'Sale Products', 'custom-product-grid-slider' ),
					'best_selling' => esc_html__( 'Best Selling', 'custom-product-grid-slider' ),
					'top_rated'    => esc_html__( 'Top Rated', 'custom-product-grid-slider' ),
					'related'      => esc_html__( 'Related Products', 'custom-product-grid-slider' ),
					'cross_sell'   => esc_html__( 'Cross-Sell Products', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->add_control(
			'product_categories',
			array(
				'label'       => esc_html__( 'Categories', 'custom-product-grid-slider' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_product_categories(),
				'label_block' => true,
			)
		);

		$this->add_control(
			'products_per_page',
			array(
				'label'   => esc_html__( 'Products Per Page', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 100,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'custom-product-grid-slider' ),
					'title'      => esc_html__( 'Title', 'custom-product-grid-slider' ),
					'price'      => esc_html__( 'Price', 'custom-product-grid-slider' ),
					'popularity' => esc_html__( 'Popularity', 'custom-product-grid-slider' ),
					'rating'     => esc_html__( 'Rating', 'custom-product-grid-slider' ),
					'rand'       => esc_html__( 'Random', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'Ascending', 'custom-product-grid-slider' ),
					'DESC' => esc_html__( 'Descending', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->end_controls_section();

		// Content Tab - Layout Settings Section.
		$this->start_controls_section(
			'layout_section',
			array(
				'label' => esc_html__( 'Layout Settings', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => esc_html__( 'Columns', 'custom-product-grid-slider' ),
				'type'            => \Elementor\Controls_Manager::NUMBER,
				'desktop_default' => 4,
				'tablet_default'  => 3,
				'mobile_default'  => 2,
				'min'             => 1,
				'max'             => 6,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => esc_html__( 'Column Gap', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-products-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => esc_html__( 'Row Gap', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-products-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Content Tab - Slider Settings Section.
		$this->start_controls_section(
			'slider_section',
			array(
				'label' => esc_html__( 'Slider Settings', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_slider_desktop',
			array(
				'label'        => esc_html__( 'Enable Slider on Desktop', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'enable_slider_mobile',
			array(
				'label'        => esc_html__( 'Enable Slider on Mobile', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'peek_percentage',
			array(
				'label'   => esc_html__( 'Peek Percentage', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 0,
				'max'     => 50,
			)
		);

		$this->add_control(
			'enable_mouse_wheel',
			array(
				'label'        => esc_html__( 'Enable Mouse Wheel', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'enable_drag_scroll',
			array(
				'label'        => esc_html__( 'Enable Drag Scroll', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Show Navigation Arrows', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Content Tab - Elements Visibility Section.
		$this->start_controls_section(
			'elements_section',
			array(
				'label' => esc_html__( 'Elements Visibility', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Show Image', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Show Price', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Show Rating', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_sale_badge',
			array(
				'label'        => esc_html__( 'Show Sale Badge', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => esc_html__( 'Show Add to Cart', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_wishlist',
			array(
				'label'        => esc_html__( 'Show Wishlist', 'custom-product-grid-slider' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'custom-product-grid-slider' ),
				'label_off'    => esc_html__( 'No', 'custom-product-grid-slider' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Style Tab - Item Hover Effects.
		$this->start_controls_section(
			'style_item_hover',
			array(
				'label' => esc_html__( 'Item Hover', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_hover_effect',
			array(
				'label'   => esc_html__( 'Hover Effect', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'lift',
				'options' => array(
					'none'       => esc_html__( 'None', 'custom-product-grid-slider' ),
					'lift'       => esc_html__( 'Lift', 'custom-product-grid-slider' ),
					'scale'      => esc_html__( 'Scale', 'custom-product-grid-slider' ),
					'lift-scale' => esc_html__( 'Lift + Scale', 'custom-product-grid-slider' ),
					'float'      => esc_html__( 'Float', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_hover_shadow',
				'label'    => esc_html__( 'Hover Shadow', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-item:hover',
			)
		);

		$this->end_controls_section();

		// Style Tab - Image Effects.
		$this->start_controls_section(
			'style_image',
			array(
				'label' => esc_html__( 'Image', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_hover_effect',
			array(
				'label'   => esc_html__( 'Image Hover Effect', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'zoom',
				'options' => array(
					'none'      => esc_html__( 'None', 'custom-product-grid-slider' ),
					'zoom'      => esc_html__( 'Zoom In', 'custom-product-grid-slider' ),
					'zoom-out'  => esc_html__( 'Zoom Out', 'custom-product-grid-slider' ),
					'rotate'    => esc_html__( 'Rotate', 'custom-product-grid-slider' ),
					'blur'      => esc_html__( 'Blur', 'custom-product-grid-slider' ),
					'grayscale' => esc_html__( 'Grayscale', 'custom-product-grid-slider' ),
					'opacity'   => esc_html__( 'Opacity', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-image-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-image',
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-product-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Title.
		$this->start_controls_section(
			'style_title',
			array(
				'label' => esc_html__( 'Title', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_limit',
			array(
				'label'   => esc_html__( 'Title Word Limit', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'max'     => 100,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-title:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Price.
		$this->start_controls_section(
			'style_price',
			array(
				'label' => esc_html__( 'Price', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => esc_html__( 'Typography', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-price',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_sale_color',
			array(
				'label'     => esc_html__( 'Sale Price Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-price' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'price_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-price',
			)
		);

		$this->add_responsive_control(
			'price_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Rating.
		$this->start_controls_section(
			'style_rating',
			array(
				'label' => esc_html__( 'Rating', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rating_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'custom-product-grid-slider' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-rating' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rating_color',
			array(
				'label'     => esc_html__( 'Star Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-product-rating .star-rating' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rating_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-product-rating',
			)
		);

		$this->add_responsive_control(
			'rating_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Add to Cart Button.
		$this->start_controls_section(
			'style_button',
			array(
				'label' => esc_html__( 'Add to Cart Button', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-add-to-cart',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => esc_html__( 'Normal', 'custom-product-grid-slider' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-add-to-cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-add-to-cart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			array(
				'label' => esc_html__( 'Hover', 'custom-product-grid-slider' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-add-to-cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-add-to-cart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-add-to-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Wishlist.
		$this->start_controls_section(
			'style_wishlist',
			array(
				'label' => esc_html__( 'Wishlist', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wishlist_position',
			array(
				'label'   => esc_html__( 'Position', 'custom-product-grid-slider' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'top-right',
				'options' => array(
					'top-left'     => esc_html__( 'Top Left', 'custom-product-grid-slider' ),
					'top-right'    => esc_html__( 'Top Right', 'custom-product-grid-slider' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'custom-product-grid-slider' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'custom-product-grid-slider' ),
				),
			)
		);

		$this->add_control(
			'wishlist_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-wishlist-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wishlist_icon_active_color',
			array(
				'label'     => esc_html__( 'Active Icon Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-wishlist-btn.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wishlist_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-wishlist-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Navigation Arrows.
		$this->start_controls_section(
			'style_arrows',
			array(
				'label' => esc_html__( 'Navigation Arrows', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrows_size',
			array(
				'label'      => esc_html__( 'Size', 'custom-product-grid-slider' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .cpg-nav-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => esc_html__( 'Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-nav-arrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-nav-arrow' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-nav-arrow:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_hover_bg_color',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-nav-arrow:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style Tab - Sale Badge.
		$this->start_controls_section(
			'style_badge',
			array(
				'label' => esc_html__( 'Sale Badge', 'custom-product-grid-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-sale-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'custom-product-grid-slider' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cpg-sale-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'label'    => esc_html__( 'Typography', 'custom-product-grid-slider' ),
				'selector' => '{{WRAPPER}} .cpg-sale-badge',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Build slider classes.
		$slider_classes = array( 'cpg-products-grid' );
		if ( 'yes' === $settings['enable_slider_desktop'] ) {
			$slider_classes[] = 'cpg-slider-desktop';
		}
		if ( 'yes' === $settings['enable_slider_mobile'] ) {
			$slider_classes[] = 'cpg-slider-mobile';
		}
		if ( ! empty( $settings['item_hover_effect'] ) && 'none' !== $settings['item_hover_effect'] ) {
			$slider_classes[] = 'cpg-hover-' . esc_attr( $settings['item_hover_effect'] );
		}
		if ( ! empty( $settings['image_hover_effect'] ) && 'none' !== $settings['image_hover_effect'] ) {
			$slider_classes[] = 'cpg-image-' . esc_attr( $settings['image_hover_effect'] );
		}

		// Get products.
		$products = $this->get_products_query( $settings );

		// Widget data attributes.
		$widget_id  = $this->get_id();
		$data_attrs = array(
			'data-widget-id'       => esc_attr( $widget_id ),
			'data-slider-desktop'  => esc_attr( $settings['enable_slider_desktop'] ),
			'data-slider-mobile'   => esc_attr( $settings['enable_slider_mobile'] ),
			'data-peek-percentage' => esc_attr( $settings['peek_percentage'] ),
			'data-mouse-wheel'     => esc_attr( $settings['enable_mouse_wheel'] ),
			'data-drag-scroll'     => esc_attr( $settings['enable_drag_scroll'] ),
			'data-columns'         => esc_attr( $settings['columns'] ),
			'data-columns-tablet'  => esc_attr( $settings['columns_tablet'] ),
			'data-columns-mobile'  => esc_attr( $settings['columns_mobile'] ),
		);

		?>
		<div class="cpg-products-wrapper" <?php echo implode( ' ', array_map( function( $key, $value ) { return $key . '="' . $value . '"'; }, array_keys( $data_attrs ), $data_attrs ) ); ?>>
			<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<button class="cpg-nav-arrow cpg-nav-prev" aria-label="<?php echo esc_attr__( 'Previous', 'custom-product-grid-slider' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			<?php endif; ?>

			<div class="<?php echo esc_attr( implode( ' ', $slider_classes ) ); ?>">
				<?php
				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
						global $product;

						if ( ! $product ) {
							continue;
						}
						?>
						<div class="cpg-product-item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
							<?php if ( 'yes' === $settings['show_image'] ) : ?>
								<div class="cpg-product-image-wrapper">
									<?php if ( 'yes' === $settings['show_sale_badge'] && $product->is_on_sale() ) : ?>
										<span class="cpg-sale-badge"><?php echo esc_html__( 'Sale!', 'custom-product-grid-slider' ); ?></span>
									<?php endif; ?>

									<?php if ( 'yes' === $settings['show_wishlist'] ) : ?>
										<button class="cpg-wishlist-btn cpg-wishlist-<?php echo esc_attr( $settings['wishlist_position'] ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php echo esc_attr__( 'Add to wishlist', 'custom-product-grid-slider' ); ?>">
											<svg class="wishlist-icon-default" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M20.84 4.61C20.3292 4.09913 19.7228 3.69374 19.0554 3.41731C18.3879 3.14088 17.6725 2.99854 16.95 2.99854C16.2275 2.99854 15.5121 3.14088 14.8446 3.41731C14.1772 3.69374 13.5708 4.09913 13.06 4.61L12 5.67L10.94 4.61C9.9083 3.57831 8.50903 2.99871 7.05 2.99871C5.59096 2.99871 4.19169 3.57831 3.16 4.61C2.1283 5.64169 1.54871 7.04097 1.54871 8.5C1.54871 9.95903 2.1283 11.3583 3.16 12.39L4.22 13.45L12 21.23L19.78 13.45L20.84 12.39C21.3509 11.8792 21.7563 11.2728 22.0327 10.6054C22.3091 9.93789 22.4515 9.22248 22.4515 8.5C22.4515 7.77752 22.3091 7.0621 22.0327 6.39464C21.7563 5.72718 21.3509 5.12075 20.84 4.61Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
											<svg class="wishlist-icon-active" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path d="M20.84 4.61C20.3292 4.09913 19.7228 3.69374 19.0554 3.41731C18.3879 3.14088 17.6725 2.99854 16.95 2.99854C16.2275 2.99854 15.5121 3.14088 14.8446 3.41731C14.1772 3.69374 13.5708 4.09913 13.06 4.61L12 5.67L10.94 4.61C9.9083 3.57831 8.50903 2.99871 7.05 2.99871C5.59096 2.99871 4.19169 3.57831 3.16 4.61C2.1283 5.64169 1.54871 7.04097 1.54871 8.5C1.54871 9.95903 2.1283 11.3583 3.16 12.39L4.22 13.45L12 21.23L19.78 13.45L20.84 12.39C21.3509 11.8792 21.7563 11.2728 22.0327 10.6054C22.3091 9.93789 22.4515 9.22248 22.4515 8.5C22.4515 7.77752 22.3091 7.0621 22.0327 6.39464C21.7563 5.72718 21.3509 5.12075 20.84 4.61Z"/>
											</svg>
										</button>
									<?php endif; ?>

									<a href="<?php echo esc_url( get_permalink() ); ?>" class="cpg-product-image">
										<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="cpg-product-content">
								<?php if ( 'yes' === $settings['show_rating'] && $product->get_average_rating() > 0 ) : ?>
									<div class="cpg-product-rating">
										<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
									</div>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_title'] ) : ?>
									<h3 class="cpg-product-title">
										<a href="<?php echo esc_url( get_permalink() ); ?>">
											<?php echo esc_html( $this->limit_title( get_the_title(), $settings['title_limit'] ) ); ?>
										</a>
									</h3>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_price'] ) : ?>
									<div class="cpg-product-price">
										<?php echo wp_kses_post( $product->get_price_html() ); ?>
									</div>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
									<button class="cpg-add-to-cart" 
										data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
										data-quantity="1"
										aria-label="<?php echo esc_attr__( 'Add to cart', 'custom-product-grid-slider' ); ?>">
										<span class="button-text"><?php echo esc_html__( 'Add to Cart', 'custom-product-grid-slider' ); ?></span>
										<span class="button-loader" style="display: none;">
											<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
												<circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2" fill="none" opacity="0.3"/>
												<path d="M10 2 A 8 8 0 0 1 18 10" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round">
													<animateTransform attributeName="transform" type="rotate" from="0 10 10" to="360 10 10" dur="1s" repeatCount="indefinite"/>
												</path>
											</svg>
										</span>
									</button>
								<?php endif; ?>
							</div>
						</div>
						<?php
					}
					wp_reset_postdata();
				} else {
					echo '<p>' . esc_html__( 'No products found.', 'custom-product-grid-slider' ) . '</p>';
				}
				?>
			</div>

			<?php if ( 'yes' === $settings['show_arrows'] ) : ?>
				<button class="cpg-nav-arrow cpg-nav-next" aria-label="<?php echo esc_attr__( 'Next', 'custom-product-grid-slider' ); ?>">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get products query based on settings.
	 *
	 * @param array $settings Widget settings.
	 * @return WP_Query
	 */
	private function get_products_query( $settings ) {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['products_per_page'],
			'order'          => $settings['order'],
		);

		// Order by.
		switch ( $settings['orderby'] ) {
			case 'price':
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'popularity':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'rating':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				break;
			default:
				$args['orderby'] = $settings['orderby'];
				break;
		}

		// Product source.
		switch ( $settings['product_source'] ) {
			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
				break;
			case 'sale':
				$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
				break;
			case 'best_selling':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				break;
			case 'top_rated':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'related':
				// Get related products for current product (single product page).
				global $product;
				if ( is_product() && $product ) {
					$related_ids = wc_get_related_products( $product->get_id(), $settings['products_per_page'] );
					if ( ! empty( $related_ids ) ) {
						$args['post__in'] = $related_ids;
					} else {
						$args['post__in'] = array( 0 ); // No results if no related products.
					}
				} else {
					$args['post__in'] = array( 0 ); // No results if not on product page.
				}
				break;
			case 'cross_sell':
				// Get cross-sell products from cart.
				$cart = WC()->cart;
				if ( $cart && ! $cart->is_empty() ) {
					$cross_sell_ids = $cart->get_cross_sells();
					if ( ! empty( $cross_sell_ids ) ) {
						$args['post__in'] = array_slice( $cross_sell_ids, 0, $settings['products_per_page'] );
					} else {
						$args['post__in'] = array( 0 ); // No results if no cross-sell products.
					}
				} else {
					$args['post__in'] = array( 0 ); // No results if cart is empty.
				}
				break;
		}

		// Categories filter.
		if ( ! empty( $settings['product_categories'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $settings['product_categories'],
			);
		}

		return new WP_Query( $args );
	}

	/**
	 * Get product categories for select control.
	 *
	 * @return array
	 */
	private function get_product_categories() {
		$categories = array();
		$terms      = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			)
		);

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$categories[ $term->term_id ] = $term->name;
			}
		}

		return $categories;
	}

	/**
	 * Limit title words.
	 *
	 * @param string $title Title string.
	 * @param int    $limit Word limit.
	 * @return string
	 */
	private function limit_title( $title, $limit ) {
		if ( $limit <= 0 ) {
			return $title;
		}

		$words = explode( ' ', $title );
		if ( count( $words ) > $limit ) {
			return implode( ' ', array_slice( $words, 0, $limit ) ) . '...';
		}

		return $title;
	}
}
