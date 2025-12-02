<?php
declare(strict_types=1);

namespace WPMedia\PackageTemplate\Tests\Integration;

use ReflectionObject;
use WPMedia\PHPUnit\Integration\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
	/**
	 * Configuration for the test data.
	 *
	 * @var array{'test_data'?: array<string, mixed>}
	 */
	protected $config;

	/**
	 * Setup method for the test case.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();

		if ( empty( $this->config ) ) {
			$this->loadTestDataConfig();
		}
	}

	/**
	 * Get the test data configuration.
	 *
	 * @return array<string, mixed>
	 */
	public function configTestData(): array {
		if ( empty( $this->config ) ) {
			$this->loadTestDataConfig();
		}

		return isset( $this->config['test_data'] )
			? $this->config['test_data']
			: $this->config;
	}

	/**
	 * Load test data configuration.
	 *
	 * @return void
	 */
	protected function loadTestDataConfig(): void {
		$obj      = new ReflectionObject( $this );
		$filename = $obj->getFileName();

		if ( false === $filename ) {
			return;
		}

		$this->config = $this->getTestData( dirname( $filename ), basename( $filename, '.php' ) );
	}
}
