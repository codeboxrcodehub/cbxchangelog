<?php

namespace cbxchangelog\includes;

use CBXChangeLog_WPBWidget;
use CBXChangeLogElemWidget;
use CBXChangelogHelper;
use CBXChangelogSettings;
use Michelf\Markdown;
use Michelf\MarkdownExtra;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/public
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXChangelogPublic {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->version = current_time( 'timestamp' ); //for development time only
		}

		//get instance of setting api
		$this->settings = new CBXChangelogSettings();

	}//end constructor

	/**
	 * Shortcode init
	 */
	public function init_shortcodes() {
		add_shortcode( 'cbxchangelog', [ $this, 'cbxchangelog_shortcode' ] );
	}//end init_shortcodes

	/**
	 * Shortcode callback
	 */
	public function cbxchangelog_shortcode( $atts ) {
		// normalize attribute keys, lowercase
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		global $post;

		$setting = $this->settings;
		//default values from global settings param
		$show_label        = absint( $setting->get_field( 'show_label', 'cbxchangelog_general', 1 ) );
		$group_label       = absint( $setting->get_field( 'group_label', 'cbxchangelog_general', 0 ) );
		$show_date         = absint( $setting->get_field( 'show_date', 'cbxchangelog_general', 1 ) );
		$show_url          = absint( $setting->get_field( 'show_url', 'cbxchangelog_general', 1 ) );
		//$show_feature_note = absint( $setting->get_field( 'show_feature_note', 'cbxchangelog_general', 0 ) );
		$relative_date     = absint( $setting->get_field( 'relative_date', 'cbxchangelog_general', 0 ) );
		$layout            = sanitize_text_field( wp_unslash( $setting->get_field( 'layout', 'cbxchangelog_general', 'prepros' ) ) );

		$shortcode_default_atts = [
			'title'             => '',
			'id'                => 0,
			//cbxchangelog type post id
			'release'           => 0,
			//individual release, index starts from 1
			'show_label'        => $show_label,
			'show_date'         => $show_date,
			'show_url'          => $show_url,
			'relative_date'     => $relative_date,
			'layout'            => $layout,
			//'show_feature_note' => $show_feature_note,//part of pro
			'orderby'           => 'default',
			//'default = saved order, date = sort by date
			'order'             => 'desc',
			//asc, desc
			'count'             => 0,
			//number of items to show, 0 means unlimited or all
			'group_label'       => $group_label,
			//display labels by group, same type labels will display in group
		];

		$shortcode_default_atts = apply_filters( 'cbxchangelog_shortcode_default_atts', $shortcode_default_atts );

		$atts = shortcode_atts( $shortcode_default_atts, $atts, 'cbxchangelog' );


		if ( $atts['id'] == 0 && is_singular( 'cbxchangelog' ) ) {
			$atts['id'] = get_the_ID(); //if id missing try to take it from global post id
		}

		$atts['id']      = absint( $atts['id'] );
		$atts['release'] = absint( $atts['release'] );


		if ( $atts['id'] == 0 ) {
			return '';
		}


		//meta values
		$meta_extra = get_post_meta( $atts['id'], '_cbxchangelog_extra', true );

		//$meta_extra['show_feature_note'] = isset( $meta_extra['show_feature_note'] ) ? absint( $meta_extra['show_feature_note'] ) : 0;
		$meta_extra['show_url']          = isset( $meta_extra['show_url'] ) ? absint( $meta_extra['show_url'] ) : 1;
		$meta_extra['show_label']        = isset( $meta_extra['show_label'] ) ? absint( $meta_extra['show_label'] ) : 1;
		$meta_extra['group_label']       = isset( $meta_extra['group_label'] ) ? absint( $meta_extra['group_label'] ) : 0;
		$meta_extra['show_date']         = isset( $meta_extra['show_date'] ) ? absint( $meta_extra['show_date'] ) : 1;
		$meta_extra['relative_date']     = isset( $meta_extra['relative_date'] ) ? absint( $meta_extra['relative_date'] ) : 0;
		$meta_extra['layout']            = isset( $meta_extra['layout'] ) ? sanitize_text_field( wp_unslash( $meta_extra['layout'] ) ) : 'prepros';
		$meta_extra['orderby']           = isset( $meta_extra['orderby'] ) ? sanitize_text_field( wp_unslash( $meta_extra['orderby'] ) ) : 'order';
		$meta_extra['order']             = isset( $meta_extra['order'] ) ? sanitize_text_field( wp_unslash( $meta_extra['order'] ) ) : 'desc';
		$meta_extra['count']             = isset( $meta_extra['count'] ) ? absint( $meta_extra['count'] ) : 0;

		/*if ( $atts['show_feature_note'] == '' ) {
			$atts['show_feature_note'] = $meta_extra['show_feature_note'];
		}*/

		if ( $atts['show_url'] == '' ) {
			$atts['show_url'] = $meta_extra['show_url'];
		}

		if ( $atts['show_label'] == '' ) {
			$atts['show_label'] = $meta_extra['show_label'];
		}

		if ( $atts['group_label'] == '' ) {
			$atts['group_label'] = $meta_extra['group_label'];
		}

		if ( $atts['show_date'] == '' ) {
			$atts['show_date'] = $meta_extra['show_date'];
		}

		if ( $atts['relative_date'] == '' ) {
			$atts['relative_date'] = $meta_extra['relative_date'];
		}

		if ( $atts['layout'] == '' ) {
			$atts['layout'] = $meta_extra['layout'];
		}

		if ( $atts['orderby'] == '' ) {
			$atts['orderby'] = $meta_extra['orderby'];
		}

		if ( $atts['order'] == '' ) {
			$atts['order'] = $meta_extra['order'];
		}

		if ( $atts['count'] == - 1 ) {
			$atts['count'] = $meta_extra['count'];
		}


		$order    = $atts['order'] = strtolower( sanitize_text_field( wp_unslash( $atts['order'] ) ) );
		$order_by = $atts['orderby'] = sanitize_text_field( wp_unslash( $atts['orderby'] ) );
		$title    = $atts['title'] = sanitize_text_field( wp_unslash( $atts['title'] ) );

		if ( $order_by === 'order' ) {
			$order_by = 'default';
		}

		//take care order and orderby
		if ( $order_by == '' ) {
			$order_by = 'default';
		}

		if ( $order == '' ) {
			$order = 'desc';
		}


		$order_keys = cbxchangelog_get_order_keys();
		if ( ! in_array( $order, $order_keys ) ) {
			$order = 'desc';
		}

		$order_by_keys = cbxchangelog_get_orderby_keys();
		if ( ! in_array( $order_by, $order_by_keys ) ) {
			$order_by = 'default';
		}

		$atts['count'] = $count = absint( $atts['count'] );


		//$show_feature_note = isset( $atts['show_feature_note'] ) ? absint( $atts['show_feature_note'] ) : 0;
		$show_label        = isset( $atts['show_label'] ) ? absint( $atts['show_label'] ) : 1;
		$group_label       = isset( $atts['group_label'] ) ? absint( $atts['group_label'] ) : 0;
		$show_date         = isset( $atts['show_date'] ) ? absint( $atts['show_date'] ) : 1;
		$show_url          = isset( $atts['show_url'] ) ? absint( $atts['show_url'] ) : 1;
		$relative_date     = isset( $atts['relative_date'] ) ? absint( $atts['relative_date'] ) : 0;
		$layout            = isset( $atts['layout'] ) ? sanitize_text_field( wp_unslash( $atts['layout'] ) ) : 'prepros';

		$use_markdown = intval( $setting->get_field( 'use_markdown', 'cbxchangelog_general', 0 ) );

		$atts = apply_filters( 'cbxchangelog_shortcode_final_atts', $atts, $meta_extra );


		//loop to print
		$release_labels_readable = CBXChangelogHelper::cbxchangelog_labels();

		$layout     = 'cbxchangelog_wrapper_' . $layout;
		$hide_label = ( 0 == $show_label ) ? ' cbxchangelog_wrapper_hide_label' : '';

		$output_html = '<div class="cbxchangelog_wrapper ' . esc_attr( $layout ) . '">';

		if ( $title != '' ) {
			$output_html .= '<h2 class="cbxchangelog_shortcode_title">' . esc_html( $title ) . '</h2>';
		}

		$release = absint( $atts['release'] );


		if ( $release > 0 ) {
			//get specific release log, no hassle for sortings
			$change_logs = CBXChangelogHelper::get_changelog( $atts['id'], $release );
			if ( ! is_array( $change_logs ) || sizeof( $change_logs ) == 0 ) {
				return '';
			}
		} else {
			$meta       = CBXChangelogHelper::get_changelog_data( $atts['id'] );
			$order_by_t = $order_by;

			if ( $order_by_t == 'default' ) {
				$order_by_t = null;
			}

			if ( $count > 0 ) {
				$change_logs_t = $meta->getPaginatedRows( 1, $count, $order_by_t, $order );
				$change_logs   = $change_logs_t['data'];
			} else {
				$change_logs = $meta->getAll( $order_by_t, $order );
			}

		}

		$change_logs = apply_filters( 'cbxchangelog_releases_shortcode', $change_logs, $atts );

		$date_format = apply_filters( 'cbxchangelog_release_date_format', get_option( 'date_format' ) );


		foreach ( $change_logs as $index => $change_log ) {
			$change_log = apply_filters( 'cbxchangelog_release_shortcode', $change_log, $atts );

			$output_html .= '<div class="cbxchangelog_release" id="cbxchangelog_release_' . $atts['id'] . '_' . ( $index + 1 ) . '">';

			$version      = isset( $change_log['version'] ) ? esc_attr( $change_log['version'] ) : '';
			$url          = isset( $change_log['url'] ) ? esc_url( $change_log['url'] ) : '';
			$date         = isset( $change_log['date'] ) ? esc_attr( $change_log['date'] ) : '';
			$release_note = isset( $change_log['note'] ) ? $change_log['note'] : '';

			$output_html .= '<div class="cbxchangelog_release_header">';
			$output_html .= '<div class="cbxchangelog_version">' . $version . '</div>';

			if ( $show_date && $date ) {
				if ( $relative_date ) {
					$output_html .= '<div class="cbxchangelog_date"><small>' . CBXChangelogHelper::getChangelogHumanReadableTime( strtotime( $date ) ) . '</small></div>';
				} else {
					$output_html .= '<div class="cbxchangelog_date"><small>' . apply_filters( 'cbxchangelog_release_date', date_i18n( $date_format, strtotime( $date ) ), $date, $date_format ) . '</small></div>';
				}
			}
			$output_html .= '</div>';//cbxchangelog_release_header
			$output_html .= '<div class="cbxchangelog_release_inner">';
			if ( $release_note != '' ) {
				$release_note = do_shortcode( $release_note );

				if ( $use_markdown ) {
					$release_note = MarkdownExtra::defaultTransform( $release_note );
				}
				$output_html .= '<div class="cbxchangelog_note">' . wpautop( wptexturize( $release_note ) ) . '</div>';
			}

			$features = isset( $change_log['feature'] ) ? $change_log['feature'] : [];
			$features = ( ! is_array( $features ) ) ? [] : array_filter( $features );
			$labels   = isset( $change_log['label'] ) ? $change_log['label'] : [];
			$labels   = ( ! is_array( $labels ) ) ? [] : array_filter( $labels );

			$labels_with_features = [];
			foreach ( $labels as $label_index => $label ) {
				$temp_label_with_feature = [ 'label' => $label, 'feature' => $features[ $label_index ] ];
				$labels_with_features[]  = apply_filters( 'cbxchangelog_label_with_feature', $temp_label_with_feature, $change_log, $label_index );

				//$labels_with_features[] = [ 'label' => $label, 'feature' => $features[ $label_index ] ];
			}

			if ( $group_label ) {
				$labels_with_features = cbxchangelog_group_by_labels( $labels_with_features );
			}


			if ( sizeof( $labels_with_features ) > 0 ) {
				$output_html .= '<div class="cbxchangelog_features' . esc_attr( $hide_label ) . '">';

				foreach ( $labels_with_features as $labels_features ) {
					$found_label    = isset( $labels_features['label'] ) ? $labels_features['label'] : '';
					$single_feature = isset( $labels_features['feature'] ) ? esc_html( $labels_features['feature'] ) : '';
					if ( $found_label == '' || $single_feature == '' ) {
						continue;
					}

					$single_feature = do_shortcode( $single_feature );
					if ( $use_markdown ) {
						$single_feature = $this->do_parsemarkdown( $single_feature );
					}

					$output_html .= ' <div class="cbxchangelog_log">';

					if ( $show_label ) {
						$label_name  = isset( $release_labels_readable[ $found_label ] ) ? $release_labels_readable[ $found_label ] : $found_label;
						$output_html .= '<div class="cbxchangelog_log-label cbxchangelog_log_label_' . esc_attr( $found_label ) . '">' . esc_attr( $label_name ) . '</div>';
					}

					$output_html .= '<div class="cbxchangelog_feature">' . $single_feature . '</div>';

					$release_after_feature = '';
					$release_after_feature = apply_filters( 'cbxchangelog_release_after_feature', $release_after_feature, $labels_features, $atts);

					$output_html .= $release_after_feature;

					$output_html .= '</div>';//cbxchangelog_log					
				}

				$output_html .= '</div>'; //cbxchangelog_features
			}


			//handle url
			if ( $show_url && $url != '' ) {

				$url_target = apply_filters( 'cbxchangelog_url_target', ' target="_blank" ', $url, $change_log, $index, $atts['id'] );

				$output_html .= '<div class="cbxchangelog_features_url">';
				//phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
				$output_html .= '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABmJLR0QA/wD/AP+gvaeTAAAB30lEQVRIia3Wv2sUQRjG8c/dRUQbRawVogYUjHp97LUQFMTO/AliGXKEJApaKFgKEkgsxMYfWNgIFoJFRKNRLGyvE5MyYHGixdyR2WF3cuvlhYHded95v/vOPvPuNoxuLVzFZUygh3U8xrtdyE8/8Vf8rRgr2DsqpI3NDGQwlqExAuQNDkVzH/FSqGAaRyLf+f+FpJXcEd7VwA6jG/kf7QZkviJ2MYpZbdaAHMAVYXu+9OcWMqA/0XWrImYoO5XxjWHNdkVPxoZI2MBFXBLk/Fs4JyuZNbdxLrp/sRPkGD4ol22vnzDd/rkkbq0kpmBtbFRA4nE9WtNJfBs4vhMkVdc67glS3Yrmb/TXzCbxm4rbNxRkUVE548KJv499mCmp5GxdyAz244EggPEovoVbSfwvnKkLme37bkZzW1jqV/OtBDKZg0yUQDqRfzrxlY2fOJ2DtIQXHS+aS2KagpR7FZD3OJqDwLVk0d1M7Emhgb7GWzzEBUN+BZ5GkE/yfSnXdrLWxIno/pViM4xtHt+FhrokVH6wDmzVdkULGUh6GNt1IISTPkjQFT5aA2sJTz4yBKaSRF3hEHYUW/1IkIEtK5ftrkIIPxQ52GdF0dS2VP9TQheYxB78wHM8U63GoewfAo7YbZrQf6sAAAAASUVORK5CYII="/><a href="' . $url . '" ' . $url_target . '>' . esc_html__( 'More details about this release',
						'cbxchangelog' ) . '</a>';
				$output_html .= '</div>';
			}


			$output_html .= '</div>'; //cbxchangelog_release_inner
			$output_html .= '</div>'; //.cbxchangelog_release
		}

		$output_html .= '</div>'; //.cbxchangelog_wrapper

		return apply_filters( 'cbxchangelog_releases_shortcode_html', $output_html, $atts );
	}//end cbxchangelog_shortcode

	/**
	 * append the changelog history to any changelog type post type details
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function append_cbxchangelog( $content ) {
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		$settings       = $this->settings;
		$changelog_auto = absint( $settings->get_field( 'changelog_auto', 'cbxchangelog_general', 1 ) );

		if ( is_singular( 'cbxchangelog' ) && $changelog_auto ) {
			global $post;
			$post_id = absint( $post->ID );
			if ( $post_id > 0 ) {
				$meta_extra = get_post_meta( $post_id, '_cbxchangelog_extra', true );

				if ( ! is_array( $meta_extra ) ) {
					$meta_extra = [];
				}

				$atts = [];

				//$atts['show_feature_note'] = $show_feature_note = isset( $meta_extra['show_feature_note'] ) ? absint( $meta_extra['show_feature_note'] ) : 0; //handled from pro addon
				$atts['show_label']        = $show_label = isset( $meta_extra['show_label'] ) ? absint( $meta_extra['show_label'] ) : 1;
				$atts['show_date']         = $show_date = isset( $meta_extra['show_date'] ) ? absint( $meta_extra['show_date'] ) : 1;
				$atts['relative_date']     = $relative_date = isset( $meta_extra['relative_date'] ) ? absint( $meta_extra['relative_date'] ) : 0;
				$atts['layout']            = $layout = isset( $meta_extra['layout'] ) ? sanitize_text_field( wp_unslash( $meta_extra['layout'] ) ) : 'prepros';

				$atts['group_label'] = $group_label = isset( $meta_extra['group_label'] ) ? absint( $meta_extra['group_label'] ) : 0;
				$atts['show_url']    = $show_url = isset( $meta_extra['show_url'] ) ? absint( $meta_extra['show_url'] ) : 1;
				$atts['orderby']     = $order_by = isset( $meta_extra['orderby'] ) ? sanitize_text_field( wp_unslash( $meta_extra['orderby'] ) ) : 'default';
				$atts['order']       = $order = isset( $meta_extra['order'] ) ? sanitize_text_field( wp_unslash( $meta_extra['order'] ) ) : 'desc';
				$atts['count']       = $count = isset( $meta_extra['count'] ) ? absint( $meta_extra['count'] ) : 0;

				if ( $order_by == 'order' ) {
					$order_by = 'default';
				}

				//if(!in_array($orderby, ['default', 'date'])) $orderby = 'default';
				//if(!in_array($order, ['desc', 'asc'])) $order = 'desc';

				$order_keys = cbxchangelog_get_order_keys();
				if ( ! in_array( $order, $order_keys ) ) {
					$order = 'desc';
					$atts['order'] = $order;
				}

				$order_by_keys = cbxchangelog_get_orderby_keys();
				if ( ! in_array( $order_by, $order_by_keys ) ) {
					$order_by = 'default';
					$atts['orderby'] = $order_by;
				}

				$atts['id'] = $post_id;

				$atts = apply_filters('cbxchangelog_params_atts', $atts, $meta_extra);

				$attr_html = '';
				foreach ( $atts as $key => $value ) {
					$attr_html .= ' ' . $key . '="' . esc_attr( $value ) . '" ';
				}

				$content_shortcode = do_shortcode( '[cbxchangelog '.$attr_html.']' );
				$content           .= '<div class="cbxchangelog_shortcode_content">' . $content_shortcode . '</div>';
			}
		}

		return $content;
	}//end append_cbxchangelog

	/**
	 * Process markdown
	 *
	 * @param $text
	 *
	 * @return mixed
	 */
	public function do_parsemarkdown( $text ) {
		//process markdown text

		return Markdown::defaultTransform( $text );
	}//end do_parsemarkdown

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$version = $this->version;

		$public_css = plugin_dir_url( __FILE__ ) . '../assets/css/cbxchangelog-public.css';
		$public_css = apply_filters( 'cbxchangelog_pubic_css', $public_css );

		wp_register_style( 'cbxchangelog-public-css', $public_css, [], $version, 'all' );
		wp_enqueue_style( 'cbxchangelog-public-css' );

		do_action( 'cbxchangelog_enqueue_pro_style' );
	}//end enqueue_styles

	/**
	 * unused method
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cbxchangelog-public.js', array( 'jquery' ), $this->version, false );
	}//end method enqueue_scripts

	/**
	 * Initialize the widgets
	 */
	function init_widgets() {
		register_widget( 'CBXChangelogWidget' );

	}//end method init_widgets

	/**
	 * Init elementor widget
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {
		if ( ! class_exists( 'CBXChangeLog_ElemWidget' ) ) {
			//include the file
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor-elements/class-cbxchangelog-elemwidget.php';
		}

		//register the widget
		\Elementor\Plugin::instance()->widgets_manager->register( new CBXChangeLogElemWidget\Widgets\CBXChangeLog_ElemWidget() );
	}//end widgets_registered

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'codeboxr',
			[
				'title' => esc_html__( 'Codeboxr Widgets', 'cbxchangelog' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}//end add_elementor_widget_categories

	/**
	 * Load Elementor Custom Icon
	 */
	function elementor_icon_loader() {
		wp_register_style( 'cbxchangelog_elementor_icon', CBXCHANGELOG_ROOT_URL . 'widgets/elementor-elements/elementor-icon/icon.css', false, CBXCHANGELOG_PLUGIN_VERSION );
		wp_enqueue_style( 'cbxchangelog_elementor_icon' );

	}//end elementor_icon_loader

	/**
	 * Before VC Init
	 */
	public function vc_before_init_actions() {
		if ( ! class_exists( 'CBXChangeLog_WPBWidget' ) ) {
			require_once CBXCHANGELOG_ROOT_PATH . 'widgets/vc-element/class-cbxchangelog-wpbwidget.php';
		}

		new CBXChangeLog_WPBWidget();
	}// end method vc_before_init_actions
}//end CBXChangelogPublic