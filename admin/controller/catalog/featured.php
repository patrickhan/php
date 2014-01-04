<?php
class ControllerCatalogFeatured extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/featured');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/featured');
		
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('catalog/featured');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/featured');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_featured->addFeatured($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/featured' . $url));
		}
		
		$this->getForm();
	}
	
	
	public function update() {
		$this->load->language('catalog/featured');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/featured');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_featured->editFeatured($this->request->get['featured_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('catalog/featured' . $url));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('catalog/featured');
		
		$this->document->title = $this->language->get('heading_title'); 
		
		$this->load->model('catalog/featured');
		
		if (isset($this->request->post['delete']) && $this->validateDelete()) {
			foreach ($this->request->post['delete'] as $featured_id) {
				$this->model_catalog_featured->deleteFeatured($featured_id);
			}
		
			$this->session->data['success'] = $this->language->get('text_success');
		
			$url = '';
		
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			$this->redirect($this->url->https('catalog/featured' . $url));
		}
		
		$this->getList();
	}
		
	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'f.sort_order';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('catalog/featured' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['insert'] = $this->url->https('catalog/featured/insert' . $url);
		$this->data['delete'] = $this->url->https('catalog/featured/delete' . $url);
		
		$this->data['featured'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$featured_total = $this->model_catalog_featured->getTotalFeatured($data);
			
		$results = $this->model_catalog_featured->getFeaturedProds($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/featured/update&featured_id=' . $result['featured_id'] . $url)
			);
			
			$this->data['featured'][] = array(
				'product_id' => $result['product_id'],
				'featured_id' => $result['featured_id'],
				'name'       => $result['name'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'sort_order' => $result['sort_order'],
				'delete'     => isset($this->request->post['delete']) && in_array($result['featured_id'], $this->request->post['delete']),
				'action'     => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_add_featured'] = $this->language->get('button_add_featured');
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
		
		$url = '';
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->https('catalog/featured&sort=pd.name' . $url);
		$this->data['sort_order'] = $this->url->https('catalog/featured&sort=f.sort_order' . $url);
		$this->data['sort_status'] = $this->url->https('catalog/featured&sort=f.status' . $url);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $featured_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/featured' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'catalog/featured_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_expire'] = $this->language->get('entry_expire');
		
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['product'])) {
			$this->data['error_product'] = $this->error['product'];
		} else {
			$this->data['error_product'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('catalog/featured' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if (!isset($this->request->get['featured_id'])) {
			$this->data['action'] = $this->url->https('catalog/featured/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('catalog/featured/update&featured_id=' . $this->request->get['featured_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('catalog/featured' . $url);
		
		if (isset($this->request->get['featured_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$featured_info = $this->model_catalog_featured->getFeatured($this->request->get['featured_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		
		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (isset($featured_info)) {
			$this->data['product_id'] = $featured_info['product_id'];
		} else {
			$this->data['product_id'] = '0';
		}
		
		$this->data['products'] = $this->model_catalog_featured->getAvaliableProducts($this->data['product_id']);
		
		if (isset($this->request->post['expire'])) {
			$this->data['expire'] = $this->request->post['expire'];
		} elseif (isset($featured_info)) {
			$this->data['expire'] = date('Y-m-d', strtotime($featured_info['expire']));
		} else {
			$this->data['expire'] = "";
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} else if (isset($featured_info)) {
			$this->data['sort_order'] = $featured_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} else if (isset($featured_info)) {
			$this->data['status'] = $featured_info['status'];
		} else {
			$this->data['status'] = 1;
		}
		
		$this->template = 'catalog/featured_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() { 
		if (!$this->user->hasPermission('modify', 'catalog/featured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (intval($this->request->post['product_id']) == 0 ) {
			$this->error['product'] = $this->language->get('error_product');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/featured')) {
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