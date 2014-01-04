<?php

//admin/controller/module/product_display.php

class ControllerModuleProductDisplay extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('module/product_display');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		
			$this->model_setting_setting->editSetting('product_display', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('extension/module'));
		}
		
		
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_featured'] = $this->language->get('text_featured');
		
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_featured'] = $this->language->get('entry_featured');
		$this->data['entry_type'] = $this->language->get('entry_type');
		
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
			'href'      => $this->url->https('module/product_display'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		
		//$this->data['action'] = $this->url->https('module/product_display');
		
		//$this->data['cancel'] = $this->url->https('extension/module');
		
		if (isset($this->request->post['product_display_position'])) {
			$this->data['product_display_position'] = $this->request->post['product_display_position'];
		} else {
			$this->data['product_display_position'] = $this->config->get('product_display_position');
		}
		
		if (isset($this->request->post['product_display_type'])) {
			$this->data['product_display_type'] = $this->request->post['product_display_type'];
		} else {
			$this->data['product_display_type'] = $this->config->get('product_display_type');
		}
		
		if (isset($this->request->post['product_display_status'])) {
			$this->data['product_display_status'] = $this->request->post['product_display_status'];
		} else {
			$this->data['product_display_status'] = $this->config->get('product_display_status');
		}
		
		if (isset($this->request->post['product_display_sort_order'])) {
			$this->data['product_display_sort_order'] = $this->request->post['product_display_sort_order'];
		} else {
			$this->data['product_display_sort_order'] = $this->config->get('product_display_sort_order');
		}
		
		
		if (isset($this->request->post['featured'])) {
			$this->data['featured'] = $this->request->post['featured'];
		} else {
			$this->data['featured'] = $this->config->get('featured');
		}
		//1
		if (isset($this->request->post['product_display_link1'])) {
			$this->data['product_display_link1'] = $this->request->post['product_display_link1'];
		} else {
			$this->data['product_display_link1'] = $this->config->get('product_display_link1');
		}
		//2
		if (isset($this->request->post['product_display_link2'])) {
			$this->data['product_display_link2'] = $this->request->post['product_display_link2'];
		} else {
			$this->data['product_display_link2'] = $this->config->get('product_display_link2');
		}
		//3
		if (isset($this->request->post['product_display_link3'])) {
			$this->data['product_display_link3'] = $this->request->post['product_display_link3'];
		} else {
			$this->data['product_display_link3'] = $this->config->get('product_display_link3');
		}
		//4
		if (isset($this->request->post['product_display_link4'])) {
			$this->data['product_display_link4'] = $this->request->post['product_display_link4'];
		} else {
			$this->data['product_display_link4'] = $this->config->get('product_display_link4');
		}
		//5
		if (isset($this->request->post['product_display_link5'])) {
			$this->data['product_display_link5'] = $this->request->post['product_display_link5'];
		} else {
			$this->data['product_display_link5'] = $this->config->get('product_display_link5');
		}

	
if (isset($this->request->post['product_display_link'])) {
			$this->data['product_display_link'] = $this->request->post['product_display_link'];
		} else {
			$this->data['product_display_link'] = $this->config->get('product_display_link');
			
			if (isset($this->request->post['product_display_link'])) {
			$this->data['product_display_link'] = $this->request->post['product_display_link'];
		} else {
			$this->data['product_display_link'] = $this->config->get('product_display_link');
		
		$this->template = 'module/product_display.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	}
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'module/product_display')) {
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