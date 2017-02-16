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
	 * @var object
	 */
	protected $config;

    /**
     * Holds Arr class object
     * @var object
     */
    protected $arr;

    /**
     * Holds fallback language
     * @var string
     */
    protected $fallback = 'en';

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->config =& load_class('Config', 'core');
        $this->arr =& load_class('Arr', 'core');
        $this->fallback = $this->config->item('language_fallback') ?: 'en';

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
    public function load($langfile, $idiom = '', $return = FALSE, $alt_path = '')
    {
        // In case of multiple files
        if (is_array($langfile))
        {
            foreach ($langfile as $value)
            {
                $this->load($value, $idiom, $return, $alt_path);
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
        $basepath = BASEPATH.'lang/'.$this->fallback.'/'.$langfile;
        if (($found = file_exists($basepath)) === TRUE)
        {
            include($basepath);
        }

        // Do we have an alternative path to look in?
        if ($alt_path !== '')
        {
            $alt_path .= 'lang/'.$this->fallback.'/'.$langfile;
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
                $package_path .= 'lang/'.$this->fallback.'/'.$langfile;
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
            show_error('Unable to load the requested language file: lang/'.$this->fallback.'/'.$langfile);
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

    // ------------------------------------------------------------------------
    // !SETTER & GETTER
    // ------------------------------------------------------------------------

    // Added by Kader Bouyakoub

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
    public function get($line, $args = NULL, $default = FALSE)
    {
        $value = $this->arr->get($this->language, $line, $default);

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
                if (strpos($arg, 'lang:') !== FALSE)
                {
                    $arg = str_replace('lang:', '', $arg);
                    $arg = $this->line($arg);
                }
            }

            $value = vsprintf($value, $args);
        }

        return $value;
    }

    /**
     * This method allows you to change line value
     * @access  public
     * @param   string  $line   the language line to change
     * @param   string  $value  language line's new value
     * @return  void
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
    public function set($line, $value = NULL)
    {
        $this->arr->set($this->language, $line, $value);
    }

	// --------------------------------------------------------------------

	// Edited by Kader Bouyakoub: 15/02/2017 @ 10:05

	/**
	 * This method is replaced by Lang::get() and kept for backward compatibility
	 * @param	string	$line		Language line key
	 * @param	mixed	$args		string, integer or array
	 * @param	mixed	$default 	to be used in case of fail
	 * @return	string	Translation
	 */
	public function line($line, $args = NULL, $default = FALSE)
	{
        return $this->get($line, $args, $default);
	}

    /**
     * Singular & plural form of language line
     * 
     * @access  public
     * @param   string  $singular   singular form of the line
     * @param   string  $plural     plural form of the line
     * @param   integer $number     number used for comparison
     * @return  string
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
    public function nline($singular, $plural, $number = 0)
    {
        $line = ($number == 1) ? $singular : $plural;
        $value = $this->line($line, $number);
        return sprintf($value, $number);
    }

    /**
     * This method is purely optional because you can use any method you want
     * to fetch a language line in a particular context.
     * By default, we use the ':' separator, so the line to get fetched would
     * be like so: $lang['post:verb'] or $lang['post:noun']
     *
     * @access  public
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
    public function xline($context, $line, $args = NULL, $default = FALSE)
    {
        return $this->line($line.':'.$context, $args, $default);
    }

    /**
     * Returns an array of available languages codes if no parameter is passed,
     * or an array of selected languages details.
     *
     * @access  public
     * @param   mixed   string, strings, or array
     * @return  array
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
    public function languages()
    {
        $langs = $this->config->item('languages');

        if ( ! empty($args = func_get_args()))
        {
            $args = (array) $args;
            is_array($args[0]) && $args = $args[0];
            $_langs = array();

            foreach ($langs as $code)
            {
                foreach ($args as $arg)
                {
                    if (isset($this->_get_language($code)[$arg]))
                    {
                        $_langs[$code][$arg] = $this->_get_language($code)[$arg];
                    }
                }
            }

            $langs = $_langs;
        }

        return $langs;
    }

    /**
     * Returns current language code if no parameter is passed.
     * If a single parameter is passed, the array key value is returned.
     * If multiple parameters or an array are passed, this method returns
     * the requestes keys values only.
     *
     * @access  public
     * @param   mixed   string, strings or array
     * @return  mixed
     *
     * @author  Kader Bouyakoub <bkader@mail.com>
     * @link    https://github.com/bkader
     * @link    https://twitter.com/KaderBouyakoub
     */
    public function language()
    {
        // Prepare the language code
        $lang = $this->config->item('language');

        // If any arguments are passed
        if ( ! empty($args = func_get_args()))
        {
            is_array($args[0]) && $args = $args[0];

            switch (count($args)) {
                case 1:
                    // Ignore arguments below
                    if (in_array($args[0], array(FALSE, NULL)))
                    {
                        continue;
                    }
                    // If TRUE is passed, the full array is returned
                    elseif ($args[0] === TRUE)
                    {
                        $lang = $this->_get_language($this->config->item('language'));
                    }
                    // If none of the above, we continue
                    else {
                        goto other;
                    }
                    break;

                default:
                    other:
                    $language = $this->_get_language($this->config->item('language'));
                    $_lang = array();

                    // Loop through arguments and fill $_lang array only if the key exists
                    foreach ($args as $arg)
                    {
                        if (isset($language[$arg]))
                        {
                            $_lang[$arg] = $language[$arg];
                        }
                    }

                    // If $_lang is not empty, we replace $lang by it.
                    // If a single key is found, we return it as it is.
                    if ( ! empty($_lang))
                    {
                        $lang = (count($_lang) == 1) ? array_pop($_lang) : $_lang;
                    }
                    break;
            }
        }

        return $lang;
    }

    /**
     * Returns an array of languages details
     * @access  protected
     * @param   string  $code   language's code to retrieve
     * @return  array
     */
    protected function _get_language($code = 'en')
    {
        $lang = require BASEPATH.'vendor/languages.php';

        if ($code && isset($lang[$code]))
        {
            $lang = $lang[$code];
        }

        return $lang;
    }
}
