<?php 
class ControllerExtensionLinks extends Controller { 
	private $error = array();
 
	public function index() {
		$this->load->language('extension/links');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/links');
		 
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('extension/links');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/links');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			
			$this->model_extension_links->addLink(array_merge($this->request->post, $data));

			$this->session->data['success'] = $this->language->get('text_link_added');
			
			$this->redirect($this->url->https('extension/links')); 
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
       		'href'      => $this->url->https('extension/links'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['add'] = $this->url->https('extension/links/insert');
		$this->data['delete'] = $this->url->https('extension/links/delete');
		
		$this->data['links'] = array();
		
		$results = $this->model_extension_links->getLinks();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('extension/links/update&link_id=' . $result['link_id'])
			);
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->https('extension/links/delete&link_id=' . $result['link_id'])
			);
					
			$this->data['links'][] = array(
				'link_id'     => $result['link_id'],
				'title'     => $result['title'],
				'url'     => $result['url'],
				'status'      => ($result['status'] == '1') ? 'Enabled' : 'Disabled',
				'date_posted' => $result['date_posted'],
				'date_modified' => $result['date_modified'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['link_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_date_posted'] = $this->language->get('column_date_posted');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_add'] = $this->language->get('button_add');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
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
		
		$this->template = 'extension/link_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function update() {
		$this->load->language('extension/links');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/links');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();

			$this->model_extension_links->editLink($this->request->get['link_id'], array_merge($this->request->post, $data));
			
			$this->session->data['success'] = $this->language->get('text_link_updated');
			
			$this->redirect($this->url->https('extension/links'));
		}

		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('extension/links');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('extension/links');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->model_extension_links->deleteLinks($this->request->post['selected']);
			
			$this->session->data['success'] = $this->language->get('text_links_deleted');

			$this->redirect($this->url->https('extension/links'));
		} else if (isset($this->request->get['link_id']) && $this->validateDelete()) {
			$this->model_extension_links->deleteLink($this->request->get['link_id']);
			
			$this->session->data['success'] = $this->language->get('text_links_deleted');

			$this->redirect($this->url->https('extension/links'));
		}

		$this->getList();
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_description'] = $this->language->get('entry_desc');
		$this->data['entry_comments'] = $this->language->get('entry_comments');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		
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
       		'href'      => $this->url->https('extension/links'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['link_id'])) {
			$this->data['action'] = $this->url->https('extension/links/insert');
		} else {
			$link_info = $this->model_extension_links->getLink($this->request->get['link_id']);
			$this->data = array_merge($this->data, $link_info);
			$this->data['action'] = $this->url->https('extension/links/update&link_id=' . $this->request->get['link_id']);
		}

		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($link_info)) {
			$this->data['url'] = $link_info['url'];
		} else {
			$this->data['url'] = '';
		}
		
		if (isset($this->request->post['comments'])) {
			$this->data['comments'] = $this->request->post['comments'];
		} elseif (isset($link_info)) {
			$this->data['comments'] = $link_info['comments'];
		} else {
			$this->data['comments'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($link_info)) {
			$this->data['status'] = $link_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['title'])) {
			$this->data['title'] = $this->request->post['title'];
		} elseif (isset($link_info)) {
			$this->data['title'] = $link_info['title'];
		} else {
			$this->data['title'] = '';
		}
		
		if (isset($this->request->post['description'])) {
			$this->data['description'] = $this->request->post['description'];
		} elseif (isset($link_info)) {
			$this->data['description'] = $link_info['description'];
		} else {
			$this->data['description'] = '';
		}
				
		$this->data['cancel'] = $this->url->https('extension/links');

		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->template = 'extension/link_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		// url is a url
		// title, desc, comments length
		// dates are dates
		// status 1 || 0
		if (!$this->user->hasPermission('modify', 'extension/links')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
   
  		if (empty($this->request->post['url'])) {
			$this->error['url'] = $this->language->get('error_url');
		}
  		
		if (empty($this->request->post['title'])) {
			$this->error['title'] = $this->language->get('error_title');
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
		if (!$this->user->hasPermission('modify', 'extension/links')) {
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