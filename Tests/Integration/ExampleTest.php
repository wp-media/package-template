<?php
declare(strict_types=1);

namespace WPMedia\PackageTemplate\Tests\Integration;

/**
 * Example integration test case.
 */
class ExampleTest extends TestCase {
	/**
	 * Checks the call to plugin init function on plugin_loaded.
	 */
	public function testShouldLoadPlugin() {
		$this->assertTrue( did_action( 'wpmedia_package_template_loaded' ) > 0 );
	}
}
