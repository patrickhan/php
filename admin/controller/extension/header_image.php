<?php
class ControllerExtensionHeaderimage extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/header_image');
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/header_image');
		$amount = $this->model_extension_header_image->getTotalEnabledHeaders();
		
		$this->data['amount'] = $amount;
		//echo $amount;
		$this->getList();
	}
	
	public function changeSettings()
	{
	$this->load->model('setting/setting');
	$this->load->model('extension/header_image');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		//echo $this->request->post['display_type']; die;
			$this->model_setting_setting->editSetting('display_type', $this->request->post);
			$this->model_setting_setting->editSetting('sec', $this->request->post);
			$template = $this->config->get('config_template');
			$template_info = $this->model_extension_header_image->getTemplate($template);
			$width = $this->request->post['width'];
			$height = $this->request->post['height'];
			if (isset($template_info['header_height'])) {
		$this->db->query("UPDATE header_templates SET header_width='$width', header_height='$height' WHERE path='$template'");
		}
		else
		{
		$this->db->query("INSERT INTO header_templates SET header_width='$width', header_height='$height', path='$template', name='$template'");
		}
			$type = $this->request->post['display_type'];
			if ($type=="eslider") {
				$this->model_extension_header_image->editXML();
				$this->load->language('extension/header_image');
				$this->session->data['success'] = $this->language->get('text_settings_saved');
			
			}
			
			$this->redirect($this->url->https('extension/header_image'));
			
			//print_r($this->request->post);
			//echo $this->request->post['display_type'];
		}
	}
	
	public function insert() {
		$this->load->language('extension/header_image');
		$this->document->title = $this->language->get('heading_title');
		$this->load->model('extension/header_image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
					
			$this->model_extension_header_image->addHeader($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_header_added');
			
			$this->redirect($this->url->https('extension/header_image'));
		}
		
		$this->getForm();
	}
	
	private function getList() {
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/header_image'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['add'] = $this->url->https('extension/header_image/insert');
		$this->data['delete'] = $this->url->https('extension/header_image/delete');
		
		$this->data['header_image'] = array();
					
		$results = $this->model_extension_header_image->getHeaders();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('extension/header_image/update&header_id=' . $result['header_id'])
			);
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->https('extension/header_image/delete&header_id=' . $result['header_id'])
			);
			
			$this->data['header_images'][] = array(
				'header_id'     => $result['header_id'],
				'image'         => $result['image'],
				'title'         => $result['title'],
				'status'        => ($result['status'] == '1') ? 'Enabled' : 'Disabled',
				'date_added'    => $result['date_added'],
				'date_modified' => $result['date_modified'],
				'selected'      => isset($this->request->post['selected']) && in_array($result['header_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}
		
		$template = $this->config->get('config_template');
		$template_info = $this->model_extension_header_image->getTemplate($template);
		if (isset($template_info['header_height'])) {
		$this->data['hheight'] = $template_info['header_height'];
		$this->data['wwidth'] = $template_info['header_width'];
		$this->data['uurl'] = $template_info['header_url'];
		$this->data['ppath'] = $template_info['path'];
		}
		else
		{
		$this->data['hheight'] = 0;
		$this->data['wwidth'] = 0;
		$this->data['uurl'] = '';
		$this->data['ppath'] = $template;
		}
		
		
		$this->data['text_useWhich'] = $this->language->get('text_useWhich');
		$this->data['text_useInstructions'] = $this->language->get('text_useInstructions');
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_id'] = $this->language->get('column_id');
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['text_path'] = $this->language->get('text_path');
		$this->data['text_height'] = $this->language->get('text_height');
		$this->data['text_width'] = $this->language->get('text_width');
		$this->data['text_original'] = $this->language->get('text_original');
		$this->data['text_replacement'] = $this->language->get('text_replacement');
		$this->data['text_save'] = $this->language->get('text_save');
		
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['text_one'] = $this->language->get('text_one');
		$this->data['text_refresh'] = $this->language->get('text_refresh');
		$this->data['text_slider'] = $this->language->get('text_slider');
		$this->data['text_eslider'] = $this->language->get('text_eslider');
		$this->data['text_off'] = $this->language->get('text_off');
		
		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_download'] = $this->language->get('button_download');
		
		if (isset($this->request->post['display_type'])) {
			$this->data['display_type'] = $this->request->post['display_type'];
		} else {
			$this->data['display_type'] = $this->config->get('display_type');
		}
		
		if (isset($this->request->post['sec'])) {
			$this->data['sec'] = $this->request->post['sec'];
		} else {
			$this->data['sec'] = $this->config->get('sec');
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($template_info)) {
			$this->data['template_info'] = $template_info;
		} else {
			$this->data['template_info'] = array();
		}
		
		/*$this->load->helper('image');
		
		if (isset($template_info['header_url'])) {
			$this->data['template_info']['preview'] = image_resize($template_info['header_url'], 100, 100);
		} else {
			$this->data['template_info']['header_url'] = array();
		} */
		
		//$this->data['preview'] = image_resize($product_info['image'], 100, 100);
		$this->template = 'extension/header_image_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function update() {
		$this->load->language('extension/header_image');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/header_image');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_extension_header_image->editHeader($this->request->get['header_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_header_updated');
			
			$this->redirect($this->url->https('extension/header_image'));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/header_image');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/header_image');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->model_extension_header_image->deleteHeaderImages($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_header_image_deleted');
			
			$this->redirect($this->url->https('extension/header_image'));
		} else if (isset($this->request->get['header_id']) && $this->validateDelete()) {
			$this->model_extension_header_image->deleteHeaderImage($this->request->get['header_id']);
			
			$this->session->data['success'] = $this->language->get('text_header_image_deleted');
			
			$this->redirect($this->url->https('extension/header_image'));
		}
		
		$this->getList();
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_start'] = $this->language->get('entry_start');
		$this->data['entry_end'] = $this->language->get('entry_end');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['error_warning'] = '';
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		
		$this->data['error_title'] = '';
 		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		}
		
		$this->data['error_url'] = '';
 		if (isset($this->error['url'])) {
			$this->data['error_url'] = $this->error['url'];
		}
		
		$this->data['error_status'] = '';
 		if (isset($this->error['status'])) {
			$this->data['error_status'] = $this->error['status'];
		}
		
		$this->document->breadcrumbs = array();
	
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('extension/header_image'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['header_id'])) {
			$this->data['action'] = $this->url->https('extension/header_image/insert');
		} else {
			$header_info = $this->model_extension_header_image->getHeaderImage($this->request->get['header_id']);
			$this->data = array_merge($this->data, $header_info);
			$this->data['action'] = $this->url->https('extension/header_image/update&header_id=' . $this->request->get['header_id']);
		}
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($header_info)) {
			$this->data['title'] = $header_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['sort'])) {
			$this->data['sort'] = $this->request->post['sort'];
		} elseif (isset($header_info)) {
			$this->data['sort'] = $header_info['sort'];
		} else {
			$this->data['sort'] = '';
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($header_info)) {
			$this->data['url'] = $header_info['url'];
		} else {
			$this->data['url'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($header_info)) {
			$this->data['image'] = $header_info['image'];
		} else {
			$this->data['image'] = '';
		}
				
		if (isset($this->request->post['start_date'])) {
			$this->data['start_date'] = $this->request->post['start_date'];
		} elseif (isset($header_info)) {
			$this->data['start_date'] = $header_info['start_date'];
		} else {
			$this->data['start_date'] = '';
		}
		
		if (isset($this->request->post['end_date'])) {
			$this->data['end_date'] = $this->request->post['end_date'];
		} elseif (isset($header_info)) {
			$this->data['end_date'] = $header_info['end_date'];
		} else {
			$this->data['end_date'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($header_info)) {
			$this->data['status'] = $header_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		$this->load->helper('image');
		
		if (isset($header_info) && $header_info['image'] && file_exists(DIR_IMAGE . $header_info['image'])) {
			$this->data['preview'] = image_resize($header_info['image'], 100, 100);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 100);
		}
		
		$this->data['cancel'] = $this->url->https('extension/header_image');
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->template = 'extension/header_image_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/header_image')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (empty($this->request->post['image'])) {
			$this->error['image'] = $this->language->get('error_image');
		}
		
		if ($this->request->post['status'] != '1' && $this->request->post['status'] != '0') {
			$this->error['status'] = $this->language->get('error_status');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/header_image')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
	private function removeItemFromArray($list, $item) {
		return array_diff($list, (array)$item);
	}
	
}
?>