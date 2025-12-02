# WP Media Package Template

Modern WordPress plugin template with dependency injection, event-driven architecture, and comprehensive testing infrastructure.

## Overview

This template provides a production-ready foundation for WordPress plugins using:
- **Dependency Injection**: League Container (PSR-11)
- **Event Management**: WP Media EventManager with subscriber pattern
- **Type Safety**: PHP 7.4+ strict types, PHPStan level 5
- **Testing**: Separate unit (Brain Monkey) and integration (WordPress test suite) tests
- **Code Quality**: WordPress Coding Standards, PHPCompatibility checks

## Quick Start

### Using as Template

When creating a new plugin from this template:

1. **Create repository**: Use this as GitHub template
2. **Update identifiers**:
   - Find/replace `WPMedia\PackageTemplate` → `WPMedia\YourPlugin` in all PHP files
   - Update `composer.json`: `name`, `description`, `homepage`
   - Update `plugin.php`: Plugin headers (Name, URI, Description, Text Domain, version)
   - Update `Config::init()` array: `plugin_slug`, `prefix`
   - Update `phpcs.xml.dist`: `text_domain`, `prefixes` properties
   - Replace `wpmedia_package_template_loaded` hook name in `Plugin.php`

### Development Setup

**Requirements:**
- PHP 7.4+
- Composer
- MySQL database (for integration tests)
- `svn` (for WordPress test suite installation)

**Installation:**
```bash
# Install dependencies
composer install

# Setup WordPress test suite
bash bin/install-wp-tests.sh wordpress_test db_user db_pass localhost latest

# Run all tests
composer run-tests

# Check code standards
composer phpcs
```

## Architecture

### Core Components

**Entry Point (`plugin.php`)**
- Loads Composer autoloader
- Initializes `Config` singleton with plugin metadata
- Creates `Plugin` instance and registers lifecycle hooks

**Config (`inc/Config.php`)**
- Singleton configuration container
- Stores: `version`, `plugin_file`, `plugin_basename`, `plugin_slug`, `prefix`
- Access via `Config::get('key')`

**Plugin (`inc/Plugin.php`)**
- Main orchestrator with `load()`, `activate()`, `deactivate()` methods
- Sets up DI container, EventManager, Options
- Registers event subscribers
- Fires `wpmedia_package_template_loaded` action for extensibility

**Subscribers (`inc/Subscriber.php`)**
- Implement `WPMedia\EventManager\SubscriberInterface`
- Return hooks array from `get_subscribed_events()`:
  ```php
  public static function get_subscribed_events(): array {
      return [
          'init' => 'on_init_callback',
          'save_post' => ['on_save_post', 10, 2], // priority & args
      ];
  }
  ```

### Dependency Pattern

This template uses **WP Media packages** (not direct WordPress hooks):

**Core Dependencies:**
- `league/container` (^4.0): PSR-11 dependency injection container
- `wp-media/event-manager` (^4.0): Event system with `EventManager` + `PluginApiManager`
- `wp-media/options` (^4.0): Options handling with automatic prefixing
- `wp-media/apply-filters-typed` (^1.0): Type-safe filter application

Register hooks via `$event_manager->add_subscriber(new Subscriber())` instead of direct `add_action()` calls.

## Available Commands

### Testing
```bash
composer run-tests        # Run all tests (unit + integration)
composer test-unit        # Unit tests only (Brain Monkey mocks)
composer test-integration # Integration tests (real WordPress)
```

### Code Quality
```bash
composer phpcs           # Check coding standards
composer phpcs:fix       # Auto-fix coding standards
composer phpstan         # Static analysis (PHPStan level 5)
```

## Testing Infrastructure

### Unit Tests (`Tests/Unit/`)
- **Base**: Extend `WPMedia\PHPUnit\Unit\TestCase`
- **Mocking**: Uses Brain Monkey for WordPress functions
- **Execution**: Fast, no WordPress installation needed
- **Pattern**: Load test data via `configTestData()` from co-located PHP files

Example:
```php
namespace WPMedia\PackageTemplate\Tests\Unit;

class MyTest extends TestCase {
    public function testSomething() {
        Functions\expect('get_option')
            ->once()
            ->with('my_option')
            ->andReturn('value');
    }
}
```

### Integration Tests (`Tests/Integration/`)
- **Base**: Extend `WPMedia\PHPUnit\Integration\TestCase`
- **Environment**: Runs against real WordPress test suite
- **Setup**: Requires `install-wp-tests.sh` execution
- **Plugin Loading**: Auto-loads plugin via `tests_add_filter` in `bootstrap.php`

Example:
```php
namespace WPMedia\PackageTemplate\Tests\Integration;

class MyTest extends TestCase {
    public function testWordPressFunctionality() {
        $this->assertTrue(did_action('wpmedia_package_template_loaded') > 0);
    }
}
```

## Code Conventions

### PHP Standards
- **Version**: PHP 7.4+ minimum
- **Strict Types**: `declare(strict_types=1);` in all files
- **Namespace**: `WPMedia\PackageTemplate` (core), `WPMedia\PackageTemplate\Tests` (tests)
- **Autoloading**: PSR-4 via Composer
- **Array Syntax**: Short syntax `[]` enforced

### Naming Conventions
- **Classes**: PascalCase filenames matching class name (`Plugin.php`, `Subscriber.php`)
- **Global Functions**: `wpmedia_` prefix
- **Database Options**: Use prefix from `Config::get('prefix')` (default: `wpmpt_`)
- **Hook Names**: `wpmedia_package_template_*` pattern

### Type Safety
- Use type hints for all parameters and return types
- PHPStan level 5 with WordPress stubs
- Suppress false positives: `@phpstan-ignore-line`

### WordPress Coding Standards
- Base: WordPress Coding Standards (WPCS)
- Excludes: File/class comments, hyphenated lowercase filenames
- Enforced: PHPCompatibility 7.4+, short array syntax, I18n text domain

## Suggested Packages

Optional Composer packages for common use cases:

### BerlinDB Core
```bash
composer require berlindb/core
```
**Use for**: Custom database tables with ORM-like interface (e.g., logs, analytics data not fitting custom post types)

### Mozart
```bash
composer require --dev coenjacobs/mozart
```
**Use for**: Prefix/wrap Composer dependencies to prevent conflicts when multiple plugins use the same libraries

### Action Scheduler
```bash
composer require woocommerce/action-scheduler
```
**Use for**: Reliable background job processing (cron alternative with automatic failure handling and retry logic)

### WP Mixpanel
```bash
composer require wp-media/wp-mixpanel
```
**Use for**: Integrate Mixpanel analytics for plugin usage tracking

## File Structure

```
plugin.php              # Entry point, plugin headers
inc/
  ├── Config.php        # Configuration singleton
  ├── Plugin.php        # Main plugin class
  └── Subscriber.php    # Event subscriber template
Tests/
  ├── Unit/             # Unit tests with Brain Monkey
  │   ├── bootstrap.php
  │   ├── TestCase.php  # Base test class
  │   └── ExampleTest.php
  ├── Integration/      # Integration tests with WordPress
  │   ├── bootstrap.php
  │   ├── TestCase.php
  │   └── ExampleTest.php
  └── Fixtures/         # Test data files
bin/
  └── install-wp-tests.sh  # WordPress test suite installer
.editorconfig          # IDE coding standards
.gitattributes         # Export exclusions
phpcs.xml.dist         # Code standards config
phpstan.neon.dist      # Static analysis config
composer.json          # Dependencies and scripts
```

## Configuration Files

- **`.editorconfig`**: IDE settings for WordPress coding standards (tabs, UTF-8, LF)
- **`.gitattributes`**: Excludes development files from plugin exports (`bin/`, `Tests/`, etc.)
- **`phpcs.xml.dist`**: WordPress Coding Standards with PHPCompatibility 7.4+, custom exclusions
- **`phpstan.neon.dist`**: PHPStan level 5 analyzing `inc/` and `Tests/` with WordPress stubs

## License

GPL-2.0-or-later
