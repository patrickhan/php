<?php
class ControllerModulePdfcatalog extends Controller {
	private $error = array(); 
		
	public function index() {   
		$this->load->language('module/pdf_catalog');

		$this->document->title = $this->language->get('heading_title');
		$this->load->helper('tcpdf/tcpdf');
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		//print_r($this->request->post);die;
			$this->model_setting_setting->editSetting('pdf_catalog', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_hide'] = $this->language->get('text_hide');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_display_categories'] = $this->language->get('entry_display_categories');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_description'] = $this->language->get('entry_description');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home',
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module',
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/pdf_catalog',
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/pdf_catalog';
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module';

		if (isset($this->request->post['pdf_catalog_position'])) {
			$this->data['pdf_catalog_position'] = $this->request->post['pdf_catalog_position'];
		} else {
			$this->data['pdf_catalog_position'] = $this->config->get('pdf_catalog_position');
		}
		
		if (isset($this->request->post['pdf_catalog_status'])) {
			$this->data['pdf_catalog_status'] = $this->request->post['pdf_catalog_status'];
		} else {
			$this->data['pdf_catalog_status'] = $this->config->get('pdf_catalog_status');
		}
		
		if (isset($this->request->post['pdf_catalog_sort_order'])) {
			$this->data['pdf_catalog_sort_order'] = $this->request->post['pdf_catalog_sort_order'];
		} else {
			$this->data['pdf_catalog_sort_order'] = $this->config->get('pdf_catalog_sort_order');
		}	
		
		if (isset($this->request->post['pdf_catalog_description'])) {
			$this->data['pdf_catalog_description'] = $this->request->post['pdf_catalog_description'];
		} else {
			$this->data['pdf_catalog_description'] = $this->config->get('pdf_catalog_description');
		}
		if (isset($this->request->post['pdf_catalog_image'])) {
			$this->data['pdf_catalog_image'] = $this->request->post['pdf_catalog_image'];
		} else {
			$this->data['pdf_catalog_image'] = $this->config->get('pdf_catalog_image');
		}
		$img = $this->config->get('pdf_catalog_image');
		$this->load->helper('image');
		
		if (isset($img) && file_exists(DIR_IMAGE . $img)) {
			$this->data['preview'] = image_resize($img, 100, 100);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 100);
		}
						
		if (isset($this->request->post['pdf_catalog_display_categories'])) {
			$this->data['pdf_catalog_display_categories'] = $this->request->post['pdf_catalog_display_categories'];
		} else {
			$this->data['pdf_catalog_display_categories'] = $this->config->get('pdf_catalog_display_categories');
		}				
		
		//$this->data['token'] = $this->session->data['token'];
		
		$this->template = 'module/pdf_catalog.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/pdf_catalog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>