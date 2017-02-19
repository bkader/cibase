<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Use Bonfire's Route class
require_once DOCROOT.'vendor/Route.php';

// Load the MX_Router class
require DOCROOT.'vendor/HMVC/Router.php';

class MY_Router extends HMVC_Router {}
