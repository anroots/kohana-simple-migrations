<?php defined('SYSPATH') or die('No direct script access.') ?>
<h2><?=__('Revision :rev', array(':rev' => Request::current()->param('id')))?></h2>

	<div class="row-fluid">
<? if ($revision->file() === FALSE): ?>
	<p class="alert alert-error">
		<?=__('Revision not found.')?>
	</p>
	<? else: ?>
<p class="alert <?=$current->version >= $revision->version ? 'alert-success' : NULL?>">
	<? if ($current->version >= $revision->version):?>
		<?= __('Applied on :date.', array(':date' => $revision->applied)) ?>
		<? else: ?>
		<?= __('Not yet applied.') ?>
	</p>
		<p>
	<a href="<?=URL::base()?>simple_migrations/migrate/<?=$revision->version?>" title="<?=__('Migrate to revision :ver',
		array(':ver' => $revision->version))?>" class="btn btn-warning pull-right"><?=__('Migrate to this revision')?></a>
		<?endif ?>
</p>
</div>

<h3><?=__('Up script')?></h3>
<p class="help-block pull-right">
	<code><?=$revision->file()?></code>
</p>
<div class="clearfix"></div>

<pre><?=$revision->sql_up?></pre>
<h3><?=__('Down script')?></h3>
<p class="help-block pull-right">
	<code><?=$revision->file($revision->version,Simple_Migration::DOWN)?></code>
</p>
<div class="clearfix"></div>

<pre><?=$revision->sql_down?></pre>
<?endif ?>