<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Debug functions
 *
 * @package 	CodeIgniter
 * @subpackage 	Helpers
 *
 * @author 		Kader Bouyakoub 	<bkader.com>
 * @link 		@bkader 			<github>
 * @link 		@KaderBouyakoub 	<twitter>
 */

if ( ! function_exists('_before')) {
	/**
	 * Print some styling before debugging
	 *
	 * @access 	public
	 * @param 	void
	 * @return 	void
	 */
	function _before()
	{
		$before = '<div style="padding:10px 20px;background-color:#fbe6f2;border:1px solid #d893a1;color:#000;font:12px/1.4 Tahoma, Arial, sans-serif;">'."\n";
		$before .= '<h5 style="font-family:verdana,sans-serif; font-weight:bold; font-size:18px;">Debug Helper Output</h5>'."\n";
		$before .= '<pre>'."\n";
		return $before;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_after')) {
	/**
	 * Print closing tags
	 *
	 * @access 	public
	 * @param 	void
	 * @return 	void
	 */
	function _after()
	{
		$after = '</pre>'."\n";
		$after .= '</div>'."\n";
		return $after;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('debug')) {
	/**
	 * Debug general variables
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	boolean 	If set, if uses exit;
	 */
	function debug($var = '', $exit = false)
	{
		echo _before().((is_array($var) || is_object($var)) ? print_r($var, true) : $var)._after();
		if ($exit === true) exit;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('debug_last_query')) {
	/**
	 * Debug last query
	 *
	 * @access 	public
	 * @param 	void
	 * @return 	string
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	function debug_last_query()
	{
		echo _before().get_instance()->db->last_query()._after();
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('debug_query_result')) {
	/**
	 * Debug last query's result
	 *
	 * @access 	public
	 * @param 	void
	 * @return 	string
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	function debug_query_result($query = '')
	{
		echo _before();
		print_r($query->result_array());
		echo _after();
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('debug_session')) {
	/**
	 * Debug session variables
	 *
	 * @access 	public
	 * @param 	boolean 	$exit 	whether to exit or not
	 * @return 	string
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	function debug_session($exit = false)
	{
		echo _before();
		print_r(get_instance()->session->all_userdata());
		echo _after();
		if ($exit === true) exit;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('log_debug'))
{
	function log_debug($message = '')
	{
		is_array($message) ? log_message('debug', print_r($message)) : log_message('debug', $message);
	}
}
