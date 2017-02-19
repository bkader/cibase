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
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}
}
