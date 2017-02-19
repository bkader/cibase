<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example Module
 *
 * This module is provided as an example of how your application's modules
 * should be structured.
 *
 * @package 	CodeIgniter
 * @category 	Modules\Controllers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Example extends Public_Controller
{
	protected $autoload = array(
		'lang'    => 'example',
		'library' => 'example_lib',
		'helper'  => 'example',
	);

	/**
	 * This method is called before any other methods
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function before()
	{
		parent::before();

		// Prepare the name
		$name = $this->uri->segment(2);
		$name == 'index' && $name = $this->uri->segment(3);
		$name OR $name = $this->input->get('name', TRUE);
		$name OR $name = 'World';

		// Pass name
		$this->data['name'] = ucwords(urldecode($name));

		// Use provided example library to pass the random stirng to view file.
		$this->data['random'] = $this->example_lib->random_string(10);

		// Use helper and hash a dummmy password
		$this->data['password'] = $this->example_lib->random_password();
		$this->data['hashed']   = hash_example($this->data['password']);
	}

	/**
	 * Index method
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	void
	 */
	public function index()
	{
		$this->data['login_form'] = $this->load_sidebar();

		$this->template
				->add_partial('sidebar')
				->set_title('Example Module');
		// In a normal mode, you would directly load the view file and pass
		// arguments. But, for the sake of this example, I am keeping the
		// output to show you the utility of the after() method below.

		$this->content = $this->template->load('example_view', $this->data, TRUE);

		// You should simply use:
		// $this->template->load('example_view', $this->data, TRUE);
	}

	protected function load_sidebar()
	{
		return $this->template->load_partial('login', array(), TRUE);
	}

	/**
	 * This method is the last one to be called
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function after($params = '')
	{
		return $this->output->set_output($this->content);
	}
}
