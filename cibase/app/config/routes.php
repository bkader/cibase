<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = false;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to true, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;

// Routes patterns.
Route::pattern('id',       '[0-9]+');
Route::pattern('key',      '[A-Za-z0-9\_\-]+');
Route::pattern('method',   '[a-z\_]+');
Route::pattern('username', '[A-Za-z0-9]+');

// ------------------------------------------------------------------------
// Start of Routes.
// ------------------------------------------------------------------------

// Remove this line because it's for demonstration only.
Route::get('admin/semantic', 'admin/admin/semantic');

// ------------------------------------------------------------------------
// End of Routes.
// ------------------------------------------------------------------------

/**
 * Admin Context.
 * Each site module can have an admin area that will be accessible on
 * "yoursite.com/admin/module", you only need to create the controller
 * "Admin.php" inside "modules/your_module/controllers" and make it extend
 * Admin_Controller.
 */
Route::context('admin', 'admin', array('home' => 'admin/index'));

/**
 * For AJAX calls, I made a class Ajax_Controller that should
 * be used for controllers requiring AJAX calls.
 * A module can have an ajax controller, just like 'admin' above.
 */
Route::context('ajax', 'ajax', array('home' => 'ajax/index'));

// Always keeps this line at the end
$route = Route::map($route);
