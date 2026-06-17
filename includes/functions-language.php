<?php
/*
	language functions
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ConditionalLinks;

/**
 * Load language files.
 */
function load_languages() {
	$plugin_rel_path = basename( dirname( PLUGIN_FILE ) ) . '/assets/languages';
	load_plugin_textdomain( 'azrcrv-cl', false, $plugin_rel_path );
}
