<?php

namespace dweller;

use dweller\processors\helper as helper;

class scope {

	/*
	*
	*
	*
	*
	*
	*/
	static public $get;

	/*
	*
	*
	*
	*
	*
	*/
	public function set($key,$value) {
		self::$get[$key] = $value;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function get($key) {
		if(!helper::if_empty(self::$get[$key])) {
			return self::$get[$key];
			print_r(self::$get);
		}
	}
}

?>
