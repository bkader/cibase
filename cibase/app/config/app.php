<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * App.php config file
 *
 * This file contains application custom configuration items.
 *
 * @package 	CodeIgniter
 * @category 	Configuration
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

$config['app']['name']        = defined('APP_NAME') ? APP_NAME : 'CodeIgniter';
$config['app']['description'] = 'CodeIgniter-restructed site description';
$config['app']['keywords']    = 'dummy, site, keywords';
$config['app']['version']     = defined('APP_VERSION') ? APP_VERSION : '0.1.0';

/**
 * Site public and admin arias themes.
*/
$config['app']['theme']       = 'default';
$config['app']['theme_admin'] = 'default';

/**
 * Google Analytics Settings.
*/
$config['google']['analytics'] = 'UA-XXXXX-Y'; // UA-XXXXX-Y
