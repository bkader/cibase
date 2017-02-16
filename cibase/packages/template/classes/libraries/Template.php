<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Template Library
 *
 * @package 	CodeIgniter
 * @category 	Libraries
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class Template {
	/**
	 * Instance of CI object
	 * @var object
	 */
	protected $CI;

	/**
	 * Module's name
	 * @var string
	 */
	protected $module = NULL;

	/**
	 * Controller's name
	 * @var string
	 */
	protected $controller = NULL;

	/**
	 * Method's name
	 * @var string
	 */
	protected $method = NULL;

	/**
	 * Master view file
	 * @var string
	 */
	protected $master = 'template';

	/**
	 * Site default layout
	 * @var string
	 */
	protected $layout = 'default';

	/**
	 * Array of partial views
	 * @var array
	 */
	protected $partials = array();

	/**
	 * Page's tiele
	 * @var string
	 */
	protected $title = '';

	/**
	 * Title parts separator
	 * @var string
	 */
	protected $title_sep = ' - ';

	/**
	 * Page's description
	 * @var string
	 */
	protected $description = '';

	/**
	 * Page's keywords
	 * @var string
	 */
	protected $keywords = '';

	/**
	 * Whether to compress HTML output or not
	 * @var boolean
	 */
	protected $compress = FALSE;

	/**
	 * Array of CSS files
	 * @var array
	 */
	protected $css_files = array();

	/**
	 * Array of JS files
	 * @var array
	 */
	protected $js_files  = array();

	/**
	 * Array of mete tags details
	 * @var array
	 */
	protected $metadata  = array();

	/**
	 * Array of data to pass to view
	 * @var array
	 */
	protected $data = array();

	/**
	 * Constructor
	 * @param 	none
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();

		if (isset($config['template']) && ! empty($config['template']))
		{
			foreach ($config['template'] as $key => $val)
			{
				if (isset($this->{$key}) && ! empty($val))
				{
					$this->{$key} = $val;
				}
			}
		}

		// Format title separator
		$this->title_sep = ' '.trim($this->title_sep).' ';

		if (method_exists($this->CI->router, 'fetch_module')) {
			$this->module = $this->CI->router->fetch_module();
		}
		$this->controller = $this->CI->router->fetch_class();
		$this->method = $this->CI->router->fetch_method();

		// Load our template helper
		$this->CI->load->helper('template');
	}

	// ------------------------------------------------------------------------

	/**
	 * Class magic __set
	 * @param 	string 	$name 	property's name
	 * @param 	mixed 	$value 	property's value
	 * @return 	void
	 */
	public function __set($name, $value = NULL)
	{
		$this->{$name} = $value;
	}

	/**
	 * Class magic __get
	 * @param 	string 	$name 	property's name
	 * @return 	mixed
	 */
	public function __get($name)
	{
		return isset($this->{$name}) ? $this->{$name} : NULL;
	}

	// ------------------------------------------------------------------------

	/**
	 * Changes master view file name
	 * @access 	public
	 * @param 	string 	$master 	master view name
	 * @return 	object
	 */
	public function set_master($master = 'template')
	{
		$this->master = $master;
		return $this;
	}

	/**
	 * Changes layout
	 * @access 	public
	 * @param 	string 	$layout 	layout's name
	 * @return 	object
	 */
	public function set_layout($layout = 'default')
	{
		$this->layout = $layout;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Add partial view to class' partials property's
	 * @access 	public
	 * @param 	string 	$view 	view name
	 * @param 	array 	$data 	data to pass to view
	 * @param 	string 	$name 	in case of a distinct partial name
	 * @return 	object
	 */
	public function add_partial($view, $data = array(), $name = NULL)
	{
		$name OR $name = $view;
		$this->partials[$name] = array('view' => $view, 'data' => $data);
		return $this;
	}

	/**
	 * Loads a partial view alone
	 * @param 	string 		$view 	partial file name
	 * @param 	array 		$data 	array of data to pass to view
	 * @param 	boolean 	$return return or echo
	 * @return 	void
	 */
	public function load_partial($view, $data = array(), $return = FALSE)
	{
		return $this->CI->load->view('partials/'.$view, $data, $return);
	}

	// ------------------------------------------------------------------------

	/**
	 * Changes page's title
	 * @access 	public
	 * @param 	string 	$title 	the title to prepend
	 * @return 	object
	 */
	public function set_title($title = '')
	{
		$_title = empty($this->title) 
					? config('site.name', 'CodeIgniter') 
					: $this->title;

		if ( ! empty($title)) {
			$_title = $title.$this->title_sep.$_title;
		}

		$this->title = $_title;
		return $this;
	}

	/**
	 * Changes page's description
	 * @access 	public
	 * @param 	string 	$description 	the description to use
	 * @return 	object
	 */
	public function set_description($description = '')
	{
		if (empty($description)) {
			$this->description = empty($this->description)
								? config('site.description', 'CodeIgniter Application')
								: $this->description;
		}
		else {
			$this->description = $description;
		}
		return $this;
	}

	/**
	 * Changes page's keywords
	 * @access 	public
	 * @param 	string 	$keywords 	the keywords to use
	 * @return 	object
	 */
	public function set_keywords($keywords = '')
	{
		if (empty($keywords)) {
			$this->keywords = config('site.keywords');
		}
		else {
			$this->keywords = $keywords;
		}
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the URL to assets folder
	 * @access 	public
	 * @param 	string 	$uri
	 * @param 	string 	$folder 	in case of distinct folder
	 * @return 	string
	 */
	public function assets_url($uri = '', $folder = NULL)
	{
		if (filter_var($uri, FILTER_VALIDATE_URL) !== FALSE) {
			return $uri;
		}

		function_exists('base_url') OR $this->CI->load->helper('url');
		$folder = 'content/'.($folder ? $folder : 'assets');
		return base_url($folder.'/'.$uri);
	}

	/**
	 * Returns the full url to a css file
	 * @access 	public
	 * @param 	string 	$file 	filename with or without .css
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	public function css_url($file, $folder = NULL)
	{
		if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE) {
			return $file;
		}
		$folder OR $folder = 'assets/css';
		$file = preg_replace('/.css$/', '', $file).'.css';
		return $this->assets_url($file, $folder);
	}

	/**
	 * Returns the full url to a js file
	 * @access 	public
	 * @param 	string 	$file 	filename with or without .js
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	public function js_url($file, $folder = NULL)
	{
		if (filter_var($file, FILTER_VALIDATE_URL) !== FALSE) {
			return $file;
		}
		$folder OR $folder = 'assets/js';
		$file = preg_replace('/.js$/', '', $file).'.js';
		return $this->assets_url($file, $folder);
	}

	/**
	 * Returns the full url to an image
	 * @access 	public
	 * @param 	string 	$file 	image name with extension
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	public function img_url($file, $folder = NULL)
	{
		$folder OR $folder = 'assets/img';
		return $this->assets_url($file, $folder);
	}

	// ------------------------------------------------------------------------

	/**
	 * pushes css files to the css_files array
	 * @access 	public
	 * @param 	mixed 	$css 	string or array
	 * @return 	object
	 */
	public function add_css($css)
	{
		is_array($css) OR $css = (array) $css;
		$this->css_files = array_merge($this->css_files, $css);
		return $this;
	}

	/**
	 * pushes js files to the js_files array
	 * @access 	public
	 * @param 	mixed 	$js 	string or array
	 * @return 	object
	 */
	public function add_js($js)
	{
		is_array($js) OR $js = (array) $js;
		$this->js_files = array_merge($this->js_files, $js);
		return $this;
	}

	/**
	 * Add some html <meta> tags
	 * @access 	public
	 * @param 	mixed 	$name 	string or array
	 * @param 	mixed 	$content
	 * @return 	object
	 */
    public function add_meta($name, $content = NULL)
    {
    	// In case of multiple elements
    	if (is_array($name)) {
    		foreach ($name as $key => $val) {
    			$this->metadata[$key] = $val;
    		}

    		return $this;
    	}

    	$this->metadata[$name] = $content;
    	return $this;
    }

	// ------------------------------------------------------------------------

    /**
     * Returns a full <link> tag
     * @access 	public
     * @param 	string 	$file 	filename
     * @param 	string 	$cdn 	to use if CDN is enabled
     * @param 	mixed 	$attrs 	string or array of attributes
     * @return 	string
     */
	public function css($file, $cdn = NULL, $attrs = '')
	{
        $folder = NULL;
		if (config('use.cdn') === TRUE) {
			$file = $cdn;
		}
		else {
        	$args = explode('|', $file);
        	if (count($args) == 2) {
        		$folder = $args[0];
        		$file = $args[1];
        	}
        	else {
        		$file = $args[0];
        	}
		}
        $output = '<link rel="stylesheet" type="text/css"';
        $output .= ' href="'.$this->css_url($file, $folder).'"';
        $output .= _stringify_attributes($attrs);
        $output .= '>'."\n";
        return $output;
	}

    /**
     * Returns a full <script></script> tag
     * @access 	public
     * @param 	string 	$file 	filename
     * @param 	string 	$cdn 	to use if CDN is enabled
     * @param 	mixed 	$attrs 	string or array of attributes
     * @return 	string
     */
	public function js($file, $cdn = NULL, $attrs = '')
	{
        $folder = NULL;
		if (config('use.cdn') === TRUE) {
			$file = $cdn;
		}
		else {
        	$args = explode('|', $file);
        	if (count($args) == 2) {
        		$folder = $args[0];
        		$file = $args[1];
        	}
        	else {
        		$file = $args[0];
        	}
		}
        $output = '<script type="text/javascript"';
        $output .= ' src="'.$this->js_url($file, $folder).'"';
        $output .= _stringify_attributes($attrs);
        $output .= '></script>'."\n";
        return $output;
	}

	/**
	 * Returns a full <img> tag
	 * @param 	string 	$file 	image name with extension
	 * @param 	mixed 	$attrs 	string or array of attributes
	 * @param 	string 	$folder in case of a distinct folder
	 * @return 	string
	 */
	public function img($file, $attrs = '', $folder = NULL)
	{
    	$args = explode('|', $file);
    	if (count($args) == 2) {
    		$folder = $args[0];
    		$file = $args[1];
    	} else {
			$file = $args[0];
    	}

        return '<img src="'.$this->img_url($file, $folder).'"'._stringify_attributes($attrs).' />';
	}

    /**
     * Display a HTML meta tag
     * @access 	public
     * @param   mixed   $name   string or associative array
     * @param   string  $value  value or NULL if $name is array
     * @return  string
     */
    public function meta($name, $content = NULL)
    {
        // Loop through multiple meta tags
        if (is_array($name)) {
            $meta = array();
            foreach ($name as $key => $val) {
                $meta[] = meta($key, $val);
            }

            return implode("\t", $meta);
        }

        // Prepare name & content first
		$name    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
		$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        return (strpos($name, 'og:') !== FALSE)
                ? '<meta property="'.$name.'" content="'.$content.'">'."\n"
                : '<meta name="'.$name.'" content="'.$content.'">'."\n";
    }

    // ------------------------------------------------------------------------

    /**
     * Set some variable to pass to views
     * @param 	mixed 	$name 		string or array
     * @param 	mixed 	$value 		variable value
     * @param 	bool 	$global 	whether to make it global or not
     * @return 	object
     */
    public function set($name, $value = NULL, $global = FALSE)
    {
    	if (is_array($name)) {
    		foreach ($name as $key => $val) {
    			$this->set($key, $val, $global);
    		}
    	}
    	elseif ($global === TRUE) {
    		$this->CI->load->vars($name, $value);
    	}
    	else {
    		$this->data[$name] = $value;
    	}

    	return $this;
    }

	// ------------------------------------------------------------------------

	/**
	 * Load view file
	 * @param  string  $view   view filename
	 * @param  array   $data   array of data to pass
	 * @param  boolean $return return or output
	 * @return mixed
	 */
	public function load($view, $data = array(), $return = FALSE)
	{
		$this->view = $view;
		$this->data = array_merge($this->data, $data);

		$output = $this->_prepare_output();
		$this->compress && $output = $this->_compress_output($output);

		return ($return) ? $output : $this->CI->output->set_output($output);
	}

	/**
	 * It does the same thing as load() except that it returns the output
	 * @param  string  $view   view filename
	 * @param  array   $data   array of data to pass
	 * @return mixed
	 */
	public function build($view, $data = array())
	{
		return $this->load($view, $data, TRUE);
	}

	/**
	 * This method can be used instead of load() to display the view
	 * @param  string  $view   view filename
	 * @param  array   $data   array of data to pass
	 * @return mixed
	 */
	public function render($view, $data = array())
	{
		return $this->load($view, $data, FALSE);
	}

	// ------------------------------------------------------------------------

    /**
     * Prepares everything before outputting everything
     * @access 	protected
     * @param 	none
     * @return 	string
     */
	protected function _prepare_output()
	{
		$title = config('site.name', 'CodeIgniter');
		empty($this->title) OR $title = $this->title;

		$description = config('site.description', 'CodeIgniter 3.1.3 Application');
		empty($this->description) OR $description = $this->description;

		$keywords = config('site.keywords', 'codeigniter, framework, bkader');
		empty($this->keywords) OR $keywords = $this->keywords;

		$metadata = '';
		if ( ! empty($this->metadata)) {
			foreach ($this->metadata as $name => $content) {
				$metadata[] = $this->meta($name, $content);
			}
			$metadata = implode("\t", $metadata);
		}

		$css_files = '';
		if ( ! empty($this->css_files)) {
			foreach ($this->css_files as $css_file) {
				$css_files[] = $this->css($css_file);
			}
			$css_files = implode("\t", $css_files);
		}

		$js_files = '';
		if ( ! empty($this->js_files)) {
			foreach ($this->js_files as $js_file) {
				$js_files[] = $this->js($js_file);
			}
			$js_files = implode("\t", $js_files);
		}

		// pass to master view file
		$template = array(
			'charset'     => strtolower($this->CI->config->item('charset')),
			'title'       => $title,
			'description' => $description,
			'keywords'    => $keywords,
			'metadata'    => $metadata,
			'css_files'   => $css_files,
			'js_files'    => $js_files,
		);

		$layout = array();

		// Load header & footer files
		$layout['header'] = $this->load_partial('header', array(), TRUE);
		$layout['footer'] = $this->load_partial('footer', array(), TRUE);
		
		// Prepare page content
		$layout['content'] = $this->CI->load->view($this->view, $this->data, TRUE);

		// Check there are any requested partial views
		if ( ! empty($this->partials)) {
			foreach ($this->partials as $name => $partial) {
				$layout[$name] = $this->load_partial($partial['view'], $partial['data'], TRUE);
			}
		}

		$template['layout'] = $this->CI->load->view('layouts/'.$this->layout, $layout, TRUE);

		return $this->CI->load->view($this->master, $template, TRUE);
	}

	/**
	 * Compresses HTML output
	 * @access 	public
	 * @param 	string 	$html 	the HTML to compress
	 * @return 	string 	$html 	the compressed HTML output
	 */
	public function _compress_output($html = '')
	{
		if ( ! empty($html))
		{
			$search  = array(
				'/\>[^\S ]+/s',	// White-spaces after tags except spaces.
				'/[^\S ]+\</s',	// White-spaces before tags except spaces.
				'/(\s)+/s',		// shorten multiple whitespace sequences
				'/<!--(?!<!)[^\[>].*?-->/s',	// strip HTML comments
				'#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s' //leave CDATA alone
			);
			$replace = array('>', '<', '\\1', '', "//&lt;![CDATA[\n".'\1'."\n//]]>" );

			$html = preg_replace($search, $replace, $html);
		}

		return $html;
	}
}
