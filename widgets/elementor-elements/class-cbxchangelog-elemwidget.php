<?php

namespace CBXChangeLogElemWidget\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CBX Changelog & Release Note Elementor Widget
 */
class CBXChangeLog_ElemWidget extends \Elementor\Widget_Base {

	/**
	 * Retrieve widget name.
	 *
	 * @return string Widget name.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'cbxchangelog_single';
	}

	/**
	 * Retrieve widget title.
	 *
	 * @return string Widget title.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'CBXChangelog Widget', 'cbxchangelog' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 * @since  1.0.10
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'codeboxr' ];
	}

	/**
	 * Retrieve widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'cbxchangelog-icon';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_cbxchangelog',
			[
				'label' => esc_html__( 'CBXChangelog Widget Setting', 'cbxchangelog' ),
			]
		);
		$this->add_control(
			'cbxchangelog_id',
			[
				'label'       => esc_html__( 'Changelog ID', 'cbxchangelog' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => 0,
				'description' => esc_html__( 'Set Post ID(Change log post type or other supported)', 'cbxchangelog' ),
			]
		);

		$this->add_control(
			'cbxchangelog_release',
			[
				'label'       => esc_html__( 'Release No/ID', 'cbxchangelog' ),
				'description' => esc_html__( '0 = all changelogs, greater than 0 means any specific release/changelog', 'cbxchangelog' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0, // Minimum value
				'step'        => 1, // Step value
			]
		);

		$this->add_control(
			'cbxchangelog_show_label',
			[
				'label'   => esc_html__( 'Show label', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_show_url',
			[
				'label'   => esc_html__( 'Show Url', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_group_label',
			[
				'label'   => esc_html__( 'Group label', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 0,
			]
		);

		$this->add_control(
			'cbxchangelog_show_date',
			[
				'label'   => esc_html__( 'Show date', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 1,
			]
		);

		$this->add_control(
			'cbxchangelog_relative_date',
			[
				'label'   => esc_html__( 'Show Relative date', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'1' => esc_html__( 'Yes', 'cbxchangelog' ),
					'0' => esc_html__( 'No', 'cbxchangelog' ),
				],
				'default' => 0,
			]
		);


		$layout      = \CbxchangelogHelper::get_layouts();
		$layout_meta = \CbxchangelogHelper::get_layouts_for_meta();
		$layouts     = array_merge( $layout_meta, $layout );
		$this->add_control(
			'cbxchangelog_layout',
			[
				'label'   => esc_html__( 'Choose layout', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $layouts,
				'default' => 'prepros',
			]
		);

		$this->add_control(
			'cbxchangelog_orderby',
			[
				'label'   => esc_html__( 'Order By', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''        => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'default' => esc_html__( 'Default', 'cbxchangelog' ),
					'id'      => esc_html__( 'Release No/ID', 'cbxchangelog' ),
					'date'    => esc_html__( 'Date', 'cbxchangelog' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'cbxchangelog_order',
			[
				'label'   => esc_html__( 'Order', 'cbxchangelog' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''     => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
					'desc' => esc_html__( 'Desc', 'cbxchangelog' ),
					'asc'  => esc_html__( 'Asc', 'cbxchangelog' ),
				],
				'default' => 'desc',
			]
		);


		$this->add_control(
			'cbxchangelog_count',
			[
				'label'       => esc_html__( 'Count', 'cbxchangelog' ),
				'description' => esc_html__( '0 = all, -1 = take from post meta, greater than 0 means any specific count', 'cbxchangelog' ),
				'type'        => \Elementor\Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => - 1, // Minimum value
				'step'        => 1,   // Step value
			]
		);

		do_action( 'cbxchangelog_elementor_widget_controls', $this );
		

		$this->end_controls_section();
	}//end method _register_controls


	/**
	 * Renderwidget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$atts = [];

		$atts['id']      = isset( $settings['cbxchangelog_id'] ) ? absint( $settings['cbxchangelog_id'] ) : 0;
		$atts['release'] = isset( $settings['cbxchangelog_release'] ) ? absint( $settings['cbxchangelog_release'] ) : 0;



		$atts['show_url']      = isset( $settings['cbxchangelog_show_url'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_show_url'] ) ) : 1;
		$atts['group_label']   = isset( $settings['cbxchangelog_group_label'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_group_label'] ) ) : 0;
		$atts['show_label']    = isset( $settings['cbxchangelog_show_label'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_show_label'] ) ) : 1;
		$atts['show_date']     = isset( $settings['cbxchangelog_show_date'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_show_date'] ) ) : 1;
		$atts['relative_date'] = isset( $settings['cbxchangelog_relative_date'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_relative_date'] ) ) : 0;
		$atts['layout']        = isset( $settings['cbxchangelog_layout'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_layout'] ) ) : 'prepros';
		$atts['orderby']       = isset( $settings['cbxchangelog_orderby'] ) ? sanitize_text_field( wp_unslash( $settings['cbxchangelog_orderby'] ) ) : 'default';                //default, date
		$atts['order']         = isset( $settings['cbxchangelog_order'] ) ? strtolower( sanitize_text_field( wp_unslash( $settings['cbxchangelog_order'] ) ) ) : 'desc';         //desc, asc

		$atts['count'] = isset( $settings['cbxchangelog_count'] ) ? intval( $settings['cbxchangelog_count'] ) : 0;   //0 = means all, greater than 0 means any specific

		$orderby = $atts['orderby'];
		$order = $atts['order'];

		if ( $orderby == '' ) {
			$orderby = 'default';
			$atts['orderby'] = $orderby;
		}
		if ( $order == '' ) {
			$order = 'desc';
			$atts['order'] = $order;
		}

		$order_keys = cbxchangelog_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'desc';
			$atts['order'] = $order;
		}

		$order_by_keys = cbxchangelog_get_orderby_keys();
		if ( ! in_array( $orderby, $order_by_keys ) ) {
			$orderby = 'default';
			$atts['orderby'] = $orderby;
		}



		if ( absint( $atts['id'] ) <= 0 && ( false !== get_post_status( $atts['id'] ) ) ) {
			esc_html_e( 'Set Post ID(Change log post type or other supported)', 'cbxchangelog' );
		} else {
			$atts = apply_filters('cbxchangelog_params_atts', $atts, $settings);

			$attr_html = '';
			foreach ( $atts as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
			}

			echo do_shortcode( '[cbxchangelog '.$attr_html.']' );
		}
	}//end method render
}//end method CBXChangeLog_ElemWidget