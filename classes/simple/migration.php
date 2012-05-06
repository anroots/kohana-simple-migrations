<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Main class of the Simple Migrations module
 *
 * @since 1.0
 * @author Ando Roots
 */
class Simple_Migration
{

	/**
	 * Module version
	 */
	const VERSION = '1.0';

	/**
	 * Kohana compatibility
	 */
	const COMPATIBLE_FROM = '3.2.0';
	const COMPATIBLE_TO = '3.2.0';

	/**
	 * Constants for database state
	 */
	const STATUS_OK = 1; // Up-to-date
	const STATUS_BEHIND = 2; // Can be migrated up

	/**
	 * The name of the table that holds module revisions
	 *
	 * @since 1.0
	 */
	const TABLE_NAME = 'simple_migrations_revisions';

	/**
	 * Base dir (relative to DOCROOT) where migrations are held
	 *
	 * @since 1.0
	 */
	const BASE_DIR = 'database/migrations/';

	/**
	 * @since 1.0
	 */
	const UP = 'up';

	/**
	 * @since 1.0
	 */
	const DOWN = 'down';

	/**
	 * Migration file extension
	 *
	 * @since 1.0
	 */
	const EXT = 'sql';

	/**
	 * Do not access directly, use instance() static function
	 *
	 * @var Simple_Migration Stores the singleton instance
	 */
	private static $_instance;

	/**
	 * Holds the last applied revision
	 *
	 * @var Simple_Migration_Revision
	 * @since 1.0
	 */
	private static $_current;

	/**
	 * @since 1.0
	 * @throws Kohana_Exception
	 */
	public function __construct()
	{
		if (version_compare(Kohana::VERSION, self::COMPATIBLE_FROM) === -1
			|| version_compare(self::COMPATIBLE_TO, Kohana::VERSION) === -1
		) {
			throw new Kohana_Exception('Module Simple Migrations version :mod_vers is compatible with Kohana versions :min -
			:max, but your Kohana version is :ver.', array(
				':mod_vers' => self::VERSION,
				':min' => self::COMPATIBLE_FROM,
				':max' => self::COMPATIBLE_TO,
				':ver' => Kohana::VERSION
			));
		}

		if (!$this->is_installed()) {
			Kohana::$log->add(Log::ERROR, 'You have enabled the Simple Migrations module for Kohana,
			but the database does not contain the module table(s). Please navigate to /simple_migrations/install for more
			details.');
			return;
		}

		// Get current revision
		Simple_Migration::$_current = $this->current();
	}

	/**
	 * Check whether the module is installed
	 *
	 * @return bool TRUE if the module table exists
	 * @since 1.0
	 */
	public function is_installed()
	{
		return in_array($this->get_table_name(), Database::instance()->list_tables());
	}

	/**
	 * Get the name of the table that tracks SM changes
	 *
	 * @return string Module DB table name
	 * @static
	 * @since 1.0
	 */
	public static function get_table_name()
	{
		return Database::instance()->table_prefix() . self::TABLE_NAME;
	}

	/**
	 * Run a check to determine if migration is necessary
	 *
	 * @return bool
	 * @since 1.0
	 */
	public function check()
	{
		// No current state (most likely the module is not installed), do not continue
		if (($current = $this->current()) === NULL) {
			return FALSE;
		}
	}

	/**
	 * Get a list of .sql files
	 *
	 * @static
	 * @param string $type Migration type (up/down)
	 * @throws Simple_Migration_Exception
	 */
	public static function get_files($type = Simple_Migration::UP)
	{
		// The dir for this type of migrations
		$dir = DOCROOT . self::BASE_DIR . $type;

		if (!file_exists($dir)) {
			throw new Simple_Migration_Exception('Migration directory :dir not found.', array(':dir' => $dir));
		}

		// Get files in dir
		$files = scandir($dir, 1);

		// Filter by ext
		foreach ($files as $i => $file_name) {

			// If file extension is not .sql...
			if (substr($file_name, (strlen(self::EXT) + 1) * (-1)) !== '.' . self::EXT) {
				unset($files[$i]);
			} else {
				// Lose file extension
				$files[$i] = substr($file_name, 0, strlen($file_name - 1 - self::EXT));
			}

		}

		return $files;
	}

	/**
	 * Get the current database revision
	 *
	 * The current revision is stored in the self::TABLE_NAME table
	 *
	 * @since 1.0
	 * @return Simple_Migration_Revision|bool False on failure or when there are no rows in revision table
	 */
	public function current()
	{
		try {
			return DB::select()
				->from($this->get_table_name())
				->order_by('version', 'DESC')
				->limit(1)
				->as_object('simple_migration_revision')
				->execute()
				->current();
		} catch (Database_Exception $e) {
			return FALSE;
		}
	}

	/**
	 * Check current database status
	 *
	 * @see self::STATUS_BEHIND
	 * @see self::STATUS_OK
	 * @since 1.0
	 * @return int
	 */
	public function status()
	{
		// Get current revision
		$current = $this->current();

		if ($current instanceof Simple_Migration_Revision && $current->can_migrate()) {
			return self::STATUS_BEHIND;
		}
		return self::STATUS_OK;
	}

	/**
	 * Get the singleton instance of the class
	 *
	 * @since 1.0
	 * @static
	 * @return Simple_Migration
	 */
	public static function instance()
	{
		if (Simple_Migration::$_instance === NULL) {
			Simple_Migration::$_instance = new Simple_Migration();
		}
		return Simple_Migration::$_instance;
	}
}