<?php
/**
 * Changelog widget functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXChangelog
 * @subpackage CBXChangelog/widgets
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * CBXChangelog widget functionality of the plugin.
 *
 *
 * @package    CBXChangelog
 * @subpackage CBXChangelogW/widgets
 * @author     codeboxr <info@codeboxr.com>
 */
class CBXChangelogWidget extends WP_Widget {
	/**
	 *
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $widget_slug = 'cbxchangelog'; //main parent plugin's language file


	public function __construct() {

		parent::__construct(
			$this->get_widget_slug(),
			esc_html__( 'CBX Changelog Widget', 'cbxchangelog' ),
			[
				'classname'   => $this->get_widget_slug() . '-widget',
				'description' => esc_html__( 'Displays CBX Changelog widget', 'cbxchangelog' )
			]
		);

	}//end constructor


	/**
	 * Return the widget slug.
	 *
	 * @return string
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param  array args  The array of form elements
	 * @param  array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Changelog Display', 'cbxchangelog' ) : $instance['title'], $instance, $this->id_base );
		// Defining the Widget Title
		if ( $title ) {
			$widget_string .= $args['before_title'] . $title . $args['after_title'];
		} else {
			$widget_string .= $args['before_title'] . $args['after_title'];
		}

		$instance = apply_filters( 'cbxchangelog_widget_instance', $instance );

		$instance['post_id']       = $post_id = isset( $instance['post_id'] ) ? absint( $instance['post_id'] ) : 0;
		$instance['release']       = $release = isset( $instance['release'] ) ? absint( $instance['release'] ) : 0;

		$instance['show_label']    = $show_label = isset( $instance['show_label'] ) ? sanitize_text_field( wp_unslash( $instance['show_label'] ) ) : 1;
		$instance['show_url']      = $show_url = isset( $instance['show_url'] ) ? sanitize_text_field( wp_unslash( $instance['show_url'] ) ) : 1;
		$instance['show_date']     = $show_date = isset( $instance['show_date'] ) ? sanitize_text_field( wp_unslash( $instance['show_date'] ) ) : 1;
		$instance['relative_date'] = $relative_date = isset( $instance['relative_date'] ) ? sanitize_text_field( wp_unslash( $instance['relative_date'] ) ) : 0;
		$instance['layout']        = $layout = isset( $instance['layout'] ) ? sanitize_text_field( wp_unslash( $instance['layout'] ) ) : 'prepros';
		$instance['orderby']       = $orderby = isset( $instance['orderby'] ) ? sanitize_text_field( wp_unslash( $instance['orderby'] ) ) : 'default';
		$instance['order']         = $order = isset( $instance['order'] ) ? sanitize_text_field( wp_unslash( $instance['order'] ) ) : 'desc';

		$instance['count']         = $count = isset( $instance['count'] ) ?  intval($instance['count']) : 0;


		extract( $instance );

		if ( $orderby == '' ) {
			$orderby = 'default';
		}
		if ( $order == '' ) {
			$order = 'desc';
		}


		$order_keys = cbxchangelog_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'desc';
		}

		$order_by_keys = cbxchangelog_get_orderby_keys();
		if ( ! in_array( $orderby, $order_by_keys ) ) {
			$orderby = 'default';
		}



		if ( absint( $post_id ) > 0 && ( false !== get_post_status( $post_id ) ) ) {
			$widget_string .= do_shortcode( '[cbxchangelog count="'.$count.'" orderby="' . $orderby . '" order="' . $order . '" id="' . $post_id . '" release="' . $release . '"  show_label="' . $show_label . '"  show_url="' . $show_url . '" show_date="' . $show_date . '" relative_date="' . $relative_date . '" layout="' . $layout . '"]' );
		} else {
			$widget_string .= '<p class="cbxchangelog-info cbxchangelog_missing">' . esc_html__( 'Changelog id missing or changelog doesn\'t exists', 'cbxchangelog' ) . '</p>';
		}

		$widget_string .= $after_widget;
		echo $widget_string; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}//end widget


	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param  array new_instance The new instance of values to be generated via the update.
	 * @param  array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		$instance['post_id']       = absint( $new_instance['post_id'] );
		$instance['release']       = absint( $new_instance['release'] );

		//allow to choose from post meta , so don't use intval or absint
		$instance['show_label']    = sanitize_text_field( wp_unslash($new_instance['show_label'] ) );
		$instance['show_url']      = sanitize_text_field( wp_unslash($new_instance['show_url'] ) );
		$instance['show_date']     = sanitize_text_field( wp_unslash($new_instance['show_date'] ) );
		$instance['relative_date'] = sanitize_text_field( wp_unslash($new_instance['relative_date'] ) );
		$instance['layout']        = sanitize_text_field( wp_unslash($new_instance['layout']) );
		$instance['orderby']       = sanitize_text_field( wp_unslash($new_instance['orderby']) );
		$instance['order']         = sanitize_text_field( wp_unslash($new_instance['order']) );

		$instance['count']         = intval($new_instance['count']);


		return apply_filters( 'cbxchangelog_widget_update', $instance, $new_instance );
	}//end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param  array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		$setting               = new CBXChangelogSettings();
		$show_url_default      = absint($setting->get_field( 'show_url', 'cbxchangelog_general', 1 ));
		$show_label_default    = absint($setting->get_field( 'show_label', 'cbxchangelog_general', 1 ));
		$show_date_default     = absint($setting->get_field( 'show_date', 'cbxchangelog_general', 1 ));
		$relative_date_default = absint($setting->get_field( 'relative_date', 'cbxchangelog_general', 0 ));
		$layout_default        = sanitize_text_field(wp_unslash($setting->get_field( 'layout', 'cbxchangelog_general', 'prepros' )));

		$fields = [
			'title'         => esc_html__( 'Changelog Display', 'cbxchangelog' ),
			'post_id'       => 0, //cbxchangelog id,
			'release'       => 0, //release
			'show_label'    => $show_label_default,
			'show_url'      => $show_url_default,
			'show_date'     => $show_date_default,
			'relative_date' => $relative_date_default,
			'layout'        => $layout_default,
			'orderby'       => 'default',// 'default = saved order, date = sort by date
			'order'         => 'desc',
			'count'         => 0 //0 = all
		];

		$fields = apply_filters( 'cbxchangelog_widget_form_fields', $fields );

		$instance = wp_parse_args(
			(array) $instance,
			$fields
		);

		$instance = apply_filters( 'cbxchangelog_widget_form_instance', $instance );

		extract( $instance, EXTR_SKIP );


		// Display the admin form
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );
	}//end form
}//end class CBXChangelogWidget