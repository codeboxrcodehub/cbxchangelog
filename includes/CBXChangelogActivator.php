<?php

/**
 * Fired during plugin activation
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/includes
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXChangelogActivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( 'cbxchangelog_flush_rewrite_rules', 'true' );

		set_transient( 'cbxchangelog_activated_notice', 1 );

		// Update the saved version
		update_option('cbxchangelog_version', CBXCHANGELOG_PLUGIN_VERSION);
	}//end method activate
}//end CBXChangelogActivator