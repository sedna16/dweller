<?php

namespace dweller\helper;

class cleaner {

	/*
	*
	* removes any unwated tags and scripts to avoid exploits
	* @param - $data is the variable
	* @return - clean date of the variable
	*
	*/
	public function __construct($data) {

		$data = removeJS($data);
		$data = removeHTML($data);
		$data = removeCSS($data);
		$data = removeComments($data);
		$data = cleanString($data);

		return $data;
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function removeJS($data) {

		return preg_replace('@<script[^>]*?>.*?</script>@si', '', $data);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function removeHTML($data) {

		return preg_replace('@<[\/\!]*?[^<>]*?>@si', '', $data);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function removeCSS($data) {

		return preg_replace('@<style[^>]*?>.*?</style>@siU', '', $data);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function removeComments($data) {

		return preg_replace('@<![\s\S]*?--[ \t\n\r]*>@', '', $data);
	}

	/*
	*
	*
	*
	*
	*
	*/
	public function cleanString($data) {

		return mysql_real_escape_string($data);
	}
}


?>
