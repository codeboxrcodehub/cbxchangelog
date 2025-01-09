<?php

/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * The code that runs during plugin uninstall.
 */
function uninstall_cbxchangelog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBXChangelogHelper.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBXChangelogSettings.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/CBXChangelogUninstall.php';

	CBXChangelogUninstall::uninstall();
}//end function uninstall_cbxchangelog

if (! defined( 'CBXCHANGELOG_PLUGIN_NAME' ) ) {
	uninstall_cbxchangelog();
}