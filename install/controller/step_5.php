<?php
class ControllerStep5 extends Controller {
	public function index() {
		$this->children = array(
			'header',
			'footer'
		);
		
		if ($this->deleteAll('../install/')) {
			$this->redirect(HTTP_JSICART);
		}
		
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->template = 'step_5.tpl';
		
		$this->response->setOutput($this->render(TRUE));
	}
	
	private function deleteAll($directory, $empty = FALSE) {
		if (substr($directory, -1) == "/") {
			$directory = substr($directory, 0, -1);
		}
		
		if ( ! file_exists($directory) || ! is_dir($directory)) {
			return FALSE;
		} else if ( ! is_readable($directory)) {
			return FALSE;
		} else {
			$directoryHandle = opendir($directory);
			
			while ($contents = readdir($directoryHandle)) {
				if ($contents != '.' && $contents != '..') {
					$path = $directory . "/" . $contents;
					
					if (is_dir($path)) {
						$this->deleteAll($path);
					} else {
						unlink($path);
					}
				}
			}
			
			closedir($directoryHandle);
			
			if ($empty == FALSE) {
				if ( ! rmdir($directory)) {
					return FALSE;
				}
			}
			
			return TRUE;
		}
	}
}
?>