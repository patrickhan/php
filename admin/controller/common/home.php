<?php
class ControllerCommonHome extends Controller {
	public function index() {
		if ($this->config->get('brochure') === TRUE) {
			$this->redirect($this->url->https('catalog/information'));
		}
		$this->load->language('common/home');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_overview'] = $this->language->get('text_overview');
		$this->data['text_statistics'] = $this->language->get('text_statistics');
		$this->data['text_latest_10_orders'] = $this->language->get('text_latest_10_orders');
		$this->data['text_total_sale'] = $this->language->get('text_total_sale');
		$this->data['text_total_sale_year'] = $this->language->get('text_total_sale_year');
		$this->data['text_total_order'] = $this->language->get('text_total_order');
		$this->data['text_total_customer'] = $this->language->get('text_total_customer');
		$this->data['text_total_customer_approval'] = $this->language->get('text_total_customer_approval');
		$this->data['text_total_product'] = $this->language->get('text_total_product');
		$this->data['text_total_review'] = $this->language->get('text_total_review');
		$this->data['text_total_review_approval'] = $this->language->get('text_total_review_approval');
		$this->data['text_day'] = $this->language->get('text_day');
		$this->data['text_week'] = $this->language->get('text_week');
		$this->data['text_month'] = $this->language->get('text_month');
		$this->data['text_year'] = $this->language->get('text_year');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_whos_online'] = $this->language->get('text_whos_online');
		
		$this->data['column_order'] = $this->language->get('column_order');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_firstname'] = $this->language->get('column_firstname');
		$this->data['column_lastname'] = $this->language->get('column_lastname');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['column_customer_id'] = $this->language->get('column_customer_id');
		$this->data['column_full_name'] = $this->language->get('column_full_name');
		$this->data['column_online'] = $this->language->get('column_online');
		$this->data['column_ip_address'] = $this->language->get('column_ip_address');
		$this->data['column_entry_time'] = $this->language->get('column_entry_time');
		$this->data['column_last_click'] = $this->language->get('column_last_click');
		$this->data['column_last_url'] = $this->language->get('column_last_url');
		$this->data['store'] = HTTP_DOMAIN;
		
		$this->data['entry_range'] = $this->language->get('entry_range');
		
		$this->document->breadcrumbs = array();
		
		$this->document->breadcrumbs[] = array(
			'href'      => $this->url->https('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);
		
		$this->load->model('report/whos_online');
		
		$this->data['whos_online'] = $this->model_report_whos_online->getWhosOnline();
		
		$this->load->model('sale/order');
		
		$this->data['total_sale'] = $this->currency->format($this->model_sale_order->getTotalSales(), $this->config->get('config_currency'));
		$this->data['total_sale_year'] = $this->currency->format($this->model_sale_order->getTotalSalesByYear(date('Y')), $this->config->get('config_currency'));
		$this->data['total_order'] = $this->model_sale_order->getTotalOrders();
		
		$this->load->model('sale/customer');
		
		$this->data['total_customer'] = $this->model_sale_customer->getTotalCustomers();
		$this->data['total_customer_approval'] = $this->model_sale_customer->getTotalCustomersAwatingApproval();
		
		$this->load->model('catalog/product');
		
		$this->data['total_product'] = $this->model_catalog_product->getTotalProducts();
		
		$this->load->model('catalog/review');
		
		$this->data['total_review'] = $this->model_catalog_review->getTotalReviews();
		$this->data['total_review_approval'] = $this->model_catalog_review->getTotalReviewsAwatingApproval();
		
		$this->data['orders'] = array(); 
		
		$data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);
		
		/*$results = $this->getOrdersf($data, $ord);
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sale/order/update&order_id=' . $result['order_id'])
			);
			
			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'action'     => $action
			);
		}*/
		//echo count($this->data['orders']); die;
		$this->getList();
		
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');
		
			$this->model_localisation_currency->updateCurrencies();
		}
		
		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
		
	}
	
	public function chart() {
		$this->load->language('common/home');
		
		$data = array();
		
		$data['order'] = array();
		$data['customer'] = array();
		$data['xaxis'] = array();
		
		$data['order']['label'] = $this->language->get('text_order');
		$data['customer']['label'] = $this->language->get('text_customer');
		
		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'month';
		}
		
		switch ($range) {
			case 'day':
				for ($i = 0; $i <= 23; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "') GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					
					if ($query->num_rows) {
						$data['order']['data'][]  = array(date('G', strtotime('-' . (int)$i . ' hour')), (int)$query->row['total']);
					} else {
						$data['order']['data'][]  = array(date('G', strtotime('-' . (int)$i . ' hour')), 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE(NOW()) AND HOUR(date_added) = '" . (int)$i . "' GROUP BY HOUR(date_added) ORDER BY date_added ASC");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array(date('G', strtotime('-' . (int)$i . ' hour')), (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array(date('G', strtotime('-' . (int)$i . ' hour')), 0);
					}
					
					$data['xaxis'][] = array(date('G', strtotime('-' . (int)$i . ' hour')), date('H', strtotime('-' . (int)$i . ' hour')));
				}
				break;
			case 'week':
				$week = mktime(0, 0, 0, date('m'), date('d') - date('w'), date('Y'));
				
				for ($i = 0; $i < 7; $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND DATE(date_added) = DATE('" . date('Y-m-d', $week + ($i * 86400)) . "') GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = DATE('" . date('Y-m-d', $week + ($i * 86400)) . "') GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), 0);
					}
					
					$data['xaxis'][] = array(date('d', strtotime('-' . (int)$i . ' day')), date('d/m', strtotime('-' . (int)$i . ' day')));
				}
				break;
			default:
			case 'month':
				$last_day_of_the_month = mktime(23, 59, 59, date('m'), 0, date('Y')); 
				
				for ($i = 1; $i <= date('j', $last_day_of_the_month); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "') GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE DATE(date_added) = '" . date('Y-m-d', strtotime('-' . (int)$i . ' day')) . "' GROUP BY DAY(date_added)");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array(date('d', strtotime('-' . (int)$i . ' day')), 0);
					}
					
					$data['xaxis'][] = array(date('d', strtotime('-' . (int)$i . ' day')), date('d/m', strtotime('-' . (int)$i . ' day')));
				}
				break;
			case 'year':
				for ($i = 0; $i < date('n'); $i++) {
					$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . $i . ' month')) . "') GROUP BY MONTH(date_added)");
					
					if ($query->num_rows) {
						$data['order']['data'][] = array(date('n', strtotime('-' . (int)$i . ' month')), (int)$query->row['total']);
					} else {
						$data['order']['data'][] = array(date('n', strtotime('-' . (int)$i . ' month')), 0);
					}
					
					$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(date_added) = '" . date('Y') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . $i . ' month')) . "' GROUP BY MONTH(date_added)");
					
					if ($query->num_rows) {
						$data['customer']['data'][] = array(date('n', strtotime('-' . (int)$i . ' month')), (int)$query->row['total']);
					} else {
						$data['customer']['data'][] = array(date('n', strtotime('-' . (int)$i . ' month')), 0);
					}
					
					$data['xaxis'][] = array(date('n', strtotime('-' . (int)$i . ' month')), date('m', strtotime('-' . (int)$i . ' month')));
				}
				break;
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($data));
	}
	
	public function login() {
		if ( ! $this->user->isLogged()) {
			return $this->forward('common/login');
		}
	}
	
	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
			
			$part = explode('/', $route);
			
			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/filemanager',
				'common/permission',
				'error/error_403',
				'error/error_404'
			);
			
			if ( ! in_array(@$part[0] . '/' . @$part[1], $ignore)) {
				if (!$this->user->hasPermission('access', @$part[0] . '/' . @$part[1])) {
					return $this->forward('error/permission');
				}
			}
		}
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
		
		
		$this->data['invoice'] = $this->url->https('sale/order/invoices');
		$this->data['packingslip'] = $this->url->https('sale/order/packingslips');
		$this->data['delete'] = $this->url->https('sale/order/delete' . $url);
		$this->data['create'] = $this->url->https('sale/order/create');
		
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
		if (isset($this->request->get['ord'])) {	
		$ord = $this->request->get['ord'];	
		$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setOrd' AND `key` = 'setOrd'");						
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setOrd', `key` = 'setOrd', `value` = '$ord'");			}				
		$seto = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setOrd' AND `key` = 'setOrd'");		
		if ($seto->rows) {			
		$ord = $seto->row['value'];		
		}		else {		
		$ord = "Latest";	
		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setOrd', `key` = 'setOrd', `value` = 'Latest'");		
		}	
		$this->data['ords'] = $ord;				
		if (isset($this->request->get['prod'])) {		$prod = $this->request->get['prod'];				$set = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `group` = 'setProd' AND `key` = 'setProd'");						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setProd', `key` = 'setProd', `value` = '$prod'");			}				$seto = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `group` = 'setProd' AND `key` = 'setProd'");		if ($seto->rows) {			$prod = $seto->row['value'];			}		else {		$prod = "Latest";		$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `group` = 'setProd', `key` = 'setProd', `value` = 'Latest'");			}		$this->data['prod'] = $prod;
		$order_total = $this->model_sale_order->getTotalOrders($data);
		
		$results = $this->getOrdersf($data, $ord);
		
		//print_r($results); die;//$results = $this->model_sale_order->getOrders($data);
		
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
		
		
		$pps = $this->prods($prod);
		
		foreach ($pps as $result) {
		$pp = $this->db->query("select SUM(quantity) AS quantity from order_product where product_id = '" . $this->db->escape($result['product_id']) . "'");
		//print_r($pp); die;
		if ($pp->row['quantity']) {
		$qn = $pp->row['quantity'];
		}
		else
		{
		$qn = 0;
		}
		
			$this->data['prds'][] = array(
				'product_id'   => $result['product_id'],
				'name'       => $result['name'],
				'model'     => $result['model'],
				'viewed'     => $result['viewed'],
				'sold'     => $qn
			);
		}
		
		//print_r($pps); die;
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
	
	public function getOrdersf($data = array(), $ord) {
	$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o";		
	if ($ord == 'Pending') {		
	$sql .= " WHERE o.order_status_id = '1'";		}	
	else if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {	
	$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";		
	} else {		
	$sql .= " WHERE (o.order_status_id > '0' || o.order_status_id = '-1')";		}		
	
	
	if ($ord == "Week" || $ord == "Month") {
	if ($ord == "Week") {		
	$date_start = date('Y-m-d', strtotime('-7 day'));
	}		else		{	
	$date_start = date('Y-m-d', strtotime('-1 month'));		}	
	$date_end = date('Y-m-d', time());		
	$sql .= " AND (DATE(o.date_added) >= DATE('" . $this->db->escape($date_start) . "') AND DATE(o.date_added) <= DATE('" . $this->db->escape($date_end) . "'))";		
	}		

	
	if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
	$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";		}	
	if (isset($data['filter_name']) && !is_null($data['filter_name'])) {		
	$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";		}		
	if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";	
	}		
	if (isset($data['filter_total']) && !is_null($data['filter_total'])) {		
	$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";	
	}		
	
	$sort_data = array(			'o.order_id',			'name',			'status',			'o.date_added',			'o.total',		);		

	if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {		
	$sql .= " ORDER BY " . $data['sort'];		
	} else {		
	$sql .= " ORDER BY o.order_id";		
	}			
	if (isset($data['order']) && ($data['order'] == 'DESC')) {		
	$sql .= " DESC";	
	} else {	
	$sql .= " ASC";		}		
	if (isset($data['start']) || isset($data['limit'])) {		
	if ($data['start'] < 0) {		
	$data['start'] = 0;			}						if ($data['limit'] < 1) {
	$data['limit'] = 20;	
	}	
		if ($ord == 'Latest') {
	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];	
	}
	}
//echo $sql; die;		
	$query = $this->db->query($sql);
//echo $sql; die;	
	//echo $query->num_rows; die;
	//print_r($query->rows); die;
	return $query->rows;	
	}	
	
	public function sales() {
	$date_start = $_GET['start'];
	$date_end = $_GET['end'];
	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND (DATE(date_added) >= DATE('" . $this->db->escape($date_start) . "') AND DATE(date_added) <= DATE('" . $this->db->escape($date_end) . "'))");
		
		echo "$" . number_format($query->row['total'],2);
	}
	
	public function prods($prd) {
	$start = 0;
	$limit = 10;
	switch ($prd) {
	case "Latest":
		$ord = "p.date_added DESC";
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p left join product_description op on (op.product_id = p.product_id) GROUP BY model ORDER BY $ord LIMIT " . (int)$start . "," . (int)$limit);
		break;
	case "Viewed":
		$ord = "viewed DESC";
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p left join product_description op on (op.product_id = p.product_id) GROUP BY model ORDER BY $ord LIMIT " . (int)$start . "," . (int)$limit);
		break;
	case "Sold":
		$ord = "SUM(op.quantity) DESC";
		$query = $this->db->query("SELECT op.product_id, op.name, op.model, SUM(op.quantity) AS quantity, p.quantity as inv, SUM(op.total + (op.total * (op.tax/100))) AS total, p.price as price, p.viewed as viewed FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id = o.order_id) LEFT JOIN product p on (op.product_id = p.product_id) WHERE o.order_status_id > '0' GROUP BY model ORDER BY $ord LIMIT " . (int)$start . "," . (int)$limit);
		break;
	}
	//$query = $this->db->query("SELECT op.product_id, op.name, op.model, SUM(op.quantity) AS quantity, p.quantity as inv, SUM(op.total + (op.total * (op.tax/100))) AS total, p.price as price, p.viewed as viewed FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_id = o.order_id) LEFT JOIN product p on (op.product_id = p.product_id) WHERE o.order_status_id > '0' GROUP BY model ORDER BY $ord LIMIT " . (int)$start . "," . (int)$limit);
	
	return $query->rows;
	}
	}
?>