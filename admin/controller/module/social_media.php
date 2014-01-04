<?php
class ControllerModuleSocialMedia extends Controller {
	private $error = array(); 
	
	public function index() {
		$this->load->language('module/social_media');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('social_media', $this->request->post);
			
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
		
		$this->data['entry_facebook'] = $this->language->get('entry_facebook');
		$this->data['entry_twitter'] = $this->language->get('entry_twitter');
		$this->data['entry_myspace'] = $this->language->get('entry_myspace');
		$this->data['entry_linkedin'] = $this->language->get('entry_linkedin');
		
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
			'href'      => $this->url->https('module/social_media'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		
		$this->data['action'] = $this->url->https('module/social_media');
		
		$this->data['cancel'] = $this->url->https('extension/module');
		
		if (isset($this->request->post['social_media_position'])) {
			$this->data['social_media_position'] = $this->request->post['social_media_position'];
		} else {
			$this->data['social_media_position'] = $this->config->get('social_media_position');
		}
		
		if (isset($this->request->post['social_media_status'])) {
			$this->data['social_media_status'] = $this->request->post['social_media_status'];
		} else {
			$this->data['social_media_status'] = $this->config->get('social_media_status');
		}
		
		if (isset($this->request->post['social_media_sort_order'])) {
			$this->data['social_media_sort_order'] = $this->request->post['social_media_sort_order'];
		} else {
			$this->data['social_media_sort_order'] = $this->config->get('social_media_sort_order');
		}
		
		if (isset($this->request->post['social_media_facebook_link'])) {
			$this->data['social_media_facebook_link'] = $this->request->post['social_media_facebook_link'];
		} else {
			$this->data['social_media_facebook_link'] = $this->config->get('social_media_facebook_link');
		}
		
		if (isset($this->request->post['social_media_twitter_link'])) {
			$this->data['social_media_twitter_link'] = $this->request->post['social_media_twitter_link'];
		} else {
			$this->data['social_media_twitter_link'] = $this->config->get('social_media_twitter_link');
		}
		
		if (isset($this->request->post['social_media_my_space_link'])) {
			$this->data['social_media_my_space_link'] = $this->request->post['social_media_my_space_link'];
		} else {
			$this->data['social_media_my_space_link'] = $this->config->get('social_media_my_space_link');
		}
		
		if (isset($this->request->post['social_media_linked_in_link'])) {
			$this->data['social_media_linked_in_link'] = $this->request->post['social_media_linked_in_link'];
		} else {
			$this->data['social_media_linked_in_link'] = $this->config->get('social_media_linked_in_link');
		}
		
		$this->template = 'module/social_media.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'module/social_media')) {
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