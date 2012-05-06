<?php defined('SYSPATH') or die('No direct script access.')?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Simple Migrations module controller">
	<meta name="author" content="Ando Roots">

	<!-- Le styles, in naive hopes that Bootstrap GitHub stays unchanged -->
	<link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
	<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}

		.sidebar-nav {
			padding: 9px 0;
		}
	</style>
	<link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=URL::base()?>simple_migrations"><?=__('Simple Migrations')?></a>

			<form class="navbar-form pull-right" method="post" action="<?=URL::base()?>simple_migrations/migrate">
				<span class="navbar-text"><?=__('Revision version')?>: </span>
				<input type="text" class="span1" title="<?=__('Database revision version to migrate to')?>" name="revision"
				       required value="<?=$current->version + 1?>"/>
				<input type="submit" value="<?=__('Migrate DB')?>" class="btn btn-warning"/>
			</form>

			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="<?=URL::base()?>simple_migrations"><?=__('Dash')?></a></li>
					<li>
						<a href="https://github.com/anroots/kohana-simple-migrations/wiki" target="_blank">
							<?=__('Module documentation')?>
						</a>
					</li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">
			<div class="well sidebar-nav">
				<ul class="nav nav-list">
					<li class="nav-header"><?=__('Database')?></li>
					<li>
						<?=__('Current revision: <strong>:rev</strong>', array(
						':rev' => $current->version,
					))?>
					</li>
					<li>
						<?=__('Latest revision: <strong>:rev</strong>', array(
						':rev' => $migrations[0]
					))?>
					</li>
					<li class="nav-header"><?=__('Revisions')?></li>
					<?if (count($migrations)): ?>
					<? foreach ($migrations as $migration_version): ?>
						<li>
							<a href="<?=URL::base()?>simple_migrations/revision/<?=$migration_version?>">
								<?=__('Revision')?> <?=$migration_version?>
							</a>
						</li>
						<? endforeach ?>
					<? endif?>
				</ul>
			</div>
			<!--/.well -->

		</div>
		<!--/span-->
		<div class="span10">
			<?=$content?>
		</div>
		<!--/span-->

	</div>
	<!--/row-->

	<hr>

	<footer>
		<p>
			<?=__('Kohana Simple Migrations version :ver', array(':ver' => Simple_Migration::VERSION))?> |
			<?=__('Fork me on :link', array(
			':link' => HTML::anchor('https://github.com/anroots/kohana-simple-migrations/wiki', 'GitHub')
		))?> |
			<?=__('Licence: LGPL')?> |
			<a href="<?=URL::base()?>simple_migrations/uninstall" title="<?=__('Deletes the module database table.')?>">
				<?=__('Uninstall the module')?>
			</a>
		</p>
	</footer>

</div>
<!--/.fluid-container-->
</body>
</html>