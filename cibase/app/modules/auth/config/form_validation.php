<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication module form validation
 *
 * @package 	CodeIgniter
 * @category 	Modules\Configuration
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

// Registration page
$config['register'] = array(
	array(	// Email address
		'field' => 'email',
		'label' => 'lang:ui.email',
		'rules' => 'required|valid_email'
	),
	array(	// Username
		'field' => 'username',
		'label' => 'lang:ui.username',
		'rules' => 'required|min_length[5]|max_length[20]'
	),
	array(	// Password
		'field' => 'password',
		'label' => 'lang:ui.password',
		'rules' => 'required|min_length[8]|max_length[20]'
	),
	array(	// Confirm password
		'field' => 'cpassword',
		'label' => 'lang:ui.confirm_password',
		'rules' => 'required|matches[password]'
	),
);
