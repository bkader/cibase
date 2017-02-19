<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This library is provided as an example only.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Libraries
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Example_lib
{
	/**
	 * Instance of CI object
	 * @access 	protected
	 * @var 	object
	 */
	protected $CI;

	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Returns a readable random string using string helper
	 * @access 	public
	 * @param 	integer 	$length
	 * @param 	boolean 	$camelize
	 * @return 	string
	 */
	public function random_string($length = 6, $camelize = false)
	{
		$this->CI->load->helper('string');
		return readable_random_string($length, $camelize);
	}

	/**
	 * Generates a dummy password using the method above
	 * @param 	integer 	$length 	length of the password
	 * @return 	string 		the new generated password
	 */
	public function random_password($length = 8)
	{
		return $this->random_string($length, false);
	}
}
