<?php
class ControllerModuleInformation extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/information');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('information', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('extension/module'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_display_sitemap'] = $this->language->get('entry_display_sitemap');
		$this->data['entry_display_printable_catalog'] = $this->language->get('entry_display_printable_catalog');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/module'),
			'text'      => $this->language->get('text_module'),
			'separator' => ' :: '
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('module/information'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['action'] = $this->url->https('module/information');
		
		$this->data['cancel'] = $this->url->https('extension/module');
		
		if (isset($this->request->post['information_position'])) {
			$this->data['information_position'] = $this->request->post['information_position'];
		} else {
			$this->data['information_position'] = $this->config->get('information_position');
		}
		
		if (isset($this->request->post['information_status'])) {
			$this->data['information_status'] = $this->request->post['information_status'];
		} else {
			$this->data['information_status'] = $this->config->get('information_status');
		}
		
		if (isset($this->request->post['information_sort_order'])) {
			$this->data['information_sort_order'] = $this->request->post['information_sort_order'];
		} else {
			$this->data['information_sort_order'] = $this->config->get('information_sort_order');
		}
		
		if (isset($this->request->post['information_display_sitemap'])) {
			$this->data['information_display_sitemap'] = $this->request->post['information_display_sitemap'];
		} else {
			$this->data['information_display_sitemap'] = $this->config->get('information_display_sitemap');
		}
		
		if (isset($this->request->post['information_display_printable_catalog'])) {
			$this->data['information_display_printable_catalog'] = $this->request->post['information_display_printable_catalog'];
		} else {
			$this->data['information_display_printable_catalog'] = $this->config->get('information_display_printable_catalog');
		}
		
		$this->template = 'module/information.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'module/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>