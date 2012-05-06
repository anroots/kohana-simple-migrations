<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class for installing the module
 *
 * @since 1.0
 */
class Simple_Migration_Installer extends Simple_Migration
{

	/**
	 * Install the database
	 *
	 * @throws Simple_Migration_Exception
	 * @since 1.0
	 */
	public final function install()
	{
		if ($this->is_installed()) {
			throw new Simple_Migration_Exception('Simple migration module is already installed!');
		}

		Database::instance()->begin();

		$sql = 'CREATE  TABLE IF NOT EXISTS `simple_migrations_revisions` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`version` INT UNSIGNED NOT NULL COMMENT \'Integer version number of the DB revision\' ,
`applied` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'The date on which the revision was applied\' ,
PRIMARY KEY (`id`) ,
UNIQUE INDEX `version_UNIQUE` (`version` ASC) )
ENGINE = MyISAM
COMMENT = \'Holds SM module revisions\';';

		// Create the table
		DB::query(Database::INSERT, $sql)
			->execute();

		Database::instance()->commit();
	}
}