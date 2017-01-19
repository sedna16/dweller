<?php

namespace dweller;

use dweller\processors\helper as helper;

class querybuilder {
	
	private $statement;
	public $values;
	public $type;
	public $table;
	public $result;

	/*
	* construct for identifying what table will be used
	* @param - string, table name
	* no return
	*
	*
	*/
	public function __construct($table) {
		$this->table = $table;
	}

	/*
	* function to setup 'select' as the main query
	* @param - string, fields wanted to be selected, commonly used is '*'
	* @return - this, for method chaining
	*
	*
	*/
	public function select($v) {
		$this->statement .= "SELECT " . $v . " FROM " . $this->table;
		return $this;
	}

	/*
	* function to identify the type of values to be used
	* @param - anything
	* @return - the type of variable used
	*
	*
	*/
	private function type($var) {
		$type = gettype($var);
		$return = '';

		if($type == 'integer') { $return = 'i'; }
		if($type == 'boolean') { $return = 'b'; }
		if($type == 'array') { $return = 'a'; }
		if($type == 'string') { $return = 's'; }
		if($type == 'double') { $return = 'd'; }
		if($type == 'object') { $return = 'o'; }
		if($type == 'resource') { $return = 'r'; }
		if($type == null) { $return = 'n'; }

		return $return;
	}

	/*
	* function to add the type and value to the class variable
	* @param - string, variable type
	* @param2 - anythingy, the value
	* no return
	*
	*/
	private function setothers($type,$value) {
		$this->result['type'] = $type;
		$this->result['values'] = $value;
	}

	/*
	* function to setup the where clause in an sql statement 
	* @param - array, array keys are the columns and array values are the sql values which will be represented by a '?'
	* @return - this, for method chaining
	*
	*
	*/
	function where($a) {
		if(count($a) > 1) {

			$this->statement .= " WHERE ";
			foreach ($a as $k => $v) {
				$this->statement .= $k . " = ? AND ";
				$this->values[] = $v;
				//$this->type .= 's';
				$this->type .= self::type($v);
				self::setothers($this->type,$this->values);
			}
			$this->statement = rtrim($this->statement, " AND");
		}
		else {
			$key = helper::removeArrayValue($a);
			$v = helper::removeArrayKey($a);
			$this->statement .= " WHERE " . $key[0] . " = ?";
			$this->values[] = $v[0];
			//$this->type .= 's';
			$this->type .= self::type($v[0]);
			self::setothers($this->type,$this->values);
		}

		return $this;
	}

	/*
	* function to setup the where not clause in an sql statement 
	* @param - array, array keys are the columns and array values are the sql values which will be represented by a '?'
	* @return - this, for method chaining
	*
	*
	*/
	function wherenot($a) {
		if(count($a) > 1) {

			$this->statement .= " WHERE ";
			foreach ($a as $k => $v) {
				$this->statement .= $k . " != ? AND ";
				$this->values[] = $v;
				//$this->type .= 's';
				$this->type .= self::type($v);
				self::setothers($this->type,$this->values);
			}
			$this->statement = rtrim($this->statement, " AND");
		}
		else {
			$k = helper::removearrayvalue($a);
			$v = helper::removearraykey($a);
			$this->statement .= " WHERE " . $k[0] . " != ?";
			$this->values[] = $v[0];
			//$this->type .= 's';
			$this->type .= self::type($v[0]);
			self::setothers($this->type,$this->values);
		}

		return $this;
	}

	/*
	* function to insert an 'AND clause in the sql statement'
	* @param - string, column
	* @param2 - anything, value
	* @return - this, for method chaining
	*
	*/
	function more($c,$v) {
		$this->statement .= " AND";
		return $this;
	}

	/*
	* function to setup the like clause in an sql statement 
	* @param - array, array keys are the columns and array values are the sql values which will be represented by a '?'
	* @return - this, for method chaining
	*
	*
	*/
	function like($a) {
		if(count($a) > 1) {

			$this->statement .= " WHERE ";
			foreach ($a as $k => $v) {
				$this->statement .= $k . " LIKE ? AND ";
				$this->values[] = "%" . $v . "%";
				//$this->type .= 's';
				$this->type .= self::type($v);
				self::setothers($this->type,$this->values);
			}
			$this->statement = rtrim($this->statement, " AND");
		}
		else {
			$k = helper::removearrayvalue($a);
			$v = helper::removearraykey($a);
			$this->statement .= " WHERE " . $k[0] . " LIKE ?";
			$this->values[] = "%" . $v[0] . "%";
			//$this->type .= 's';
			$this->type .= self::type($v[0]);
			self::setothers($this->type,$this->values);
		}

		return $this;
	}

	/*
	* function to setup the like not clause in an sql statement 
	* @param - array, array keys are the columns and array values are the sql values which will be represented by a '?'
	* @return - this, for method chaining
	*
	*
	*/
	function likenot($a) {
		if(count($a) > 1) {

			$this->statement .= " WHERE ";
			foreach ($a as $k => $v) {
				$this->statement .= $k . " NOT LIKE ? AND ";
				$this->values[] = $v;
				//$this->type .= 's';
				$this->type .= self::type($v);
				self::setothers($this->type,$this->values);
			}
			$this->statement = rtrim($this->statement, " AND");
		}
		else {
			$k = helper::removearrayvalue($a);
			$v = helper::removearraykey($a);
			$this->statement .= " WHERE " . $k[0] . " NOT LIKE ?";
			$this->values[] = $v[0];
			//$this->type .= 's';
			$this->type .= self::type($v[0]);
			self::setothers($this->type,$this->values);
		}

		return $this;
	}

	/*
	* function to setup order to which the rows will appear
	* @param - array, array key is the column and array values can either be ASC or DESC
	* @return - this, for method chaining
	*
	*
	*/
	function order($array) {

		$this->statement .= " ORDER BY";
		$order = '';
		foreach ($array as $k => $v) {
			$order .= ' ' . $k . ' ' . $v . ',';
		}
		$this->statement .= rtrim($order, ",");

		return $this;
	}

	/*
	* function to setup the oldest row to show up first
	* @param - string, column
	* @return - this, for method chaining
	*
	*
	*/
	function first($o) {
		$this->statement .= " ORDER BY " . $o . ' asc';
		return $this;
	}

	/*
	* function to setup the latest row to show up first
	* @param - string, column
	* @return - this, for method chaining
	*
	*
	*/
	function latest($o) {
		$this->statement .= " ORDER BY " . $o . ' desc';
		return $this;
	}

	/*
	* function to limit the results to a chunk
	* @param - int, number of row you want to show
	* @param2 - int, the offset - where will the count start
	* @return - this, for method chaining
	*
	*/
	function chunk($num,$page = 0) {
		$this->statement .= " LIMIT " . $num . " OFFSET " . $page;
		return $this;
	}

	/*
	* (pending decision) to delete or not to delete
	*
	*
	*
	*
	*/
	function limit($num) {
		$this->statement .= " LIMIT " . $num;
		return $this;
	}

	/*
	* (pending decision) to delete or not to delete
	*
	*
	*
	*
	*/
	function offset($n) {
		$this->statement .= " OFFSET " . $n;
		return $this;
	}

	/*
	* function to setup 'insert' as the main query
	* @param - array, array keys are the columns and the array value are the insert values
	* @return - this, for method chaining
	*
	*
	*/
	function insert($array) {
		$this->statement .= "INSERT INTO " . $this->table ."("; 

		foreach ($array as $k => $v) {
			$this->statement .= $k . ',';
			$this->values[] = $v;
			//$this->type .= 's';
			$this->type .= self::type($v);
			self::setothers($this->type,$this->values);
		}
		$this->statement = rtrim($this->statement, ",") . ") VALUES (";

		foreach ($array as $key => $value) {
			$this->statement .= '?,';
		}
		$this->statement = rtrim($this->statement, ",") . ")";

		return $this;
	}

	/*
	* function to setup 'update' as the main query
	* @param - array, array keys are the columns and the array value are the insert values
	* @return - this, for method chaining
	*
	*
	*/
	function update($array) {
		$this->statement .= "UPDATE " . $this->table . " SET ";
		$set = '';
		foreach ($array as $k => $v) {
			$set .= $k . '=?,';
			$this->values[] = $v;
			//$this->type .= 's';
			$this->type .= self::type($v);
			self::setothers($this->type,$this->values);
		}

		$this->statement .= rtrim($set, ",");

		return $this;
	}

	/*
	* function to setup 'delete' as the main query
	* no param
	* @return - this, for method chaining
	*
	*
	*/
	function delete() {
		$this->statement .= "DELETE FROM " . $this->table . '';

		return $this;
	}

	/*
	* end of the chain - function to retrieve the statement, the variable types and the values
	* no param
	* @return - sql statement, variable type, and values
	*
	*
	*/
	function get() {
		$this->result['sql'] = $this->statement;
		return $this->result;
	}
}

?>
