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

	public function __construct()
	{
		parent::__construct();
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
		$this->template->set(array(
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
	public function prepare_form($rules = array())
	{
		// Load form validation library if not alreadu loaded
		if ( ! class_exists('CI_Form_validation')) {
			$this->load->library('form_validation');
		}

		// Load form helper if not already loaded
		if ( ! function_exists('form_open')) {
			$this->load->helper('form');
		}

		// Set rules
		if ( ! empty($rules) && is_array($rules)) {
			$this->form_validation->set_rules($rules);
		}
	}

    // ------------------------------------------------------------------------

    /**
     * This method sets a flash message to be displayed after a redirect
     *
     * @access  public
     * @param   string  $message    the message of the flash message
     * @param   string  $type       type of the flash message
     * @param   string  $heading 	message heading
     * @return  void
     *
     * @author 	Kader Bouyakoub <bkader@mail.com>
     * @link 	https://github.com/bkader
     * @link 	https://twitter.com/KaderBouyakoub
     */
    public function set_flash_message($message = '', $type = 'info', $heading = '')
    {
        class_exists('CI_Session') OR $this->load->library('session');

        if ( ! empty($message))
        {
            $this->session->set_flashdata('__ci_flash', array(
                'type'    => $type,
                'heading' => $heading,
                'message' => $message,
            ));
        }
    }

    // ------------------------------------------------------------------------

	/**
	 * This method is the last one to be called
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
    public function after($params = '')
    {}
}

// ------------------------------------------------------------------------

/**
 * Public_Controller
 *
 * All application controllers should extend this class.
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

class Public_Controller extends MY_Controller
{}

// ------------------------------------------------------------------------

/**
 * Private_Controller
 *
 * Controllers extending this class are NEVER accessible via URL
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Private_Controller extends Public_Controller {
	/**
	 * Remapping and always show_404 in case of direct access
	 *
	 * @access 	public
	 * @param 	string 	$method 	method's name
	 * @param 	mixed 	$params 	parameters to pass
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		show_404();
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

class Ajax_Controller extends Public_Controller
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
			redirect('', 'refresh');
			exit;
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

class User_Controller extends Public_Controller
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
		$uri = $this->uri->uri_string();
		in_array($uri, $this->ignored_pages) && $uri = '';

		// Login check logic
		// if ( ! $this->auth_lib->is_logged_in())
		// {
			redirect(Route::named('login').'?next='.urlencode($uri), 'refresh');
			exit;
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
