<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/language_helper.html
 */

if ( ! function_exists('language'))
{
    /**
     * Returns current language code if no parameter is passed.
     * If a single parameter is passed, the array key value is returned.
     * If multiple parameters or an array are passed, this method returns
     * the requestes keys values only.
     *
     * @param   mixed   string, strings or array
     * @return  mixed
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function language()
	{
		return call_user_func_array(array(
			get_instance()->lang,
			'language'
		), func_get_args());
	}
}

if ( ! function_exists('languages'))
{
    /**
     * Returns an array of available languages codes if no parameter is passed,
     * or an array of selected languages details.
     *
     * @param   mixed   string, strings, or array
     * @return  array
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function languages()
	{
		return call_user_func_array(array(
			get_instance()->lang,
			'languages'
		), func_get_args());
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('lang'))
{
	/**
	 * Lang
	 *
	 * Fetches a language variable and optionally outputs a form label
	 *
	 * @param	string	$line		The language line
	 * @param	string	$for		The "for" value (id of the form element)
	 * @param	array	$attributes	Any additional HTML attributes
	 * @return	string
	 */
	function lang($line, $for = '', $attributes = array())
	{
		$line = get_instance()->lang->line($line);

		if ($for !== '')
		{
			$line = '<label for="'.$for.'"'._stringify_attributes($attributes).'>'.$line.'</label>';
		}

		return $line;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('line'))
{
    /**
     * Fetches a single line of text from the language array
     * 
     * @param   string  $line       Language line key
     * @param   mixed   $args       string, integer or array
     * @param   mixed   $default    to be used in case of fail
     * @return  string  Translation
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function line($line, $args = NULL, $default = FALSE)
	{
		return get_instance()->lang->get($line, $args, $default);
	}
}

if ( ! function_exists('__'))
{
    /**
     * Alias of the function above
     * 
     * @param   string  $line       Language line key
     * @param   mixed   $args       string, integer or array
     * @param   mixed   $default    to be used in case of fail
     * @return  string  Translation
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function __($line, $args = NULL, $default = FALSE)
	{
		return get_instance()->lang->get($line, $args, $default);
	}
}

if ( ! function_exists('_e'))
{
    /**
     * Alias of the function above except that it echoes the line.
     * 
     * @param   string  $line       Language line key
     * @param   mixed   $args       string, integer or array
     * @param   mixed   $default    to be used in case of fail
     * @return  string  Translation
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function _e($line, $args = NULL, $default = FALSE)
	{
		echo get_instance()->lang->get($line, $args, $default);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('nline'))
{
    /**
     * Singular & plural form of language line
     * 
     * @param   string  $singular   singular form of the line
     * @param   string  $plural     plural form of the line
     * @param   integer $number     number used for comparison
     * @return  string
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function nline($singular, $plural, $number = 0)
	{
		return get_instance()->lang->nline($line, $args, $default);
	}
}

if ( ! function_exists('_n'))
{
    /**
     * Alias of the function above.
     * 
     * @param   string  $singular   singular form of the line
     * @param   string  $plural     plural form of the line
     * @param   integer $number     number used for comparison
     * @return  string
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function _n($singular, $plural, $number = 0)
	{
		return get_instance()->lang->nline($line, $args, $default);
	}
}

if ( ! function_exists('_en'))
{
    /**
     * Alias of the function above except that it echoes the line.
     * 
     * @param   string  $singular   singular form of the line
     * @param   string  $plural     plural form of the line
     * @param   integer $number     number used for comparison
     * @return  string
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function _en($singular, $plural, $number = 0)
	{
		echo get_instance()->lang->nline($line, $args, $default);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('xline'))
{
    /**
     * This method is purely optional because you can use any method you want
     * to fetch a language line in a particular context.
     * By default, we use the ':' separator, so the line to get fetched would
     * be like so: $lang['post:verb'] or $lang['post:noun']
     * 
     * @param   string      $context    the context to use
     * @param   string      $line       the language line to fetch
     * @param   mixed       $args       arguments to pass parse
     * @param   boolean     $default    value to use if no line is found
     * @return  string      the fetched language line
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function xline($context, $line, $args = NULL, $default = FALSE)
	{
		return get_instance()->lang->xline($context, $line, $args, $default);
	}
}

if ( ! function_exists('_x'))
{
    /**
     * Alias of the function above.
     * 
     * @param   string      $context    the context to use
     * @param   string      $line       the language line to fetch
     * @param   mixed       $args       arguments to pass parse
     * @param   boolean     $default    value to use if no line is found
     * @return  string      the fetched language line
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function _x($context, $line, $args = NULL, $default = FALSE)
	{
		return get_instance()->lang->xline($context, $line, $args, $default);
	}
}

if ( ! function_exists('_ex'))
{
    /**
     * Alias of the function above except that it echoes it.
     * 
     * @param   string      $context    the context to use
     * @param   string      $line       the language line to fetch
     * @param   mixed       $args       arguments to pass parse
     * @param   boolean     $default    value to use if no line is found
     * @return  string      the fetched language line
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
	function _ex($context, $line, $args = NULL, $default = FALSE)
	{
		echo get_instance()->lang->xline($context, $line, $args, $default);
	}
}
