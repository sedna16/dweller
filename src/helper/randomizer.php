<?php

namespace dweller\helper;

class random {

	/*
	*
	* generates random string based on the type (@param2)
	* @param - num, the length of the string
	* @param2 - num, the type of string - numerical, alphabet, alphanum(with uppercase), alphanum (lowercase) 
	* @return - the randomized string value
	*
	*/
	public function string($length = 7,$string = 3) {
		$type['num'] 				= '0123456789';
		$type['alpha'] 				= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$type['alphanum'] 			= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$type['alphanumlower'] 		= '0123456789abcdefghijklmnopqrstuvwxyz';
		switch ($string) {
		    case '1':
		        $characters = $type['num'];
		        break;
		    case '2':
		        $characters = $type['alpha'];
		        break;
		    case '3':
		        $characters = $type['alphanum'];
		        break;
		    case '4':
		        $characters = $type['alphanumlower'];
		        break;
		}
		//$characters = strtolower($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
		    $randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function salt() {
		return uniqid(self::randomstring(64,3));
	}
}

?>
