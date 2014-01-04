<?php
class ControllerExtensionAttracta extends Controller {
	public function index() {
		$this->load->language('extension/total');
		 
		$this->document->title = 'Attracta';

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/attracta'),
       		'text'      => 'Attracta',
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = 'Attracta';
			
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['error'] = '';
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('total');
		
		$this->data['extensions'] = array();
			
		$this->template = 'extension/attracta.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function install() {
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session['error'] = $this->language->get('error_permission'); 
			
			$this->redirect($this->url->https('extension/total'));
		} else {				
			$this->load->model('setting/extension');
		
			$this->model_setting_extension->install('total', $this->request->get['extension']);

			$this->load->model('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'total/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'total/' . $this->request->get['extension']);

			$this->redirect($this->url->https('extension/total'));
		}
	}
	
	public function uninstall() {
		if (!$this->user->hasPermission('modify', 'extension/total')) {
			$this->session['error'] = $this->language->get('error_permission'); 
			
			$this->redirect($this->url->https('extension/total'));
		} else {			
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');
		
			$this->model_setting_extension->uninstall('total', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			$this->redirect($this->url->https('extension/total'));
		}
	}	
}
?>