<?php

namespace dweller;

use dweller\processors\helper as helper;
use dweller\hashing\hashprovider as hashprovider;
use dweller\helper\exceptions as exceptions;

class hash {

	/*
	*
	*
	*
	*
	*
	*/
	static private $cost = 12; // default cost is 10

	/*
	*
	*
	*
	*
	*
	*/
	public function password($data,$salt) {

		return hashprovider::create($data,$salt);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function re($data,$salt) {

		return hashprovider::recreate($data,$salt);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function verify($data,$hash) {

		return hashprovider::verify($data,$hash);
	}
}

?>
