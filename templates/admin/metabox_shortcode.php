<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    Cbxchangelog
 * @subpackage Cbxchangelog/templates/admin
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="cbxshortcode-wrap">
	<?php
	echo '<span data-clipboard-text=\'[cbxchangelog id="' . absint($post_id) . '"]\' title="' . esc_html__( "Click to clipboard",
			"cbxchangelog" ) . '" id="cbxchangelogshortcode-' . absint($post_id) . '" class="cbxshortcode cbxshortcode-edit cbxshortcode-' . absint( $post_id ) . '">[cbxchangelog id="' . absint( $post_id ) . '"]</span>';
	echo '<span class="cbxballon_ctp_btn cbxballon_ctp" aria-label="' . esc_html__( 'Click to copy', 'cbxchangelog' ) . '" data-balloon-pos="up"><i></i></span>';
	?>
</div>