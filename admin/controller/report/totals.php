<?php
class ControllerReportTotals extends Controller { 
	public function getOrdersd($date1, $date2) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o WHERE o.date_added >= '" . $this->db->escape($date1) . "' AND o.date_added <= '" . $this->db->escape($date2) . "' AND o.order_status_id>0 ORDER BY order_id DESC";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function index() {  
		$this->load->language('report/sale');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime('-1 month'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d', time());
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/sale' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/sale');
		$this->load->model('sale/order');
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'start'                  => ($page - 1) * 100000000,
			'limit'                  => 1000000000
		);
		
		$order_total = $this->model_report_sale->getSaleReportTotal($data);
		
		$oresults = $this->getOrdersd($filter_date_start, $filter_date_end);
		
		foreach ($oresults as $res) {
		$oid = $res['order_id'];
		$ops = $this->db->query("select * from order_product where order_id='$oid'");
		$prods = '';
		foreach ($ops->rows as $op) {
		$prods .= "<a target='_blank' href='index.php?route=catalog/product&product_id=" . $op['product_id'] . "'>" . $op['name'] . "</a> x " . $op['quantity'] . "<br>";
		}
		$this->data['ords'][] = array(
				'order_id'   => $res['order_id'],
				'name'       => $res['name'],
				'status'     => $res['status'],
				'prods'     => $prods,
				'date_added' => date($this->language->get('date_format_short'), strtotime($res['date_added'])),
				'total'      => $this->currency->format($res['total'], $res['currency'], $res['value'])
			);
		}
		//echo "here"; die;
		$results = $this->model_report_sale->getSaleReport1($data);
		$ex = $this->model_report_sale->gettotSaleReport($data);
		$price = 0;
		$oprice = 0;
		$total = 0;
		$taxes = 0;
		$prods = 0;
		foreach ($ex as $e) {
		$oids[] = $e['order_id'];
		$oid = $e['order_id'];
		//print_r($oids); die;
		
		$pp = $this->db->query("select * from `product` p left join `order_product` op on (p.product_id=op.product_id) left join `order` o on (o.order_id=op.order_id) where o.order_id = '$oid'");
		
		foreach ($pp->rows as $p) {
		$prods+= $p['quantity'];
		$price += $p['price'] * $p['quantity'];
		$oprice += $p['oprice'] * $p['quantity'];
		//$taxes += (($p['tax'] / 100) * ($p['quantity'] * $p['price']));
		}
		}
		
		$query2 = $this->db->query("select * from tax_rate");
		foreach ($query2->rows as $rw) {
		$txs[] = "'" . $rw['description'] . ":'";
		}
		if (!empty($oids)) { 
		$q2 = $this->db->query("select sum(value) as tax from order_total where title in (" . implode(",", $txs) . ") AND order_id in (" . implode(",", $oids) . ")");
		$taxes = $q2->row['tax'];
		foreach ($results as $result) {
		$profit = $result['total'] - $taxes - $oprice;
			$this->data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'price'     => $this->currency->format($price, $this->config->get('config_currency')),
				'prods'     => $prods,
				'oprice'     => $this->currency->format($oprice, $this->config->get('config_currency')),
				'taxes'     => $this->currency->format($taxes, $this->config->get('config_currency')),
				'profit'     => $this->currency->format($profit, $this->config->get('config_currency')),
				'orders'     => $result['orders'],
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		} }

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_date_start'] = $this->language->get('column_date_start');
		$this->data['column_date_end'] = $this->language->get('column_date_end');
    	$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_total'] = $this->language->get('column_total');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['groups'] = array();

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$this->data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('report/totals' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		 
		$this->template = 'report/totals.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>