<?php
/**
 * CBX Changelog & Release Notecore file
 *
 * @link              http://codeboxr.com
 * @since             1.0.0
 * @package           Cbxchangelog
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Changelog & Release Note
 * Plugin URI:        http://codeboxr.com/product/cbx-changelog-for-wordpress/
 * Description:       Easy change log manager for WordPress, use for any product post type or releases notes
 * Version:           2.0.8
 * Author:            Codeboxr
 * Author URI:        http://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxchangelog
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


defined( 'CBXCHANGELOG_PLUGIN_NAME' ) or define( 'CBXCHANGELOG_PLUGIN_NAME', 'cbxchangelog' );
defined( 'CBXCHANGELOG_PLUGIN_VERSION' ) or define( 'CBXCHANGELOG_PLUGIN_VERSION', '2.0.8' );
defined( 'CBXCHANGELOG_ROOT_PATH' ) or define( 'CBXCHANGELOG_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXCHANGELOG_ROOT_URL' ) or define( 'CBXCHANGELOG_ROOT_URL', plugin_dir_url( __FILE__ ) );
defined( 'CBXCHANGELOG_BASE_NAME' ) or define( 'CBXCHANGELOG_BASE_NAME', plugin_basename( __FILE__ ) );

defined( 'CBXCHANGELOG_WP_MIN_VERSION' ) or define( 'CBXCHANGELOG_WP_MIN_VERSION', '5.3' );
defined( 'CBXCHANGELOG_PHP_MIN_VERSION' ) or define( 'CBXCHANGELOG_PHP_MIN_VERSION', '7.4' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/CBXChangelog.php';

/**
 * Checking wp version
 *
 * @return bool
 */
function cbxchangelog_compatible_wp_version($version = '') {
	if($version == '') $version = CBXCHANGELOG_WP_MIN_VERSION;
	if ( version_compare( $GLOBALS['wp_version'], $version, '<' ) ) {
		return false;
	}

	// Add sanity checks for other version requirements here

	return true;
}//end method cbxchangelog_compatible_wp_version

/**
 * Checking php version
 *
 * @return bool
 */
function cbxchangelog_compatible_php_version($version = '') {
	if($version == '') $version = CBXCHANGELOG_PHP_MIN_VERSION;
	if ( version_compare( PHP_VERSION, $version, '<' ) ) {
		return false;
	}

	return true;
}//end method cbxchangelog_compatible_php_version


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxchangelog-activator.php
 */
function activate_cbxchangelog() {
	if ( ! cbxchangelog_compatible_wp_version() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'CBX Changelog & Release Note plugin requires WordPress 5.3 or higher!', 'cbxchangelog' ) );
	}

	if ( ! cbxchangelog_compatible_php_version() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'CBX Changelog & Release Note plugin requires PHP 7.4 or higher!', 'cbxchangelog' ) );
	}

	//register_uninstall_hook( __FILE__, 'uninstall_cbxchangelog' );
	
	//now it's safe to move forward
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBXChangelogActivator.php';
	CBXChangelogActivator::activate();
}//end method activate_cbxchangelog

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxchangelog-deactivator.php
 */
function deactivate_cbxchangelog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBXChangelogDeactivator.php';
	CBXChangelogDeactivator::deactivate();
}//end method deactivate_cbxchangelog



register_activation_hook( __FILE__, 'activate_cbxchangelog' );
register_deactivation_hook( __FILE__, 'deactivate_cbxchangelog' );




/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxchangelog() {
	return Cbxchangelog::instance();
}

$GLOBALS['cbxchangelog_core'] = run_cbxchangelog();