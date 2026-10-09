# Debug Helper For WordPress

![Version](https://img.shields.io/badge/version-1.1.0-blue)
![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-777BB4?logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-%3E%3D5.6-21759B?logo=wordpress&logoColor=white)

**Simple, lightweight, and developer-friendly debugging utilities for WordPress.**

Debug Helper For WordPress is a lightweight debugging plugin designed to make inspecting PHP variables easier during WordPress theme and plugin development.

With familiar `dump()` and `dumpAndDie()` functions, developers can quickly inspect arrays, objects, and other PHP values without repeatedly writing `print_r()`, `var_dump()`, or custom debugging code.

The plugin provides readable output, automatic source file and line number detection, support for multiple variables, and context-aware safeguards to help prevent debugging output from interfering with WordPress AJAX and REST API responses.

No complicated setup. No external dependencies. Just simple debugging when you need it.

## Features

- **Quick Variable Inspection** — Debug PHP values using simple `dump()` and `dumpAndDie()` functions.
- **Multiple Variable Support** — Inspect several variables in a single call.
- **Source Location Tracking** — Automatically display the originating filename and line number.
- **Readable Output** — Display formatted debugging information using optional HTML `<pre>` blocks.
- **Type Information** — Switch between `print_r()` and `var_dump()` output.
- **HTML-Safe Output** — Escape debugging output to prevent unintended HTML rendering.
- **WordPress-Aware Debugging** — Suppress output in detected AJAX, REST, and JSON response contexts.
- **CLI Compatibility** — Support plain-text debugging in command-line environments.
- **Access Protection** — Restrict HTTP debugging output to users with the `manage_options` capability.
- **Lightweight by Design** — No external libraries, complex configuration, or unnecessary overhead.

## Requirements

- PHP 8.0 or newer
- WordPress 5.6 or newer
- `WP_DEBUG` enabled

## Installation

1. Download the plugin ZIP or clone the repository.
2. Place the plugin directory inside `wp-content/plugins/`.
3. Activate **Debug Helper For WordPress** from the WordPress admin dashboard.
4. Enable debugging in your `wp-config.php` file:

```php
define('WP_DEBUG', true);
```

The debugging functions are registered only when `WP_DEBUG` is enabled.

## Usage

### Basic debugging

Inspect a variable using the `dump()` function.

```php
dump($user);
```

### Pretty-print output

Display output inside a styled HTML `<pre>` block.

```php
dump($user, prettyPrint: true);
```

### Include variable types

Use `var_dump()` instead of `print_r()` to inspect variable types and values.

```php
dump($user, dumpType: true, prettyPrint: true);
```

### Debug multiple variables

Inspect multiple variables in a single call.

```php
dump($user, false, true, $settings, $post);
```

Additional variables are passed after the original three arguments to maintain backward compatibility.

### Dump and terminate

Display debugging information and stop execution.

```php
dumpAndDie($settings, prettyPrint: true);
```

**Note:** In suppressed HTTP contexts such as detected REST API, AJAX, and JSON requests, `dumpAndDie()` does not terminate execution.

## Available Functions

### `dump()`

Displays debugging information without terminating execution.

```php
dump(
    mixed $variable,
    bool $dumpType = false,
    bool $prettyPrint = false,
    mixed ...$additionalVariables
): void
```

### `dumpAndDie()`

Displays debugging information and terminates execution when output is permitted.

```php
dumpAndDie(
    mixed $variable,
    bool $dumpType = false,
    bool $prettyPrint = false,
    mixed ...$additionalVariables
): void
```

### Parameters

| Parameter | Type | Description |
|---|---|---|
| `$variable` | `mixed` | Primary variable to inspect |
| `$dumpType` | `bool` | Use `var_dump()` instead of `print_r()` |
| `$prettyPrint` | `bool` | Enable formatted HTML output |
| `$additionalVariables` | `mixed ...` | Additional variables to inspect |

## Safety and Compatibility

Debug Helper For WordPress is intended for local development and debugging environments.

- Functions are available only when `WP_DEBUG` is enabled.
- HTTP debugging output is restricted to users with the `manage_options` capability.
- Output is suppressed in detected REST API, AJAX, and JSON request contexts.
- HTML output is escaped for safer browser rendering.
- CLI output uses plain text rather than HTML formatting.
- The plugin avoids modifying HTTP `Content-Type` headers.

**Important:** Disable debugging in production environments and remove unnecessary debugging calls before deployment.

## Changelog

### v1.1.0 — Improved Debugging Experience

- Added automatic filename and line number tracking.
- Added support for dumping multiple variables.
- Improved formatted debugging output.
- Added consistent HTML escaping for both output modes.
- Fixed unwanted HTML closing tags in CLI output.
- Removed unsolicited `Content-Type` header changes.
- Improved compatibility with AJAX, REST, and JSON requests.
- Added permission checks for HTTP debugging output.
- Updated plugin include paths to be relative to the plugin file.
- Preserved existing function signatures and named arguments.

## Philosophy

Debugging tools should make development easier, not introduce additional complexity.

Debug Helper For WordPress focuses on providing essential debugging functionality through a small, straightforward API that integrates naturally into existing WordPress development workflows.

**Keep it simple. Debug faster. Build better WordPress projects.**