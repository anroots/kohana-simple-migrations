<?php defined('SYSPATH') or die('No direct script access.') ?>
<h2><?=__('Migration from database revision :from to revision :to', array(
	':from' => $current->version,
	':to' => Request::current()->param('id')
))?></h2>

<a href="<?=URL::base()?>simple_migrations"><?=__('Back to Dash')?></a>
<br/>
<br/>
<div class="alert <?=$output['status'] === 0 ? 'alert-success' : 'alert-error'?>">
	<h3 class="alert-heading"><?=__('Migration result')?></h3>

	<p>
		<?if ($output['status'] === 0): ?>
		<?= __('Migration successfully completed.') ?>
		<? else: ?>
		<?= __('There was an error while running the migration.') ?>
		<?endif?>
	</p>
</div>

<h4><?=__('Command output')?></h4>

<pre><?=$output['output']?></pre>
<div class="clearfix"></div>
<br/>

<a href="<?=URL::base()?>simple_migrations"><?=__('Back to Dash')?></a>