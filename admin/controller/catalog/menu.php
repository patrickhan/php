<?php 
class ControllerCatalogMenu extends Controller {
	private $error = array();
	
	
	public function index() {
		$this->load->language('catalog/menu');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/menu');
		 
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('catalog/menu');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/menu');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			
			$this->model_catalog_menu->addMenu(array_merge($this->request->post, $data));
			
			$this->session->data['success'] = $this->language->get('text_menu_added');
			
			$this->redirect($this->url->https('catalog/menu')); 
		}
		
		$this->getForm();
	}
	
	private function getList() {
	
		$this->data['menuComplete'] = array();
		$this->data['menuComplete'] = $this->model_catalog_menu->getMenus();
	
	
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'm.sort_order';
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
			'href'      => $this->url->https('catalog/menu'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$this->data['add'] = $this->url->https('catalog/menu/insert');
		$this->data['delete'] = $this->url->https('catalog/menu/delete');
		
		$this->data['menus'] = array();
		
		$menu_total = $this->model_catalog_menu->getTotalMenus();
		
		$results = $this->model_catalog_menu->getMenus($data);
		
		$pagination = new Pagination();
		$pagination->total = $menu_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('catalog/menu' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('catalog/menu/update&menu_id=' . $result['menu_id'] . $url)
			);
			
			$this->data['menus'][] = array(
				'menu_id'    => $result['menu_id'],
				'title'      => $result['title'],
				'url'        => $result['url'],
				'status'     => $result['status'],
				'sort_order' => $result['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($result['menu_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_url'] = $this->language->get('column_url');
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
		
		$this->template = 'catalog/menu_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function update() {
		$this->load->language('catalog/menu');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/menu');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data = array();
			
			$this->model_catalog_menu->editMenu($this->request->get['menu_id'], array_merge($this->request->post, $data));
			
			$this->session->data['success'] = $this->language->get('text_menu_updated');
			
			$this->redirect($this->url->https('catalog/menu'));
			
		
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('catalog/menu');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('catalog/menu');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $menu_id) {
				$this->model_catalog_menu->deleteMenu($menu_id);
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
			
			$this->redirect($this->url->https('catalog/menu' . $url));
		}

		$this->getList();
	}
	
	
	
	private function getForm() {
		
		$this->data['menuComplete'] = array();
		$this->data['menuComplete'] = $this->model_catalog_menu->getMenus();
		
		/*echo "<pre>";
			print_r($this->data['menuComplete']);
		echo "<pre>";*/
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_parent'] = $this->language->get('entry_parent');
		
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
			'href'      => $this->url->https('catalog/menu'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if ( ! isset($this->request->get['menu_id'])) {
			$this->data['action'] = $this->url->https('catalog/menu/insert');
		} else {
			$menu_info = $this->model_catalog_menu->getMenu($this->request->get['menu_id']);
			$this->data = array_merge($this->data, $menu_info);
			$this->data['action'] = $this->url->https('catalog/menu/update&menu_id=' . $this->request->get['menu_id']);
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} elseif (isset($menu_info)) {
			$this->data['url'] = $menu_info['url'];
		} else {
			$this->data['url'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (isset($menu_info)) {
			$this->data['status'] = $menu_info['status'];
		} else {
			$this->data['status'] = '1';
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($menu_info)) {
			$this->data['sort_order'] = $menu_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}
		
		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (isset($menu_info)) {
			$this->data['parent_id'] = $menu_info['parent_id'];
		} else {
			$this->data['parent_id'] = '';
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['menu_descriptions'])) {
			$this->data['menu_descriptions'] = $this->request->post['menu_descriptions'];
		} elseif (isset($this->request->get['menu_id'])) {
			$this->data['menu_descriptions'] = $this->model_catalog_menu->getMenuDescriptions($this->request->get['menu_id']);
		} else {
			$this->data['menu_descriptions'] = array();
		}
		
		$this->data['cancel'] = $this->url->https('catalog/menu');
		
		$this->template = 'catalog/menu_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
	}
	
	private function validateForm() {
		// need to validate text per language!
		if ( ! $this->user->hasPermission('modify', 'catalog/menu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
			
		if (empty($this->request->post['url'])) {
			$this->error['url'] = $this->language->get('error_url');
		}
  		
		if ($this->request->post['status'] != '1' && $this->request->post['status'] != '0') {
			$this->error['status'] = $this->language->get('error_status');
		}
		
		foreach ($this->request->post['menu_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 1) || (strlen(utf8_decode($value['title'])) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if ( ! $this->user->hasPermission('modify', 'catalog/menu')) {
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