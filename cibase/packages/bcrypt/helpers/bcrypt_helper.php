<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bcrypt helper
 *
 * This file contains quick access to Bcrypt library to hash and compare strings
 *
 * @package 	CodeIgniter
 * @category 	Helpers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

if ( ! function_exists('bcrypt_hash'))
{
	/**
	 * Uses bcrypt library provided as a package to hash a string
	 *
	 * @param 	string 	$password 	the password to hash
	 * @return 	string 	the new password after being hashed
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	function bcrypt_hash($password)
	{
		$CI =& get_instance();

		// Load bcrypt library if not alread loaded
		class_exists('Bcrypt') OR $CI->load->library('bcrypt');

		return $CI->bcrypt->hash_password($password);
	}
}

if ( ! function_exists('bcrypt_check'))
{
	/**
	 * Compares between a given password and hashed string
	 *
	 * @param 	string 	$password 	the password to check
	 * @param 	string 	$hashed 	the hashed string to compare to
	 * @return 	string 	the new password after being checked
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	function bcrypt_check($password, $hashed)
	{
		$CI =& get_instance();

		// Load bcrypt library if not alread loaded
		class_exists('Bcrypt') OR $CI->load->library('bcrypt');

		return $CI->bcrypt->check_password($password, $hashed);
	}
}
