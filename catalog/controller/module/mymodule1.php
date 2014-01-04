<?php
class ControllerModuleMyModule1 extends Controller {
	
	private $_name = 'mymodule1';
	
	protected function index() {
		$this->language->load('module/' . $this->_name);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (isset($this->request->post['mymodule1_code' . $language['language_id']])) {
				$this->data['mymodule1_code' . $language['language_id']] = $this->request->post['mymodule1_code' . $language['language_id']];
			} else {
				$this->data['mymodule1_code' . $language['language_id']] = $this->config->get('mymodule1_code' . $language['language_id']);
			}
		}
		$this->data['code'] = html_entity_decode($this->config->get('mymodule1_code' . $this->config->get('config_language_id')));
		
		foreach ($languages as $language) {
			if (isset($this->request->post['mymodule1_title' . $language['language_id']])) {
				$this->data['mymodule1_title' . $language['language_id']] = $this->request->post['mymodule1_title' . $language['language_id']];
			} else {
				$this->data['mymodule1_title' . $language['language_id']] = $this->config->get('mymodule1_title' . $language['language_id']);
			}
		}
		//$this->data['header'] = $this->config->get( $this->_name . '_header' ); 
		$this->data['title'] = $this->config->get($this->_name . '_title' . $this->config->get('config_language_id'));
		
		$this->data['header'] = $this->config->get( $this->_name . '_header');
		
		$this->id = $this->_name;
		
		$tmpl = '/template/module/' . $this->_name . '.tpl'; 
		
		if( !$this->data['title'] ) { 
			$this->data['title'] = $this->data['heading_title']; 
		} 
		if( !$this->data['header'] ) { 
			$this->data['title'] = ''; 
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $tmpl)) {
			$this->template = $this->config->get('config_template') . $tmpl;
		} else {
			$this->template = 'default' . $tmpl;
		}
		
		$this->render();
	}
}
?>