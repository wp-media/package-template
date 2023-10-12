<?php

namespace WPMedia\PHPUnit;

use WPMedia\PHPUnit\BootstrapManager;
use function WPMedia\PHPUnit\init_test_suite;
use Yoast\WPTestUtils\WPIntegration;

require_once dirname(dirname(__DIR__)).'/vendor/wp-media/phpunit/BootstrapManager.php';
BootstrapManager::setupConstants( $_SERVER['argv'][1] );

require_once WPMEDIA_PHPUNIT_ROOT_DIR . '/vendor/yoast/wp-test-utils/src/WPIntegration/bootstrap-functions.php';
require_once dirname(dirname(__DIR__)).'/vendor/wp-media/phpunit/bootstrap-functions.php';
init_test_suite();

/**
 * Bootstraps the integration testing environment with WordPress.
 */
function bootstrap_integration_suite() {
	// Set MULTISITE constant when running the Multisite group of tests.
	if ( BootstrapManager::isGroup( 'Multisite' ) && ! defined( 'MULTISITE' ) ) {
		define( 'MULTISITE', true );
	}

	$wp_tests_dir = WPIntegration\get_path_to_wp_test_dir();

	// Give access to tests_add_filter() function.
	require_once $wp_tests_dir . '/includes/functions.php';

	tests_add_filter(
		'muplugins_loaded',
		function() {
			// Set WP_ADMIN constant when running the AdminOnly group of tests.
			// This is necessary to set is_admin() for Rocket to load all the admin files.
			if ( BootstrapManager::isGroup( 'AdminOnly' ) && ! defined( 'WP_ADMIN' ) ) {
				define( 'WP_ADMIN', true );
			}
		},
		8
	);

	// Bootstrap the wp-media/phpunit-{add-on}.
	if (
		defined( 'WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR' )
		&&
		is_readable( WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR . '/bootstrap.php' )
	) {
		require_once WPMEDIA_PHPUNIT_ADDON_ROOT_TEST_DIR . '/bootstrap.php';
	}

	// Bootstrap the plugin.
	if ( is_readable( WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php' ) ) {
		require_once WPMEDIA_PHPUNIT_ROOT_TEST_DIR . '/bootstrap.php';
	}

	// Start up the WP testing environment.
	WPIntegration\bootstrap_it();
}

bootstrap_integration_suite();
