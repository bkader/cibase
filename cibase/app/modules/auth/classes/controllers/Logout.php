<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication module's logout controller
 *
 * This reason we put the logout method as a single controller class is because
 * of class inheritance. Logout class extends User_Controller which in this
 * case needs a logged-in user.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Controllers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

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
		// Put your account logout login below and don't forget to do a redirect.

		// Example:
		// $this->auth_lib->logout();
		// redirect('','refresh');
		// exit;

		echo 'users logout';
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
