<?php

class DebugHelperForWordPress
{
    /**
     * Print a variable and terminate execution when output is permitted.
     *
     * @param mixed $variable
     * @param bool $dumpType Use var_dump instead of print_r.
     * @param bool $prettyPrint Wrap output in a readable block.
     * @param mixed ...$additionalVariables More values to inspect.
     */
    public static function dumpAndDie(mixed $variable, bool $dumpType = false, bool $prettyPrint = false, mixed ...$additionalVariables): void
    {
        if (!self::canOutput()) {
            return;
        }

        self::dump($variable, $dumpType, $prettyPrint, ...$additionalVariables);
        exit;
    }

    /**
     * Print debug information without changing response headers.
     *
     * Existing positional and named arguments remain compatible with v1.0.
     * Additional variables may be supplied after the original three arguments.
     */
    public static function dump(mixed $variable, bool $dumpType = false, bool $prettyPrint = false, mixed ...$additionalVariables): void
    {
        if (!self::canOutput()) {
            return;
        }

        $values = array_merge([$variable], $additionalVariables);
        $cli = PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
        $location = self::callerLocation();

        if ($cli) {
            echo '[Debug Helper] ' . $location . PHP_EOL;
        } else {
            if ($prettyPrint) {
                echo '<pre style="background:#282c34;color:#fff;padding:12px;border-radius:5px;white-space:pre-wrap;overflow-wrap:anywhere">';
            }
            echo self::escape('[Debug Helper] ' . $location) . "\n";
        }

        foreach ($values as $index => $value) {
            if (count($values) > 1) {
                $label = 'Variable ' . ($index + 1) . ':' . PHP_EOL;
                echo $cli ? $label : self::escape($label);
            }

            if ($dumpType) {
                ob_start();
                var_dump($value);
                $output = (string) ob_get_clean();
            } else {
                $output = print_r($value, true);
                if (!is_string($output)) {
                    $output = (string) $output;
                }
            }

            echo $cli ? $output : self::escape($output);
            if ($index < count($values) - 1 || $prettyPrint) {
                echo PHP_EOL;
            }
        }

        if (!$cli && $prettyPrint) {
            echo '</pre>';
        }
    }

    /** Prevent disclosure to visitors and avoid corrupting JSON/AJAX responses. */
    private static function canOutput(): bool
    {
        if (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') {
            return true;
        }

        if ((defined('DOING_AJAX') && DOING_AJAX)
            || (defined('REST_REQUEST') && REST_REQUEST)
            || (function_exists('wp_doing_ajax') && wp_doing_ajax())
            || (function_exists('wp_is_json_request') && wp_is_json_request())) {
            return false;
        }

        // WordPress may run callbacks before the current user is established.
        // Fail closed instead of exposing potentially sensitive values.
        return function_exists('current_user_can') && current_user_can('manage_options');
    }

    /** Identify the original call site while skipping plugin-internal frames. */
    private static function callerLocation(): string
    {
        $pluginEntry = dirname(__DIR__) . '/debugHelperForWordPress.php';
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            $file = $frame['file'] ?? null;
            if ($file === null || $file === __FILE__ || $file === $pluginEntry) {
                continue;
            }
            return basename($file) . ':' . ($frame['line'] ?? '?');
        }
        return 'unknown';
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
