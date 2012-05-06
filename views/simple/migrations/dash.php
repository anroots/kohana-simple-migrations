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
		':num' => $migrations[1]
	))?>
	</p>
	<p>
		<a href="<?=URL::base()?>simple_migrations/migrate/<?=$migrations[1]?>" class="btn btn-warning">
			<?=__('Migrate database to revision :rev', array(':rev' => $migrations[1]))?>
		</a>
	</p>
	<?endif ?>
</div>

