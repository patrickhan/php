<?php
class ControllerSaleDiscount extends Controller {
	private $error = array();
	
	public function index() {
	
		$this->db->query("CREATE TABLE IF NOT EXISTS `discount` (
  `discount_id` int(11) NOT NULL auto_increment,
  `amount` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `cost` double NOT NULL,
  PRIMARY KEY  (`discount_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		
		$this->load->language('sale/discount');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/discount');
		
		$this->getList();
	}
	
	public function insert() {
		$this->load->language('sale/discount');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/discount');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_sale_discount->adddiscount($this->request->post);
			
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
			
			$this->redirect($this->url->https('sale/discount' . $url));
		}
		
		$this->getForm();
	}
	
	public function update() {
		$this->load->language('sale/discount');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/discount');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_sale_discount->editdiscount($this->request->get['discount_id'], $this->request->post);
			
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
			
			$this->redirect($this->url->https('sale/discount' . $url));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/discount');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/discount');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $discount_id) {
				$this->model_sale_discount->deletediscount($discount_id);
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
			
			$this->redirect($this->url->https('sale/discount' . $url));
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
			$sort = 'id.title';
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
			'href'      => $this->url->https('sale/discount' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['insert'] = $this->url->https('sale/discount/insert' . $url);
		$this->data['delete'] = $this->url->https('sale/discount/delete' . $url);
		
		$this->data['discounts'] = array();
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 1000,
			'limit' => 1000
		);
		
		$discount_total = $this->model_sale_discount->getTotaldiscounts();
		
		$results = $this->model_sale_discount->getdiscounts($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sale/discount/update&discount_id=' . $result['discount_id'] . $url)
			);
			
			$date_modified = '';
			if ($result['type'] == '0') {
			$type = "Dollars Off";
			}
			else
			{
			$type = "Percent Off";
			}
			$this->data['discounts'][] = array(
				'discount_id' => $result['discount_id'],
				'amount'          => $result['amount'],
				'type'          => $type,
				'cost'     => $result['cost'],
				'action'         => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_cost'] = $this->language->get('column_cost');
		$this->data['column_action'] = $this->language->get('column_action');
		$this->data['column_modified'] = $this->language->get('column_modified');
		
		$this->data['button_add_discount'] = $this->language->get('button_add_discount');
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
		
		$this->data['sort_title'] = $this->url->https('sale/discount&sort=id.title' . $url);
		$this->data['sort_cost'] = $this->url->https('sale/discount&sort=i.cost' . $url);
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $discount_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sale/discount' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/discount_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_title_tag'] = $this->language->get('entry_title_tag');
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_amounts'] = $this->language->get('entry_meta_amounts');
		$this->data['entry_cost'] = $this->language->get('entry_cost');
		$this->data['entry_location'] = $this->language->get('entry_location');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = '';
		}
		
		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('sale/discount'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
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
		
		if ( ! isset($this->request->get['discount_id'])) {
			$this->data['action'] = $this->url->https('sale/discount/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('sale/discount/update&discount_id=' . $this->request->get['discount_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('sale/discount' . $url);
		
		if (isset($this->request->get['discount_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$discount_info = $this->model_sale_discount->getdiscount($this->request->get['discount_id']);
		}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['discount_description'])) {
			$this->data['discount_description'] = $this->request->post['discount_description'];
		} elseif (isset($this->request->get['discount_id'])) {
			$this->data['discount_description'] = $this->model_sale_discount->getdiscountDescriptions($this->request->get['discount_id']);
		} else {
			$this->data['discount_description'] = array();
		}
		
		if (isset($this->request->post['amount'])) {
			$this->data['amount'] = $this->request->post['amount'];
		} elseif (isset($discount_info)) {
			$this->data['amount'] = $discount_info['amount'];
		} else {
			$this->data['amount'] = '';
		}
		
		if (isset($this->request->post['cost'])) {
			$this->data['cost'] = $this->request->post['cost'];
		} elseif (isset($discount_info)) {
			$this->data['cost'] = $discount_info['cost'];
		} else {
			$this->data['cost'] = '';
		}
		
		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (isset($discount_info)) {
			$this->data['type'] = $discount_info['type'];
		} else {
			$this->data['type'] = '';
		}
		
		if (isset($this->request->post['location'])) {
			$this->data['location'] = $this->request->post['location'];
		} elseif (isset($discount_info)) {
			$this->data['location'] = 0;
		} else {
			$this->data['location'] = '';
		}
		
		$this->template = 'sale/discount_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateForm() {
		if ( ! $this->user->hasPermission('modify', 'sale/discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['discount_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['title'])) < 3) || (strlen(utf8_decode($value['title'])) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		
			if (strlen(utf8_decode($value['description'])) < 3) {
				$this->error['description'][$language_id] = $this->language->get('error_description');
			}
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function validateDelete() {
		if ( ! $this->user->hasPermission('modify', 'sale/discount')) {
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