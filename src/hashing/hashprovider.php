<?php

namespace dweller\hashing;

use dweller\processors\helper as helper;

class hashprovider {

	/*
	*
	*
	*
	*
	*
	*/
	private function option($salt) {
		return  [
			'salt' => $salt, // need proper salt function here
			'cost' => 12 // default cost is 10
		];
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function create($data,$salt) {

		helper::checkPHPversion();

		return password_hash($data, PASSWORD_DEFAULT, self::option($salt));
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function recreate($data,$salt) {

		helper::checkPHPversion();

		return password_needs_rehash($data, PASSWORD_DEFAULT, self::option($salt));
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function verify($data,$hash) {

		helper::checkPHPversion();

		return password_verify($data, $hash);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function getOptions($hash) {

		helper::checkPHPversion();

		return password_get_info($hash);
	}
}

?>
