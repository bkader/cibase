<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class HMVC_Controller extends CI_Controller
{
	public $autoload = array();
	
	public function __construct() 
	{
		parent::__construct();
		$class = str_replace($this->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." HMVC_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;	
		
		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);
		
		/* autoload module items */
		$this->load->_autoloader($this->autoload);
	}
	
	public function __get($class) 
	{
		return $this->$class;
	}
}