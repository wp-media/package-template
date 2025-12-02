<?php
/**
 * Plugin Name: Package Template
 * Plugin URI:  TO FILL
 * Description: A WordPress plugin template to kickstart your development.
 * Version:     1.0.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author:      WP Media
 * Author URI:  https://wp-media.me
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: package-template
 * Domain Path: /languages
 */
namespace WPMedia\PackageTemplate;

defined( 'ABSPATH' ) || exit;

// Load the dependencies installed through composer.
if ( ! class_exists( Config::class ) && is_file( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

Config::init(
	[
		'version'         => 'TO FILL',
		'plugin_file'     => __FILE__,
		'plugin_basename' => plugin_basename( __FILE__ ),
		'plugin_slug'     => 'package-template',
		'prefix'          => 'wpmpt_',
	]
);

$wpmedia_plugin = new Plugin();

add_action( 'plugins_loaded', [ $wpmedia_plugin, 'load' ] );
register_activation_hook( __FILE__, [ $wpmedia_plugin, 'activate' ] );
register_deactivation_hook( __FILE__, [ $wpmedia_plugin, 'deactivate' ] );
