<?php
class ControllerReportProfitLoss extends Controller { 
	public function getOrdersd($date1, $date2) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS name, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.date_added, o.total, o.currency, o.value FROM `" . DB_PREFIX . "order` o WHERE o.date_added >= '" . $this->db->escape($date1) . "' AND o.date_added <= '" . $this->db->escape($date2) . "' AND o.order_status_id>0 ORDER BY order_id DESC";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function index() {  
		$this->load->language('report/sale');

		$this->document->title = 'Profit/Loss Report';

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = date('Y-m-01',strtotime($this->request->get['filter_date_start']));
			$filter_date_start2 = $this->request->get['filter_date_start'];
		} else {
			//$filter_date_start = date('Y-m-d', time());//
			/*$filter_date_start = date('Y-m-d', strtotime('-1 month'));
			$filter_date_start2 = date('F Y', strtotime('-1 month'));*/
			$filter_date_start = date('Y-m-01', time());
			$filter_date_start2 = date('F Y', time());
		}
//echo $filter_date_start; die;
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = date('Y-m-t',strtotime($this->request->get['filter_date_end']));
			$filter_date_end2 = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-t', time());
			$filter_date_end2 = date('F Y', time());
		}
		
		$m1 = date('m',strtotime($filter_date_start));
		$y1 = date('y',strtotime($filter_date_start));
		$m2 = date('m',strtotime($filter_date_end));
		$y2 = date('y',strtotime($filter_date_end));
		//echo $m2; die;
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
       		'href'      => $this->url->https('report/profitloss' . $url),
       		'text'      => 'Profit/Loss Report',
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/sale');
		$this->load->model('report/purchased');
		$this->load->model('sale/order');
		
		$this->data['orders'] = array();
		if ( strtotime($filter_date_start) == strtotime($filter_date_end) ) {
		//echo "here"; die;
		}
		if ($filter_date_start == $filter_date_end) {
		//echo $filter_date_start; die;
		}
		//die;
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'start'                  => ($page - 1) * 100000000,
			'limit'                  => 1000000000
		);
		
		$order_total = $this->model_report_sale->getSaleReportTotal($data);
		//print_r($order_total); die;
		
		$results = $this->model_report_sale->getSaleReport1($data);
		//print_r($results); die;
		$tott = $results[0]['total'];
		$ex = $this->model_report_sale->gettotSaleReport($data);
		
		$price = 0;
		$oprice = 0;
		$total = 0;
		$taxes = 0;
		$prods = 0;
		$owed = 0;
		$exps = 0;
		
		$oresults = $this->getOrdersd($filter_date_start, $filter_date_end);
		foreach ($oresults as $res) {
		$oid[] = $res['order_id'];
		}				if (!empty($oid)) {				$pr = $this->db->query("select sum(op.quantity * p.price) AS pprice, sum(op.quantity * p.oprice) AS cprice from order_product op LEFT JOIN product p on (p.product_id = op.product_id) WHERE op.order_id IN (" . implode($oid,',') . ")");				$cprice = $pr->row['cprice'];		$pprice = $pr->row['pprice'];				}				else				{				$pr = array();				$cprice = 0;		$pprice = 0;				}
		
		$cprice = $pr->row['cprice'];
		$pprice = $pr->row['pprice'];
//print_r($ex); die;
		foreach ($ex as $e) {
		$oids[] = $e['order_id'];
		}
		$query2 = $this->db->query("select * from tax_rate");
		foreach ($query2->rows as $rw) {
		$txs[] = "'" . $rw['description'] . ":'";
		}		if (!empty($oid)) {		
		$q2 = $this->db->query("select sum(value) as tax from order_total where title in (" . implode(",", $txs) . ") AND order_id in (" . implode(",", $oids) . ")");
		$taxes = $q2->row['tax'];		}				else				{		$q2 = array();		$taxes = 0;		}

		
		//echo "<pre>";print_r($results); echo "</pre>"; die;
		//$q2 = $this->db->query("select sum(value) as tax from order_total where title in (" . implode(",", $txs) . ") AND order_id in (" . implode(",", $oids) . ")");
		if ($filter_date_start == $filter_date_end) {
		//echo "here"; die;
		$sqll = "WHERE (`month` = '$m1' AND `year` = '$y1')";
		}
		else if ($y1 == $y2) {
			$sqll = "WHERE (`month` >= '$m1' AND `month` <= '$m2')";
			}
			else
			{
			//start m1y1, end m2y2
			$sqll = "WHERE (`month` >= '$m1' AND `year` = '$y1') OR (`month` <= '$m2' AND `year` = '$y2') OR (`year` > $y1 AND `year` < '$y2')";
			}
			
			$results = $this->db->query("select *, sum(`amount`) AS tot from expenses_monthly $sqll GROUP BY year,month");
		
		foreach ($results->rows as $result) {
		$year = $result['year'];
		//echo $year; die;
		$month = $result['month'];
		$val = $this->db->query("select * from expenses_monthly em LEFT JOIN expenses e on (e.expense_id = em.expense_id) where em.`year`='$year' and em.`month`='$month'");
		//$this->data['expss'] = $val->rows;
		//print_r($val); die;
		//print_r($val); die;
		foreach ($val->rows as $v) {
		$this->data['expss'][$v['expense_id']]['name'] = $v['name'];
		$this->data['expss'][$v['expense_id']]['amount'] += $v['amount'];
		if (false) { /*code to calculate day*/ }
		$owed += ($v['amount'] * ($v['tax'] / 100));
		$exps += $v['amount'];
		}
		}
		$this->data['gsale'] = $pprice;
		$this->data['csale'] = $cprice;
		$this->data['gprof'] = $pprice - $cprice;
		$this->data['exps'] = $exps;
		//echo $exps; die;
		$this->data['net'] = $pprice - $cprice - $exps;
		//echo $owed; die;
		//$this->data['producttaxes'] = $taxes;
		foreach ($results as $result) {
		$profit = $result['total'] - $taxes - $oprice;
		$remit = $taxes - $owed;
		
			$this->data['orders'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($filter_date_start)),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($filter_date_end)),
				'price'     => $this->currency->format($price, $this->config->get('config_currency')),
				'prods'     => $prods,
				'oprice'     => $this->currency->format($oprice, $this->config->get('config_currency')),
				'taxes'     => $this->currency->format($taxes, $this->config->get('config_currency')),
				'otaxes'     => $this->currency->format($owed, $this->config->get('config_currency')),
				'profit'     => $this->currency->format($profit, $this->config->get('config_currency')),
				'orders'     => $result['orders'],
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'remit'      => $this->currency->format($remit, $this->config->get('config_currency')),
				'tot'      => $this->currency->format($tott, $this->config->get('config_currency'))
			);
		}

		$this->data['heading_title'] = 'Profit/Loss Report';
		
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
		$pagination->url = $this->url->https('report/profitloss' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start2;
		$this->data['filter_date_end'] = $filter_date_end2;		
		$this->data['filter_group'] = $filter_group;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		 
		$this->template = 'report/profitloss.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>