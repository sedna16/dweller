<?php

namespace dweller;

use dweller\app as app;
use dweller\router\routeprovider as routeprovider;

class router {

	static private $uri;
	static protected $rootURI;
	static protected $rootPATH;

	static public $template;
	static public $controller;
	static public $pattern;

	/*
	*
	*
	*
	*
	*
	*/
	public function setrootURI($v) {
		self::$rootURI = $v;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function setrootPATH($v) {
		self::$rootPATH = $v;
	}

	/*
	* retrieves the current uri
	* no param
	* @return - uri string e.g. '/blog/1'
	*
	*
	*/
	public function uri() {
		if(isset($_GET['dwellerurl'])) {
			$uri = filter_var(rtrim($_GET['dwellerurl'], '/'), FILTER_SANITIZE_URL);
			self::$uri = $uri;
			unset($_GET['dwellerurl']);
			return $uri;
		}
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function uriArray() {
		$uri = self::$uri;
		return explode('/', $uri);
	}
	
	/*
	* setup routers
	* @param - array = ['/' => ['template' => 'file.php', 'controller' => 'file.php']]
	* no return
	*
	*
	*/
	public function config($a) {
		routeprovider::set($a);
	}

	/*
	* setup default fallback uri
	* @param - pattern. e.g. '/404'
	* no return
	*
	*
	*/
	public function fallback($p) {
		routeprovider::setfallback($p);
	}

	/*
	* start routing the uri and files
	* no param
	* no return
	*
	*
	*/
	public function run() {

		$uri = self::uri();
		$match = routeprovider::matchuri($uri);

		if($match) {
			self::$template = $match['template'];
			self::$controller = $match['controller'];
		}
		else {
			app::redirectTo(self::$rootURI . routeprovider::getfallback());
		}
	}
}

?>
