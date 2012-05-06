<h2><?=__('Uninstall')?></h2>

<p>
	<?=__('Uninstalling the module deletes the migration history and the module\'s database table (`:tbl`). Continue?',
	array(':tbl' => Simple_Migration::get_table_name()))?>
</p>
<p>
	<a href="<?=URL::base()?>simple_migrations/uninstall/continue" class="btn btn-danger">
		<?=__('Uninstall the module')?>
	</a>
</p>