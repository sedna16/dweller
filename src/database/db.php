
<?php 

namespace dweller;

use dweller\querybuilder as querybuilder;

class db {
	
	static private $db_host;
	static private $db_username;
	static private $db_password;
	static private $db_database;
	static private $connection;

	static private $type;
	static private $values;
	static private $sql;
	static private $mysqli;
	static private $stmt;
	static public $row;
	static public $result;

	/*
	*
	*
	*
	*
	*
	*/
	public function config($host,$db,$user,$pass) {
		self::$db_host = $host;
		self::$db_database = $db;
		self::$db_username = $user;
		self::$db_password = $pass;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function table($t) {
		$q = new querybuilder($t);
		return $q;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function setup($array) {
		self::$db_host = $array['host'];
		self::$db_database = $array['database'];
		self::$db_username = $array['user'];
		self::$db_password = $array['password'];
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function mysqli() {

		return new \mysqli(self::$db_host, self::$db_username, self::$db_password, self::$db_database);
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function connect() {

		if (self::mysqli()->connect_error) {
			die('Failed to connect to MySQL');
		}
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function disconnect() {

		return self::mysqli()->close();
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function clean($data) {

		return ultima::clean($data);
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function bind_result_array($stmt) {
	    $meta = $stmt->result_metadata();
	    $result = array();
	    while ($field = $meta->fetch_field())
	    {
	        $result[$field->name] = NULL;
	        $params[] = &$result[$field->name];
	    }
	    call_user_func_array(array($stmt, 'bind_result'), $params);
	    return $result;
	}

	/*
	*
	*
	*
	*
	*
	*/
	private function bind_values($types,$array) {

		// setup bind arguments
		$bind_names[] = $types; 
        for ($i = 0; $i < count($array); $i++) { 
            $bind_name = 'bind' . $i;
            $$bind_name = $array[$i];
            $bind_names[] = &$$bind_name;
        }
        return $bind_names;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function query($statement) {
		self::$sql 			= $statement['sql'];
		if(isset($statement['type'])) { self::$type = $statement['type']; }
		if(isset($statement['values'])) { self::$values = $statement['values']; }
		self::$mysqli 	= self::mysqli();
		self::$stmt 	= self::$mysqli->prepare(self::$sql);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function bind() {
		call_user_func_array(array( self::$stmt, 'bind_param'), self::bind_values(self::$type,self::$values));
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function row() {
		self::$row = self::bind_result_array(self::$stmt);
	}

	/*
	*
	*
	* return true or false
	*
	*
	*/
	public function execute() {
		if(self::$stmt->execute()) {
			self::$result = true;
		}
		else {
			self::$result = false;
		}
		return self::$result;
	}

	/*
	*
	*
	* return array values or false
	*
	*
	*/
	public function fetch() {	
		self::$stmt->execute();
		self::$stmt->store_result();
		if(self::$stmt->num_rows > 0) {
			self::$stmt->fetch();
			self::$result = self::$row;
			return self::$result;
		}
		else {
			self::$result = false;
			return self::$result;
		}
	}

	/*
	*
	*
	* return array values or false
	*
	*
	*/
	public function fetchall() {
		self::$stmt->execute();
		self::$stmt->store_result();

		if(self::$stmt->num_rows > 0) {
			while (self::$stmt->fetch()) {
				$sub = array();
				foreach (self::$row as $key => $value) {
					$sub[] = $value;
				}
				$new[] = $sub;
			}
			self::$result = $new;
			return self::$result;
		}
		else {
			self::$result = false;
			return self::$result;
		}
	}

	/*
	*
	*
	* returns numbers
	*
	*
	*/
	public function count() {
		self::$stmt->execute();
		self::$stmt->store_result();
		self::$result = self::$stmt->num_rows;
		return self::$result;
	}

	/*
	*
	*
	* 
	*
	*
	*/
	public function stop() {
		self::$stmt->free_result();
		self::$stmt->close();
	}
}

?>
