<?php defined('SYSPATH') or die('No direct script access.') ?>
<h2>Module installation</h2>
<div class="row-fluid">
	<p>
		Welcome to the Kohana Simple Migrations module. The module needs a database table to function,
		please go ahead and create it by clicking the Install button. A new table, <code><?=Simple_Migration::get_table_name()
		?></code>
		will be created in your database which will start tracking applied migrations.
	</p>

	<p>
		<a href="<?=URL::base()?>simple_migrations/install/continue" class="btn btn-primary btn-large">
			<?=__('Install the module')?>
		</a>
	</p>
</div>