<?php
class ControllerStep3 extends Controller {
	private $error = array();
	
	public function index() {
		$base = str_replace('/install/controller', '', dirname(__FILE__)) . '/';
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('install');
			
			$this->model_install->mysql($this->request->post);
			
			$output = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_JSICART . '\');' . "\n";
			$output .= 'define(\'HTTP_IMAGE\', \'' . HTTP_JSICART . 'image/\');' . "\n\n";
			
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
			$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";
			
			$output .= '// DIR' . "\n";
			
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_JSICART . 'catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_JSICART. 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_JSICART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_JSICART . 'catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_JSICART . 'catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_JSICART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_JSICART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_JSICART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_JSICART . 'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_JSICART . 'system/logs/\');' . "\n\n";
			
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . $this->request->post['db_host'] . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . $this->request->post['db_user'] . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . $this->request->post['db_password'] . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . $this->request->post['db_name'] . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . $this->request->post['db_prefix'] . '\');' . "\n";
			$output .= '?>';
			
			$file = fopen(DIR_JSICART . 'config.php', 'w');
			
			fwrite($file, $output);
			
			fclose($file);
			
			$output = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_JSICART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_JSICART . '\');' . "\n";
			$output .= 'define(\'HTTP_IMAGE\', \'' . HTTP_JSICART . 'image/\');' . "\n\n";
			$output .= 'define(\'HTTP_DOMAIN\', \'' . $this->request->post['domain'] . '\');' . "\n\n";
			
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'\');' . "\n";
			$output .= 'define(\'HTTPS_IMAGE\', \'\');' . "\n\n";
			
			$output .= '// DIR' . "\n";
			
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_JSICART . 'admin/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_JSICART . 'system/\');' . "\n";
			$output .= 'define(\'DIR_DATABASE\', \'' . DIR_JSICART . 'system/database/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_JSICART . 'admin/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_JSICART . 'admin/view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_JSICART . 'system/config/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_JSICART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_JSICART . 'system/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_JSICART . 'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_JSICART . 'system/logs/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', \'' . DIR_JSICART . 'catalog/\');' . "\n\n";
			
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'mysql\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . $this->request->post['db_host'] . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . $this->request->post['db_user'] . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . $this->request->post['db_password'] . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . $this->request->post['db_name'] . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . $this->request->post['db_prefix'] . '\');' . "\n";
			$output .= '?>';
			
			$file = fopen(DIR_JSICART . 'admin/config.php', 'w');
			
			fwrite($file, $output);
			
			fclose($file);
			
			$output = 'magic_quotes_gpc = Off;' . "\n";
			$output .= 'register_globals = Off;' . "\n";
			$output .= 'default_charset = UTF-8;' . "\n";
			$output .= 'memory_limit = 64M;' . "\n";
			$output .= 'max_execution_time = 18000;' . "\n";
			$output .= 'max_upload_filesize = 100M;' . "\n";
			$output .= 'safe_mode = Off;' . "\n";
			$output .= 'mysql.connect_timeout = 20;' . "\n";
			$output .= 'session.use_cookies = On;' . "\n";
			$output .= 'session.use_trans_sid = Off;' . "\n";
			$output .= 'session.gc_maxlifetime = 12000000;' . "\n";
			
			/*
			if ($this->request->post['host'] == '1') {
				$output .= 'zend_extension="/usr/local/Zend/lib/Optimizer-3.3.9/php-5.2.x/ZendOptimizer.so"' . "\n";
			} else if ($this->request->post['host'] == '2') {
				$output .= '; Hostgator Zend Optimizer Settings' . "\n";
				$output .= 'zend_extension="/usr/local/IonCube/ioncube_loader_lin_5.2.so"' . "\n";
				$output .= 'zend_extension_ts="/usr/local/IonCube/ioncube_loader_lin_5.2_ts.so"' . "\n";
				$output .= 'zend_extension_manager.optimizer=/usr/local/Zend/lib/Optimizer-3.3.3' . "\n";
				$output .= 'zend_extension_manager.optimizer_ts=/usr/local/Zend/lib/Optimizer_TS-3.3.3' . "\n";
				$output .= 'zend_optimizer.version=3.3.3' . "\n";
				$output .= 'zend_extension=/usr/local/Zend/lib/ZendExtensionManager.so' . "\n";
				$output .= 'zend_extension_ts=/usr/local/Zend/lib/ZendExtensionManager_TS.so' . "\n";
			} else if ($this->request->post['host'] == '3') {
				$output .= $this->request->post['host_other'] . "\n";
			}
			$output .= 'zend_optimizer.optimization_level=15' . "\n";
			$output .= 'zend_optimizer.enable_loader=1' . "\n";
			$output .= 'zend_optimizer.license_path=' . $base . $this->request->post['license'] . "\n";
			*/
			
			$file = fopen(DIR_JSICART . 'php.ini', 'w');
			
			fwrite($file, $output);
			
			fclose($file);
			
			$file = fopen(DIR_JSICART . 'admin/php.ini', 'w');
			
			fwrite($file, $output);
			
			fclose($file);
			
			$this->redirect($this->url->http('step_4'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['error_domain'])) {
			$this->data['error_domain'] = $this->error['domain'];
		} else {
			$this->data['error_domain'] = '';
		}
		
		if (isset($this->error['error_db_host'])) {
			$this->data['error_db_host'] = $this->error['db_host'];
		} else {
			$this->data['error_db_host'] = '';
		}
		
		if (isset($this->error['db_user'])) {
			$this->data['error_db_user'] = $this->error['db_user'];
		} else {
			$this->data['error_db_user'] = '';
		}
		
		if (isset($this->error['db_name'])) {
			$this->data['error_db_name'] = $this->error['db_name'];
		} else {
			$this->data['error_db_name'] = '';
		}
		
		if (isset($this->error['license'])) {
			$this->data['error_license'] = $this->error['license'];
		} else {
			$this->data['error_license'] = '';
		}
		
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		$this->data['action'] = $this->url->http('step_3');
		
		if (isset($this->request->post['domain'])) {
			$this->data['domain'] = $this->request->post['domain'];
		} else {
			$this->data['domain'] = 'http://';
		}
		
		if (isset($this->request->post['db_host'])) {
			$this->data['db_host'] = $this->request->post['db_host'];
		} else {
			$this->data['db_host'] = 'localhost';
		}
		
		if (isset($this->request->post['db_user'])) {
			$this->data['db_user'] = html_entity_decode($this->request->post['db_user']);
		} else {
			$this->data['db_user'] = '';
		}
		
		if (isset($this->request->post['db_password'])) {
			$this->data['db_password'] = html_entity_decode($this->request->post['db_password']);
		} else {
			$this->data['db_password'] = '';
		}
		
		if (isset($this->request->post['db_name'])) {
			$this->data['db_name'] = html_entity_decode($this->request->post['db_name']);
		} else {
			$this->data['db_name'] = '';
		}
		
		if (isset($this->request->post['db_prefix'])) {
			$this->data['db_prefix'] = html_entity_decode($this->request->post['db_prefix']);
		} else {
			$this->data['db_prefix'] = '';
		}
		
		if (isset($this->request->post['license'])) {
			$this->data['license'] = $this->request->post['license'];
		} else {
			$this->data['license'] = '';
		}
		
		if (isset($this->request->post['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = 'admin';
		}
		
		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['host'])) {
			$this->data['host'] = $this->request->post['host'];
		} else {
			$this->data['host'] = '';
		}
		
		if (isset($this->request->post['host_other'])) {
			$this->data['host_other'] = $this->request->post['host_other'];
		} else {
			$this->data['host_other'] = '';
		}
		
		$this->data['base'] = substr((implode("/", (explode('/', $_SERVER["REQUEST_URI"], -1))) . '/'), 0, -1);
		
		$this->data['base'] = $base;
		
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->template = 'step_3.tpl';
		
		$this->response->setOutput($this->render(TRUE));
	}
	
	private function validate() {
		if ( ! $this->request->post['db_host']) {
			$this->error['db_host'] = 'Host required!';
		}
		
		if ( ! $this->request->post['db_user']) {
			$this->error['db_user'] = 'User required!';
		}
		
		if ( ! $this->request->post['db_name']) {
			$this->error['db_name'] = 'Database Name required!';
		}
		
		/*
		if ( ! $this->request->post['license']) {
			$this->error['license'] = 'Product license path required!';
		}
		*/
		
		if ( ! $this->request->post['domain']) {
			$this->error['domain'] = 'Domain required!';
		}
		
		if ( ! $this->request->post['username']) {
			$this->error['username'] = 'Username required!';
		}
		
		if ( ! $this->request->post['password']) {
			$this->error['password'] = 'Password required!';
		}
		
		$pattern = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])(([a-z0-9-])*([a-z0-9]))+(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
		
		if ( ! preg_match($pattern, $this->request->post['email'])) {
			$this->error['email'] = 'Invalid E-Mail!';
		}
		
		if ( ! $connection = @mysql_connect($this->request->post['db_host'], $this->request->post['db_user'], $this->request->post['db_password'])) {
			$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!';
		} else {
			if ( ! @mysql_select_db($this->request->post['db_name'], $connection)) {
				$this->error['warning'] = 'Error: Database does not exist!';
			}
			
			mysql_close($connection);
		}
		
		if ( ! is_writable(DIR_JSICART . 'config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_JSICART . 'config.php!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'admin/config.php')) {
			$this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_JSICART . 'admin/config.php!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'php.ini')) {
			$this->error['warning'] = 'Error: Could not write to php.ini please check you have set the correct permissions on: ' . DIR_JSICART . 'php.ini!';
		}
		
		if ( ! is_writable(DIR_JSICART . 'admin/php.ini')) {
			$this->error['warning'] = 'Error: Could not write to php.ini please check you have set the correct permissions on: ' . DIR_JSICART . 'admin/php.ini!';
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>