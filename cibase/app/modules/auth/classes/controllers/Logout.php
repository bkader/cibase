<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends User_Controller
{
	protected $autoload = array(
		'lang'    => 'auth',
		'library' => 'auth_lib',
	);

	/**
	 * This method is called before any other methods
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function before()
	{
		parent::before();
	}

	// ------------------------------------------------------------------------

	/**
	 * User's logout method
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		echo 'Logout';
	}

	// ------------------------------------------------------------------------

	/**
	 * This method is the last one to be called
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function action_after($params = '')
	{
		parent::after($params);
	}
}
