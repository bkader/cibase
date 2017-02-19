<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', true, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

/**
 * Defined DIRECTORY_SEPARATOR constant.
 */
defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

/**
 * Site public root.
 */
define('FCPATH', __DIR__.DS);

/**
 * The name of this file
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

/**
 * Website document root.
 */
define('DOCROOT', realpath(__DIR__.DS.'cibase/').DS);

/**
 * The path to CodeIgniter system folder.
 */
define('BASEPATH', realpath(DOCROOT.'core/').DS);

// Name of the "system" directory
define('SYSDIR', basename(BASEPATH));

/**
 * The path to CodeIgniter application folder.
 */
define('APPPATH', realpath(DOCROOT.DS.'app/').DS);

/**
 * The path to default views folder
 */
define('VIEWPATH', realpath(APPPATH.'views/').DS);

/**
 * Path to default packages folder.
 */
define('PKGPATH', realpath(DOCROOT.'packages/').DS);

// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

/**
 * Load the bootstrap file
 */
require_once BASEPATH.'bootstrap.php';
