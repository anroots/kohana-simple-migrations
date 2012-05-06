SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `simple_migrations_revisions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `simple_migrations_revisions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `version` INT UNSIGNED NOT NULL COMMENT 'Integer version number of the DB revision' ,
  `applied` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date on which the revision was applied' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `version_UNIQUE` (`version` ASC) )
ENGINE = MyISAM
COMMENT = 'Holds SM module revisions';



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
