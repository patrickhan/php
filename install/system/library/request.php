<?php
final class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
	public function __construct() {
		$_SERVER = $this->clean($_SERVER);
		$_GET = $this->clean($_GET);
		$_POST = $this->clean($_POST);
		$_COOKIE = $this->clean($_COOKIE);
		$_FILES = $this->clean($_FILES);
		
		$this->get = $_GET;
		$this->post = $_POST;
		$this->cookie = $_COOKIE;
		$this->files = $_FILES;
		$this->server = $_SERVER;
	}
	
	public function clean($data) {
		$result = array();
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$result[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$result = htmlentities($data, ENT_QUOTES, 'UTF-8');
		}
		
		return $result;
	}
}
?>