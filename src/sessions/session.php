<?php  

namespace dweller;

use dweller\app as app;
use dweller\processors\helper as helper;

class session {

	static public $token;
	static private $time = 720; // 30 days

	/*
	*
	* create a session field
	* @param - creates the field key with 'dw_' before it
	* @param2 - value of the session field
	*
	*/
	public function add($field,$value) {
		
		$_SESSION['dw_' . $field] = $value;
	}

	/*
	*
	*
	* session::token();
	*
	*
	*/
	public function token() {

		self::add('token',bin2hex(helper::randomstring(50,4)));
	}

	/*
	*
	* setup session timestamp, to have a reference for timeout
	* no param
	* no return
	*
	*/
	public function timestamp() {

		self::add('timestamp',time());
	}

	/*
	*
	* check if the session has timedout
	* @param - optional, the timedout duration, in hours
	* @return - true or false
	*
	*/
	public function is_timedout($time = null) {

		$timeout = false;

		// if @param is empty
		if(!isset($time) || $time == null || $time == '') {
			// set default time to 24 hours
			$time = self::$time; 
		}

		// convert hours to minutes
		$time * 60; 

		// value to check against current time
		$checkthis = $_SESSION['dw_timestamp'] + 24 * 60;

		// check if $check this is less than the current time, then timeout
		if ($checkthis < time()) { 
			$timeout = true; 
		}

		return $timeout;
	}

	/*
	*
	* checks if the session is safe from exploits
	* no param
	* @return - true or false
	*
	*/
	public function is_safe() {

		// check if session ipaddress or session useragent is empty
		if(!isset($_SESSION['dw_ipaddress']) || !isset($_SESSION['dw_useragent'])) {
			return false;
		}

		// check if session ipaddress is not the same is the server address
		if ($_SESSION['dw_ipaddress'] != $_SERVER['REMOTE_ADDR']) {
			return false;
		}

		// check if session useragent is not the same as the useragent the server detects
		if( $_SESSION['dw_useragent'] != $_SERVER['HTTP_USER_AGENT']) {
			return false;
		}

		// check if session token is empty
		if(!isset($_SESSION['dw_token']) || $_SESSION['dw_token'] == '' || $_SESSION['dw_token'] == null) {
			return false;
		}

		return true;
	}

	/*
	*
	* 
	* session::start();
	*
	*
	*/
	public function start() {

		if (!session_id()) {
		    session_start();
		}
		if(!self::is_safe()) {
			$_SESSION['dw_ipaddress'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['dw_useragent'] = $_SERVER['HTTP_USER_AGENT'];
			session_name("DwellerApp-needs+change");
			self::token();
			self::timestamp();
		}
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function login($url,$user,$pic,$pic) {
		self::token();
		self::timestamp();
		self::add('loggedin',true);
		self::add('url',$url);
		self::add('user',$user);
		self::add('pic',$email);
		self::add('status',$status);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function is_loggedin() {
		$loggedin = false;

		if(self::is_safe()) {
			if(isset($_SESSION['dw_loggedin']) || isset($_SESSION['dw_user']) || isset($_SESSION['dw_email']) || isset($_SESSION['dw_pic'])) {
				$loggedin = true;
			}
		}

		return $loggedin;
	}

	/*
	*
	*
	* session::end();
	*
	*
	*/
	public function end() {
		$_SESSION = array();
		session_destroy();
	}
}

?>
