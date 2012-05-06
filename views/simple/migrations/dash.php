<?php defined('SYSPATH') or die('No direct script access.') ?>
<h2><?=__('Dash')?></h2>

<div class="alert <?=$status === Simple_Migration::STATUS_OK ? 'alert-success' : 'alert-info'?>">
	<h3 class="alert-heading"><?=__('Database status')?></h3>

	<?if ($status === Simple_Migration::STATUS_OK): ?>
	<p><?=__('Current database version is :version and no UP scripts found: the database is up-to-date.', array(
		':version' => $current->version
	))?></p>

	<? else: ?>
	<p>
		<?=__('Current database version is :version, but an update script with version :num exists. The database can be
		migrated UP.', array(
		':version' => $current->version,
		':num' => $migrations[0]
	))?>
	</p>
	<p>
		<a href="<?=URL::base()?>simple_migrations/migrate/<?=$migrations[0]?>" class="btn btn-warning">
			<i class="icon-arrow-up"></i> <?=__('Migrate database to revision :rev', array(':rev' => $migrations[0]))?>
		</a>
	</p>
	<?endif ?>
</div>

<h3><?=__('Quick tips')?></h3>
<p class="help-block"><?=__('For longer documentation, see :here.', array(
	':here' => HTML::anchor('https://github.com/anroots/kohana-simple-migrations/wiki', 'here')
))?></p>

<ul>
	<li><?=__('Every discrete chunk of change you want to make to your database is a revision.')?></li>
	<li><?=__('UP revision progress your database forwards. DOWN revisions undo the changes made by UP revisions.')?></li>
	<li><?=__('Name your revisions by using positive, ever-increasing integer values for filenames (23.sql).')?></li>
	<li><?=__('Don\'t forget to greate a reverse-revision for your change.')?></li>
	<li><?=__('The module warns you when it detects a new revision file and asks if you want to apply it.')?></li>
</ul>