<?php
class ControllerStep2 extends Controller {
	private $error = array();
	
	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->redirect($this->url->http('step_3'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['action'] = $this->url->http('step_2');
		
		$this->data['config_catalog'] = DIR_JSICART . 'config.php';
		$this->data['config_admin'] = DIR_JSICART . 'admin/config.php';
		$this->data['phpini_catalog'] = DIR_JSICART . 'php.ini';
		$this->data['phpini_admin'] = DIR_JSICART . 'admin/php.ini';
		
		$this->data['cache'] = DIR_SYSTEM . 'cache';
		$this->data['logs'] = DIR_SYSTEM . 'logs';
		$this->data['image'] = DIR_JSICART . 'image';
		$this->data['image_cache'] = DIR_JSICART . 'image/cache';
		$this->data['image_data'] = DIR_JSICART . 'image/data';
		$this->data['download'] = DIR_JSICART . 'download';
		
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->template = 'step_2.tpl';
		
		$this->response->setOutput($this->render(TRUE));
	}
	
	private function validate() {
		if (phpversion() < '5.0') {
			$this->error['warning'] = 'Warning: You need to use PHP5 or above for JSI-Cart to work!';
		}
		
		if ( ! ini_get('file_uploads')) {
			$this->error['warning'] = 'Warning: file_uploads needs to be enabled!';
		}
		
		if (ini_get('session.auto_start')) {
			$this->error['warning'] = 'Warning: JSI-Cart will not work with session.auto_start enabled!';
		}
		
		if ( ! extension_loaded('mysql')) {
			$this->error['warning'] = 'Warning: MySQL extension needs to be loaded for JSI-Cart to work!';
		}
		
		if ( ! extension_loaded('gd')) {
			$this->error['warning'] = 'Warning: GD extension needs to be loaded for JSI-Cart to work!';
		}
		
		if ( ! extension_loaded('zlib')) {
			$this->error['warning'] = 'Warning: ZLIB extension needs to be loaded for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'config.php')) {
			$this->error['warning'] = 'Warning: config.php needs to be writable for JSI-Cart to be installed!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'admin/config.php')) {
			$this->error['warning'] = 'Warning: admin/config.php needs to be writable for JSI-Cart to be installed!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'php.ini')) {
			$this->error['warning'] = 'Warning: php.ini needs to be writable for JSI-Cart to be installed!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'admin/php.ini')) {
			$this->error['warning'] = 'Warning: admin/php.ini needs to be writable for JSI-Cart to be installed!';
		}
		
		if ( ! is_writable(DIR_SYSTEM . 'cache')) {
			$this->error['warning'] = 'Warning: Cache directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_SYSTEM . 'logs')) {
			$this->error['warning'] = 'Warning: Logs directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'image')) {
			$this->error['warning'] = 'Warning: Image directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'image/cache')) {
			$this->error['warning'] = 'Warning: Image cache directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'image/data')) {
			$this->error['warning'] = 'Warning: Image data directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'download')) {
			$this->error['warning'] = 'Warning: Download directory needs to be writable for JSI-Cart to work!';
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>