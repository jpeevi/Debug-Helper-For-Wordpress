<?php
/*
Plugin Name: Debug Helper For WordPress
Version: 1.1.0
Description: Debug Helper For WordPress provides essential debugging tools for developers.
Requires PHP: 8.0
Requires at least: 5.6
Author: Jithin PV
Author URI: https://github.com/jpeevi
*/

require_once __DIR__ . '/src/DebugHelperForWordPress.php';

if (defined('WP_DEBUG') && WP_DEBUG === true) {

    if (!function_exists('dump')) {
        /**
         * @param mixed $variable
         * @param bool $dumpType
         * @param bool $prettyPrint
         * @param mixed ...$additionalVariables
         * @return void
         */

        function dump(mixed $variable, bool $dumpType = false, bool $prettyPrint = false, mixed ...$additionalVariables): void
        {
            DebugHelperForWordPress::dump($variable, $dumpType, $prettyPrint, ...$additionalVariables);
        }
    }
    if (!function_exists('dumpAndDie')) {

        /**
         * @param mixed $variable
         * @param bool $dumpType
         * @param bool $prettyPrint
         * @param mixed ...$additionalVariables
         * @return void
         */
        function dumpAndDie(mixed $variable, bool $dumpType = false, bool $prettyPrint = false, mixed ...$additionalVariables): void
        {
            DebugHelperForWordPress::dumpAndDie($variable, $dumpType, $prettyPrint, ...$additionalVariables);
        }
    }


}
