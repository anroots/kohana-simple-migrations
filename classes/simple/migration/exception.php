<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @since 1.0
 */
class Simple_Migration_Exception extends Kohana_Exception
{
	public function __construct($message = 'Database migration error', array $variables = NULL, $code = 0)
	{
		parent::__construct($message, $variables, $code);
	}
}