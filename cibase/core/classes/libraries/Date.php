<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Date {
	/**
	 * Time details
	 */
	protected $_week   = 604800;
	protected $_day    = 86400;
	protected $_hour   = 3600;
	protected $_minute = 60;

	/**
	 * Server's time() offset from gmt in seconds
	 * @var int
	 */
	protected $server_gmt_offset = 0;

	/**
	 * The timezone to be used to output formatted data
	 * @var string
	 */
	public $display_timezone = null;

	/**
	 * Default config
	 */
	protected $config = array(
		'server_gmt_offset' => 0,
		'default_timezone' => NULL,
		'date_patterns' => array(
			'local'      => '%c',
			'mysql'      => '%Y-%m-%d %H:%M:%S',
			'mysql_date' => '%Y-%m-%d',
			'us'         => '%m/%d/%Y',
			'us_short'   => '%m/%d',
			'us_named'   => '%B %d %Y',
			'us_full'    => '%I:%M %p, %B %d %Y',
			'eu'         => '%d/%m/%Y',
			'eu_short'   => '%d/%m',
			'eu_named'   => '%d %B %Y',
			'eu_full'    => '%H:%M, %d %B %Y',
			'24h'        => '%H:%M',
			'12h'        => '%I:%M %p',
		)
	);

	/**
	 * Constructor
	 * @param 	array 	$config
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();

		$config['server_gmt_offset'] = config_item('server_gmt_offset', 0);
		$config['default_timezone'] = config_item('default_timezone') ?: date_default_timezone_get();

		$this->config = array_replace_recursive($this->config, $config);
	}
}
