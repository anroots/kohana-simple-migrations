<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Represents a single revision
 *
 * @since 1.0
 */
class Simple_Migration_Revision
{

	public $id;
	public $version;
	public $applied;

	/**
	 * Empty constructor for mysql_fetch_object
	 *
	 * @since 1.0
	 */
	public function __construct()
	{

	}

	/**
	 * Check if there exist such migration script (SQL file) that the revision can be migrated UP/DOWN
	 *
	 * @since 1.0
	 * @param string $direction Migration direction (UP/DOWN, use constants)
	 * @return bool TRUE if such a script exists
	 */
	public function can_migrate($direction = Simple_Migration::UP)
	{
		// List migration scripts
		$files = Simple_Migration::get_files($direction);

		return in_array($direction === Simple_Migration::UP ? $this->version+1 : $this->version-1,$files);
	}
}