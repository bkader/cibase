<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example helper provided as an example helper that comes with a module.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Helpers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

if ( ! function_exists('hash_example'))
{
	function hash_example($string)
	{
		// Return null if no string is provided
		if (is_null($string))
		{
			return NULL;
		}

		// This function gets called first because CI comes with it.
		if (function_exists('password_hash'))
		{
			return password_hash($string, PASSWORD_BCRYPT);
		}
		// If the function above does not exist, we use provided bcrypt package.
		else
		{
			$CI =& get_instance();
			$CI->load->add_package_path(PKGPATH.'bcrypt');
			$CI->load->helper('bcrypt');
			$hashed = bcrypt_hash($string);
			$CI->load->remove_package_path(PKGPATH.'bcrypt');
			return $hashed;
		}
	}
}
