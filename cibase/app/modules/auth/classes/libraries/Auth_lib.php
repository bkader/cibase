<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication module library.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Libraries
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Auth_lib
{
	/**
	 * Instance of CI object
	 * @access 	protected
	 * @var 	object
	 */
	protected $CI;

	/**
	 * Message to be returned
	 * @var array
	 */
	protected $msg = array(
		'type' => 'danger',
		'heading' => '',
		'message' => 'An Error Occured',
	);

	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	// ------------------------------------------------------------------------

	/**
	 * Create new user's account
	 *
	 * @access 	public
	 * @param 	array 	$data 	array of user's data
	 * @return 	array
	 */
	public function user_create(array $data)
	{
		// If account activation is enabled:
		if (config('account.activation.key_life') > 0) {
			$this->msg['type'] = 'info';
			$this->msg['message'] = 'Account created but needs to be activate...';
		}
		// Otherwise
		else {
			$this->msg['type'] = 'success';
			$this->msg['message'] = 'Account created you may now login';
		}

		return $this->msg;
	}

	// ------------------------------------------------------------------------

	/**
	 * Activate user's account using the provided account activation key
	 *
	 * @access 	public
	 * @param 	string 	$key 	account's activation key
	 * @return 	array
	 */
	public function user_activate($key)
	{
		// 1) Check if the key is valid
		if (empty($key) OR mb_strlen($key) !== 40) {
			return false;
		}

		// 2) Look for the key in database and check it.
		// 3) Enable user's account.
		// 4) Delete the account's activation key.
		// 5) Redirect the user to login page.

		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * Prepare new account's activation link
	 *
	 * @access 	public
	 * @param 	string 	$login 	username or email address
	 * @return 	array
	 */
	public function resend_link($login)
	{
		// Do your magic

		// Keep this at the end
		return $this->msg;
	}

	// ------------------------------------------------------------------------

	/**
	 * Proceed to user's login
	 *
	 * @access 	public
	 * @param 	string 	$login 		username or email address
	 * @param 	bool 	$persist 	whether to store the cookie or not
	 * @return 	bool
	 */

	public function user_login($login, $persist = false)
	{
		// Get the user from database to user his/her ID
		// Example:
		// $id = $this->auth_lib->get_user_by_username_or_email($login);
	}

	// ------------------------------------------------------------------------

	/**
	 * Prepares password reset key
	 *
	 * @access 	public
	 * @param 	string 	$login 	username or email address
	 * @return 	array
	 */
	public function password_recover($login)
	{
		// // Example
		// if ($user = $this->auth_lib->get_user_by_username_or_email($login)) {
		// 	$key = sha1(session_id().microtime(TRUE).$user->email);

		// 	// Store the key & email it to the user
		// }

		// Keep this at the end
		return $this->msg;
	}

	// ------------------------------------------------------------------------

	/**
	 * Check whether the given password reset key is valid or not
	 *
	 * @access 	public
	 * @param 	string 	$key password reset key
	 * @return 	bool
	 */
	public function password_reset_check($key)
	{
		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Changes targeted accounts password
	 *
	 * @access 	public
	 * @param 	int 	$id 		user's iD
	 * @param 	string 	$password 	new password
	 * @return 	array
	 */
	public function password_reset($id, $password)
	{
		// Keep this at the end
		return $this->msg;
	}
}
