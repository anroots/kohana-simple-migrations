# Kohana 3.2 Simple Migrations

Simple Migrations is a module for the [Kohana Framework](http://kohanaframework.org/) to simplify the versioning of the MySQL database.

The module is extremely minimalistic and was inspired by the [Play framework](http://www.playframework.org/) (thanks,
[@alvarlumberg](https://twitter.com/#!/alvarlumberg))!

**Update: This is not supported or maintained. Since I wrote this, two amazing and better solutions have emerged:
[dbv.php](http://dbv.vizuina.com/) and [phinx](http://phinx.org/), which you should use instead.**

# Idea

You have the initial dump of a MySQL database - generated by a tool ([MySQL Workbench](http://www.mysql.com/products/workbench/)) or hand-crafted - that's the "initial" state (similar to `git init`) of your database. For every subsequent change you make to the schema/data, you create a new file, with increasing numeric filenames.

Each change has a 'up' and 'down' .sql file. Files in `APPPATH/database/migrations/up/` progress the database forward in the
development timeline, files in `APPPATH/migrations/down` bring it backwards. Each DOWN .sql file should undo the changes made
by it's counterpart UP file.

## Example

### APPPATH/migrations/up/2.sql

    CREATE  TABLE IF NOT EXISTS `companies` (
    
      `id` INT(12) UNSIGNED NOT NULL AUTO_INCREMENT ,
    
      `name` VARCHAR(64) NOT NULL ,
      PRIMARY KEY (`id`) )

    ENGINE = InnoDB;

### APPPATH/migrations/down/2.sql

    DROP TABLE IF EXISTS `companies` ;

You have to write the files by hand, but the module keeps track of their execution by storing information about already run migrations in the database and warning you if it's detected a new migration file.

# Use cases

1. You want to add a table to your existing database schema
2. You create the file `APPPATH/migrations/up/42.sql` and write the necessary SQL (`CREATE TABLE ...`).
3. Next, You add the reverse SQL to the file `APPPATH/migrations/down/42.sql (`DROP TABLE ...`)
4. You refresh any project page in your browser
5. The module detects the new file and requires you to execute it
6. The module runs the SQL in Your MySQL database
7. You continue development

# Installation

1. Clone and add the module to your MODPATH 
    (git submodule add git://github.com/anroots/kohana-simple-migrations.git modules/simple-migrations)
2. Enable in `APPPATH/bootstrap.php`
3. Modify your `APPPATH/config/database.php` profiles to include the module (see `/config/database.php` for an example)
4. `mkdir APPPATH/migrations/up && mkdir APPPATH/migrations/down`
5. Open http://yourbaseurl.com/simple_migrations in your browser and click the install button
6. Start writing your .sql migrations

# Documentation

See the [GitHub Wiki](https://github.com/anroots/kohana-simple-migrations/wiki) for further documentation. The source code is
annoted with PHPDoc style comments (and the plain old regular ones, too).

# Requirements

* Kohana 3.2
* Kohana Database module
* MySQL as the database
* Linux as the server machine

# Development

This is an **unstable, untested** version of the module. Use at your own risk. I'm currently trying to incorporate the module
into my own projects so I could get a feel of what needs to change.

## Licence

The module is released under the [LGPL](http://www.opensource.org/licenses/lgpl-2.1.php) licence.
