<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication module's language file
 *
 * @package 	CodeIgniter
 * @category 	Modules\Language
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

$lang['auth'] = array(

	/**
	 * ----------------------------------------------
	 * Registration page
	 * ----------------------------------------------
	 */
	'register' => array(
		'title' => 'Create account',
		'description' => 'Join %s community',
		'heading' => 'Create new account'
	),

	// Resend activation link
	'resend' => array(
		'title' => 'Resend link',
		'description' => 'Resend account\'s activation link',
		'heading' => 'Resend activation link'
	),

	// Login page
	'login' => array(
		'title' => 'Login',
		'description' => 'Member\'s Login',
		'heading' => 'Member\'s login'
	),

	// Lost password page
	'recover' => array(
		'title' => 'Lost Password',
		'description' => 'Recover account\'s lost password',
		'heading' => 'Recover password'
	),

	// Reset password
	'reset' => array(
		'title' => 'Reset password',
		'description' => 'Reset account\'s password',
		'heading' => 'Change password'
	),
);
