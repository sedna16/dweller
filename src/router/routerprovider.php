<?php

namespace dweller\router;

use dweller\router as router;

class routeprovider {

	static protected $routes;
	static protected $fallback;
	static private $wildcards = [
		'{id}' 			=> '([0-9]+)', // digit only, single or more
		'{name}' 		=> '(.*\S\D)', // word only
		'{any}' 		=> '(.*\S)' // alpha and num
	];

	/*
	* settle in homes
	* @param - array
	* no return
	*
	*
	*/
	public function set($a) {
		self::$routes = $a;
	}

	/*
	* sets the default fallback uri
	* @param - the uri to set as fallback e.g. '/404'
	* no return
	*
	*
	*/
	public function setfallback($p) {
		self::$fallback = $p;
	}

	/*
	* retrieves the fallback uri string
	* no param
	* @return - fallback string e.g. '/404'
	*
	*
	*/
	public function getfallback() {
		return self::$fallback;
	}

	/*
	* transform the pattern into regex ready string
	* @param - the pattern from the routes
	* @return - regex ready string
	*
	*
	*/
	private function getregex($s) {
		foreach (self::$wildcards as $k => $v) {
			$s = str_replace($k, $v, $s);
		}
		$s = str_replace('/', '\/', $s);
		$s = '/' . $s . '/';
		return $s;
	}

	private function matchRegex($s) {

	}

	/*
	* searches for a match for the @param
	* @param - string, for uri
	* @return - returns either false or an array('template'=>'file','controller'=>'file');
	*
	*
	*/
	public function matchuri($uri) {

		$matchuri = false;
		$uri = '/' . $uri;
		$uriArray = explode('/', $uri);
		array_shift($uriArray);
		if(!isset($uriArray[0]) || $uriArray[0] == '') { $uriArray[0] = '/'; }
		$uriCount = count($uriArray);

		foreach (self::$routes as $pattern => $callbacks) {

			$matchCount = 0;

			$patternArray = explode('/',$pattern);
			array_shift($patternArray);
			if(!isset($patternArray[0]) || $patternArray[0] == '') { $patternArray[0] = '/'; }
			$patternCount = count($patternArray);

			if($uriCount == $patternCount) {

				$regex = self::getregex($pattern);
				$preg = preg_match($regex, $uri, $match);

				if($preg) {

					if($match[0] == $uri) {
						
						$matchuri['template'] = self::$routes[$pattern]['template'];
						$matchuri['controller'] = self::$routes[$pattern]['controller'];
						$matchuri['pattern'] = $pattern;
					}
					else {
						$matchuri = false;
					}
				}
			}
		}

		return $matchuri;
	}
}

?>
