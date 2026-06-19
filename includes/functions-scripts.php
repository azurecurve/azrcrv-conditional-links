<?php
/*
	script functions
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ConditionalLinks;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Register admin scripts.
 */
function register_admin_scripts() {
	wp_register_script( 'azrcrv-admin-standard-js', esc_url_raw( plugins_url( '../assets/js/admin-standard.js', __FILE__ ) ), array(), '2.0.0', true );
}

/**
 * Enqueue admin scripts.
 */
function enqueue_admin_scripts() {
	global $pagenow;

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['page'] ) && ( $_GET['page'] == PLUGIN_HYPHEN || $_GET['page'] == 'azrcrv-plugin-menu' ) || $pagenow == 'profile.php' || $pagenow == 'edit-user.php' ) {
		wp_enqueue_script( 'azrcrv-admin-standard-js' );
	}
}
