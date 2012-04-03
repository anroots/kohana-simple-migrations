<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple Migrations init action, runs every page load
 */

// Run a check: is migrating neccessary?
if (Kohana::$config->load('database.migrations') === TRUE) {
	Simple_Migration::check();
}