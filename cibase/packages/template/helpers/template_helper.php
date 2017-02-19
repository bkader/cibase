<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template helper
 *
 * This file contains helper functions used for template library
 *
 * @package 	CodeIgniter
 * @category 	Helpers
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

if ( ! function_exists('assets_url')) {
	/**
	 * Returns the URL to assets folder
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$folder 	in case of distinct folder
	 * @return 	string
	 */
	function assets_url($uri = '', $folder = null)
	{
		return get_instance()->template->assets_url($uri, $folder);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('css_url')) {
	/**
	 * Returns the full url to a css file
	 * @access 	public
	 * @param 	string 	$file 	filename with or without .css
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	function css_url($file, $folder = null)
	{
		return get_instance()->template->css_url($file, $folder);
	}
}

if ( ! function_exists('js_url')) {
	/**
	 * Returns the full url to a js file
	 * @access 	public
	 * @param 	string 	$file 	filename with or without .js
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	function js_url($file, $folder = null)
	{
		return get_instance()->template->js_url($file, $folder);
	}
}

if ( ! function_exists('img_url')) {
	/**
	 * Returns the full url to an image
	 * @access 	public
	 * @param 	string 	$file 	image name with extension
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	function img_url($file, $folder = null)
	{
		return get_instance()->template->img_url($file, $folder);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('meta')) {
    /**
     * Display a HTML meta tag
     * @access 	public
     * @param   mixed   $name   string or associative array
     * @param   string  $value  value or null if $name is array
     * @return  string
     */
	function meta($name, $content = '')
	{
		return get_instance()->template->meta($name, $content);
	}
}

if ( ! function_exists('css')) {
    /**
     * Returns a full <link> tag
     * @access 	public
     * @param 	string 	$file 	filename
     * @param 	string 	$cdn 	to use if CDN is enabled
     * @param 	mixed 	$attrs 	string or array of attributes
     * @return 	string
     */
	function css($file, $cdn = null, $folder = null)
	{
		return get_instance()->template->css($file, $cdn, $folder);
	}
}

if ( ! function_exists('js')) {
    /**
     * Returns a full <script></script> tag
     * @access 	public
     * @param 	string 	$file 	filename
     * @param 	string 	$cdn 	to use if CDN is enabled
     * @param 	mixed 	$attrs 	string or array of attributes
     * @return 	string
     */
	function js($file, $cdn = null, $folder = null)
	{
		return get_instance()->template->js($file, $cdn, $folder);
	}
}

if ( ! function_exists('img')) {
	/**
	 * Returns a full <img> tag
	 * @param 	string 	$file 	image name with extension
	 * @param 	mixed 	$attrs 	string or array of attributes
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	function img($file, $folder = null)
	{
		return get_instance()->template->img($file, $folder);
	}
}
