<?php
class ControllerTotalPoints extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/points');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('points', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('extension/total'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_signup_points'] = $this->language->get('entry_signup_points');
		$this->data['entry_point_value'] = $this->language->get('entry_point_value');
		$this->data['entry_points_per_unit'] = $this->language->get('entry_points_per_unit');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
 
		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['signup_points'])) {
			$this->data['error_signup_points'] = $this->error['signup_points'];
		} else {
			$this->data['error_signup_points'] = '';
		}
		
		if (isset($this->error['point_value'])) {
			$this->data['error_point_value'] = $this->error['point_value'];
		} else {
			$this->data['error_point_value'] = '';
		}
		
		if (isset($this->error['points_per_unit'])) {
			$this->data['error_points_per_unit'] = $this->error['points_per_unit'];
		} else {
			$this->data['error_points_per_unit'] = '';
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/total'),
       		'text'      => $this->language->get('text_total'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('total/points'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('total/points');
		
		$this->data['cancel'] = $this->url->https('extension/total');

		if (isset($this->request->post['points_status'])) {
			$this->data['points_status'] = $this->request->post['points_status'];
		} else {
			$this->data['points_status'] = $this->config->get('points_status');
		}
		
		if (isset($this->request->post['signup_points'])) {
			$this->data['signup_points'] = $this->request->post['signup_points'];
		} else {
			$this->data['signup_points'] = $this->config->get('signup_points');
		}
		
		if (isset($this->request->post['point_value'])) {
			$this->data['point_value'] = $this->request->post['point_value'];
		} else {
			$this->data['point_value'] = $this->config->get('point_value');
		}
		
		if (isset($this->request->post['points_per_unit'])) {
			$this->data['points_per_unit'] = $this->request->post['points_per_unit'];
		} else {
			$this->data['points_per_unit'] = $this->config->get('points_per_unit');
		}

		if (isset($this->request->post['points_sort_order'])) {
			$this->data['points_sort_order'] = $this->request->post['points_sort_order'];
		} else {
			$this->data['points_sort_order'] = $this->config->get('points_sort_order');
		}
		
		$this->template = 'total/points.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/points')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['signup_points'] < 0) {
			$this->error['signup_points'] = $this->language->get('error_signup_points');
		}
		
		if ($this->request->post['point_value'] < 0) {
			$this->error['point_value'] = $this->language->get('error_point_value');
		}
		
		if ($this->request->post['points_per_unit'] < 0) {
			$this->error['points_per_unit'] = $this->language->get('error_points_per_unit');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>