<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controller for installing the module and handling revisions
 *
 * @since 1.0
 */
class Controller_Simple_Migrations extends Controller_Template
{

	public $template = 'simple/migrations/template';

	/**
	 * @var Simple_Migration
	 */
	private $_instance;

	public function before()
	{
		parent::before();

		$this->_check_install();


		$this->_instance = Simple_Migration::instance();

		$this->template->title = __('Kohana Simple Migrations');
		$this->template->content = NULL;

		// Current DB status
		View::set_global('status', $this->_instance->status());

		// Current revision
		View::set_global('current', $this->_instance->current());

		// List of SQL files
		$files = Simple_Migration::get_files();
		View::set_global('migrations', $files);
	}

	/**
	 * Show current status
	 *
	 * @since 1.0
	 */
	public function action_index()
	{
		$this->template->content = View::factory('simple/migrations/dash');
	}

	/**
	 * Show details about a particular revision
	 *
	 * @since 1.0
	 */
	public function action_revision()
	{
		$id = Request::current()->param('id');

		$this->template->content = View::factory('simple/migrations/revision');
		$this->template->content->revision = new Simple_Migration_Revision((int)$id);
	}

	/**
	 * Redirect to installer action if not installed
	 *
	 * @since 1.0
	 */
	private function _check_install()
	{
		if ($this->request->action() != 'install' && !Simple_Migration::instance()->is_installed()) {
			$this->request->redirect('simple_migrations/install');
		}
	}

	/**
	 * Migrate DB to a particular revision
	 *
	 * @since 1.0
	 */
	public function action_migrate()
	{
		// Get revision number to migrate TO
		if ($this->request->post('revision') !== NULL) { // From navbar form
			$revision = $this->request->post('revision');
		} elseif ($this->request->param('id') !== NULL) { // From URL
			$revision = $this->request->param('id');
		} else { // Latest available revision by default
			$revision = $this->_instance->current()->version + 1;
		}

		$output = $this->_instance->migrate($revision);

		$this->template->content = View::factory('simple/migrations/migrate', array(
			'output' => $output
		));
	}

	/**
	 * Install the module
	 *
	 * @since 1.0
	 */
	public function action_install()
	{
		$this->template->content = View::factory('simple/migrations/install');

		// Clicked Install btn
		if ($this->request->param('id') === 'continue') {
			$installer = new Simple_Migration_Installer();

			// Install and redirect
			$installer->install();
			$this->request->redirect('simple_migrations');
		}
	}

	/**
	 * Uninstall the module
	 *
	 * @since 1.0
	 */
	public function action_uninstall()
	{
		$this->template->content = View::factory('simple/migrations/uninstall');

		if ($this->request->param('id') === 'continue') {
			$installer = new Simple_Migration_Installer();

			// Uninstall and redirect
			$installer->uninstall();
			$this->request->redirect('simple_migrations');
		}
	}
}