<?php
declare( strict_types=1 );

namespace WPMedia\PackageTemplate;

final class Config {
	/**
	 * The config container.
	 *
	 * @var array|null
	 */
	private static ?array $container = null;

	/**
	 * Initialize the config
	 *
	 * @param array $container The config container.
	 *
	 * @return void
	 */
	public static function init( array $container ): void {
		if ( isset( self::$container ) ) {
			return;
		}

		self::$container = $container;
	}

	/**
	 * Retrieves a config value by name.
	 *
	 * @param string $name The config name.
	 *
	 * @return mixed|null The config value or null if not found.
	 */
	public static function get( string $name ) {
		if ( ! isset( self::$container ) || ! array_key_exists( $name, self::$container ) ) {
			return null;
		}

		return self::$container[ $name ];
	}
}
