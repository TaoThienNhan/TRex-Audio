<?php
/**
 * TNS functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package TNS
 */

/**
 * Currently theme version.
 */
define('TNS_VERSION', '2.0.1');

/**
 * The core theme class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once __DIR__ . '/includes/class-tns.php';

/**
 * Begins execution of the theme.
 */
function run_tns(){

	$plugin = new Tns();
	$plugin->run();

}

run_tns();
