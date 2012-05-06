<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class for installing the module
 *
 * @since 1.0
 */
class Simple_Migration_Installer extends Simple_Migration
{
	/**
	 * @static
	 * @since 1.0
	 * @var string Holds the MySQL table name
	 */
	protected static $_table;

	public function __construct()
	{
		self::$_table = self::get_table_name();
	}

	/**
	 * Install the database
	 *
	 * @throws Simple_Migration_Exception
	 * @since 1.0
	 * @return bool
	 */
	public final function install()
	{
		if ($this->is_installed()) {
			throw new Simple_Migration_Exception('Simple migration module is already installed!');
		}

		Database::instance()->begin();

		// Create the table
		DB::query(Database::INSERT, self::get_install_sql())
			->execute();

		Database::instance()->commit();
		return TRUE;
	}

	/**
	 * Get the SQL for that needs to be executed to install the module
	 *
	 * @since 1.0
	 * @static
	 * @return string SQL to run in DB
	 */
	public static function get_install_sql()
	{
		return 'CREATE TABLE IF NOT EXISTS `' . self::$_table . '` (
  `version` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `applied` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
	}

	/**
	 * Uninstall the module (drop system table(s))
	 *
	 * @since 1.0
	 * @throws Simple_Migration_Exception
	 * @return bool
	 */
	public final function uninstall()
	{

		if (!$this->is_installed()) {
			throw new Simple_Migration_Exception('Simple migration module is not installed!');
		}

		DB::query(Database::DELETE, 'DROP TABLE ' . self::$_table . ';')
			->execute();

		return TRUE;
	}
}