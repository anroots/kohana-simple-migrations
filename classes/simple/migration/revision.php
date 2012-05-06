<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Represents a single revision
 *
 * @since 1.0
 */
class Simple_Migration_Revision
{

	/**
	 * SQL execution methods
	 * Since one migration script can contain multiple SQL statements
	 * (and PHP's mysql_query can only handle one), we need a way to execute
	 * several statements.
	 */
	const METHOD_SHELL = 1; // Use UNIX mysql command to dump the file
	const METHOD_EXPLODE = 2; // Explode file contents by ';' character and query one-by-one

	// Database
	public $id;
	public $version;
	public $applied;

	// SQL
	protected $sql_up;
	protected $sql_down;

	/**
	 * Empty constructor for mysql_fetch_object
	 *
	 * @since 1.0
	 * @param int $version Load a particular version
	 */
	public function __construct($version = NULL)
	{
		if (is_numeric($version)) {
			$this->_load_sql($version);
		}
	}

	/**
	 * Execute the SQL of the current revision in the database
	 *
	 * @since 1.0
	 * @param string $direction The direction of the SQL: whether to migrate UP or DOWN
	 * @throws Simple_Migration_Exception
	 */
	public function execute($direction = Simple_Migration::UP)
	{
		if ($this->version === NULL) {
			throw new Simple_Migration_Exception('Simple Migration Revision is not loaded!');
		}

		$sql = $this->{'sql_' . $direction};

		// Get migration method from the config file
		$method = Kohana::$config->load('database')->{Database::$default};
		$method = array_key_exists('migration_method', $method) ? (int)$method['migration_method'] : self::METHOD_SHELL;

		Kohana::$log->add(Log::INFO, 'Running DB migration from version :from to version :to.', array(
			':from' => $this->version,
			':to' => $direction === Simple_Migration::UP ? $this->version + 1 : $this->version - 1
		));

		// Execute the appropriate method
		if ($method === self::METHOD_SHELL) {
			$this->_execute_by_shell($this->file($this->version, $direction));
		} else {
			$this->_execute_by_explode($sql);
		}
	}

	/**
	 * Load SQL into the database using UNIX mysql command
	 *
	 * @since 1.0
	 * @param string $sql_path Full path to an SQL file
	 * @return array|null Output from the php exec command
	 */
	protected function _execute_by_shell($sql_path)
	{
		$db_conf = Kohana::$config->load('database')->{Database::$default};

		$command = 'mysql -u ' . $db_conf["username"] . ' -p' . $db_conf["password"] . ' -D ' . $db_conf["database"] . ' -h '
			. $db_conf["hostname"] . ' < ' . $sql_path;

		Kohana::$log->add(Log::INFO, 'Execute shell command: :command.', array(
			':command' => $command
		));

		exec($command, $output);
		return $output;
	}

	/**
	 * @throws Kohana_Exception
	 */
	protected function execute_by_explode()
	{ // Todo
		throw new Kohana_Exception('Not implemented yet');
	}

	/**
	 * Load SQL for the revision
	 *
	 * @param int $version The revision version
	 * @return \Simple_Migration_Revision
	 * @throws Simple_Migration_Exception
	 */
	protected function _load_sql($version)
	{
		// Load SQL
		$this->sql_up = file_get_contents($this->file($version, Simple_Migration::UP));
		$this->sql_down = file_get_contents($this->file($version, Simple_Migration::DOWN));

		return $this;
	}

	/**
	 * Get full path to a SQL migration file
	 *
	 * @since 1.0
	 * @param int $version The revision version
	 * @param string $direction UP or DOWN
	 * @throws Simple_Migration_Exception
	 * @return string Full path to a SQL file
	 */
	public function file($version, $direction = Simple_Migration::UP)
	{
		// Dir that holds up and down subdirs
		$dir = DOCROOT . Simple_Migration::BASE_DIR;

		// Filename + extension
		$file_suffix = DIRECTORY_SEPARATOR . $version . '.' .
			Simple_Migration::EXT;

		// Full path of the UP migration
		$path = $dir . $direction . $file_suffix;


		if (!file_exists($path)) {
			throw new Simple_Migration_Exception('Tried to load SQL for migration, but no such file (:path) exists. Please
			ensure that both UP and DOWN migration files exist for revision :ver.', array(
				':path' => $path,
				':ver' => $version
			));
		}

		return $path;
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

		return in_array($direction === Simple_Migration::UP ? $this->version + 1 : $this->version - 1, $files);
	}
}