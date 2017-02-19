<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Security Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/security_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('xss_clean'))
{
	/**
	 * XSS Filtering
	 *
	 * @param	string
	 * @param	bool	whether or not the content is an image file
	 * @return	string
	 */
	function xss_clean($str, $is_image = false)
	{
		return get_instance()->security->xss_clean($str, $is_image);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('sanitize_filename'))
{
	/**
	 * Sanitize Filename
	 *
	 * @param	string
	 * @return	string
	 */
	function sanitize_filename($filename)
	{
		return get_instance()->security->sanitize_filename($filename);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('do_hash'))
{
	/**
	 * Hash encode a string
	 *
	 * @todo	Remove in version 3.1+.
	 * @deprecated	3.0.0	Use PHP's native hash() instead.
	 * @param	string	$str
	 * @param	string	$type = 'sha1'
	 * @return	string
	 */
	function do_hash($str, $type = 'sha1')
	{
		if ( ! in_array(strtolower($type), hash_algos()))
		{
			$type = 'md5';
		}

		return hash($type, $str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('strip_image_tags'))
{
	/**
	 * Strip Image Tags
	 *
	 * @param	string
	 * @return	string
	 */
	function strip_image_tags($str)
	{
		return get_instance()->security->strip_image_tags($str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('encode_php_tags'))
{
	/**
	 * Convert PHP tags to entities
	 *
	 * @param	string
	 * @return	string
	 */
	function encode_php_tags($str)
	{
		return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('sanitize'))
{
    /**
     * Sanitize text
     *
     * @param   string 	$str 	the string to sanitize
     * @return  string
     *
     * @author 	Kader Bouyakoub <bkader@mail.com>
     * @link 	https://github.com/bkader
     * @link 	https://twitter.com/KaderBouyakoub
     */
    function sanitize($str)
    {
    	// Load string helper if not already loaded
    	if ( ! function_exists('strip_slashes'))
    	{
    		get_instance()->load->helper('string');
    	}

        return xss_clean(htmlentities(strip_slashes($str), ENT_QUOTES, 'UTF-8'));
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('generate_safe_token'))
{
    /**
     * Generate Safe Token using md5 or sha1. This token is needed for
     * validating $_GET requests
     *
     * @param   integer 	$time 	unix_timestamp
     * @param 	boolean 	$sha1 	whether to use sha1 or md5
     * @return  string
     *
     * @author 	Kader Bouyakoub <bkader@mail.com>
     * @link 	https://github.com/bkader
     * @link 	https://twitter.com/KaderBouyakoub
     */
    function generate_safe_token($time = false, $sha1 = false)
    {
    	$time OR $time = time();
    	$str = $time.config_item('encryption_key').session_id();
    	return ($sha1 === true) ? sha1($str) : md5($str);
    }
}
