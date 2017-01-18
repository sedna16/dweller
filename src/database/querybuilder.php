<?php

/*
*
* Needs to change a lot of things, but still working
*
*/

namespace dweller;

use dweller\processors\helper as helper;

class querybuilder {
	
	private $statement;
	public $values;
	public $type;
	public $table;
	public $result;

	/*
	*
	*
	*
	*
	*
	*/
	public function __construct($table) {
		$this->table = $table;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function select($v) {
		$this->statement .= "SELECT " . $v . " FROM " . $this->table;
		return $this;
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function type($var) {
		$type = gettype($var);
		$return = '';

		if($type == 'integer') { $return = 'i'; }
		if($type == 'boolean') { $return = 'b'; }
		if($type == 'a') { $return = 'a'; }
		if($type == 'string') { $return = 's'; }
		if($type == 'double') { $return = 'd'; }
		if($type == 'object') { $return = 'o'; }
		if($type == 'resource') { $return = 'r'; }
		if($type == null) { $return = 'n'; }

		return $return;
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function setothers($type,$value) {
		$this->result['type'] = $type;
		$this->result['values'] = $value;
	}

	/*
	*
	*
	*
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
	*
	*
	*
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
	*
	*
	*
	*
	*
	*/
	function more($c,$v) {
		$this->statement .= " AND";
		return $this;
	}

	/*
	*
	*
	*
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
	*
	*
	*
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
	*
	*
	*
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
	*
	*
	*
	*
	*
	*/
	function first($o) {
		$this->statement .= " ORDER BY " . $o . ' asc';
		return $this;
	}

	/*
	*
	*
	*
	*
	*
	*/
	function latest($o) {
		$this->statement .= " ORDER BY " . $o . ' desc';
		return $this;
	}

	/*
	*
	*
	*
	*
	*
	*/
	function chunk($num,$page = 0) {
		$this->statement .= " LIMIT " . $num . " OFFSET " . $page;
		return $this;
	}

	/*
	*
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
	*
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
	*
	*
	*
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
	*
	*
	*
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
	*
	*
	*
	*
	*
	*/
	function delete() {
		$this->statement .= "DELETE FROM " . $this->table . '';

		return $this;
	}

	/*
	*
	*
	*
	*
	*
	*/
	function get() {
		$this->result['sql'] = $this->statement;
		return $this->result;
	}
}

?>
