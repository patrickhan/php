<?php
class ControllerReportPurchased extends Controller { 
	public function oprice() {
	$pid = $this->request->get['product_id'];
	$op = "op$pid";
	$this->db->query("UPDATE product SET oprice='" . $this->db->escape($this->request->post[$op]) . "' WHERE product_id='$pid'");
	$this->redirect($this->url->https('report/purchased'));
	//print_r($_POST); die;
	}
	public function price() {
	$pid = $this->request->get['product_id'];
	$op = "p$pid";
	$this->db->query("UPDATE product SET price='" . $this->db->escape($this->request->post[$op]) . "' WHERE product_id='$pid'");
	$this->redirect($this->url->https('report/purchased'));
	//print_r($_POST); die;
	}
	public function inv() {
	$pid = $this->request->get['product_id'];
	$op = "qt$pid";
	$this->db->query("UPDATE product SET quantity='" . $this->db->escape($this->request->post[$op]) . "' WHERE product_id='$pid'");
	$this->redirect($this->url->https('report/purchased'));
	//print_r($_POST); die;
	}
	public function index() {   
	$fields = mysql_list_fields(DB_DATABASE, 'product');
	$columns = mysql_num_fields($fields);
	for ($i = 0; $i < $columns; $i++) {
	$field_array[] = mysql_field_name($fields, $i);
	}
	if (!in_array('oprice', $field_array)){
	$result = mysql_query('ALTER TABLE product ADD oprice DOUBLE');

	}		
		$this->load->language('report/purchased');

		$this->document->title = $this->language->get('heading_title');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('report/purchased' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/purchased');
		
		$product_total = $this->model_report_purchased->getTotalOrderedProducts();
		
		$this->data['products'] = array();

		$results = $this->model_report_purchased->getProductPurchasedReport(($page - 1) * 100000, 100000);
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'product_id'     => $result['product_id'],
				'name'     => $result['name'],
				'model'    => $result['model'],
				'inv'    => $result['inv'],
				'oprice'    => number_format($result['oprice'],2, '.', ''),
				'price'    => number_format($result['price'],2, '.', ''),
				'viewed'    => $result['viewed'],
				'profit'    => $this->currency->format(($result['total'] - ($result['oprice'] * $result['quantity'])), $this->config->get('config_currency')),
				'quantity' => $result['quantity'],
				'total'    => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 100; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('report/purchased&page=%s');
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/purchased.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}	
}
?>