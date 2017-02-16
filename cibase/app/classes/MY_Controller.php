<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{
	/**
	 * Files to be auto-loaded
	 * @var array
	 */
	public $autoload = array();
	
	/**
	 * Module's name
	 * @var string
	 */
	protected $module = '';
	
	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
	}
}

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
	public function __construct()
	{
		parent::__construct();
		
		// Make sure the request is always AJAX
		if ( ! $this->input->is_ajax_request())
		{
			redirect('', 'refresh');
			exit;
		}
	}
}

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
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Login check logic
	}
}

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
	public function __construct()
	{
		parent::__construct();
		
		// Access level check.
	}
}
