<?php

/**
 * Helper class with lots of static methods
 *
 * Class CBXChangelogHelper
 */
class CBXChangelogHelper {
	/**
	 * supported post types
	 *
	 * @return mixed|void
	 */
	public static function supported_post_types() {
		$allowed_post_types = [ 'cbxchangelog' ];

		$allowed_post_types = apply_filters( 'cbxchangelog_post_types_support', $allowed_post_types );

		return $allowed_post_types;
	}//end supported_post_types


	/**
	 * Issue label
	 *
	 * @return mixed|void
	 */
	public static function cbxchangelog_labels() {
		$label_options = [
			//'none'          => esc_html__( 'Issue Type', 'cbxchangelog' ),
			'added'         => esc_html__( 'Added', 'cbxchangelog' ),
			'fixed'         => esc_html__( 'Fixed', 'cbxchangelog' ),
			'updated'       => esc_html__( 'Updated', 'cbxchangelog' ),
			'improved'      => esc_html__( 'Improved', 'cbxchangelog' ),
			'removed'       => esc_html__( 'Removed', 'cbxchangelog' ),
			'deprecate'     => esc_html__( 'Deprecated', 'cbxchangelog' ),
			'compatibility' => esc_html__( 'Compatibility', 'cbxchangelog' ),
		];

		return apply_filters( 'cbxchangelog_labels', $label_options );
	}//end cbxchangelog_labels

	/**
	 * Get avaliable layouts
	 *
	 * @return mixed|void
	 */
	public static function get_layouts() {
		$layouts = [
			'prepros'       => esc_html__( 'Prepros(Default)', 'cbxchangelog' ),
			'classic_plain' => esc_html__( 'Classic Plain', 'cbxchangelog' ),
		];

		return apply_filters( 'cbxchangelog_layouts', $layouts );
	}//end method get_layouts


	/**
	 * Get layouts from mata
	 *
	 * @return mixed|void
	 */
	public static function get_layouts_for_meta() {
		$layouts = [
			'' => esc_html__( 'Choose from post meta', 'cbxchangelog' ),
		];

		return apply_filters( 'get_layouts_for_meta', $layouts );
	}//end method get_layouts_for_meta

	/**
	 * Returns post types as array
	 *
	 * @return array
	 */
	public static function post_types() {
		$post_type_args = [
			'builtin' => [
				'options' => [
					'public'   => true,
					'_builtin' => true,
					'show_ui'  => true,
				],
				'label'   => esc_html__( 'Built in post types', 'cbxchangelog' ),
			]

		];

		$post_type_args = apply_filters( 'cbxchangelog_post_types', $post_type_args );

		$output    = 'objects'; // names or objects, note names is the default
		$operator  = 'and';     // 'and' or 'or'
		$postTypes = [];

		foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
			$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

			if ( ! empty( $types ) ) {
				foreach ( $types as $type ) {
					if ( $type->name == 'cbxchangelog' ) {
						continue;
					}
					$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
					$postTypes[ $postArgType ]['types'][ $type->name ] = $type->labels->name;
				}
			}
		}

		return $postTypes;
	}//end post_types

	/**
	 * Return the key value pair of post types
	 *
	 * @param $all_post_types
	 *
	 * @return array
	 */
	public static function post_types_multiselect( $all_post_types ) {

		$posts_definition = [];

		foreach ( $all_post_types as $key => $post_type_defination ) {
			foreach ( $post_type_defination as $post_type_type => $data ) {
				if ( $post_type_type == 'label' ) {
					$opt_grouplabel = $data;
				}

				if ( $post_type_type == 'types' ) {
					foreach ( $data as $opt_key => $opt_val ) {
						$posts_definition[ $opt_grouplabel ][ $opt_key ] = $opt_val;
					}
				}
			}
		}

		return $posts_definition;
	}//end post_types_multiselect

	/**
	 * Plain post types list
	 *
	 * @return array
	 */
	public static function post_types_plain() {
		$post_types = self::post_types();
		$post_arr   = [];

		foreach ( $post_types as $optgroup => $types ) {
			foreach ( $types['types'] as $type_slug => $type_name ) {
				$post_arr[ esc_attr( $type_slug ) ] = wp_unslash( $type_name );
			}
		}

		return $post_arr;
	}//end post_types_plain

	/**
	 * Plain post types list in reverse
	 *
	 * @return array
	 */
	public static function post_types_plain_r() {
		$post_types = self::post_types_plain();

		$post_arr = [];

		foreach ( $post_types as $key => $value ) {
			$post_arr[ esc_attr( wp_unslash( $value ) ) ] = esc_attr( $key );
		}

		return $post_arr;
	}//end post_types_plain_r


	/**
	 * Setup a post object and store the original loop item so we can reset it later
	 *
	 * @param  obj  $post_to_setup  The post that we want to use from our custom loop
	 */
	public static function setup_admin_postdata( $post_to_setup ) {
		//only on the admin side
		if ( is_admin() ) {

			//get the post for both setup_postdata() and to be cached
			global $post;

			//only cache $post the first time through the loop
			if ( ! isset( $GLOBALS['post_cache'] ) ) {
				$GLOBALS['post_cache'] = $post;
			}

			//setup the post data as usual
			$post = $post_to_setup;
			setup_postdata( $post );
		} else {
			setup_postdata( $post_to_setup );
		}
	}//end method setup_admin_postdata


	/**
	 * Reset $post back to the original item
	 *
	 */
	public static function wp_reset_admin_postdata() {

		//only on the admin and if post_cache is set
		if ( is_admin() && ! empty( $GLOBALS['post_cache'] ) ) {

			//globalize post as usual
			global $post;

			//set $post back to the cached version and set it up
			$post = $GLOBALS['post_cache'];
			setup_postdata( $post );

			//cleanup
			unset( $GLOBALS['post_cache'] );
		} else {
			wp_reset_postdata();
		}
	}//end method wp_reset_admin_postdata

	/**
	 * Add utm params to any url
	 *
	 * @param  string  $url
	 *
	 * @return string
	 */
	public static function url_utmy( $url = '' ) {
		if ( $url == '' ) {
			return $url;
		}

		$url = add_query_arg( [
			'utm_source'   => 'plgsidebarinfo',
			'utm_medium'   => 'plgsidebar',
			'utm_campaign' => 'wpfreemium',
		], $url );

		return $url;
	}//end url_utmy


	/**
	 * Get Related time from a timestamp
	 *
	 * @param $utimestamp
	 *
	 * @return string
	 */
	public static function getChangelogHumanReadableTime( $utimestamp ) {
		/* translators: 1. Time difference  */
		return sprintf( _x( '%s ago', '%s = human-readable time difference', 'cbxchangelog' ),
			human_time_diff( $utimestamp, time() ) );
	}//end method getChangelogHumanReadableTime


	/**
	 * List all global option name with prefix cbxchangelog_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix = 'cbxchangelog_';

		$wild = '%';
		$like = $wpdb->esc_like( $prefix ) . $wild;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$option_names = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE %s", $like ), ARRAY_A );

		return apply_filters( 'cbxchangelog_option_names', $option_names );
	}//end method getAllOptionNames

	/**
	 * Option names only
	 *
	 * @return array
	 */
	public static function getAllOptionNamesValues() {
		$option_values = self::getAllOptionNames();
		$names_only    = [];

		foreach ( $option_values as $key => $value ) {
			$names_only[] = $value['option_name'];
		}

		return $names_only;
	}//end method getAllOptionNamesValues

	/**
	 * Returns setting sections
	 *
	 * @return mixed|null
	 */
	public static function get_settings_sections() {
		return apply_filters(
			'cbxchangelog_setting_sections', [
				[
					'id'    => 'cbxchangelog_general',
					'title' => esc_html__( 'General', 'cbxchangelog' )
				],
				[
					'id'    => 'cbxchangelog_tools',
					'title' => esc_html__( 'Tools', 'cbxchangelog' )
				]
			]
		);
	}//end method get_settings_sections

	/**
	 * Return setting fields
	 *
	 * @return mixed|null
	 */
	public static function get_settings_fields() {
		$ajax_nonce = wp_create_nonce( "cbxchangelog_nonce" );

		/*$reset_data_link = add_query_arg( [
			'cbxchangelog_fullreset' => 1,
			'security'               => $ajax_nonce
		], admin_url( 'edit.php?post_type=cbxchangelog&page=cbxchangelog-settings' ) );*/

		$table_html = '<div id="cbxchangelog_resetinfo_wrap">' . esc_html__( 'Loading ...', 'cbxchangelog' ) . '</div>';

		$layout_options          = CbxchangelogHelper::get_layouts();
		$settings_builtin_fields = [
			'cbxchangelog_general' => [
				'basics_heading' => [
					'name'    => 'basics_heading',
					'label'   => esc_html__( 'General Settings', 'cbxchangelog' ),
					'type'    => 'heading',
					'default' => '',
				],
				'use_markdown'   => [
					'name'    => 'use_markdown',
					'label'   => esc_html__( 'Markdown Support', 'cbxchangelog' ),
					'desc'    => esc_html__( 'If selected, then markdown will be parsed while displaying release note or any single feature', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 0,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' ),
					]
				],
				'show_label'     => [
					'name'    => 'show_label',
					'label'   => esc_html__( 'Show Release Labels', 'cbxchangelog' ),
					'desc'    => esc_html__( 'Show release item labels (example: added, fixed, deleted etc) while displaying in frontend', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 1,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' ),
					]
				],
				'show_date'      => [
					'name'    => 'show_date',
					'label'   => esc_html__( 'Show Release Date', 'cbxchangelog' ),
					'desc'    => esc_html__( 'Show release date in frontend', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 1,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' ),
					]
				],
				'show_url'       => [
					'name'    => 'show_url',
					'label'   => esc_html__( 'Show Release url', 'cbxchangelog' ),
					'desc'    => esc_html__( 'Show release url in frontend', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 1,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' ),
					]
				],
				'relative_date'  => [
					'name'    => 'relative_date',
					'label'   => esc_html__( 'Relative date', 'cbxchangelog' ),
					'desc'    => esc_html__( 'Show/Hide Relative date ', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 0,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' )
					]
				],

				'layout'         => [
					'name'    => 'layout',
					'label'   => esc_html__( 'Choose layout', 'cbxchangelog' ),
					'desc'    => '',
					'type'    => 'select',
					'default' => 'prepros',
					'options' => $layout_options
				],
				'changelog_auto' => [
					'name'    => 'changelog_auto',
					'label'   => esc_html__( 'Append change logs', 'cbxchangelog' ),
					'desc'    => esc_html__( 'Append change logs for changelog post type. If you disable this then you have to use the shortcode method to display change log information inside changelog post type\'s content', 'cbxchangelog' ),
					'type'    => 'radio',
					'default' => 1,
					'options' => [
						1 => esc_html__( 'Yes', 'cbxchangelog' ),
						0 => esc_html__( 'No', 'cbxchangelog' ),
					],
				],
				/*'test4'          => [
					'name'    => 'test4',
					'label'   => esc_html__( 'Color Field Test 2', 'cbxchangelog' ),
					'type'    => 'color',
					'default' => '#000000',
				],*/
				/*'file'          => [
					'name'    => 'file',
					'label'   => esc_html__( 'Color Field Test 2', 'cbxchangelog' ),
					'type'    => 'file',
					'default' => '',
				]*/

			],
			'cbxchangelog_tools'   => [
				'tools_heading'        => [
					'name'    => 'tools_heading',
					'label'   => esc_html__( 'Tools Settings', 'cbxchangelog' ),
					'type'    => 'heading',
					'default' => '',
				],
				'delete_global_config' => [
					'name'    => 'delete_global_config',
					'label'   => esc_html__( 'On Uninstall delete plugin data', 'cbxchangelog' ),
					'desc'    => '<p>' . esc_html__( 'Delete Global Config data and custom table created by this plugin on uninstall.', 'cbxchangelog' ) . '</p>' . '<p><strong>' . esc_html__( 'Please note that this process can not be undone and it is recommended to keep full database backup before doing this.', 'cbxchangelog' ) . '</strong></p>',
					'type'    => 'radio',
					'options' => [
						'yes' => esc_html__( 'Yes', 'cbxchangelog' ),
						'no'  => esc_html__( 'No', 'cbxchangelog' ),
					],
					'default' => 'no'
				],
				'reset_data'           => [
					'name'    => 'reset_data',
					'label'   => esc_html__( 'Reset all data', 'cbxchangelog' ),
					'desc'    => $table_html . '<p>' . esc_html__( 'Reset option values and all tables created by this plugin', 'cbxchangelog' ) . '<a data-busy="0" class="button secondary ml-20" id="reset_data_trigger"  href="#">' . esc_html__( 'Reset Data', 'cbxchangelog' ) . '</a></p>',
					'type'    => 'html',
					'default' => 'off'
				],
			]
		];


		$settings_fields = []; //final setting array that will be passed to different filters

		$sections = self::get_settings_sections();


		foreach ( $sections as $section ) {
			if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
				$settings_builtin_fields[ $section['id'] ] = [];
			}
		}


		foreach ( $sections as $section ) {

			$settings_fields[ $section['id'] ] = apply_filters( 'cbxchangelog_global_' . $section['id'] . '_fields', $settings_builtin_fields[ $section['id'] ] );
		}

		$settings_fields = apply_filters( 'cbxchangelog_global_fields', $settings_fields ); //final filter if need

		return $settings_fields;
	}//end method get_settings_fields

	/**
	 * Plugin reset html table
	 *
	 * @return string
	 * @since 1.1.0
	 *
	 */
	public static function setting_reset_html_table() {
		$option_values = CBXChangelogHelper::getAllOptionNames();

		$table_html = '<div id="cbxchangelog_resetinfo"';
		$table_html .= '<p style="margin-bottom: 15px;" id="cbxchangelog_plg_gfig_info"><strong>' . esc_html__( 'Following option values created by this plugin(including addon) from WordPress core option table', 'cbxchangelog' ) . '</strong></p>';

		$table_html .= '<p style="margin-bottom: 10px;" class="grouped gapless grouped_buttons" id="cbxchangelog_setting_options_check_actions"><a href="#" class="button primary cbxchangelog_setting_options_check_action_call">' . esc_html__( 'Check All', 'cbxchangelog' ) . '</a><a href="#" class="button outline cbxchangelog_setting_options_check_action_ucall">' . esc_html__( 'Uncheck All', 'cbxchangelog' ) . '</a></p>';

		$table_html .= '<table class="widefat widethin cbxchangelog_table_data">
	<thead>
	<tr>
		<th class="row-title">' . esc_attr__( 'Option Name', 'cbxchangelog' ) . '</th>
		<th>' . esc_attr__( 'Option ID', 'cbxchangelog' ) . '</th>		
	</tr>
	</thead>';

		$table_html .= '<tbody>';

		$i = 0;
		foreach ( $option_values as $key => $value ) {
			$alternate_class = ( $i % 2 == 0 ) ? 'alternate' : '';
			$i ++;
			$table_html .= '<tr class="' . esc_attr( $alternate_class ) . '">
									<td class="row-title"><input checked class="magic-checkbox reset_options" type="checkbox" name="reset_options[' . $value['option_name'] . ']" id="reset_options_' . esc_attr( $value['option_name'] ) . '" value="' . $value['option_name'] . '" />
  <label for="reset_options_' . esc_attr( $value['option_name'] ) . '">' . esc_attr( $value['option_name'] ) . '</td>
									<td>' . esc_attr( $value['option_id'] ) . '</td>									
								</tr>';
		}

		$table_html .= '</tbody>';
		$table_html .= '<tfoot>
	<tr>
		<th class="row-title">' . esc_attr__( 'Option Name', 'cbxchangelog' ) . '</th>
		<th>' . esc_attr__( 'Option ID', 'cbxchangelog' ) . '</th>				
	</tr>
	</tfoot>
</table>';

		$table_html .= '</div>';

		return $table_html;
	}//end method setting_reset_html_table

	/**
	 * Get order keys
	 *
	 * @return string[]
	 * @since 1.1.6
	 *
	 */
	public static function get_order_keys() {
		return [ 'asc', 'desc' ];
	}//end method get_order_keys

	/**
	 * Get order keys
	 *
	 * @return string[]
	 * @since 1.1.6
	 *
	 */
	public static function get_orderby_keys() {
		return [ 'default', 'date' ];
	}//end method get_orderby_keys

	/**
	 * Get any plugin version number
	 *
	 * @param $plugin_slug
	 *
	 * @return mixed|string
	 */
	public static function get_any_plugin_version( $plugin_slug = '' ) {
		if ( $plugin_slug == '' ) {
			return '';
		}

		// Ensure the required file is loaded
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get all installed plugins
		$all_plugins = get_plugins();

		// Check if the plugin exists
		if ( isset( $all_plugins[ $plugin_slug ] ) ) {
			return $all_plugins[ $plugin_slug ]['Version'];
		}

		// Return false if the plugin is not found
		return '';
	}//end method get_pro_addon_version

	/**
	 * Convert change logs from old format to new
	 *
	 * @param $logs
	 *
	 *
	 * @return array|void
	 * @since 1.1.6
	 */
	public static function changelogs_convert( $logs = [] ) {
		if ( ! is_array( $logs ) || ( is_array( $logs ) && sizeof( $logs ) == 0 ) ) {
			return [
				'data'      => [],
				'nextIndex' => 1,
				'usedKeys'  => []
			];
		}

		//check if already converted
		if(isset($logs['usedKeys'])) return $logs;

		$data       = [];
		$used_keys  = [];
		$next_index = 0;

		foreach ( $logs as $index => $log ) {
			$index     = absint( $index );
			$id        = $index + 1;
			$log['id'] = $id;
			$data[]    = $log;

			$used_keys[] = $id;
			$next_index  = $id + 1;
		}

		return [
			'data'      => $data,
			'usedKeys'  => $used_keys,
			'nextIndex' => $next_index
		];
	}//end method changelogs_convert

	/**
	 * Get changelog by post id in ready format
	 *
	 * @param  integer  $post_id
	 * @param  integer  $release_id
	 *
	 * @return array|mixed
	 * @since 1.1.6
	 */
	public static function get_changelog( $post_id = 0, $release_id = 0 ) {
		$post_id    = absint( $post_id );
		$release_id = absint( $release_id );

		$meta_data = new CBXChangelogMetaAsArray( $post_id, '_cbxchangelog' );

		return ( $release_id > 0 ) ? $meta_data->get( $release_id ) : $meta_data->getAll();
	}//end method get_changelog

	/**
	 * Get changelog data by post id
	 *
	 * @param  integer  $post_id
	 *
	 *
	 * @return CBXChangelogMetaAsArray
	 * @since 1.1.6
	 */
	public static function get_changelog_data( $post_id = 0 ) {
		$post_id = absint( $post_id );

		return new CBXChangelogMetaAsArray( $post_id, '_cbxchangelog' );
	}//end method get_changelog_data

	/**
	 * WordPress readme.txt file content parsing for changelog
	 *
	 * @param $readmeContent
	 * @since 1.1.6
	 * @return array
	 */
	public static function parse_wordpress_readme_changelog($readmeContent) {
		$lines = explode("\n", $readmeContent);
		$changelog = [];
		$inChangelog = false;
		$currentVersion = null;

		foreach ($lines as $line) {
			$line = trim($line);

			// Check for the start of the changelog section
			if (preg_match('/^==\s*Changelog\s*==$/i', $line)) {
				$inChangelog = true;
				continue;
			}

			// Exit if changelog ends (e.g., another section starts)
			if ($inChangelog && preg_match('/^==\s*.+\s*==$/', $line)) {
				break;
			}

			// Parse version headings
			if ($inChangelog && preg_match('/^=\s*([\d.]+)\s*=/', $line, $matches)) {
				$currentVersion = $matches[1];
				$changelog[$currentVersion] = [];
				continue;
			}

			// Add changelog entries
			if ($inChangelog && $currentVersion && $line !== '') {
				$changelog[$currentVersion][] = $line;
			}
		}

		return $changelog;
	}//end method parse_wordpress_readme_changelog
}//end method CBXChangelogHelper