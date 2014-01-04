<?php  
class ControllerModuleGoogleTranslate extends Controller {
	protected function index() {
		$this->language->load('module/googletranslate');

      	$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->id = 'googletranslate';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/googletranslate.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/googletranslate.tpl';
		} else {
			$this->template = 'default/template/module/googletranslate.tpl';
		}
		
		$this->render();
	}
}
?>