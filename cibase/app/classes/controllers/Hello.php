<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hello extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($name = 'World')
	{
		echo "Hello, {$name}!";
	}
}
