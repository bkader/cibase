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
 * Language Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Language
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/language.html
 */
class CI_Lang {

	/**
	 * List of translations
	 *
	 * @var	array
	 */
	public $language =	array();

	/**
	 * List of loaded language files
	 *
	 * @var	array
	 */
	public $is_loaded =	array();

	/**
	 * Holds Config class object
	 * @var array
	 */
	protected $config;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->config = load_class('Config', 'core');

		log_message('info', 'Language Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Load a language file
	 *
	 * @param	mixed	$langfile	Language file name
	 * @param	string	$idiom		Language name (en, etc.)
	 * @param	bool	$return		Whether to return the loaded array of translations
	 * @param 	string	$alt_path	Alternative path to look for the language file
	 *
	 * @return	void|string[]	Array containing translations, if $return is set to TRUE
	 */
    public function load($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
    {
        // In case of multiple files
        if (is_array($langfile))
        {
            foreach ($langfile as $value)
            {
                $this->load($value, $idiom, $return, $add_suffix, $alt_path);
            }

            return;
        }

        // Remove .php extension if set
        $langfile = str_replace('.php', '', $langfile);

        $langfile .= '.php';

        if (empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
        {
            $idiom = $this->config->item('language');
        }

        if ($return === FALSE && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom)
        {
            return;
        }

        // Load the english version first, then we load the the requested one
        $full_lang = array();

        // Load the base file, so any others found can override it
        $basepath = BASEPATH.'lang/en/'.$langfile;
        if (($found = file_exists($basepath)) === TRUE)
        {
            include($basepath);
        }

        // Do we have an alternative path to look in?
        if ($alt_path !== '')
        {
            $alt_path .= 'lang/en/'.$langfile;
            if (file_exists($alt_path))
            {
                include($alt_path);
                $found = TRUE;
            }
        }
        else
        {
            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
            {
                $package_path .= 'lang/en/'.$langfile;
                if ($basepath !== $package_path && file_exists($package_path))
                {
                    include($package_path);
                    $found = TRUE;
                    break;
                }
            }
        }

        if ($found !== TRUE)
        {
            show_error('Unable to load the requested language file: lang/en/'.$langfile);
        }

        $full_lang = isset($lang) ? $lang : array();
        $lang = array();

        // Load the base file, so any others found can override it
        $basepath = BASEPATH.'lang/'.$idiom.'/'.$langfile;
        if (file_exists($basepath))
        {
            include($basepath);
        }

        // Do we have an alternative path to look in?
        if ($alt_path !== '')
        {
            $alt_path .= 'lang/'.$idiom.'/'.$langfile;
            if (file_exists($alt_path))
            {
                include($alt_path);
            }
        }
        else
        {
            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path)
            {
                $package_path .= 'lang/'.$idiom.'/'.$langfile;
                if ($basepath !== $package_path && file_exists($package_path))
                {
                    include($package_path);
                    break;
                }
            }
        }

        if ($found !== TRUE)
        {
            show_error('Unable to load the requested language file: lang/'.$idiom.'/'.$langfile);
        }

        isset($lang) OR $lang = array();

        $full_lang = array_replace_recursive($full_lang, $lang);

        if ( ! isset($full_lang) OR ! is_array($full_lang))
        {
            log_message('error', 'Language file contains no data: lang/'.$idiom.'/'.$langfile);

            if ($return === TRUE)
            {
                return array();
            }
            return;
        }

        if ($return === TRUE)
        {
            return $full_lang;
        }

        $this->is_loaded[$langfile] = $idiom;
        $this->language = array_merge($this->language, $full_lang);

        log_message('info', 'Language file loaded: lang/'.$idiom.'/'.$langfile);
        return TRUE;
    }

	// --------------------------------------------------------------------

	// Edited by Kader Bouyakoub: 15/02/2017 @ 10:05

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array
	 *
	 * @param	string	$line		Language line key
	 * @param	mixed	$args		string, integer or array
	 * @param	mixed	$default 	to be used in case of fail
	 * @return	string	Translation
	 */
	public function line($line, $args = NULL, $default = FALSE)
	{
		$value = function_exists('dot') 
					? dot($this->language, $line, $default) 
					: $this->dot($this->language, $line, $default);

		// Log message error if the line is not found
		if ($value === FALSE)
		{
			log_message('error', 'Cound not find the language line "'.$line.'".');
		}
		// If the line is found, we parse arguments
		elseif ($args)
		{
			$args = (array) $args;

			// Is the user trying to translate arguments?
			foreach ($args as &$arg)
			{
				if (strpos('lang:', $arg) !== 0)
				{
					$arg = str_replace('lang:', '', $arg);
					$arg = $this->line($arg);
				}
			}

			$value = vsprintf($value, $args);
		}

		return $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Access multidimensional array using dot-notation
     *
     * @author 	Kader Bouyakoub  <bkader@mail.com>
     * @link    @bkader          <github>
     * @link    @KaderBouyakoub  <twitter>
     *
     * @param 	array 	$arr 	the array to search in
	 * @param   string 	$path 	the path to the array's element
	 * @param 	mixed 	$default returned if no element found
	 * @return  mixed
	 */
    protected function dot(&$arr, $path = NULL, $default = NULL)
    {
        if ( ! $path) {
            user_error("Missing array path for array", E_USER_WARNING);
        }
        $parts = explode(".", $path);
        is_array($arr) or $arr = (array) $arr;
        $path =& $arr;
        foreach ($parts as $e) {
            if ( ! isset($path[$e]) or empty($path[$e])) {
                return $default;
            }
            $path =& $path[$e];
        }
        return $path;
    }

}
