<?php
declare(strict_types=1);

namespace WPMedia\PackageTemplate\Tests\Integration;

define( 'WPMEDIA_PACKAGE_TEMPLATE_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'WPMEDIA_PACKAGE_TEMPLATE_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );
define( 'WPMEDIA_PACKAGE_TEMPLATE_TESTS_DIR', __DIR__ );

tests_add_filter(
	'muplugins_loaded',
	function () {
		require_once WPMEDIA_PACKAGE_TEMPLATE_PLUGIN_ROOT . 'plugin.php';
	}
);
