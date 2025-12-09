# Copilot Instructions for WP Media Package Template

## Project Overview
WordPress plugin/package template using modern PHP with dependency injection (League Container), event-driven architecture (WP Media EventManager), and comprehensive testing infrastructure.

## Creating a New Plugin from Template

When starting a new plugin project, update these key identifiers:

1. **Namespace**: Find/replace `WPMedia\PackageTemplate` → `WPMedia\YourPlugin` in:
   - `composer.json` autoload sections
   - All PHP files in `inc/` and `Tests/`
   
2. **composer.json**: Update `name`, `description`, `homepage`, text domain
3. **plugin.php**: Update plugin headers (Plugin Name, URI, Description, Text Domain, version)
4. **Config**: Update `plugin_slug` and `prefix` in `plugin.php` Config::init() array
5. **phpcs.xml.dist**: Update `text_domain` and `prefixes` properties for I18n and naming conventions
6. **Hook names**: Replace `wpmedia_package_template_loaded` with your plugin-specific hook in `Plugin.php`

## Architecture

### Core Components
- **`plugin.php`**: Entry point that initializes `Config` and `Plugin` class, registers activation/deactivation hooks
- **`inc/Config.php`**: Singleton config container initialized in `plugin.php` with `version`, `plugin_file`, `plugin_basename`, `plugin_slug`, `prefix`
- **`inc/Plugin.php`**: Main plugin orchestrator with `load()`, `activate()`, `deactivate()` methods; instantiates Container, EventManager, Options
- **`inc/Subscriber.php`**: Event subscribers implementing `SubscriberInterface` with `get_subscribed_events()` returning hooks array

### Dependency Pattern
Plugin uses WP Media packages (not bundled WordPress conventions):
- `wp-media/event-manager`: Event/hook system via `EventManager` + `PluginApiManager`
- `wp-media/options`: Options handling via `Options` class with prefix from Config
- `wp-media/apply-filters-typed`: Type-safe filter application
- `league/container`: PSR-11 dependency injection container

Event subscribers register via `$event_manager->add_subscriber(new Subscriber())` instead of direct `add_action()` calls.

## Development Workflow

### Setup
```bash
composer install
bash bin/install-wp-tests.sh wordpress_test <db_user> <db_pass> localhost latest
```

### Quality Checks
- **Tests**: `composer run-tests` (runs both unit + integration), or separately: `composer test-unit`, `composer test-integration`
- **Code Standards**: `composer phpcs` (check), `composer phpcs:fix` (auto-fix)
- **Static Analysis**: `composer phpstan` (level 5, analyzes `inc/` and `Tests/`)

### Testing Structure
- **Unit tests** (`Tests/Unit/`): Extend `WPMedia\PHPUnit\Unit\TestCase`, use Brain Monkey for WP function mocking
- **Integration tests** (`Tests/Integration/`): Extend `WPMedia\PHPUnit\Integration\TestCase`, run against real WordPress test suite
- Both test base classes support `configTestData()` pattern for loading test fixtures from co-located PHP files

## Code Conventions

### PHP Standards
- **Minimum PHP**: 7.4+ with strict types (`declare(strict_types=1);` in all files)
- **Namespace**: `WPMedia\PackageTemplate` for core, `WPMedia\PackageTemplate\Tests` for tests
- **Coding style**: WordPress Coding Standards with PSR-4 autoloading, short array syntax `[]` enforced
- **Prefixes**: Global functions/hooks use `wpmedia_` prefix, database options use `pt_` prefix (from Config)

### File Naming
- Classes use PascalCase filenames matching class name (e.g., `Plugin.php`, `Subscriber.php`)
- No strict hyphenated-lowercase requirement for class files

### Type Safety
- Use type hints for parameters and return types (`:void`, `:array`, etc.)
- PHPStan level 5 with WordPress stubs for static analysis
- Suppress false positives with `@phpstan-ignore-line` comments

## Integration Points

### Plugin Initialization Flow
1. `plugin.php` loads Composer autoloader
2. `Config::init()` called with plugin metadata array
3. `Plugin` instance created
4. `plugins_loaded` hook triggers `Plugin::load()`
5. `load()` sets up Container, EventManager, Options, registers Subscribers
6. `do_action('wpmedia_package_template_loaded')` fired for extensibility

### Adding Event Subscribers
Implement `SubscriberInterface` and return hooks array:
```php
public static function get_subscribed_events(): array {
    return [
        'init' => 'on_init_method',
        'save_post' => ['on_save_post', 10, 2], // priority & args
    ];
}
```

## Suggested Packages (Optional Enhancements)

The template includes composer suggestions for common use cases:

- **`berlindb/core`**: Use for custom database tables with ORM-like interface (e.g., logs, custom data not fitting CPTs)
- **`coenjacobs/mozart`**: Wrap/prefix Composer dependencies to prevent conflicts when multiple plugins use same libraries
- **`woocommerce/action-scheduler`**: Add for reliable background job processing (cron alternative with failure handling)
- **`wp-media/wp-mixpanel`**: Integrate Mixpanel analytics for usage tracking

Install via: `composer require <package>` for production, or `composer require --dev <package>` for dev dependencies.

## Key Files to Reference
- `inc/Plugin.php`: Template for DI setup and lifecycle hooks
- `Tests/Unit/TestCase.php` & `Tests/Integration/TestCase.php`: Base test classes with data loading pattern
- `phpcs.xml.dist`: WordPress standards configuration with PHPCompatibility rules
- `composer.json`: Available scripts and dependency versions
