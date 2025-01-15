<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'cbxchangelog_get_single' ) ) {
	function cbxchangelog_get_single( $post_id = 0 ) {
		$post_id = absint( $post_id );

		if ( $post_id == 0 ) {
			return [];
		}

		$meta_changelog = CBXChangelogHelper::get_changelog( $post_id );
		//$meta_changelog = get_post_meta( $post_id, '_cbxchangelog', true );
		if ( is_array( $meta_changelog ) && sizeof( $meta_changelog ) > 0 ) {
			$meta_extra = get_post_meta( $post_id, '_cbxchangelog_extra', true );
			if ( ! is_array( $meta_extra ) ) {
				$meta_extra = [];
			}

			$meta = [
				'cbxchangelogs_data' => $meta_changelog,
				'cbxchangelog_extra' => $meta_extra,
			];

			return $meta;
		}

		return [];
	}//end function cbxchangelog_get_single
}//end if function exists cbxchangelog_get_single

if ( ! function_exists( 'cbxchangelog_get_order_keys' ) ) {
	/**
	 * Get order keys
	 *
	 * @return string[]
	 */
	function cbxchangelog_get_order_keys() {
		return CBXChangelogHelper::get_order_keys();
	}//end method cbxchangelog_get_order_keys
}

if ( ! function_exists( 'cbxchangelog_get_orderby_keys' ) ) {
	/**
	 * Get order keys
	 *
	 * @return string[]
	 */
	function cbxchangelog_get_orderby_keys() {
		return CBXChangelogHelper::get_orderby_keys();
	}//end method cbxchangelog_get_orderby_keys
}


if ( ! function_exists( 'cbxchangelog_load_svg' ) ) {
	/**
	 * Load an SVG file from a directory.
	 *
	 * @param  string  $svg_name  The name of the SVG file (without the .svg extension).
	 * @param  string  $directory  The directory where the SVG files are stored.
	 *
	 * @return string|false The SVG content if found, or false on failure.
	 * @since 1.0.0
	 */
	function cbxchangelog_load_svg( $svg_name = '', $folder = '' ) {
		if ( $svg_name == '' ) {
			return '';
		}


		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$credentials = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, null );
		if ( ! WP_Filesystem( $credentials ) ) {
			return ''; // Error handling here
		}

		global $wp_filesystem;


		$directory = cbxchangelog_icon_path();

		// Sanitize the file name to prevent directory traversal attacks.
		$svg_name = sanitize_file_name( $svg_name );
		if ( $folder != '' ) {
			$folder = trailingslashit( $folder );
		}

		// Construct the full file path.
		$file_path = $directory . $folder . $svg_name . '.svg';
		$file_path = apply_filters( 'cbxchangelog_svg_file_path', $file_path, $svg_name );

		// Check if the file exists.
		//if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
		if ( $wp_filesystem->exists( $file_path ) && is_readable( $file_path ) ) {
			// Get the SVG file content.
			return $wp_filesystem->get_contents( $file_path );
		} else {
			// Return false if the file does not exist or is not readable.
			return '';
		}
	}//end method cbxchangelog_load_svg
}

if ( ! function_exists( 'cbxchangelog_icon_path' ) ) {
	/**
	 * Resume icon path
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	function cbxchangelog_icon_path() {
		$directory = trailingslashit( CBXCHANGELOG_ROOT_PATH ) . 'assets/icons/';

		return apply_filters( 'cbxchangelog_icon_path', $directory );
	}//end method cbxchangelog_icon_path
}

if ( ! function_exists( 'cbxchangelog_custom_max' ) ) {
	function cbxchangelog_custom_max( ...$values ): int {
		// If a single array is passed as the first argument
		if ( count( $values ) === 1 && is_array( $values[0] ) ) {
			$values = $values[0];
		}

		// If the array is empty or no arguments were passed, return 0
		return empty( $values ) ? 0 : max( $values );
	}//end function cbxchangelog_custom_max
}

if ( ! function_exists( 'cbxchangelog_search_2d_array' ) ) {
	/**
	 * Searches a 2D array for a specific key and value.
	 *
	 * @param  array  $array  The 2D array to search.
	 * @param  string  $key  The key name to search for.
	 * @param  mixed  $value  The value to compare against.
	 *
	 * @return bool True if a row with the key and value is found, otherwise false.
	 * @since 2.0.1
	 */
	function cbxchangelog_search_2d_array( $array, $key, $value ) {
		foreach ( $array as $row ) {
			if ( isset( $row[ $key ] ) && $row[ $key ] == $value ) {
				return true;
			}
		}

		return false;
	}//end function cbxchangelog_search_2d_array
}

if ( ! function_exists( 'cbxchangelog_labels' ) ) {
	function cbxchangelog_labels() {
		return CbxchangelogHelper::cbxchangelog_labels();
	}//end function cbxchangelog_labels
}

if ( ! function_exists( 'cbxchangelog_label_keys' ) ) {
	function cbxchangelog_label_keys() {
		$labels = CbxchangelogHelper::cbxchangelog_labels();

		return array_keys( $labels );
	}//end function cbxchangelog_label_keys
}


if ( ! function_exists( 'cbxchangelog_label_key_matching' ) ) {
	function cbxchangelog_label_key_matching( $feature = '' ) {
		if ( $feature == '' ) {
			return 'added';
		}

		$label_options = cbxchangelog_label_keys();
		// Define exceptions for specific keywords
		$exceptions = [
			'add'     => 'added',
			'fix'     => 'fixed',
			'update'  => 'updated',
			'improve' => 'improved',
			'remove'  => 'removed',
		];

		// Check for exceptions first
		foreach ( $exceptions as $keyword => $label ) {
			if ( stripos( $feature, $keyword ) !== false ) {
				return $label; // Return the exception label if a match is found
			}
		}

		// Check for exact matches in the label options
		foreach ( $label_options as $label ) {
			if ( stripos( $feature, $label ) !== false ) {
				return $label; // Return the label if found
			}
		}

		// Default return if no match is found
		return 'added';
	}//end method cbxchangelog_label_key_matching
}

if(!function_exists('cbxchangelog_isValidSemver')){
	/**
	 * Validates if a version string follows Semantic Versioning (SemVer).
	 *
	 * @param string $version The version string to validate.
	 * @return bool True if the version follows SemVer, otherwise false.
	 */
	function cbxchangelog_isValidSemver($version) {
		// SemVer regex pattern
		$semverPattern = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)' . // Major.Minor.Patch
		                 '(?:-([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?' .    // Optional pre-release
		                 '(?:\+([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?$/'; // Optional build metadata

		// Check if the version matches the pattern
		return preg_match($semverPattern, $version) === 1;
	}//end function cbxchangelog_isValidSemver
}


if(!function_exists('cbxchangelog_group_by_labels')){
	function cbxchangelog_group_by_labels($data) {
		$label_options = cbxchangelog_label_keys();
		// Create an associative array of labels with their priorities (index in $label_options)
		$label_priorities = array_flip($label_options);

		// Sort the $data array using a custom comparison function
		usort($data, function ($a, $b) use ($label_priorities) {
			// Get the priorities for the labels from the $label_priorities array
			$priorityA = $label_priorities[$a['label']] ?? PHP_INT_MAX;
			$priorityB = $label_priorities[$b['label']] ?? PHP_INT_MAX;

			// Compare based on priority
			return $priorityA <=> $priorityB;
		});

		return $data;
	}//end function cbxchangelog_group_by_labels
}