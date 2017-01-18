<?php

namespace dweller\helper;

class exceptions {

	/*
	*
	*
	*
	*
	*
	*/
	public function throwException($msg) {
		throw new \Exception($msg);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function checkPHPversion() {
		if (version_compare(PHP_VERSION, '5.3') < 0) {
	    	self::throwException('Dweller Framework requires PHP 5.3 or above');
	    }
	}
}

?>
