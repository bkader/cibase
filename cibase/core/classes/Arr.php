<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CI_Arr - Arr.php
 *
 * This class was taken from Fuelphp and adapted to be used with CodeIgniter.
 *
 * @package 	CodeIgniter
 * @category 	Core
 * @author 	Kader Bouyakoub <bkader@mail.com>
 * @link 	https://github.com/bkader
 */

class CI_Arr {
	/**
	 * Gets a dot-notated key from an array, with a default value if it does
	 * not exist.
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @param   string  $default  The default value
	 * @return  mixed
	 */
	public function get($array, $key, $default = NULL)
	{
		// $array must always be an array
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		// If no key is provided, we return the full array
		if (is_NULL($key))
		{
			return $array;
		}

		// If case $key is an array
		if (is_array($key))
		{
			$return = array();
			foreach ($key as $k)
			{
				$return[$k] = $this->get($array, $k, $default);
			}
			return $return;
		}

		// If $key is an object, we turn it into a string
		is_object($key) && $key = (string) $key;

		foreach (explode('.', $key) as $key_part)
		{
			if (($array instanceof ArrayAccess && isset($array[$key_part])) === FALSE)
			{
				if ( ! is_array($array) or ! array_key_exists($key_part, $array))
				{
					return $this->value($default);
				}
			}

			$array = $array[$key_part];
		}

		return $array;
	}

	/**
	 * Set an array item (dot-notated) to the value.
	 *
	 * @param   array   $array  The array to insert it into
	 * @param   mixed   $key    The dot-notated key to set or array of keys
	 * @param   mixed   $value  The value
	 * @return  void
	 */
	public function set(&$array, $key, $value = NULL)
	{
		if (is_NULL($key))
		{
			$array = $value;
			return;
		}

		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->set($array, $k, $v);
			}
		}
		else
		{
			$keys = explode('.', $key);

			while (count($keys) > 1)
			{
				$key = array_shift($keys);

				if ( ! isset($array[$key]) OR ! is_array($array[$key]))
				{
					$array[$key] = array();
				}

				$array =& $array[$key];
			}

			$array[array_shift($keys)] = $value;
		}
	}

	/**
	 * Pluck an array of values from an array.
	 *
	 * @param  array   $array  collection of arrays to pluck from
	 * @param  string  $key    key of the value to pluck
	 * @param  string  $index  optional return array index key, TRUE for original index
	 * @return array   array of plucked values
	 */
	public function pluck($array, $key, $index = NULL)
	{
		$return = array();
		$get_deep = strpos($key, '.') !== FALSE;

		if ( ! $index)
		{
			foreach ($array as $i => $a)
			{
				$return[] = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$key} :
					($get_deep ? $this->get($a, $key) : $a[$key]);
			}
		}
		else
		{
			foreach ($array as $i => $a)
			{
				$index !== TRUE && $i = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$index} : $a[$index];
				$return[$i] = (is_object($a) && ! ($a instanceof ArrayAccess)) ? $a->{$key} :
					($get_deep ? $this->get($a, $key) : $a[$key]);
			}
		}

		return $return;
	}



	/**
	 * Array_key_exists with a dot-notated key from an array.
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @return  mixed
	 */
	public function key_exists($array, $key)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		is_object($key) && $key = (string) $key;

		if ( ! is_string($key))
		{
			return FALSE;
		}

		if (array_key_exists($key, $array))
		{
			return TRUE;
		}

		foreach (explode('.', $key) as $key_part)
		{
			if (($array instanceof ArrayAccess && isset($array[$key_part])) === FALSE)
			{
				if ( ! is_array($array) or ! array_key_exists($key_part, $array))
				{
					return FALSE;
				}
			}

			$array = $array[$key_part];
		}

		return TRUE;
	}

	/**
	 * Unsets dot-notated key from an array
	 *
	 * @param   array   $array    The search array
	 * @param   mixed   $key      The dot-notated key or array of keys
	 * @return  mixed
	 */
	public function delete(&$array, $key)
	{
		if (is_NULL($key))
		{
			return FALSE;
		}

		if (is_array($key))
		{
			$return = array();
			foreach ($key as $k)
			{
				$return[$k] = $this->delete($array, $k);
			}
			return $return;
		}

		$key_parts = explode('.', $key);

		if ( ! is_array($array) or ! array_key_exists($key_parts[0], $array))
		{
			return FALSE;
		}

		$this_key = array_shift($key_parts);


		if ( ! empty($key_parts))
		{
			$key = implode('.', $key_parts);
			return $this->delete($array[$this_key], $key);
		}
		else
		{
			unset($array[$this_key]);
		}

		return TRUE;
	}

	/**
	 * Converts a multi-dimensional associative array into an array of key => values with the provided field names
	 *
	 * @param   array   $assoc      the array to convert
	 * @param   string  $key_field  the field name of the key field
	 * @param   string  $val_field  the field name of the value field
	 * @return  array
	 * @throws  InvalidArgumentException
	 */
	public function assoc_to_keyval($assoc, $key_field, $val_field)
	{
		if ( ! is_array($assoc) && ! $assoc instanceof Iterator)
		{
			throw new InvalidArgumentException('The first parameter must be an array.');
		}

		$output = array();
		foreach ($assoc as $row)
		{
			if (isset($row[$key_field]) && isset($row[$val_field]))
			{
				$output[$row[$key_field]] = $row[$val_field];
			}
		}

		return $output;
	}

	/**
	 * Converts an array of key => values into a multi-dimensional associative array with the provided field names
	 *
	 * @param   array   $array      the array to convert
	 * @param   string  $key_field  the field name of the key field
	 * @param   string  $val_field  the field name of the value field
	 * @return  array
	 * @throws  InvalidArgumentException
	 */
	public function keyval_to_assoc($array, $key_field, $val_field)
	{
		if ( ! is_array($array) && ! $array instanceof Iterator)
		{
			throw new InvalidArgumentException('The first parameter must be an array.');
		}

		$output = array();
		foreach ($array as $key => $value)
		{
			$output[] = array(
				$key_field => $key,
				$val_field => $value,
			);
		}

		return $output;
	}

	/**
	 * Converts the given 1 dimensional non-associative array to an associative
	 * array.
	 *
	 * The array given must have an even number of elements or NULL will be returned.
	 *
	 *     Arr::to_assoc(array('foo','bar'));
	 *
	 * @param   string      $arr  the array to change
	 * @return  array|NULL  the new array or NULL
	 * @throws  InvalidArgumentException
	 */
	public function to_assoc($arr)
	{
		if (($count = count($arr)) % 2 > 0)
		{
			throw new InvalidArgumentException('Number of values in to_assoc must be even.');
		}
		$keys = $vals = array();

		for ($i = 0; $i < $count - 1; $i += 2)
		{
			$keys[] = array_shift($arr);
			$vals[] = array_shift($arr);
		}
		return array_combine($keys, $vals);
	}

	/**
	 * Checks if the given array is an assoc array.
	 *
	 * @param   array  $arr  the array to check
	 * @return  bool   TRUE if its an assoc array, FALSE if not
	 */
	public function is_assoc($arr)
	{
		if ( ! is_array($arr))
		{
			throw new InvalidArgumentException('The parameter must be an array.');
		}

		$counter = 0;
		foreach ($arr as $key => $unused)
		{
			if ( ! is_int($key) OR $key !== $counter++)
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Filters an array by an array of keys
	 *
	 * @param   array  $array   the array to filter.
	 * @param   array  $keys    the keys to filter
	 * @param   bool   $remove  if TRUE, removes the matched elements.
	 * @return  array
	 */
	public function filter_keys($array, $keys, $remove = FALSE)
	{
		$return = array();
		foreach ($keys as $key)
		{
			if (array_key_exists($key, $array))
			{
				$remove OR $return[$key] = $array[$key];
				if($remove)
				{
					unset($array[$key]);
				}
			}
		}
		return $remove ? $array : $return;
	}

	/**
	 * Insert value(s) into an array, mostly an array_splice alias
	 * WARNING: original array is edited by reference, only boolean success is returned
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $value     the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   int          $pos       the numeric position at which to insert, negative to count from the end backwards
	 * @return  bool         FALSE when array shorter then $pos, otherwise TRUE
	 */
	public function insert(array &$original, $value, $pos)
	{
		if (count($original) < abs($pos))
		{
			throw new Exception('Position larger than number of elements in array in which to insert.');
		}

		array_splice($original, $pos, 0, $value);

		return TRUE;
	}

	/**
	 * Insert value(s) into an array, mostly an array_splice alias
	 * WARNING: original array is edited by reference, only boolean success is returned
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $values    the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   int          $pos       the numeric position at which to insert, negative to count from the end backwards
	 * @return  bool         FALSE when array shorter then $pos, otherwise TRUE
	 */
	public function insert_assoc(array &$original, array $values, $pos)
	{
		if (count($original) < abs($pos))
		{
			return FALSE;
		}

		$original = array_slice($original, 0, $pos, TRUE) + $values + array_slice($original, $pos, NULL, TRUE);

		return TRUE;
	}

	/**
	 * Insert value(s) into an array before a specific key
	 * WARNING: original array is edited by reference, only boolean success is returned
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $value     the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   string|int   $key       the key before which to insert
	 * @param   bool         $is_assoc  whether the input is an associative array
	 * @return  bool         FALSE when key isn't found in the array, otherwise TRUE
	 */
	public function insert_before_key(array &$original, $value, $key, $is_assoc = FALSE)
	{
		$pos = array_search($key, array_keys($original));

		if ($pos === FALSE)
		{
			throw new Exception('Unknown key before which to insert the new value into the array.');
		}

		return $is_assoc ? $this->insert_assoc($original, $value, $pos) : $this->insert($original, $value, $pos);
	}

	/**
	 * Insert value(s) into an array after a specific key
	 * WARNING: original array is edited by reference, only boolean success is returned
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $value     the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   string|int   $key       the key after which to insert
	 * @param   bool         $is_assoc  whether the input is an associative array
	 * @return  bool         FALSE when key isn't found in the array, otherwise TRUE
	 */
	public function insert_after_key(array &$original, $value, $key, $is_assoc = FALSE)
	{
		$pos = array_search($key, array_keys($original));

		if ($pos === FALSE)
		{
			throw new Exception('Unknown key after which to insert the new value into the array.');
		}

		return $is_assoc ? $this->insert_assoc($original, $value, $pos + 1) : $this->insert($original, $value, $pos + 1);
	}

	/**
	 * Insert value(s) into an array after a specific value (first found in array)
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $value     the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   string|int   $search    the value after which to insert
	 * @param   bool         $is_assoc  whether the input is an associative array
	 * @return  bool         FALSE when value isn't found in the array, otherwise TRUE
	 */
	public function insert_after_value(array &$original, $value, $search, $is_assoc = FALSE)
	{
		$key = array_search($search, $original);

		if ($key === FALSE)
		{
			throw new Exception('Unknown value after which to insert the new value into the array.');
			return FALSE;
		}

		return $this->insert_after_key($original, $value, $key, $is_assoc);
	}

	/**
	 * Insert value(s) into an array before a specific value (first found in array)
	 *
	 * @param   array        $original  the original array (by reference)
	 * @param   array|mixed  $value     the value(s) to insert, if you want to insert an array it needs to be in an array itself
	 * @param   string|int   $search    the value after which to insert
	 * @param   bool         $is_assoc  whether the input is an associative array
	 * @return  bool         FALSE when value isn't found in the array, otherwise TRUE
	 */
	public function insert_before_value(array &$original, $value, $search, $is_assoc = FALSE)
	{
		$key = array_search($search, $original);

		if ($key === FALSE)
		{
			throw new Exception('Unknown value before which to insert the new value into the array.');
		}

		return $this->insert_before_key($original, $value, $key, $is_assoc);
	}

	/**
	 * Sorts a multi-dimensional array by it's values.
	 *
	 * @access	public
	 * @param	array   $array       The array to fetch from
	 * @param	string  $key         The key to sort by
	 * @param	string  $order       The order (asc or desc)
	 * @param	int	    $sort_flags  The php sort type flag
	 * @return	array
	 */
	public function sort($array, $key, $order = 'asc', $sort_flags = SORT_REGULAR)
	{
		if ( ! is_array($array))
		{
			throw new InvalidArgumentException('Arr::sort() - $array must be an array.');
		}

		if (empty($array))
		{
			return $array;
		}

		foreach ($array as $k => $v)
		{
			$b[$k] = $this->get($v, $key);
		}

		switch ($order)
		{
			case 'asc':
				asort($b, $sort_flags);
			break;

			case 'desc':
				arsort($b, $sort_flags);
			break;

			default:
				throw new InvalidArgumentException('Arr::sort() - $order must be asc or desc.');
			break;
		}

		foreach ($b as $key => $val)
		{
			$c[] = $array[$key];
		}

		return $c;
	}

	/**
	 * Sorts an array on multiple values, with deep sorting support.
	 *
	 * @param   array  $array        collection of arrays/objects to sort
	 * @param   array  $conditions   sorting conditions
	 * @param   bool   $ignore_case  whether to sort case insensitive
	 * @return  array
	 */
	public function multisort($array, $conditions, $ignore_case = FALSE)
	{
		$temp = array();
		$keys = array_keys($conditions);

		foreach($keys as $key)
		{
			$temp[$key] = $this->pluck($array, $key, TRUE);
			is_array($conditions[$key]) OR $conditions[$key] = array($conditions[$key]);
		}

		$args = array();
		foreach ($keys as $key)
		{
			$args[] = $ignore_case ? array_map('strtolower', $temp[$key]) : $temp[$key];
			foreach($conditions[$key] as $flag)
			{
				$args[] = $flag;
			}
		}

		$args[] = &$array;

		call_user_func_array('array_multisort', $args);
		return $array;
	}

	/**
	 * Find the average of an array
	 *
	 * @param   array   $array  the array containing the values
	 * @return  number          the average value
	 */
	public function average($array)
	{
		// No arguments passed, lets not divide by 0
		if ( ! ($count = count($array)) > 0)
		{
			return 0;
		}

		return (array_sum($array) / $count);
	}

	/**
	 * Replaces key names in an array by names in $replace
	 *
	 * @param   array           $source   the array containing the key/value combinations
	 * @param   array|string    $replace  key to replace or array containing the replacement keys
	 * @param   string          $new_key  the replacement key
	 * @return  array                     the array with the new keys
	 */
	public function replace_key($source, $replace, $new_key = NULL)
	{
		if(is_string($replace))
		{
			$replace = array($replace => $new_key);
		}

		if ( ! is_array($source) OR ! is_array($replace))
		{
			throw new InvalidArgumentException('Arr::replace_key() - $source must an array. $replace must be an array or string.');
		}

		$result = array();

		foreach ($source as $key => $value)
		{
			if (array_key_exists($key, $replace))
			{
				$result[$replace[$key]] = $value;
			}
			else
			{
				$result[$key] = $value;
			}
		}

		return $result;
	}

	/**
	 * Merge 2 arrays recursively, differs in 2 important ways from array_merge_recursive()
	 * - When there's 2 different values && not both arrays, the latter value overwrites the earlier
	 *   instead of merging both into an array
	 * - Numeric keys that don't conflict aren't changed, only when a numeric key already exists is the
	 *   value added using array_push()
	 *
	 * @return  array
	 * @throws  InvalidArgumentException
	 */
	public function merge()
	{
		$array  = func_get_arg(0);
		$arrays = array_slice(func_get_args(), 1);

		if ( ! is_array($array))
		{
			throw new InvalidArgumentException('Arr::merge() - all arguments must be arrays.');
		}

		foreach ($arrays as $arr)
		{
			if ( ! is_array($arr))
			{
				throw new InvalidArgumentException('Arr::merge() - all arguments must be arrays.');
			}

			foreach ($arr as $k => $v)
			{
				// numeric keys are appended
				if (is_int($k))
				{
					array_key_exists($k, $array) ? $array[] = $v : $array[$k] = $v;
				}
				elseif (is_array($v) && array_key_exists($k, $array) && is_array($array[$k]))
				{
					$array[$k] = $this->merge($array[$k], $v);
				}
				else
				{
					$array[$k] = $v;
				}
			}
		}

		return $array;
	}

	/**
	 * Merge 2 arrays recursively, differs in 2 important ways from array_merge_recursive()
	 * - When there's 2 different values && not both arrays, the latter value overwrites the earlier
	 *   instead of merging both into an array
	 * - Numeric keys are never changed
	 *
	 * @return  array
	 * @throws  InvalidArgumentException
	 */
	public function merge_assoc()
	{
		$array  = func_get_arg(0);
		$arrays = array_slice(func_get_args(), 1);

		if ( ! is_array($array))
		{
			throw new InvalidArgumentException('Arr::merge_assoc() - all arguments must be arrays.');
		}

		foreach ($arrays as $arr)
		{
			if ( ! is_array($arr))
			{
				throw new InvalidArgumentException('Arr::merge_assoc() - all arguments must be arrays.');
			}

			foreach ($arr as $k => $v)
			{
				if (is_array($v) && array_key_exists($k, $array) && is_array($array[$k]))
				{
					$array[$k] = $this->merge_assoc($array[$k], $v);
				}
				else
				{
					$array[$k] = $v;
				}
			}
		}

		return $array;
	}

	/**
	 * Prepends a value with an associative key to an array.
	 * Will overwrite if the value exists.
	 *
	 * @param   array           $arr     the array to prepend to
	 * @param   string|array    $key     the key or array of keys && values
	 * @param   mixed           $value   the value to prepend
	 */
	public function prepend(&$arr, $key, $value = NULL)
	{
		$arr = (is_array($key) ? $key : array($key => $value)) + $arr;
	}

	/**
	 * Recursive in_array
	 *
	 * @param   mixed  $needle    what to search for
	 * @param   array  $haystack  array to search in
	 * @param   bool   $strict
	 * @return  bool   whether the needle is found in the haystack.
	 */
	public function in_array_recursive($needle, $haystack, $strict = FALSE)
	{
		foreach ($haystack as $value)
		{
			if ( ! $strict && $needle == $value)
			{
				return TRUE;
			}
			elseif ($needle === $value)
			{
				return TRUE;
			}
			elseif (is_array($value) && $this->in_array_recursive($needle, $value, $strict))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Checks if the given array is a multidimensional array.
	 *
	 * @param   array  $arr       the array to check
	 * @param   bool   $all_keys  if TRUE, check that all elements are arrays
	 * @return  bool   TRUE if its a multidimensional array, FALSE if not
	 */
	public function is_multi($array, $all_keys = FALSE)
	{
		$values = array_filter($array, 'is_array');
		return $all_keys ? count($arr) === count($values) : count($values) > 0;
	}

	/**
	 * Searches the array for a given value && returns the
	 * corresponding key or default value.
	 * If $recursive is set to TRUE, then the Arr::search()
	 * function will return a delimiter-notated key using $delimiter.
	 *
	 * @param   array   $array     The search array
	 * @param   mixed   $value     The searched value
	 * @param   string  $default   The default value
	 * @param   bool    $recursive Whether to get keys recursive
	 * @param   string  $delimiter The delimiter, when $recursive is TRUE
	 * @param   bool    $strict    If TRUE, do a strict key comparison
	 * @return  mixed
	 */
	public function search($array, $value, $default = NULL, $recursive = TRUE, $delimiter = '.', $strict = FALSE)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		if ( ! is_NULL($default) && ! is_int($default) && ! is_string($default))
		{
			throw new InvalidArgumentException('Expects parameter 3 to be an string or integer or NULL.');
		}

		if ( ! is_string($delimiter))
		{
			throw new InvalidArgumentException('Expects parameter 5 must be an string.');
		}

		$key = array_search($value, $array, $strict);

		if ($recursive && $key === FALSE)
		{
			$keys = array();
			foreach ($array as $k => $v)
			{
				if (is_array($v))
				{
					$rk = $this->search($v, $value, $default, TRUE, $delimiter, $strict);
					if ($rk !== $default)
					{
						$keys = array($k, $rk);
						break;
					}
				}
			}
			$key = count($keys) ? implode($delimiter, $keys) : FALSE;
		}

		return $key === FALSE ? $default : $key;
	}

	/**
	 * Returns only unique values in an array. It does not sort. First value is used.
	 *
	 * @param   array  $arr       the array to dedup
	 * @return  array   array with only de-duped values
	 */
	public function unique($arr)
	{
		// filter out all duplicate values
		return array_filter($arr, function($item)
		{
			// contrary to popular belief, this is not as static as you think...
			static $vars = array();

			if (in_array($item, $vars, TRUE))
			{
				// duplicate
				return FALSE;
			}
			else
			{
				// record we've had this value
				$vars[] = $item;

				// unique
				return TRUE;
			}
		});
	}

	/**
	 * Calculate the sum of an array
	 *
	 * @param   array   $array  the array containing the values
	 * @param   string  $key    key of the value to pluck
	 * @return  number          the sum value
	 */
	public function sum($array, $key)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		return array_sum($this->pluck($array, $key));
	}

	/**
	 * Returns the array with all numeric keys re-indexed, && string keys untouched
	 *
	 * @param   array  $arr       the array to reindex
	 * @return  array  re-indexed array
	 */
	public function reindex($arr)
	{
		// reindex this level
		$arr = array_merge($arr);

		foreach ($arr as $k => &$v)
		{
			is_array($v) && $v = $this->reindex($v);
		}

		return $arr;
	}

	/**
	 * Get the previous value or key from an array using the current array key
	 *
	 * @param   array    $array      the array containing the values
	 * @param   string   $key        key of the current entry to use as reference
	 * @param   bool     $get_value  if TRUE, return the previous value instead of the previous key
	 * @param   bool     $strict     if TRUE, do a strict key comparison
	 *
	 * @return  mixed  the value in the array, NULL if there is no previous value, or FALSE if the key doesn't exist
	 */
	public function previous_by_key($array, $key, $get_value = FALSE, $strict = FALSE)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		// get the keys of the array
		$keys = array_keys($array);

		// && do a lookup of the key passed
		if (($index = array_search($key, $keys, $strict)) === FALSE)
		{
			// key does not exist
			return FALSE;
		}

		// check if we have a previous key
		elseif ( ! isset($keys[$index-1]))
		{
			// there is none
			return NULL;
		}

		// return the value or the key of the array entry the previous key points to
		return $get_value ? $array[$keys[$index-1]] : $keys[$index-1];
	}

	/**
	 * Get the next value or key from an array using the current array key
	 *
	 * @param   array    $array      the array containing the values
	 * @param   string   $key        key of the current entry to use as reference
	 * @param   bool     $get_value  if TRUE, return the next value instead of the next key
	 * @param   bool     $strict     if TRUE, do a strict key comparison
	 *
	 * @return  mixed  the value in the array, NULL if there is no next value, or FALSE if the key doesn't exist
	 */
	public function next_by_key($array, $key, $get_value = FALSE, $strict = FALSE)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		// get the keys of the array
		$keys = array_keys($array);

		// && do a lookup of the key passed
		if (($index = array_search($key, $keys, $strict)) === FALSE)
		{
			// key does not exist
			return FALSE;
		}

		// check if we have a previous key
		elseif ( ! isset($keys[$index+1]))
		{
			// there is none
			return NULL;
		}

		// return the value or the key of the array entry the previous key points to
		return $get_value ? $array[$keys[$index+1]] : $keys[$index+1];
	}

	/**
	 * Get the previous value or key from an array using the current array value
	 *
	 * @param   array    $array      the array containing the values
	 * @param   string   $value      value of the current entry to use as reference
	 * @param   bool     $get_value  if TRUE, return the previous value instead of the previous key
	 * @param   bool     $strict     if TRUE, do a strict key comparison
	 *
	 * @return  mixed  the value in the array, NULL if there is no previous value, or FALSE if the key doesn't exist
	 */
	public function previous_by_value($array, $value, $get_value = TRUE, $strict = FALSE)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		// find the current value in the array
		if (($key = array_search($value, $array, $strict)) === FALSE)
		{
			// bail out if not found
			return FALSE;
		}

		// get the list of keys, && find our found key
		$keys = array_keys($array);
		$index = array_search($key, $keys);

		// if there is no previous one, bail out
		if ( ! isset($keys[$index-1]))
		{
			return NULL;
		}

		// return the value or the key of the array entry the previous key points to
		return $get_value ? $array[$keys[$index-1]] : $keys[$index-1];
	}

	/**
	 * Get the next value or key from an array using the current array value
	 *
	 * @param   array    $array      the array containing the values
	 * @param   string   $value      value of the current entry to use as reference
	 * @param   bool     $get_value  if TRUE, return the next value instead of the next key
	 * @param   bool     $strict     if TRUE, do a strict key comparison
	 *
	 * @return  mixed  the value in the array, NULL if there is no next value, or FALSE if the key doesn't exist
	 */
	public function next_by_value($array, $value, $get_value = TRUE, $strict = FALSE)
	{
		if ( ! is_array($array) && ! $array instanceof ArrayAccess)
		{
			throw new InvalidArgumentException('First parameter must be an array or ArrayAccess object.');
		}

		// find the current value in the array
		if (($key = array_search($value, $array, $strict)) === FALSE)
		{
			// bail out if not found
			return FALSE;
		}

		// get the list of keys, && find our found key
		$keys = array_keys($array);
		$index = array_search($key, $keys);

		// if there is no next one, bail out
		if ( ! isset($keys[$index+1]))
		{
			return NULL;
		}

		// return the value or the key of the array entry the next key points to
		return $get_value ? $array[$keys[$index+1]] : $keys[$index+1];
	}

	/**
	 * Return the subset of the array defined by the supplied keys.
	 *
	 * Returns $default for missing keys, as with Arr::get()
	 *
	 * @param   array    $array    the array containing the values
	 * @param   array    $keys     list of keys (or indices) to return
	 * @param   mixed    $default  value of missing keys; default NULL
	 *
	 * @return  array  An array containing the same set of keys provided.
	 */
	public function subset(array $array, array $keys, $default = NULL)
	{
		$result = array();

		foreach ($keys as $key)
		{
			$this->set($result, $key, $this->get($array, $key, $default));
		}

		return $result;
	}

	/**
	 * Takes a value && checks if it is a Closure or not, if it is it
	 * will return the result of the closure, if not, it will simply return the
	 * value.
	 *
	 * @param   mixed  $var  The value to get
	 * @return  mixed
	 */
	protected function value($var)
	{
		return ($var instanceof Closure) ? $var() : $var;
	}
}
