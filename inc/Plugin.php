<?php
declare( strict_types=1 );

namespace WPMedia\PackageTemplate;

use League\Container\Container;
use WPMedia\EventManager\EventManager;
use WPMedia\EventManager\PluginApiManager;
use WPMedia\Options\Options;

/**
 * Main plugin class. It manages initialization, install, and activations.
 */
class Plugin {
	/**
	 * Loads the plugin components.
	 *
	 * @return void
	 */
	public function load(): void {
		$container     = new Container();
		$event_manager = new EventManager( new PluginApiManager() );
		$options       = new Options( Config::get( 'prefix' ) );

		$event_manager->add_subscriber( new Subscriber() );

		do_action( 'wpmedia_package_template_loaded' );
	}

	/**
	 * Handles plugin activation
	 *
	 * @return void
	 */
	public function activate(): void {}

	/**
	 * Handles plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate(): void {}
}
