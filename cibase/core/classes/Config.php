<?php
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
				if (strpos($_SERVER['SERVER_ADDR'], ':') !== false)
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
	 * @param	bool	$fail_gracefully	Whether to just return false or display an error message
	 * @return	bool	true if the file was loaded correctly or false on failure
	 */
	public function load($file = '', $use_sections = false, $fail_gracefully = false)
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = false;

		foreach ($this->_config_paths as $path)
		{
			foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, true))
				{
					return true;
				}

				if ( ! file_exists($file_path))
				{
					continue;
				}

				include($file_path);

				if ( ! isset($config) OR ! is_array($config))
				{
					if ($fail_gracefully === true)
					{
						return false;
					}

					show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
				}

				if ($use_sections === true)
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
				$config = null;
				$loaded = true;
				log_message('debug', 'Config file loaded: '.$file_path);
			}
		}

		if ($loaded === true)
		{
			return true;
		}
		elseif ($fail_gracefully === true)
		{
			return false;
		}

		show_error('The configuration file '.$file.'.php does not exist.');
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a config file item
	 * @param 	string 	$item 		string or dot-notation string
	 * @param 	mixed 	$default 	value to use if no item found
	 * @return 	mixed
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	public function get($item, $default = false)
	{
		return $this->arr->get($this->config, $item, $default);
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a config file item (Kept for backward compatibility)
	 *
	 * @param	string	$item	Config item name
	 * @param	string	$index	Index name
	 * @return	string|null	The configuration item or null if the item doesn't exist
	 */
	public function item($item, $default = false)
	{
		return $this->get($item, $default);
	}

	// ------------------------------------------------------------------------

	public function save($file = '', $config = array(), $module = '', $apppath = APPPATH)
	{
		if (empty($file) OR empty($config)) {
			return false;
		}

		$config_file = "config/{$file}";

        // Look in module first.
        $found = false;
        if ($module) {
        	foreach (config_item('modules_locations') as $location) {
        		if (file_exists($file = $location.$module.'/'.$config_file.EXT)) {
        			$found = true;
        		}
        	}
        }

        // Fallback to application folder
        if ( ! $found) {
        	$config_file = "{$apppath}{$config_file}";
        	$found = file_exists($config_file.EXT);
        }

        // If the file is found, we load its content
        if ($found) {
        	$content = file_get_contents($config_file.EXT);
        	$empty = false;
        }
        // If the file was not found, we create it
        else {
        	$content = '';
        	$empty = true;
        }

        // Loop through config items
        foreach ($config as $key => $val) {
        	// Check if the config exists?
        	$start = strpos($content, '$config[\''.$key.'\']');
        	$end = strpos($content, ';', $start);
        	$search = substr($content, $start, $end - $start + 1);

            // Format the value to be written to the file.
            if (is_array($val)) {
                // Get the array output.
                $val = $this->_array_output($val);
            } elseif (! is_numeric($val)) {
                $val = "\"$val\"";
            }

            // For a new file, just append the content. For an existing file, search
            // the file's content and replace the config setting.
            //
            // @todo Don't search new files at the beginning of the loop?

            if ($empty) {
                $content .= '$config[\''.$key.'\'] = '.$val.";\n";
            } else {
                $content = str_replace(
                    $search,
                    '$config[\''.$key.'\'] = '.$val.';',
                    $content
                );
            }
        }

        // Backup the file for safety.
        $source = $config_file.'.php';
        $dest = ($module == '' ? "{$apppath}backup/{$file}" : $config_file).'.php.bak';

        if ($empty === false) {
            copy($source, $dest);
        }

        // Make sure the file still has the php opening header in it...
        if (strpos($content, '<?php') === false) {
            $content = "<?php\ndefined('BASEPATH') OR exit('No direct script access allowed');\n\n".$content;
        }

        // Write the changes out...
        if ( ! function_exists('write_file')) {
            get_instance()->load->helper('file');
        }
        $result = write_file("{$config_file}.php", $content);

        return $result !== false;
	}

	// ------------------------------------------------------------------------

    /**
     * Output the array string which is then used in the config file.
     *
     * @access 	protected
     * @param 	array 		array 		Values to store in the config.
     * @param 	integer 	$numtabs 	Optional number of tabs to use in front of array items
     * @return 	string/boolean 			A string containing the array values in the config file, or false.
     */
	protected function _array_output($array, $numtabs = 1)
	{
        if ( ! is_array($array)) {
            return false;
        }

        $tval = 'array(';

        // Allow for two-dimensional arrays.
        $arrayKeys = array_keys($array);

        // Check whether they are basic numeric keys.
        if (is_numeric($arrayKeys[0]) && $arrayKeys[0] == 0) {
            $tval .= "'".implode("','", $array)."'";
        } else {
            // Non-numeric keys.
            $tabs = "";
            for ($num = 0; $num < $numtabs; $num++) {
                $tabs .= "\t";
            }

            foreach ($array as $key => $value) {
                $tval .= "\n{$tabs}'{$key}' => ";
                if (is_array($value)) {
                    $numtabs++;
                    $tval .= $this->_array_output($value, $numtabs);
                } else {
                    $tval .= "'{$value}'";
                }
                $tval .= ',';
            }
            $tval .= "\n{$tabs}";
        }

        $tval .= ')';

        return $tval;
    }

	// --------------------------------------------------------------------

	/**
	 * Sets a config file item using dot-notation
	 *
	 * @access 	public
	 * @param 	string 	$item 	dot-notation string to access item
	 * @param 	mixed 	$value 	config item's new value
	 * @return 	void
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	public function set($item, $value = null)
	{
		$this->arr->set($this->config, $item, $value);
	}

	/**
	 * Set a config file item (kept for backward compatibility)
	 *
	 * @param	string	$item	Config item key
	 * @param	string	$value	Config item value
	 * @return	void
	 */
	public function set_item($item, $value)
	{
		$this->set($item, $value);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item with slash appended (if not empty)
	 *
	 * @param	string		$item	Config item name
	 * @return	string|null	The configuration item or null if the item doesn't exist
	 */
	public function slash_item($item)
	{
		$item = $this->item($item, false);

		if ($item === false)
		{
			return null;
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
	 * Returns base_url.index_page [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	public function site_url($uri = '', $protocol = null)
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

		if ($this->item('enable_query_strings') === false)
		{
			$suffix = isset($this->config['url_suffix']) ? $this->config['url_suffix'] : '';

			if ($suffix !== '')
			{
				if (($offset = strpos($uri, '?')) !== false)
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
		elseif (strpos($uri, '?') === false)
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
	public function base_url($uri = '', $protocol = null)
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
		if ($this->item('enable_query_strings') === false)
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

	// ------------------------------------------------------------------------

    /**
     * Returns site's current language
     * @access  public
     * @param   mixed 	$key 	string or array
     * @return  mixed   language code or language array
     */
	public function language($key = null)
    {
        // Prepare the language code
        $lang = $this->item('language');

        // If any arguments are passed
        if ( ! empty($args = func_get_args()))
        {
            is_array($args[0]) && $args = $args[0];

            switch (count($args)) {
                case 1:
                    // Ignore arguments below
                    if (in_array($args[0], array(false, null)))
                    {
                        continue;
                    }
                    // If true is passed, the full array is returned
                    elseif ($args[0] === true)
                    {
                        $lang = $this->_get_language($this->item('language'));
                    }
                    // If none of the above, we continue
                    else {
                        goto other;
                    }
                    break;

                default:
                    other:
                    $language = $this->_get_language($this->item('language'));
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

    // ------------------------------------------------------------------------

    /**
     * Returns an array of available languages
     * @access  public
     * @param   none
     * @return  array
     */
    public function languages()
    {
        $langs = $this->item('languages');

        if ( ! empty($args = func_get_args()))
        {
            $args = (array) $args;
            is_array($args[0]) && $args = $args[0];

            switch (count($args)) {
                case 1:
                    // Ignore arguments below
                    if (in_array($args[0], array(false, null)))
                    {
                        continue;
                    }
                    // If true is passed, the full array is returned
                    elseif ($args[0] === true)
                    {
                    	$_langs = array();
                    	foreach ($langs as $code)
                    	{
                    		$_langs[$code] = $this->_get_language($code);
                    	}
                    	$langs = $_langs;
                    }
                    // If none of the above, we continue
                    else {
                        goto other;
                    }
                    break;

                default:
                    other:
                    $languages = $this->_get_language();
                    $_langs = array();

                    foreach ($langs as $code)
                    {
                    	foreach ($args as $arg)
                    	{
                    		if (isset($languages[$code][$arg]))
                    		{
                    			$_langs[$code][$arg] = $languages[$code][$arg];
                    		}
                    	}
                    }

                    empty($_langs) OR $langs = $_langs;

                    // If a single item is inside an array, use it as the value
                    foreach ($langs as $code => &$lang)
                    {
                    	if (count($lang) == 1)
                    	{
                    		$lang = array_pop($lang);
                    	}
                    }

                    break;
            }
        }

        return $langs;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns true if the website is multilingual
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
     * Returns true if the language is available
     * @access  public
     * @param   string  $code   language code
     * @return  boolean
     */
    public function valid_language($code)
    {
    	return (in_array($code, $this->languages()));
    }

    // ------------------------------------------------------------------------

    /**
     * Returns an array of languages details
     * @access  protected
     * @param   string  $code   language's code to retrieve
     * @return  array
     */
    protected function _get_language($code = null)
    {
        $lang = require BASEPATH.'vendor/languages.php';

        if ($code && isset($lang[$code]))
        {
            $lang = $lang[$code];
        }

        return $lang;
    }
}
