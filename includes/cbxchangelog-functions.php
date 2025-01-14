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

		$meta_changelog  = CBXChangelogHelper::get_changelog( $post_id );
		//$meta_changelog = get_post_meta( $post_id, '_cbxchangelog', true );
		if ( is_array( $meta_changelog ) && sizeof($meta_changelog) > 0 ) {
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

if(!function_exists('cbxchangelog_get_order_keys')){
	/**
	 * Get order keys
	 *
	 * @return string[]
	 */
	function cbxchangelog_get_order_keys() {
		return CBXChangelogHelper::get_order_keys();
	}//end method cbxchangelog_get_order_keys
}

if(!function_exists('cbxchangelog_get_orderby_keys')){
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
	function cbxchangelog_load_svg( $svg_name = '', $folder = '') {
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
		if($folder != ''){
			$folder = trailingslashit($folder);
		}

		// Construct the full file path.
		$file_path = $directory. $folder . $svg_name . '.svg';
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

if(!function_exists('cbxchangelog_custom_max')){
	function cbxchangelog_custom_max(...$values): int {
		// If a single array is passed as the first argument
		if (count($values) === 1 && is_array($values[0])) {
			$values = $values[0];
		}

		// If the array is empty or no arguments were passed, return 0
		return empty($values) ? 0 : max($values);
	}//end function cbxchangelog_custom_max
}
