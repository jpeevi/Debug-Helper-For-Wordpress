<?php
// Run: php tests/smoke.php
error_reporting(E_ALL);
define('WP_DEBUG', true);
require dirname(__DIR__) . '/debugHelperForWordPress.php';

function check(bool $result, string $message): void {
    if (!$result) {
        throw new RuntimeException('FAIL: ' . $message);
    }
    echo 'PASS: ' . $message . PHP_EOL;
}

ob_start();
dump(['x' => '<script>'], dumpType: true, prettyPrint: true);
$result = ob_get_clean();
check(str_contains($result, 'array(1)') && !str_contains($result, '</pre>'), 'CLI var_dump has types and no HTML');
check(str_contains($result, 'smoke.php:'), 'File and line printed for global function');

ob_start();
dump('first', false, true, 'second');
$result = ob_get_clean();
check(str_contains($result, 'Variable 1:') && str_contains($result, 'Variable 2:'), 'Additional variables supported');

ob_start();
DebugHelperForWordPress::dump('static');
$result = ob_get_clean();
check(str_contains($result, 'smoke.php:'), 'File and line printed for direct static call');

$method = new ReflectionMethod(DebugHelperForWordPress::class, 'escape');
check($method->invoke(null, '<script>"') === '&lt;script&gt;&quot;', 'HTML output is escaped');

check(function_exists('dumpAndDie'), 'Original helper still exists');
