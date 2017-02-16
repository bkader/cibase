<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Template library configuration file
 *
 * @package 	CodeIgniter
 * @category 	Configuration
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

// Default site master view file
$config['template']['master'] = 'template';

// Site default layout
$config['template']['layout'] = 'default';

// Title parts separator
$config['template']['title_sep'] = '::';

// Title to be used as default
$config['template']['title'] = 'CodeIgniter';

// Description to be used as default
$config['template']['description'] = 'CodeIgniter 3.1.3 Application';

// Keywords to be used as default
$config['template']['keywords'] = 'codeigniter, framework';

// Whether to minify output or not
$config['template']['compress'] = (defined('ENVIRONMENT') && ENVIRONMENT == 'production');
