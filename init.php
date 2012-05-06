<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple Migrations init action, runs every page load
 */

// API v1 route
Route::set('simple-migrations', 'simple-migrations/(/<action>(/<id>))')
	->defaults(array(
	'directory' => 'simple',
	'controller' => 'migrations',
	'action' => 'index',
));

// Get config for the default database (profiles not yet supported)
$config = Kohana::$config->load('database')->{Database::$default};

// Run a check: is migrating necessary?
if ($config['migrations'] === TRUE) {
	Simple_Migration::instance()
		->check();
}

unset($config);