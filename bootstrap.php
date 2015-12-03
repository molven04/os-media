<?php
/*
Plugin Name:       OS media - HTML5 Featured Video plugin
Plugin URI:        http://mariomarino.eu/
Description:       Responsive HTML5 video platform for WordPress, based on the latest Video.js player library. 
Allows you to embed video in your post or page using HTML5 with Flash fallback support for non-HTML5 browsers.
Version:           2.1
Author:            Mario Marino
Author URI:        http://mariomarino.eu/
Text Domain:       single-post-meta-manager-locale
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:       /languages
*/

/////////////////////////////////////////////// COSTANT /////////////////////////////////////////////////////////////
define( 'OSmedia_NAME', 'OSmedia' );
define( 'OSmedia_REQUIRED_PHP_VERSION', '5.3' );                          // because of get_called_class()
define( 'OSmedia_REQUIRED_WP_VERSION', '3.1' );                          // because of esc_textarea()
define( 'OSmedia_PATH', plugin_dir_path( __FILE__ ) );
define( 'OSmedia_FOLDER', basename(OSmedia_PATH) );
define( 'OSmedia_OPTS', 'OSmedia_settings' );

// CPT
define( 'POST_TYPE_NAME', 'OSmedia video' );
define( 'POST_TYPE_SLUG', 'osmedia_cpt' );
define( 'TAG_NAME', 'OSmedia Taxonomy' );
define( 'TAG_SLUG', 'osmedia_tax' );
// post
define( 'POSTMETA_NAME', 'OSmedia: HTML5 Video Shortcode Generator' );
define( 'POSTMETA_SLUG', 'post' );
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ( ! defined( 'ABSPATH' ) ) {	die( 'Access denied.' ); }

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function OSmedia_requirements_met() {
	
	global $wp_version;
	//require_once( ABSPATH . '/wp-admin/includes/plugin.php' );		// to get is_plugin_active() early
	if ( version_compare( PHP_VERSION, OSmedia_REQUIRED_PHP_VERSION, '<' ) ) return false;
	if ( version_compare( $wp_version, OSmedia_REQUIRED_WP_VERSION, '<' ) ) return false;
	// if ( ! is_plugin_active( 'plugin-directory/plugin-file.php' ) ) return false;
	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function OSmedia_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

/*
*
*/
function OSmedia_parent_path_with_file($filepath, $needle, $limit) {
	$curr_path = dirname($filepath);
	for ($i = 0; $i < $limit; $i++) {
		$ls = scandir($curr_path);
		if (isset($ls) && is_array($ls) && in_array($needle, $ls)) return $curr_path;
		$curr_path = dirname($curr_path);
	}
	return NULL;
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( OSmedia_requirements_met() ) {
	require_once( __DIR__ . '/OSmedia-functions.php' );
	require_once( __DIR__ . '/classes/OSmedia_browser.php' );
	require_once( __DIR__ . '/classes/CPT_columns.php' );	
	require_once( __DIR__ . '/classes/S3.php' );
	require_once( __DIR__ . '/classes/OSmedia_videostream.php' );
	require_once( __DIR__ . '/classes/OSmedia-module.php' );
	require_once( __DIR__ . '/classes/OSmedia-base.php' );
	require_once( __DIR__ . '/classes/OSmedia-version-vars.php' );
	require_once( __DIR__ . '/classes/OSmedia-settings.php' );
	require_once( __DIR__ . '/classes/OSmedia-post-admin.php' );
	require_once( __DIR__ . '/classes/OSmedia-post-frontend.php' );
	// require_once( __DIR__ . '/classes/OSmedia-cpt-interface.php' );
	require_once( __DIR__ . '/includes/admin-notice-helper/admin-notice-helper.php' );	
	require_once( __DIR__ . '/classes/OSmedia-instance-class.php' );
	// require_once( __DIR__ . '/classes/OSmedia-cron.php' );

	if ( class_exists( 'OSmedia_base' ) ) {
		$GLOBALS['OSmedia'] = OSmedia_base::get_instance();
		register_activation_hook(   __FILE__, array( $GLOBALS['OSmedia'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['OSmedia'], 'deactivate' ) );
	}
} else {
	add_action( 'admin_notices', 'OSmedia_requirements_error' );
}
