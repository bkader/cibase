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
 * Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Config {

	/**
	 * List of all loaded config values
	 *
	 * @var	array
	 */
	public $config = array();

	/**
	 * List of all loaded config files
	 *
	 * @var	array
	 */
	public $is_loaded =	array();

	/**
	 * List of paths to search when trying to load a config file.
	 *
	 * @used-by	CI_Loader
	 * @var		array
	 */
	public $_config_paths =	array(APPPATH);

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable.
	 *
	 * @return	void
	 */
	public function __construct()
	{
		global $ARR;
		$this->arr = $ARR;

		$this->config =& get_config();

		// Set the base_url automatically if none was provided
		if (empty($this->config['base_url']))
		{
			if (isset($_SERVER['SERVER_ADDR']))
			{
				if (strpos($_SERVER['SERVER_ADDR'], ':') !== FALSE)
				{
					$server_addr = '['.$_SERVER['SERVER_ADDR'].']';
				}
				else
				{
					$server_addr = $_SERVER['SERVER_ADDR'];
				}

				$base_url = (is_https() ? 'https' : 'http').'://'.$server_addr
					.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
			}
			else
			{
				$base_url = 'http://localhost/';
			}

			$this->set_item('base_url', $base_url);
		}

		$_config = array();
		$config = array();

		// Load application configuration file
        if (file_exists(APPPATH.'config/app.php')) {
            require APPPATH.'config/app.php';
            $_config = array_replace_recursive($_config, $config);
            $config = array();
        }

        if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/app.php')) {
            require APPPATH.'config/'.ENVIRONMENT.'/app.php';
            $_config = array_replace_recursive($_config, $config);
            $config = array();
        }

        $this->config = array_replace_recursive($this->config, $_config);

		log_message('info', 'Config Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Load Config File
	 *
	 * @param	string	$file			Configuration file name
	 * @param	bool	$use_sections		Whether configuration values should be loaded into their own section
	 * @param	bool	$fail_gracefully	Whether to just return FALSE or display an error message
	 * @return	bool	TRUE if the file was loaded correctly or FALSE on failure
	 */
	public function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = FALSE;

		foreach ($this->_config_paths as $path)
		{
			foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, TRUE))
				{
					return TRUE;
				}

				if ( ! file_exists($file_path))
				{
					continue;
				}

				include($file_path);

				if ( ! isset($config) OR ! is_array($config))
				{
					if ($fail_gracefully === TRUE)
					{
						return FALSE;
					}

					show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
				}

				if ($use_sections === TRUE)
				{
					$this->config[$file] = isset($this->config[$file])
						? array_merge($this->config[$file], $config)
						: $config;
				}
				else
				{
					$this->config = array_merge($this->config, $config);
				}

				$this->is_loaded[] = $file_path;
				$config = NULL;
				$loaded = TRUE;
				log_message('debug', 'Config file loaded: '.$file_path);
			}
		}

		if ($loaded === TRUE)
		{
			return TRUE;
		}
		elseif ($fail_gracefully === TRUE)
		{
			return FALSE;
		}

		show_error('The configuration file '.$file.'.php does not exist.');
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item
	 *
	 * @param	string	$item	Config item name
	 * @param	string	$index	Index name
	 * @return	string|null	The configuration item or NULL if the item doesn't exist
	 */
	public function item($item, $default = FALSE)
	{
		return $this->arr->get($this->config, $item, $default);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item with slash appended (if not empty)
	 *
	 * @param	string		$item	Config item name
	 * @return	string|null	The configuration item or NULL if the item doesn't exist
	 */
	public function slash_item($item)
	{
		$item = $this->item($item, FALSE);

		if ($item === FALSE)
		{
			return NULL;
		}
		elseif (trim($item) === '')
		{
			return '';
		}

		return rtrim($item, '/').'/';
	}

	// --------------------------------------------------------------------

	/**
	 * Site URL
	 *
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	public function site_url($uri = '', $protocol = NULL)
	{
		$base_url = $this->slash_item('base_url');

		if (isset($protocol))
		{
			// For protocol-relative links
			if ($protocol === '')
			{
				$base_url = substr($base_url, strpos($base_url, '//'));
			}
			else
			{
				$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
			}
		}

		if (empty($uri))
		{
			return $base_url.$this->item('index_page');
		}

		$uri = $this->_uri_string($uri);

		if ($this->item('enable_query_strings') === FALSE)
		{
			$suffix = isset($this->config['url_suffix']) ? $this->config['url_suffix'] : '';

			if ($suffix !== '')
			{
				if (($offset = strpos($uri, '?')) !== FALSE)
				{
					$uri = substr($uri, 0, $offset).$suffix.substr($uri, $offset);
				}
				else
				{
					$uri .= $suffix;
				}
			}

			return $base_url.$this->slash_item('index_page').$uri;
		}
		elseif (strpos($uri, '?') === FALSE)
		{
			$uri = '?'.$uri;
		}

		return $base_url.$this->item('index_page').$uri;
	}

	// -------------------------------------------------------------

	/**
	 * Base URL
	 *
	 * Returns base_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	public function base_url($uri = '', $protocol = NULL)
	{
		$base_url = $this->slash_item('base_url');

		if (isset($protocol))
		{
			// For protocol-relative links
			if ($protocol === '')
			{
				$base_url = substr($base_url, strpos($base_url, '//'));
			}
			else
			{
				$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
			}
		}

		return $base_url.$this->_uri_string($uri);
	}

	// -------------------------------------------------------------

	/**
	 * Build URI string
	 *
	 * @used-by	CI_Config::site_url()
	 * @used-by	CI_Config::base_url()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @return	string
	 */
	protected function _uri_string($uri)
	{
		if ($this->item('enable_query_strings') === FALSE)
		{
			is_array($uri) && $uri = implode('/', $uri);
			return ltrim($uri, '/');
		}
		elseif (is_array($uri))
		{
			return http_build_query($uri);
		}

		return $uri;
	}

	// --------------------------------------------------------------------

	/**
	 * System URL
	 *
	 * @deprecated	3.0.0	Encourages insecure practices
	 * @return	string
	 */
	public function system_url()
	{
		$x = explode('/', preg_replace('|/*(.+?)/*$|', '\\1', BASEPATH));
		return $this->slash_item('base_url').end($x).'/';
	}

	// --------------------------------------------------------------------

	/**
	 * Set a config file item
	 *
	 * @param	string	$item	Config item key
	 * @param	string	$value	Config item value
	 * @return	void
	 */
	public function set_item($item, $value)
	{
		$this->arr->set($this->config, $item, $value);
	}

	// ------------------------------------------------------------------------

    /**
     * Returns site's current language
     * @access  public
     * @param   mixed 	$key 	string or array
     * @return  mixed   language code or language array
     */
	public function language($key = NULL)
    {
        $lang = $this->item('language');

        if ( ! empty($args = func_get_args())) {

        	$lang = $this->languages(TRUE)[$lang];

        	if (is_array($key))
        	{
        		$_lang = array();
        		foreach ($key as $k)
        		{
        			if (isset($lang[$k]))
        			{
        				$_lang[$k] = $lang[$k];
        			}
        		}

        		empty($_lang) OR $lang = $_lang;
        	}

        	elseif (isset($lang[$key]))
        	{
        		$lang = $lang[$key];
        	}

        }

        return $lang;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns an array of available languages
     * @access  public
     * @param   none
     * @return  array
     */
    public function languages()
    {
        // We prepare our array of languages codes
        $languages = $this->item('languages');

        // If any arguments are passed to this method
        if ( ! empty($args = func_get_args())) {

        	// Grab languages details
        	$langs = require_once BASEPATH.'vendor'.DS.'languages.php';

            // Prepare an empty array and fill it after
            $_languages = array();

            // Make sure $args is not a multidimensional array
            isset($args[0]) && is_array($args[0]) && $args = $args[0];

            // We walk through languages codes and fill our array
            foreach ($languages as $code) {
                
                // We start by assigning the key with an empty value
                $_languages[$code] = array();

                // We walk through passed arguments
                foreach ($args as $arg) {

                    // In case of a TRUE bool, we return all languages details.
                    // In case of a FALSE bool, we return an array of 'code' => 'name_en'
                    // In case of any requested key, we fill $_languages array.
                    // If none of the above, we simply return languages codes.

                    if (is_bool($arg) && (bool) $arg === TRUE) {
                        $_languages[$code] = $langs[$code];
                    } elseif (is_bool($arg) && (bool) $arg === FALSE) {
                        $_languages[$code] = $langs[$code]['name_en'];
                    } elseif (array_key_exists($arg, $langs[$code])) {
                        $_languages[$code][$arg] = $langs[$code][$arg];
                    }
                }
            }

            // replace our $languages array with $_languages
            $languages  = $_languages;
            unset($_languages);
        }

        return $languages;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns TRUE if the website is multilingual
     * @access  public
     * @param   none
     * @return  boolean
     */
    public function multilingual()
    {
    	return (count($this->languages()) >= 2);
    }

    // ------------------------------------------------------------------------

    /**
     * Returns TRUE if the language is available
     * @access  public
     * @param   string  $code   language code
     * @return  boolean
     */
    public function valid_language($code)
    {
    	return (in_array($code, $this->languages()));
    }
}
