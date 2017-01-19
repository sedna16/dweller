<?php

/*
*
*
* subject for changes, asap
*
*
*/

namespace dweller\router;

class routeprovider {

	static protected $routes;
	static protected $fallback;
	static private $wildcards = [
		//'{num}' 			=> '(.*[0-7])', 
		'{num}' 			=> '([0-9]+)', // digit only, single or more
		'{string}' 			=> '(.*\S\D)', // word only
		'{varchar}' 		=> '(.*\S)' // alpha and num
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

		if(array_key_exists($uri, self::$routes)) {
			$matchuri['template'] = self::$routes[$uri]['template'];
			$matchuri['controller'] = self::$routes[$uri]['controller'];
			$matchuri['pattern'] = $uri;
		}
		else {
			array_shift(self::$routes);
			foreach (self::$routes as $pattern => $callbacks) {

				$regex = self::getregex($pattern);
				$preg = preg_match($regex, $uri, $match);

				if($preg) {

					if($match[0] == $uri) {
						$matchuri['template'] = self::$routes[$pattern]['template'];
						$matchuri['controller'] = self::$routes[$pattern]['controller'];
						$matchuri['pattern'] = $pattern;
					}
				}
			}
		}

		return $matchuri;
	}
}

?>
