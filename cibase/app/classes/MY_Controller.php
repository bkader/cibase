<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extending CodeIgniter CI_Controller
 *
 * @package 	CodeIgniter
 * @category 	core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class MY_Controller extends CI_Controller
{
	/**
	 * To use Laravel static routes.
	 */
	private $__filter_params;

	/**
	 * Files to be auto-loaded
	 * @var 	array
	 */
	protected $autoload = array();

	/**
	 * Array of site available langauegs
	 * @var array
	 */
	protected $languages = array();

	/**
	 * Current language array
	 * @var array
	 */
	protected $language = array();

	/**
	 * Module's name
	 * @var 	string
	 */
	public $module = null;

	/**
	 * Controller's name
	 * @var string
	 */
	protected $controller = null;

	/**
	 * Requested method's name
	 * @var string
	 */
	protected $method = null;

	/**
	 * Data of arrat to pass to views
	 * @var 	array
	 */
	protected $data = array();

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->__filter_params = array($this->uri->uri_string());
		$this->call_filters('before');

		// Github buttons (Remove this please)
		$this->theme->add_js('https://buttons.github.io/buttons.js');

		log_message('debug', 'MY_Controller Class Initialized');
	}

    // ------------------------------------------------------------------------

	/**
	 * This method is called before any other methods
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */

	public function before()
	{
		// Load debug helper when not in production mode
		(ENVIRONMENT !== 'production') && $this->load->helper('debug');

		// Auto-load resources
		$this->_autoloader();

		// Set available languages and current language
		$this->language = $this->config->language(true);
		$this->languages = $this->config->languages(true);
		// Remove the current language from the lsit
		unset($this->languages[$this->language['code']]);

		// Set some global variables
		$this->theme->set(array(
			'language' => $this->language,
			'languages' => $this->languages,
			'site_name' => config('app.name'), // Use @$site_name to avoid errors
		), null, true);
	}

	// ------------------------------------------------------------------------

	/**
	 * Auto-load requested files
	 *
	 * @access 	protected
	 * @param 	none
	 * @return 	void
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	protected function _autoloader()
	{
		if (empty($this->autoload)) {
			return;
		}

		$this->load->helper('inflector');
		foreach ($this->autoload as $type => $file) {
			$this->load->{singular($type)}($file);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Remap to use action_method instead of method (optional)
	 *
	 * @access 	public
	 * @param 	string 	$method 	method's name
	 * @param 	array 	$params 	arguments to pass to method
	 * @return 	mixed
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	public function _remap($method, $params = array())
	{
		$action = 'action_'.str_replace('action_', '', $method);

		// Check if before() method exists
		method_exists($this, 'before') && $this->before();

		// Look for method prefixed 'action_'
		if (method_exists($this, $action))
		{
			call_user_func_array(array($this, $action), (array) $params);
		}

		// Search for the method normally
		elseif (method_exists($this, $method))
		{
			call_user_func_array(array($this, $method), (array) $params);
		}
		// Show error 404
		else
		{
			// show_error('The requested action "'.get_class($this).'::'.$method.'()" does not exists.', 404, '404 Page Not Found');
			show_404();
		}

		// Is there a after() method?
		method_exists($this, 'after') && $this->after();
		($method != 'call_filters') && $this->call_filters('after');
	}

	// ------------------------------------------------------------------------

	/**
	 * Calls filters at routing.
	 *
	 * @param string $type
	 */
	private function call_filters($type)
	{
		$loaded_route = $this->router->get_active_route();
		$filter_list = Route::get_filters($loaded_route, $type);

		foreach ($filter_list as $filter_data)
		{
			$param_list = $this->__filter_params;

			$callback = $filter_data['filter'];
			$params = $filter_data['parameters'];

			// check if callback has parameters
			if ( ! is_null($params))
			{
				// separate the multiple parameters in case there are defined
				$params = explode(':', $params);

				// search for uris defined as parameters, they will be marked as {(.*)}
				foreach ($params as &$p)
				{
					if (preg_match('/\{(.*)\}/', $p, $match_p))
					{
						$p = $this->uri->segment($match_p[1]);
					}
				}

				$param_list = array_merge($param_list, $params);
			}

			if (class_exists('Closure') and method_exists('Closure', 'bind'))
			{
				$callback = Closure::bind($callback, $this);
			}

			call_user_func_array($callback, $param_list);
		}

		log_message('debug', "\"{$type}\" Filter Called");
	}

	// ------------------------------------------------------------------------

	/**
	 * Prepares form validation library and form helper
	 *
	 * @access 	public
	 * @param 	mixed 	$rules 	string or array
	 * @return 	void
	 *
	 * @author 	Kader Bouyakoub <bkader@mail.com>
	 * @link 	https://github.com/bkader
	 * @link 	https://twitter.com/KaderBouyakoub
	 */
	protected function prepare_form($rules = array())
	{
		// Load validation form if not already loaded.
		if ( ! class_exists('CI_Form_validation', false))
			$this->load->library('form_validation');

		// Hack to make form validation HMVC work.
		$this->form_validation->CI =& $this;

		// Load form helper if not already loaded.
		(function_exists('form_open')) or $this->load->helper('form');

		// Area they any rules to use?
		empty($rules) or $this->form_validation->set_rules($rules);
	}

    // ------------------------------------------------------------------------

	/**
	 * This method is the last one to be called
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
    public function after($params = '')
    {
    	//
    }
}

// ------------------------------------------------------------------------

/**
 * Ajax_Controller
 *
 * Controllers extending this class require an ajax request only
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

class Ajax_Controller extends MY_Controller
{
	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function before()
	{
		parent::before();
		// Make sure the request is always AJAX
		if ( ! $this->input->is_ajax_request())
		{
			show_404();
		}
	}
}

// ------------------------------------------------------------------------

/**
 * User_Controller
 *
 * Controllers extending this class require a logged-in user.
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

class User_Controller extends MY_Controller
{
	/**
	 * Ignored URI when redirecting to login
	 * @var array
	 */
	protected $ignored_pages = array('logout');

	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function before()
	{
		parent::before();

		// Prepare redirection URL
		// $uri = $this->uri->uri_string();
		// in_array($uri, $this->ignored_pages) && $uri = '';

		// // Login check logic
		// if ( ! $this->auth_lib->is_logged_in())
		// {
		// 	redirect(Route::named('login').'?next='.urlencode($uri), 'refresh');
		// 	exit;
		// }
	}
}

// ------------------------------------------------------------------------

/**
 * Admin_Controller
 *
 * Controllers extending this class require a logged-in user.
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

class Admin_Controller extends User_Controller
{
	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function before()
	{
		parent::before();

		// Access level check.
	}
}
