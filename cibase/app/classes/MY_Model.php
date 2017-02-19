<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Extending CI_Model
 *
 * This extension helps you code less. All you need to do is to created a model
 * and name it i.e: 'users_model', the table is guessed (users) or you can set it.
 * DONE! All you need to do is to call any of the methods that this class has
 *
 * create(DATA), create_many(DATA)
 * find(ID), find_one(FIELD, MATCH), find_many(FIELD, MATCH) ... etc
 * update(ID, DATA), update_many(DATA, WHERE)
 * ... explore the file to see all available methods?
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 * @link 	https://twitter.com/KaderBouyakoub
 */

class MY_Model extends CI_Model
{
	/**
	 * @var  object
	 */
	protected $database;

	/**
	 * @var  array
	 */
	protected $db_group;

	/**
	 * The name of the table for this model.
	 * (may be automatically generated from the classname).
	 * @var  string  Model defaut database table
	 */
	protected $table = '';

	/**
	 * The name of this model.
	 * (may be automatically generated from the classname).
	 * @var  string  Model's name
	 */
	protected $model = '';

	/**
	 * Contains database fields for this object
	 * @var  array
	 */
	protected $fields = array();

	/**
	 * The primary key used for this model's table.
	 * @var  string
	 */
	protected $primary_key = 'id';

	/**
	 * @var  string  return type
	 * options: object / array
	 */
	protected $return_type = 'object';

	/**
	 * @var  bool  Using of timestamp instead of datetime
	 */
	protected $unix_timestamp = true;

	/**
	 * @var  string  datetime format
	 */
	protected $datetime_format = 'Y-m-d H:i:s';

	/**
	 * @var  string  column holding date of insert
	 */
	protected $created_at = 'created_at';

	/**
	 * @var  string  column holding date of updating
	 */
	protected $updated_at = 'updated_at';

	/**
	 * @var boolean
	 */
	protected $soft_delete = false;

	/**
	 * Table soft delete key
	 *
	 * @var string
	 */
	protected $soft_delete_key = 'deleted';

	/**
	 * @var  string  column holding deleted_at
	 */
	protected $deleted_at = 'deleted_at';

	/**
	 * @var  boolean Get even deleted records
	 */
	protected $even_deleted = false;

	/**
	 * @var  boolean  Get only deleted records
	 */
	protected $only_deleted = false;

	/**
	 * @var array
	 */
	protected $with = array();

	/**
	 * Contains any related objects of which this model
	 * is singularly related.
	 * @var array
	 */
	protected $has_one = array();

	/**
	 * Contains any related objects of which this model
	 * is related one OR more times.
	 * @var array
	 */
	protected $has_many = array();

	/**
	 * @var  array  fields that should not be touched
	 */
	protected $protected_attributes = array();

	/**
	 * Whether to skip validation OR not
	 * @var  boolean
	 */
	protected $skip_validation = false;
	/**
	 * @var array
	 */
	protected $validation_rules = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('inflector', 'array'));

		// Try to guess the model name unless it is set
		$this->_set_model();

		// Here we guess the table's name
		$this->_set_table();

		// We start the database object
		$this->_set_database();

		// Set model's table fields
		$this->_set_fields();

		// Prepare validation rules
		$this->_set_validation();

		// Next thing to do is to check the soft
		// delete column exists on the table,
		// if so, we automatically enabled soft
		// deleting ;)
		$this->_set_soft_delete();
	}

	/**
	 * Set model's name
	 *
	 * @access  private
	 * @param   void
	 * @return  strig
	 */
	private function _set_model()
	{
		if ($this->model === '')
		{
			$this->model = ucfirst(get_class($this));
		}
	}

	/**
	 * Set table name
	 *
	 * @access  private
	 * @param   void
	 * @return  void
	 */
	private function _set_table()
	{
		if ($this->table === '')
		{
			$this->table = preg_replace(
				'/(_m|_mod|_model)?$/',
				'',
				strtolower($this->model)
			);
		}
	}

	/**
	 * Create database variable to allow multiple databases to be loaded
	 * without overloading the main db variable
	 */
	private function _set_database()
	{
		if ($this->db_group !== null)
		{
			$this->database = $this->load->database($this->db_group, true, true);
		}
		else
		{
			if ( ! isset($this->db) OR ! is_object($this->db))
			{
				$this->load->database('', false, true);
			}
		}
		$this->database = $this->db;
	}

	/**
	 * Set model's tabl fields
	 *
	 * @access  public
	 * @param   void
	 * @return  array
	 */
	public function _set_fields()
	{
		$this->fields = ($this->table === '' OR $this->table === 'my')
						? null
						: $this->database->list_fields($this->table);
	}

	/**
	 * Set validation rules
	 *
	 * @access  private
	 * @param   void
	 * @return  void
	 */
	private function _set_validation()
	{
		if ( ! empty($this->validation_rules))
		{
			$this->load->library('form_validation');
			//$this->load->library('jquery_validation');
			$this->form_validation->set_rules($this->validation_rules);
			//$this->jquery_validation->set_rules($this->validation_rules);
		}
	}

	/**
	 * Set Soft Delete
	 *
	 * @access  private
	 * @param   void
	 * @return  void
	 */
	private function _set_soft_delete()
	{
		if ($this->table !== 'my' AND $this->field_exists($this->soft_delete_key))
		{
			$this->soft_delete = true;
		}
	}

	/**
	 * Protect attributes
	 *
	 * @access  public
	 * @param   mixed
	 * @return  mixed
	 */
	public function protect_attributes($row)
	{
		foreach ($this->protected_attributes as $attribute)
		{
			if (is_object($row))
			{
				unset($row->$attribute);
			}
			else
			{
				unset($row[$attribute]);
			}
		}
		return $row;
	}

	/**
	 * Return AND array of tables fields
	 *
	 * @access  public
	 * @param   void
	 * @return  array
	 */
	public function list_fields()
	{
		return $this->fields;
	}

	/* ==================================================================
	 * QUERY BUILDER METHODS
	 * ================================================================== */

	/**
	 * Return CI db SELECT
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function select($fields)
	{
		$this->database->select($fields);
		return $this;
	}

	/**
	 * Return CI db SET
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function set($field, $value = null, $escape = true)
	{

		// If case we pass an associative array
		// to this method, we loop through it

		if (is_array($field) AND $value === null)
		{
			foreach ($field as $key => $val)
			{
				// Here we allow the array value to be an
				// associative array as well. Why? Well,
				// in case you pass an associative array
				// with multiple children among which one
				// has to be passed as it is, not escaped
				// In this case, you do as the following:
				//
				// array(
				//      'email' => 'new@email.com',
				//      'count_updated' => array(
				//          'count_updated+1', false
				//      )
				// );
				//
				// Simple array, no keys ! THe first param
				// Is what to set AND the second is the
				// escape boolean

				if (is_array($val))
				{
					$this->database->set($key, $val[0], $val[1]);
				}

				// In the val is a simple simple, proceed to
				// simple db->set.

				else
				{
					$this->database->set($key, $val);
				}
			}
		}

		// In case we pass two string params, we use the
		// simple db->set([], [], [])

		else
		{
			$this->database->set($field, $value, $escape);
		}
		return $this;
	}

	/**
	 * Use WHERE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function where($field = null, $match = null)
	{
		if ($field === null)
		{
			return $this;
		}
		// In case only the first parameter is passed
		// BUT it is an array, we loop through where
		elseif (is_array($field))
		{
			foreach ($field as $key => $value)
			{
				$this->database->where($key, $value);
			}
		}

		// In case it is not an array AND the second
		// parameter is passed, we use a simple where
		else
		{
			// In case the second parameter is an array
			// it is better to use where_in ;)
			if (is_array($match))
			{
				$this->where_in($field, $match);
			}

			// Other wise, do a really simple where :D
			else
			{
				$this->database->where($field, $match);
			}
		}

		// We make sure to exclude deleted records
		if ($this->even_deleted === true)
		{
			$this->soft_delete = false;
		}
		if ($this->soft_delete === true)
		{
			if ($this->only_deleted === true)
			{
				$this->database->where($this->soft_delete_key, 1);
			}
			else
			{
				$this->database->where($this->soft_delete_key, 0);
			}
		}

		// By returning this, we allow chaining
		return $this;
	}

	/**
	 * Use OR WHERE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function or_where($where, $match = null)
	{
		// Like the method before! If the first
		// param is passed AND it's an array

		if (is_array($where))
		{
			// We loop through the array
			foreach ($where as $key => $value)
			{
				$this->database->or_where($key, $value);
			}
		}

		// In cases it is a string
		else
		{
			// If the second param is an array
			// we use the or_where_in

			if (is_array($match))
			{
				$this->or_where_in($where, $match);
			}

			// Otherwise, simple use of or_where
			else
			{
				$this->database->or_where($where, $match);
			}
		}
		return $this;
	}

	/**
	 * user WHERE IN
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function where_in($field, $where = array())
	{
		$this->database->where_in($field, $where);
		return $this;
	}

	/**
	 * user OR WHERE IN
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function or_where_in($field, $where = array())
	{
		$this->database->or_where_in($field, $where);
		return $this;
	}

	/**
	 * user WHERE NOT IN
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function where_not_in($field, $where)
	{
		$this->database->where_not_in($field, $where);
		return $this;
	}

	/**
	 * user OR WHERE NOT IN
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function or_where_not_in($field, $where)
	{
		$this->database->or_where_not_in($field, $where);
		return $this;
	}

	/**
	 * Use LIKE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function like($field, $match = '', $wildcard = 'both')
	{
		// If the first param is a string, we use
		// codeigniter simple like which need three
		// params : field / match / AND the wildcard

		if (is_string($field))
		{
			$this->database->like($field, $match, $wildcard);
		}

		// But if the first param is an array/object
		// we use the second option :D

		else
		{
			$this->database->like((array) $field);
		}

		// We make sure to exclude deleted records
		if ($this->soft_delete === true)
		{
			$this->database->where($this->soft_delete_key, false);
		}

		return $this;
	}

	/**
	 * Use OR LIKE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function or_like($field, $match, $wildcard = 'both')
	{
		// If the first param is a string, we use
		// codeigniter simple or_like which need three
		// params : field / match / AND the wildcard

		if (is_string($field))
		{
			$this->database->or_like($field, $match, $wildcard);
		}

		// But if the first param is an array/object
		// we use the second option :D

		else
		{
			$this->database->or_like($field);
		}
		return $this;
	}

	/**
	 * Use NOT LIKE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function not_like($field, $match, $option = 'both')
	{
		if (is_string($field))
		{
			$this->database->not_like($field, $match, $option);
		}
		else
		{
			$this->database->not_like($field);
		}
		return $this;
	}

	/**
	 * Use OR LIKE
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function or_not_like($field, $match, $option = 'both')
	{
		if (is_string($field))
		{
			$this->database->or_not_like($field, $match, $option);
		}
		else
		{
			$this->database->or_not_like($field);
		}
		return $this;
	}

	/**
	 * use CI Group By
	 *
	 * @access  public
	 * @param   mixed
	 * @return  void
	 */
	public function group_by($param)
	{
		$this->database->group_by($param);
		return $this;
	}

	/**
	 * use Distinct
	 *
	 * @access  public
	 * @return  void
	 */
	public function distinct()
	{
		$this->database->distinct();
		return $this;
	}

	/**
	 * Use Having
	 *
	 * @access  public
	 * @param   mixed
	 * @param   mixed
	 * @return  void
	 */
	public function having($param1, $param2 = false)
	{
		$this->database->having($param1, $param2);
		// We make sure to exclude deleted records
		if ($this->soft_delete === true)
		{
			$this->database->where($this->soft_delete_key, false);
		}
		return $this;
	}

	/**
	 * Use Or Having
	 *
	 * @access  public
	 * @param   mixed
	 * @param   mixed
	 * @return  void
	 */
	public function or_having($param1, $param2 = false)
	{
		$this->database->or_having($param1, $param2);
		return $this;
	}

	/**
	 * Use the orDER_BY
	 *
	 * @access  public
	 * @param   array
	 * @param   string
	 */
	public function order_by($fields, $order = 'ASC')
	{
		// In case the first param is an array
		// We loop through the array to se the
		// orber_by.
		//
		// i.e: array('name' => 'desc', 'email' => 'asc');

		if (is_array($fields))
		{
			foreach($fields as $key => $val)
			{
				$this->database->order_by($key, $val);
			}
		}
		else
		{
			$this->database->order_by($fields, $order);
		}
		return $this;
	}

	/**
	 * Use Codeigniter LIMIT
	 *
	 * @param   integer
	 * @param   integer
	 */
	public function limit($limit, $offset = 0)
	{
		$this->database->limit($limit, $offset);
		return $this;
	}

	/**
	 * Join Method
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @param   string
	 * @return  object
	 */
	public function join($table, $cond, $type = '', $escape = true)
	{
		if ( ! $table OR ! $this->table_exists($table)) return false;
		$this->database->join($table, $cond, $type, $escape);
		return $this;
	}

	/**
	 * Use of CI group_start
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function group_start()
	{
		$this->database->group_start();
		return $this;
	}

	/**
	 * Use of CI or_group_start
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function or_group_start()
	{
		$this->database->or_group_start();
		return $this;
	}

	/**
	 * Use of CI not_group_start
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function not_group_start()
	{
		$this->database->not_group_start();
		return $this;
	}

	/**
	 * Use of CI or_not_group_start
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function or_not_group_start()
	{
		$this->database->or_not_group_start();
		return $this;
	}

	/**
	 * Use of CI group_end
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function group_end()
	{
		$this->database->group_end();
		return $this;
	}

	/**
	 * List all database tables
	 *
	 * @access  public
	 * @return  object
	 */
	public function list_tables()
	{
		return $this->database->list_tables();
	}

	/**
	 * Check if a table exists
	 *
	 * @access  public
	 * @param   string
	 * @return  boolean
	 */
	public function table_exists($table = null)
	{
		$table OR $table = $this->table;
		return $this->database->table_exists($table);
	}

	/**
	 * Check if a single field exists
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @return  boolean
	 */
	public function field_exists($field, $table = null)
	{
		$table OR $table = $this->table;
		return (in_array($field, $this->fields)) ? true : false;
	}

	/* ==================================================================
	 * CRUD METHODS
	 * ================================================================== */

	/* ------------------------------------------------------------------
	 * CREATE
	 * ------------------------------------------------------------------ */

	/**
	 * Insert single row into table
	 *
	 * @access  public
	 * @param   array
	 * @return  integer
	 */
	public function create($data)
	{
		if ( ! isset($data))
		{
			return false;
		}

		if (isset($data[0]) AND is_array($data[0]))
		{
			return $this->create_many($data);
		}

		// We call this method to set the created_at
		$data = $this->_before_create($this->protect_attributes($data));

		foreach ((array) $data as $key => $value)
		{
			if ($this->field_exists($key))
			{
				$this->set($key, $value);
			}
		}
		$this->database->insert($this->table);
		return $this->database->insert_id();
	}

	/**
	 * Multiple insert rows into table
	 *
	 * @access  public
	 * @param   array
	 * @return  array
	 */
	public function create_many($data)
	{
		$ids = array();
		$this->database->trans_start();
		foreach ($data as $key => $value)
		{
			$ids[] = $this->create($value);
		}
		$this->database->trans_complete();
		return ($this->database->trans_status()) ? $ids : false;
	}

	/* ------------------------------------------------------------------
	 * FINDERS
	 * ------------------------------------------------------------------ */

	/**
	 * Get single row by primary key if it exists
	 *
	 * @access  public
	 * @param   integer
	 * @return  object
	 */
	public function find($id = null)
	{
		if ($this->primary_key === null OR ! $this->field_exists($this->primary_key))
		{
			return false;
		}

		// Get type of $id variable
		$type = gettype($id);

		// If an array of IDS is passed!
		if ($type === 'array')
		{
			return $this->where_in($this->primary_key, $id)->all();
		}

		elseif ($type === 'integer')
		{
			return $this->find_one($this->primary_key, $id);
		}

		else
		{
			switch ($id) {
				case 'all':
					return $this->all();
					break;
				case 'first':
					return $this->first();
					break;
				case 'last':
					return $this->last();
					break;
				default:
					return $this->find_one($this->primary_key, $id);
					break;
			}
		}
		return null;
	}

	/**
	 * Find where condition
	 *
	 * @access  public
	 * @param   array
	 * @return  mixed
	 */
	public function find_where($where = array(), $limit = 0, $offset = 0)
	{
		$query = $this->database->get_where($this->table, $where, $limit, $offset);
		return ($this->return_type === 'object') ? $query->result(): $query->result_array();
	}

	/**
	 * Get a single row with conditions
	 *
	 * @access  public
	 * @param   array
	 * @return  mixed
	 */
	public function find_one($field = null, $match = null)
	{
		if ($result = $this->limit(1)->find_by($field, $match)) {
			return ($this->return_type == 'json')
					? json_encode($result)
					: is_object($result) ? $result : $result[0];
		}
		return null;
	}

	/**
	 * Find by
	 *
	 * @access  public
	 * @param   string
	 * @param   mixed
	 * @return  mixed
	 */
	public function find_by($field = null, $match = null, $type = 'and')
	{
		if ($field === null OR ( ! is_array($field) AND $match === null))
		{
			return false;
		}
		$where = is_array($field) ? $field : array($field => $match);
		if ($type == 'or')
		{
			$this->or_where($where);
		}
		else
		{
			$this->where($where);
		}
		$query = $this->database->get($this->table);
		if ($query->num_rows() > 0)
		{
			$result = ($this->return_type === 'object')
						? $query->row()
						: $query->row_array();
			$result = $this->relate($result);
			return ($this->return_type == 'json')
					? json_encode($result)
					: $result;
		}
		if (is_string($field)) {
			if (is_string($match)) {
				return $this->find_many(array($field => $match));
			} else {
				return $this->where_in($field, $match)->all();
			}
		} else {
			return $this->find_many($field);
		}
		return null;
	}

	/**
	 * Get multiple rows with conditions
	 *
	 * @access  public
	 * @param   array
	 * @return  mixed
	 */
	public function find_many($field = null, $match = null)
	{
		$this->where($field, $match);
		$query = $this->database->get($this->table);
		if ($query->num_rows() > 0)
		{
			$result = ($this->return_type === 'object') ? $query->result() : $query->result_array();
			foreach ($result as $key => &$row)
			{
				$row = $this->relate($row);
			}
			$this->with = array();
			return $result;
		}
		return null;
	}

	/**
	 * Return all tables records
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function all()
	{
		return $this->find_many();
	}
	public function find_all()
	{
		return $this->all();
	}

	/**
	 * Find multiple rows selecting only a single column
	 *
	 * @access  public
	 * @param   string
	 * @param   array
	 * @return  mixed
	 */
	public function find_col($field, $where = array())
	{
		if ($this->field_exists($field))
		{
			if ($results = $this->select($field)->find_many($where))
			{
				$_result = array();
				foreach ($results as $result)
				{
					$_result[] = $result->{$field};
				}
				return $_result;
			}
		}
		return null;
	}

	public function find_cell($field, $where = array())
	{
		return ($cell = $this->find_col($field, $where)) ? $cell[0] : null;
	}

	/**
	 * Return the first record
	 *
	 * @access  public
	 * @param   string
	 * @return  object
	 */
	public function first($field = null)
	{
		$field OR $field = $this->primary_key;
		if ( ! $this->field_exists($field))
		{
			return null;
		}
		if ($result = $this->order_by($field, 'ASC')->all())
		{
			return $result[0];
		}
		return null;
	}

	/**
	 * Find the last record
	 *
	 * @access  public
	 * @param   string
	 * @return  object
	 */
	public function last($field = null)
	{
		$field OR $field = $this->primary_key;
		if ( ! $this->field_exists($field))
		{
			return null;
		}
		if ($result = $this->order_by($field, 'DESC')->all())
		{
			return $result[0];
		}
		return null;
	}

	/* ------------------------------------------------------------------
	 * UPDATES
	 * ------------------------------------------------------------------ */

	/**
	 * Update row in table
	 *
	 * @access  public
	 * @param   integer
	 * @param   array
	 * @return  boolean
	 */
	public function update($id = null, $data = null)
	{
		if ($id === null)
		{
			$this->database->update($this->table);
			return $this;
		}
		return $this->update_many($data, array($this->primary_key => $id));
	}

	/**
	 * Update many records, based on an array of IDs.
	 *
	 * @param  array   $ids  Array of IDs
	 * @param  array   $data Associative array of data
	 * @return boolean       Success
	 */
	public function update_many($data = array(), $where = array(), $updated_at = true)
	{
		$data = $this->_before_update($this->protect_attributes($data), $updated_at);
		foreach ($data as $d_key => $d_value)
		{

			if ($this->database->field_exists($d_key, $this->table))
			{
				if (is_array($d_value))
				{
					$this->set($d_key, $d_value[0], $d_value[1]);
				}
				else
				{
					$this->set($d_key, $d_value);
				}
			}
		}
		if (is_array($where) AND ! empty($where))
		{
			foreach ($where as $w_key => $w_value)
			{
				if ($this->database->field_exists($w_key, $this->table))
				{
					$this->database->where($w_key, $w_value);
				}
			}
		}
		$this->database->update($this->table);
		return (bool) ($this->database->affected_rows() > 0);
	}

	/**
	 * Update all records
	 *
	 * @param  array $data Associative array of data
	 * @return boolean     Success
	 */
	public function update_all($data = array())
	{
		return $this->update_many($data);
	}

	/* ------------------------------------------------------------------
	 * DELETE
	 * ------------------------------------------------------------------ */

	/**
	 * Delete a single row by ID
	 *
	 * @access  public
	 * @param   integer
	 * @return  boolean
	 */
	public function delete($id = false)
	{
		if ($id)
		{
			return $this->delete_many(array($this->primary_key => $id));
		}
		else
		{
			$this->database->delete($this->table);
			return $this;
		}
		return false;
	}

	/**
	 * Delete multiple rows with conditions
	 *
	 * @access  public
	 * @param   array
	 * @return  boolean
	 */
	public function delete_many($where = array())
	{
		// If no parameter is passed to this method
		// it will simply empty the table
		if ( ! isset($where) OR empty($where))
		{
			return $this->empty_table();
		}

		// If a parameter is set but it is not an
		// array, we simple abort AND stop
		if ( ! is_array($where))
		{
			return false;
		}

		// If the soft delete option is ON
		// We update the table instead of
		// removing the record
		if ($this->soft_delete)
		{
			$data = array(
				$this->soft_delete_key => true,
				$this->deleted_at    => $this->_set_timestamp()
			);
			return $this->update_many($data, $where, false);
		}

		// In case the soft delete option in
		// not ON, we proceed to removing the
		// record
		else
		{
			foreach ($where as $key => $value)
			{
				$this->database->where($key, $value);
			}
			$this->database->delete($this->table);
			return (bool) ($this->database->affected_rows() > 0);
		}
	}

	/**
	 * Delete all records in table
	 *
	 * @access  public
	 * @return  boolean
	 */
	public function delete_all()
	{
		return $this->delete_many();
	}

	/**
	 * Completely remove row
	 *
	 * @access  public
	 * @param   integer
	 * @return  boolean
	 */
	public function remove($id = false)
	{
		$this->soft_delete = false;
		return $this->delete($id);
	}

	/**
	 * Remove multiple rows with conditions
	 *
	 * @access  public
	 * @param   array
	 * @return  boolean
	 */
	public function remove_many($where = array())
	{
		$this->soft_delete = false;
		return $this->delete_many($where);
	}

	/**
	 * Runs the selection query
	 *
	 * @access  public
	 * @param   string  $table  optional table name
	 * @return  mixed
	 */
	public function get($table = null)
	{
		$table OR $table = $this->table;
		$query = $this->database->get($table);
		if ($query->num_rows() > 0)
		{
			return ($this->return_type === 'object')
					? $query->result()
					: $query->result_array();
		}

		return null;
	}

	/**
	 * Remove all records in table
	 *
	 * @access  public
	 * @return  boolean
	 */
	public function remove_all()
	{
		return $this->remove_many();
	}

	/**
	 * Empty the tabe
	 *
	 * @access  public
	 * @param   string
	 * @return  bool
	 */
	public function empty_table($table = null)
	{
		$table OR $table = $this->table;
		return $this->database->empty_table($table);
	}

	/**
	 * Truncate a table
	 *
	 * @access  public
	 */
	public function truncate($table = null)
	{
		$table OR $table = $this->table;
		return $this->database->truncate($this->table);
	}

	/**
	 * Call MySQL function
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function func()
	{
		return $this->database->call_function();
	}

	/* ==================================================================
	 * QUERY
	 * ================================================================== */

	/**
	 * Use db->query
	 *
	 * @access  public
	 * @param   string
	 */
	public function execute($query) {
		return $this->database->query($query);
	}

	/* ==================================================================
	 * RELATIONSHPS
	 * ================================================================== */
	public function with($relationship)
	{
		$args = str_replace(' ', '', explode(',', $relationship));
		foreach ($args as $arg)
		{
			$this->with[] = $arg;
		}
		/*$this->with[] = $relationship;*/
		return $this;
	}

	protected function relate($row)
	{
		// Loop through has_one in order to
		// get data from the related table
		if ( ! empty($this->has_one) AND is_array($this->has_one))
		{
			foreach ($this->has_one as $key => $value)
			{
				if (is_string($value))
				{
					$relationship = $value;
					$options      = array(
						'model'   => plural($value).'_model',
						'local'   => $this->primary_key,
						'foreign' => singular($this->table).'_id'
					);
				}
				else
				{
					$relationship = $key;
					$options      = $value;
				}
				if (in_array($relationship, $this->with))
				{
					$this->load->model($options['model']);
					$_model = explode('/', $options['model']);
					$_model = end($_model);
					if (is_array($row))
					{
						$row[$relationship] = $this->{$_model}->find_one(array($options['foreign'] => $row[$options['local']]));
					}
					else
					{
						$row->$relationship = $this->{$_model}->find_one(array($options['foreign'] => $row->{$options['local']}));
					}
				}
			}
		}

		// Loop through has_many in order to
		// get data from the related table
		if ( ! empty($this->has_many) AND is_array($this->has_many))
		{
			foreach ($this->has_many as $key => $value)
			{
				if (is_string($value)) {
					$relationship = $value;
					$options      = array(
						'model'   => plural($value).'_model',
						'local'   => $this->primary_key,
						'foreign' => singular($this->table).'_id'
					);
				}
				else
				{
					$relationship = $key;
					$options      = $value;
				}
				if (in_array($relationship, $this->with))
				{
					$this->load->model($options['model']);
					$_model = explode('/', $options['model']);
					$_model = end($_model);
					if (is_array($row))
					{
						$row[$relationship] = $this->{$_model}->find_many(array($options['foreign'] => $row->{$options['local']}));
					}
					else
					{
						$row->$relationship = $this->{$_model}->find_many(array($options['foreign'] => $row->{$options['local']}));
					}
				}
			}
		}
		return $row;
	}

	/* ==================================================================
	 * UTILITIES METHODS
	 * ================================================================== */
	/**
	 * Count rows
	 *
	 * @access  public
	 * @param   array
	 * @return  integer
	 */
	public function count($where = array())
	{
		$this->where($where);
		return $this->database->get($this->table)->num_rows();
	}

	/**
	 * Select Min
	 *
	 * @param  string
	 * @param  string
	 * @return object
	 */
	public function min($field, $where = false)
	{
		$this->database->select_min($field);
		if ($where AND is_array($where)) {
			$this->where($where);
		}
		return $this->database->get($this->table)->row();
	}

	/**
	 * Select Max
	 *
	 * @param  string
	 * @param  string
	 * @return object
	 */
	public function max($field, $where = false)
	{
		$this->database->select_max($field);
		if ($where AND is_array($where)) {
			$this->where($where);
		}
		return $this->database->get($this->table)->row();
	}

	/**
	 * Select Average
	 *
	 * @param  string
	 * @param  string
	 * @return object
	 */
	public function avg($field)
	{
		$this->database->select_avg($field);
		if ($where AND is_array($where)) {
			$this->where($where);
		}
		return $this->database->get($this->table)->row();
	}

	/**
	 * Select Sum
	 *
	 * @param  string
	 * @param  string
	 * @return object
	 */
	public function sum($field)
	{
		return $this->database->select_sum($field)->get($this->table);
	}

	/**
	 * Build Multidimensional Array using the parent_guid column
	 *
	 * @access  public
	 * @param   array
	 * @param   integer
	 * @return  array
	 */
	public function build_tree(array $elements, $parent_guid = 0, $parent_field = 'parent_guid')
	{
		$branch = array();
		foreach ($elements as $element)
		{
			if ($element[$parent_field] == $parent_guid)
			{
				$children = $this->build_tree($elements, $element[$this->primary_key]);
				if ($children)
				{
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}
		return $branch;
	}

	/* ==================================================================
	 * MorE UTILITIES METHODS
	 * ================================================================== */

	/**
	 * Turn on cache before executing the query
	 *
	 * @access  public
	 * @param   void
	 * @return  object
	 */
	public function cache_one()
	{
		$this->database->cache_on();
		return $this;
	}

	/**
	 * Turn OFF cache before executing the query
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function cache_off()
	{
		$this->database->cache_off();
		return $this;
	}

	/**
	 * Delete database cache
	 *
	 * @access  public
	 * @param   string
	 * @param   string
	 * @return  this
	 */
	public function cache_delete($controller = null, $method = null)
	{
		$this->database->cache_delete($controller, $method);
		return $this;
	}

	/**
	 * Delete all database cache
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function cache_delete_all()
	{
		$this->database->cache_delete_all();
		return $this;
	}
	/**
	 * Change the return type to array
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function as_array()
	{
		$this->return_type = 'array';
		return $this;
	}

	/**
	 * Change the return type to object
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function as_object()
	{
		$this->return_type = 'object';
		return $this;
	}

	/**
	 * Chage the return to json
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function as_json()
	{
		$this->return_type = 'json';
		return $this;
	}

	/**
	 * Set time() depending on the config
	 * $this->unix_timestamp
	 *
	 * @access  public
	 * @param   void
	 * @return  time
	 */
	public function _set_timestamp()
	{
		return ($this->unix_timestamp) ? time() : date($this->datetime_format);
	}

	/**
	 * Set Date Created
	 *
	 * @access  public
	 * @param   array
	 * @return  array
	 */
	function _before_create($data)
	{
		$default = array();
		if ($this->field_exists($this->created_at))
		{
			if (array_key_exists($this->created_at, $data))
			{
				$default[$this->created_at] = $data[$this->created_at];
			}
			else
			{
				$default[$this->created_at] = $this->_set_timestamp();
			}
		}
		$data = array_merge($data, $default);
		return $data;
	}

	/**
	 * Set Date Updated
	 *
	 * @access  public
	 * @param   array
	 * @return  array
	 */
	public function _before_update($data, $updated_at = true)
	{
		$default = array();
		if ($this->field_exists($this->updated_at) AND $updated_at === true)
		{
			if (array_key_exists($this->updated_at, $data))
			{
				$default[$this->updated_at] = $data[$this->updated_at];
			}
			else
			{
				$default[$this->updated_at] = $this->_set_timestamp();
			}
		}
		$data = array_merge($data, $default);
		return $data;
	}

	/**
	 * Set Date Created
	 *
	 * @access  public
	 * @param   array
	 * @return  array
	 */
	public function _before_delete($data)
	{
		$default = array();
		if ($this->field_exists($this->deleted_at))
		{
			if (array_key_exists($this->deleted_at, $data))
			{
				$default[$this->deleted_at] = $data[$this->deleted_at];
			}
			else
			{
				$default[$this->deleted_at] = $this->_set_timestamp();
			}
		}
		$data = array_merge($data, $default);
		return $data;
	}

	/**
	 * Return even deleted records
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function even_deleted()
	{
		$this->even_deleted = true;
		return $this;
	}

	/**
	 * Return only deleted
	 *
	 * @access  public
	 * @param   void
	 * @return  this
	 */
	public function only_deleted()
	{
		$this->only_deleted = true;
		return $this;
	}

	/* ==================================================================
	 * MAGIC METHODS
	 * ================================================================== */

	/**
	 * Magic Call Method
	 *
	 * @access  public
	 * @param   string
	 * @param   mixed
	 * @return  mixed
	 */
	public function __call($method, $args)
	{
		if (preg_match('/^find_by_([^)]+)$/', $method, $m))
		{
			if ($this->field_exists($m[1]))
			{
				$_args = (is_array($args[0])) ? $args[0] : $args;
				if (in_array($m[1], array('id', 'guid', $this->primary_key)))
				{
					return $this->find($_args);
				}
				return $this->find_by($m[1], $_args);
			}
			return null;
		}
		else if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $args);
		}
		return "Unknown method '{$method}'.";
	}

	/**
	 * Magic Set method to set variables
	 *
	 * @param   string
	 * @return  mixed
	 */
	public function __set($name, $value)
	{
		return $this->{$name} = $value;
	}
}
