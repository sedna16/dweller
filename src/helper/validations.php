<?php 

namespace dweller\helper;

class validate {

	/*
	*
	* checks any variable if its not isset, or '', or empty()
	* @param - $data is the variable
	* @return - true or false
	*
	*/
	public function if_empty($data) {

		if(!isset($data) || $data == '' || 	empty($data) || $data == null) {
			return true;
		}
		else {
			return false;
		}
	}

	/*
	*
	* checks whether the data submitted is the same as the token, to avoid csrf
	* @param - $data is the token value from the form
	* @return - has_equals value or false
	*
	*/
	public function _token($data) {
		if(!self::if_empty($_SESSION['dw_token'])) {
			return hash_equals($data, $_SESSION['dw_token']);
		}
		else {
			self::token();
			return false;
		}
	}

	/*
	*
	* checks whether the data is a valid username
	* @param - $data is the username variable
	* @return - true or false
	*
	*/
	public function _username($data) {

		//$preg = "/^[a-zA-Z0-9_]+$/";
		$preg = "/^[A-Za-z][A-Za-z0-9]*(?:_+[A-Za-z0-9]+)*$/";
		if(preg_match($preg,$data)) {
			return true;
		}
		else {
			return false;
		}
	}

	/*
	*
	* checks whether the data is a valid email
	* @param - $data is the email variable
	* @return - true or false
	*
	*/
	public function _email($data) {

		if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		else {
			return false;
		}
	}

	/*
	*
	* checks whether the data is a valid name
	* @param - $data is the name variable
	* @return - true or false
	*
	*/
	public function _name($data) {

		if(preg_match("/^[a-zA-Z ]*$/",$data)) {
			return true;
		}
		else {
			return false;
		}
	}

	/*
	*
	* checks whether the data is a valid url
	* @param - $data is the url variable
	* @return - true or false
	*
	*/
	public function _url($data) {
		
		if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$data)) {
			return true;
		}
		else {
			return false;
		}
	}
}

?>
