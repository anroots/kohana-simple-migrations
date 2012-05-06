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
		View::set_global('migrations', Simple_Migration::get_files());
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
}