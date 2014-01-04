<?php
class ControllerModuleQuoteRequest extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('module/quote_request');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('quote_request', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('extension/module'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
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
			'href'      => $this->url->https('module/quote_request'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		
		$this->data['action'] = $this->url->https('module/quote_request');
		
		$this->data['cancel'] = $this->url->https('extension/module');
		
		if (isset($this->request->post['quote_request_position'])) {
			$this->data['quote_request_position'] = $this->request->post['quote_request_position'];
		} else {
			$this->data['quote_request_position'] = $this->config->get('quote_request_position');
		}
		
		if (isset($this->request->post['quote_request_status'])) {
			$this->data['quote_request_status'] = $this->request->post['quote_request_status'];
		} else {
			$this->data['quote_request_status'] = $this->config->get('quote_request_status');
		}
		
		if (isset($this->request->post['quote_request_sort_order'])) {
			$this->data['quote_request_sort_order'] = $this->request->post['quote_request_sort_order'];
		} else {
			$this->data['quote_request_sort_order'] = $this->config->get('quote_request_sort_order');
		}
		///$data = $this->db->query("SELECT * FROM " . DB_PREFIX . "quote_request");
		///$data = $data->row;
		
		if (isset($this->request->post['quote_email'])) {
			$this->data['quote_email'] = $this->request->post['quote_email'];
		} else {
			$this->data['quote_email'] = $this->config->get('quote_email');
		}
		if ($this->data['quote_email'] == "")
		{
		$this->data['quote_email'] = $this->config->get('config_email');
		}
		
		if (isset($this->request->post['quote_description'])) {
			$this->data['quote_description'] = $this->request->post['quote_description'];
		} else {
			$this->data['quote_description'] = $this->config->get('quote_description');
		}
		if ($this->data['quote_description'] == "")
		{
		$initialdesc = "<p>
	First Name: <input name=\"firstname\" type=\"text\" /></p>
<p>
	Last Name: <input name=\"lastname\" type=\"text\" /></p>
<p>
	Email: &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <input name=\"email\" type=\"text\" /></p>
	<p>
	Phone:&nbsp; &nbsp; &nbsp; &nbsp; <input name=\"phone\" type=\"text\" /></p>";
		$this->data['quote_description'] = $initialdesc;
		}
		
		$this->template = 'module/quote_request.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'module/quote_request')) {
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