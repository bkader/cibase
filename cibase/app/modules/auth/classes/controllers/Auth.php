<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Module
 *
 * This module is provided as an example of how your application's modules
 * should be structured.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Controllers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Auth extends Public_Controller
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
	 * Index method that redirect logged-in user to homepage
	 * and other users to login page.
	 *
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	void
	 */
	public function action_index()
	{
		echo 'Auth Index';
	}

	// ------------------------------------------------------------------------

	/**
	 * User's registration method
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function action_register()
	{
		// Prepare form validation
		// You can find config inside form_validation.php
		$this->prepare_form();

		// If the form is processed
		if ($this->form_validation->run('register') == false)
		{
			$this->template
				->set_title(__('auth.register.title', null, 'Create Account'))
				->set_description(__('auth.register.description', config('app.name'), 'Join %s community'))
				->load('register', $this->data);
		}
		else
		{
			// Collect $_POST data
			$data = $this->input->post(array(
				'email',
				'username',
				'password'
			), true);

			echo '<pre>';
			print_r($data);
		}
	}

	// ------------------------------------------------------------------------

	public function action_activate($key = null)
	{
		echo 'Activation';
	}

	// ------------------------------------------------------------------------

	public function action_resend()
	{
		echo 'Resend link';
	}

	// ------------------------------------------------------------------------

	/**
	 * Member's login method
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function action_login()
	{
		echo 'Login';
	}

	// ------------------------------------------------------------------------

	public function action_recover()
	{
		echo 'Lost Password';
	}

	// ------------------------------------------------------------------------

	public function action_reset($key = null)
	{
		echo 'Reset Password';
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
