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

	/**
	 * Account activation
	 *
	 * @access 	public
	 * @param 	string 	$key 	account activation key
	 * @return 	void
	 */
	public function action_activate($key = null)
	{
		// 4O because I usually use sha1
		if (empty($key) OR mb_strlen($key) <> 40) {
			die('No key is set in order to activate an account.');
		}

		// You have to put your own login to activate user's account
		// You have to check the account activation key first, enable user's
		// account then delete the key.
		// Once all that is done, you simple need to set some flash messages
		// and redirect the user whether to the same page or to the login
		// page.

		die('Account activation.<br>Key: '.$key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Resend account's activation key
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function action_resend()
	{
		// prepare form validation
		$this->prepare_form(array(
			array(	'field' => 'login',
					'label' => 'lang:ui.username_or_email',
					'rules' => 'required|min_length[5]')
		));

		// Before form is submitted, we load the view file.
		if ($this->form_validation->run() == false) {
			$this->template
					->set_title(__('auth.resend.title', array(), 'Resend link'))
					->set_description(__('auth.resend.description', array(), 'Resend account\'s activation link'))
					->load('resend', $this->data);
		}
		// If the form is proceed, make sure to put your logic
		else {
			$login = $this->input->post('login', true);

			print_r($login);

			// Suggestion: here is what I would do
			// 1) Optional: use a library to put the rest of the code
			// 2) I check if there an available (not deleted) activation key,
			//    if I find it, I send it back to use, DONE.
			// 3) If the key does not exists, we create a new one and then
		}
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
		// Prepare form validation
		$this->prepare_form();

		// Before form is submitted
		if ($this->form_validation->run('login') == false) {
			$this->template
					->set_title(__('auth.login.title', array(), 'Login'))
					->set_description(__('auth.login.description', array(), 'Member\'s login'))
					->load('login', $this->data);
		}
		// Once the form is submitted and all conditions are OK, proceed to login
		else {
			$data = $this->input->post(array('login', 'password', 'persist'), true);

			// Do your login to login

			echo '<pre>';
			print_r($data);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Recover a lost password
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function action_recover()
	{
		// Prepare form validation (same as resend)
		$this->prepare_form(array(
			array(	'field' => 'login',
					'label' => 'lang:ui.username_or_email',
					'rules' => 'required|min_length[5]')
		));

		// Before submitting the form
		if ($this->form_validation->run() == false) {
			$this->template
					->set_title(__('auth.recover.title', array(), 'Lost password'))
					->set_description(__('auth.recover.description', array(), 'Lost password recovery'))
					->load('recover', $this->data);
		}
		else {
			$login = $this->input->post('login', true);

			// Put the rest of the code below!
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * After a password reset request, this method is called with the account's
	 * password reset key passed as the argument.
	 *
	 * @access 	public
	 * @param 	string 	$key 	account password reset key.
	 */
	public function action_reset($key = null)
	{
		// 4O because I usually use sha1
		if (empty($key) OR mb_strlen($key) <> 40) {
			die('No key is set in order to reset account\'s password.');
		}

		// You have to put your own login to activate user's account
		// You have to check the account activation key first, enable user's
		// account then delete the key.
		// Once all that is done, you simple need to set some flash messages
		// and redirect the user whether to the same page or to the login
		// page.

		// Pass the key to the view because we need it
		// $this->data['key'] = $key;

		die('Proceeding to password reset.<br>Key: '.$key);
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
